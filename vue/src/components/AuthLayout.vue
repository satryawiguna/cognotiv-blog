<template>
  <div className="flex min-h-full items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
    <div className="w-full max-w-md space-y-8">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import {LockClosedIcon} from '@heroicons/vue/20/solid'

export default {
  components: {
    LockClosedIcon
  }
}
</script>

<style scoped>

</style>
