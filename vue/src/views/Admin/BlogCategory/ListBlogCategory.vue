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
    <div v-if="blogCategories.loading" class="flex justify-center">Loading...</div>
    <div v-else-if="blogCategories.data.length">
      <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-700">
          <tr>
            <th scope="col" class="py-3 px-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">ID</th>
            <th scope="col" class="py-3 px-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">Title</th>
            <th scope="col" class="py-3 px-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">slug</th>
            <th scope="col" class="py-3 px-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
          <BlogCategoryListItem
            v-for="(blogCategory, ind) in blogCategories.data"
            :key="blogCategory.id"
            :blogCategory="blogCategory"
            @delete="deleteBlogCategory(blogCategory)"/>
        </tbody>
      </table>
    </div>
  </PageComponent>
</template>

<script setup>
import PageComponent from "../../../components/PageComponent.vue"
import TButton from '../../../components/core/TButton.vue'
import {computed} from "vue";
import store from "../../../store";
import BlogCategoryListItem from "../../../components/BlogCategoryListItem.vue";


const blogCategories = computed(() => store.state.blogCategories);

store.dispatch("getBlogCategories");

function deleteBlogCategory(blogCategory) {
  if (
    confirm(
      `Are you sure you want to delete this blog category? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deleteBlogCategory", blogCategory.id).then(() => {
      store.dispatch("getBlogCategories");
    });
  }
}
</script>

<style scoped>

</style>
