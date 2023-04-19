import {createStore} from "vuex";
import axiosClient from "../axios.js";

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem('TOKEN')
    },
    blogCategories: {
      meta: null,
      data: [],
      loading: false
    },
    currentBlogCategory: {
      meta: null,
      data: {},
      loading: false
    },
    blogs: {
      meta: null,
      data: [],
      loading: false
    },
    currentBlog: {
      meta: null,
      data: {},
      loading: false
    },
  },
  getters: {},
  actions: {
    register({commit}, user) {
      return axiosClient.post('/register', user)
        .then(({data}) => {
          return data
        })
    },
    login({commit}, user) {
      return axiosClient.post('/auth/login', user)
        .then(({data}) => {
          commit('setUser', {
            email: data.data.email,
            role: data.data.role
          })
          commit('setToken', data.data.access_token)

          return data
        })
    },
    logout({commit}) {
      return axiosClient.post('/auth/logout')
        .then((response) => {
          commit('logout')
          return response
        })
    },

    getBlogCategories({commit}, params) {
      commit('setBlogCategoriesLoading', true)

      return axiosClient.post('/blog-category/all/search/page', params)
        .then((res) => {
          commit('setBlogCategoriesLoading', false)
          commit("setBlogCategories", res.data)

          return res
        })
    },
    getBlogCategory({commit}, id) {
      commit("setBlogCategoriesLoading", true);

      return axiosClient
        .get(`/blog-category/${id}`)
        .then((res) => {
          commit("setCurrentBlogCategory", res.data);
          commit("setCurrentBlogCategoryLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentBlogCategoryLoading", false);
          throw err;
        });
    },
    saveBlogCategory({commit, dispatch}, blogCategory) {
      let response;
      if (blogCategory.id) {
        response = axiosClient
          .put(`/blog-category/${blogCategory.id}/update`, blogCategory)
          .then((res) => {
            commit('setCurrentBlogCategory', res.data)
            return res;
          });
      } else {
        response = axiosClient.post("/blog-category/create", blogCategory).then((res) => {
          commit('setCurrentBlogCategory', res.data)
          return res;
        });
      }

      return response;
    },
    deleteBlogCategory({dispatch}, id) {
      return axiosClient.delete(`/blog-category/${id}/delete`).then((res) => {
        dispatch('getBlogCategories')
        return res;
      });
    },

    getBlogs({commit}, params) {
      commit('setBlogsLoading', true)

      return axiosClient.post('/blog/all/search/page', params)
        .then((res) => {
          commit('setBlogsLoading', false)
          commit("setBlogs", res.data)

          return res
        })
    },
    getBlog({commit}, id) {
      commit("setBlogsLoading", true);

      return axiosClient
        .get(`/blog/${id}`)
        .then((res) => {
          commit("setCurrentBlog", res.data);
          commit("setCurrentBlogLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentBlogLoading", false);
          throw err;
        });
    },
    saveBlog({commit, dispatch}, blog) {
      let response;
      if (blog.id) {
        response = axiosClient
          .put(`/blog/${blog.id}/update`, blog)
          .then((res) => {
            commit('setCurrentBlog', res.data)
            return res;
          });
      } else {
        response = axiosClient.post("/blog/create", blog).then((res) => {
          commit('setCurrentBlog', res.data)
          return res;
        });
      }

      return response;
    },
    deleteBlog({dispatch}, id) {
      return axiosClient.delete(`/blog/${id}/delete`).then((res) => {
        dispatch('getBlogs')
        return res;
      });
    },
  },
  mutations: {
    logout: state => {
      state.user.data = {}
      state.user.token = null
      sessionStorage.removeItem('TOKEN')
    },
    setUser: (state, user) => {
      state.user.data = user
    },
    setToken: (state, token) => {
      state.user.token = token
      sessionStorage.setItem('TOKEN', token)
    },

    setBlogCategoriesLoading: (state, loading) => {
      state.blogCategories.loading = loading;
    },
    setBlogCategories: (state, blogCategories) => {
      state.blogCategories.meta = blogCategories.meta;
      state.blogCategories.data = blogCategories.datas;
    },
    setCurrentBlogCategoryLoading: (state, loading) => {
      state.currentBlogCategory.loading = loading;
    },
    setCurrentBlogCategory: (state, blogCategory) => {
      state.currentBlogCategory.data = blogCategory.data;
    },

    setBlogsLoading: (state, loading) => {
      state.blogs.loading = loading;
    },
    setBlogs: (state, blogs) => {
      state.blogs.meta = blogs.meta;
      state.blogs.data = blogs.datas;
    },
    setCurrentBlogLoading: (state, loading) => {
      state.currentBlog.loading = loading;
    },
    setCurrentBlog: (state, blog) => {
      state.currentBlog.data = blog.data;
    },
  },
  modules: {}
})

export default store
