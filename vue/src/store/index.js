import {createStore} from "vuex";
import axiosClient from "../axios.js";

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem('TOKEN')
    }
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
          commit('setUser', data)
          return data
        })
    }
  },
  mutations: {
    logout: state => {
      state.user.data = {};
      state.user.token = null;
    },
    setUser: (state, res) => {
      state.user.data = {
        email: res.data.email,
        role: res.data.role
      }
      state.user.token = res.data.access_token
    }
  },
  modules: {}
})

export default store
