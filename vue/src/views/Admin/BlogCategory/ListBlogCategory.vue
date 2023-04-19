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
      <table class="min-w-full text-left text-sm font-light">
        <thead class="border-b font-medium dark:border-neutral-500">
          <tr>
            <th scope="col" class="px-6 py-4">ID</th>
            <th scope="col" class="px-6 py-4">Title</th>
            <th scope="col" class="px-6 py-4">slug</th>
            <th scope="col" class="px-6 py-4">Actions</th>
          </tr>
        </thead>
        <tbody>
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
