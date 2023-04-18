import {createStore} from "vuex";

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
      return fetch('http://localhost/api/register', {
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        },
        method: "POST",
        body: JSON.stringify(user)
      })
        .then((res) => res.json())
        .then((res) => {
          commit("setUser", res);

          return res;
        })
    }
  },
  mutations: {
    logout: state => {
      state.user.data = {};
      state.user.token = null;
    },
    setUser: (state, res) => {
      state.user.data = res.data
    }
  },
  modules: {}
})

export default store
