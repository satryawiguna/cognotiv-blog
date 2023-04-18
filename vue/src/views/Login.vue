<template>
  <div>
    <img className="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
         alt="Cognitiv"/>
    <h2 className="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Sign in to your account</h2>
  </div>
  <form className="mt-8 space-y-6" @submit="login">
    <Alert v-if="errorMessage">
      {{ errorMessage }}
      <span
        @click="errorMessage = ''"
        class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </span>
    </Alert>
    <input type="hidden" name="remember" value="true"/>
    <div className="-space-y-px rounded-md shadow-sm">
      <div>
        <label htmlFor="identity" className="sr-only">Username / Password</label>
        <input id="identity" name="identity" type="text" autoComplete="identity" required="" v-model="user.identity"
               className="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               placeholder="Identity"/>
      </div>
      <div>
        <label htmlFor="password" className="sr-only">Password</label>
        <input id="password" name="password" type="password" autoComplete="current-password" required="" v-model="user.password"
               className="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               placeholder="Password"/>
      </div>
    </div>

    <div className="flex items-center justify-between">
      <div className="flex items-center">
        <input id="remember-me" name="remember-me" type="checkbox" v-model="user.remember"
               className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
        <label htmlFor="remember-me" className="ml-2 block text-sm text-gray-900">Remember me</label>
      </div>

      <div className="text-sm">
        <router-link :to="{name: 'Register'}" className="font-medium text-indigo-600 hover:text-indigo-500">
          Register
        </router-link>
      </div>
    </div>

    <div>
      <button type="submit"
              className="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <span className="absolute inset-y-0 left-0 flex items-center pl-3">
              <LockClosedIcon class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" aria-hidden="true"/>
            </span>
        Sign in
      </button>
    </div>
  </form>
</template>

<script setup>
import {LockClosedIcon} from '@heroicons/vue/20/solid'
import store from "../store/index.js";
import {useRouter} from "vue-router";
import {ref} from "vue";
import Alert from "../components/Alert.vue";

const user = {
  identity: '',
  password: '',
  remember: false
}

let errorMessage = ref('')

const router = useRouter();

function login(e) {
  e.preventDefault();

  store.dispatch("login", user)
    .then(() => {
      router.push({
        name: "Dashboard"
      })
    })
    .catch(err => {
      errorMessage.value = err.response.data.message;
    })
}
</script>

<style scoped>

</style>
