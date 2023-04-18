<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Blog Category</h1>
        <TButton color="green" :to="{ name: 'AddBlogCategory' }">
          Add New Blog Category
        </TButton>
      </div>
    </template>
  </PageComponent>
</template>

<script setup>
import PageComponent from "../../../components/PageComponent.vue"
import TButton from "../../../components/core/TButton.vue"

</script>

<style scoped>

</style>
