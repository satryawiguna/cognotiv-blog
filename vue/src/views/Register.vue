<template>
  <div>
    <img className="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
         alt="Cognitiv"/>
    <h2 className="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Registration account</h2>
  </div>
  <form className="mt-8 space-y-6" @submit="register">
    <input type="hidden" name="remember" value="true"/>
    <div className="-space-y-px rounded-md shadow-sm">
      <div>
        <label htmlFor="email" className="sr-only">Email</label>
        <input id="email" name="email" type="email" autoComplete="email" required="" v-model="user.email"
               className="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               placeholder="Email"/>
      </div>
      <div>
        <label htmlFor="username" className="sr-only">Username</label>
        <input id="username" name="username" type="text" autoComplete="username" required="" v-model="user.username"
               className="relative block w-full border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               placeholder="Username"/>
      </div>
      <div>
        <label htmlFor="fullname" className="sr-only">Full Name</label>
        <input id="fullname" name="fullname" type="text" autoComplete="fullname" required="" v-model="user.full_name"
               className="relative block w-full border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               placeholder="Full Name"/>
      </div>
      <div>
        <label htmlFor="nickname" className="sr-only">Nick Name</label>
        <input id="nickname" name="nickname" type="text" autoComplete="nickname" required="" v-model="user.nick_name"
               className="relative block w-full border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               placeholder="Nick Name"/>
      </div>
      <div>
        <label htmlFor="password" className="sr-only">Password</label>
        <input id="password" name="password" type="password" autoComplete="password" required="" v-model="user.password"
               className="relative block w-full border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               placeholder="Password"/>
      </div>
      <div>
        <label htmlFor="password_confirmation" className="sr-only">Password Confirmation</label>
        <input id="password_confirmation" name="password_confirmation" type="password" autoComplete="password_confirmation" required="" v-model="user.password_confirmation"
               className="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               placeholder="Password Confirmation"/>
      </div>
    </div>

    <div className="flex items-center justify-between">
      <div className="flex items-center">
        <input id="remember-me" name="remember-me" type="checkbox"
               className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
        <label htmlFor="remember-me" className="ml-2 block text-sm text-gray-900">Remember me</label>
      </div>

      <div className="text-sm">
        <a href="#" className="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
      </div>
    </div>

    <div>
      <button type="submit"
              className="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <span className="absolute inset-y-0 left-0 flex items-center pl-3">
              <LockClosedIcon class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" aria-hidden="true"/>
            </span>
        Sign up
      </button>
    </div>
  </form>
</template>

<script setup>
import {LockClosedIcon} from '@heroicons/vue/20/solid'
import store from "../store/index.js";
import {useRouter} from "vue-router";

const router = useRouter();
const user = {
  email: '',
  username: '',
  full_name: '',
  nick_name: '',
  password: '',
  password_confirmation: ''
}

function register(e) {
  e.preventDefault();

  store.dispatch('register', user)
    .then((res) => {
      router.push({
        name: 'Login'
      })
    })
}

</script>

<style scoped>

</style>
