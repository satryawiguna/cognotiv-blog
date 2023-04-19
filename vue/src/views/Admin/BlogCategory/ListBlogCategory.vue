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
            v-for="(blogCategory) in blogCategories.data"
            :key="blogCategory.id"
            :blogCategory="blogCategory"
            @delete="deleteBlogCategory(blogCategory)"/>
        </tbody>
      </table>
      <div class="flex justify-center mt-5">
        <nav
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <a
            v-for="i in Math.ceil(blogCategories.meta.total_count / blogCategories.meta.per_page)"
            :key="i"
            :disabled="blogCategories.meta.current_page !== i"
            href="#"
            @click="getForPage($event, i, blogCategories.meta.current_page)"
            aria-current="page"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
            :class="[
              blogCategories.meta.current_page === i
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 1 ? 'rounded-l-md bg-gray-100 text-gray-700' : '',
              i === Math.ceil(blogCategories.meta.total_count / blogCategories.meta.per_page) ? 'rounded-r-md' : '',
            ]"
            v-html="i"
          >
          </a>
        </nav>
      </div>

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
let param = {
  "search": null,
  "order_by": "id",
  "sort": "DESC",
  "per_page": 5,
  "page": 1
}

store.dispatch("getBlogCategories", param);

function deleteBlogCategory(blogCategory) {
  if (
    confirm(
      `Are you sure you want to delete this blog category? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deleteBlogCategory", blogCategory.id).then(() => {
      store.dispatch("getBlogCategories", param);
    });
  }
}

function getForPage(e, i, currentPage) {
  e.preventDefault();

  if (i === currentPage) {
    return;
  }

  param.page = i
  store.dispatch("getBlogCategories", param);
}
</script>

<style scoped>

</style>
