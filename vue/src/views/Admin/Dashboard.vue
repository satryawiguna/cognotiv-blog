<template>
  <PageComponent title="Dashboard">
    Welcome... <strong>{{ user.data.email }}</strong>
  </PageComponent>
</template>

<script setup>
import PageComponent from "../../components/PageComponent.vue"
import {computed} from "vue";
import store from "../../store/index.js";

const user = computed(() => store.state.user);

</script>

<style scoped>

</style>
