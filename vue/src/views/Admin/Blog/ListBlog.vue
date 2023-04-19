<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Blog</h1>
        <TButton color="green" :to="{ name: 'AddBlog' }">
          Add New Blog
        </TButton>
      </div>
    </template>
    <div v-if="blogs.loading" class="flex justify-center">Loading...</div>
    <div v-else-if="blogs.data.length">
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
        <BlogListItem
          v-for="(blog, ind) in blogs.data"
          :key="blog.id"
          :blog="blog"
          @delete="deleteBlog(blog)"
        />
      </div>
      <div class="flex justify-center mt-5">
        <nav
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <a
            v-for="i in Math.ceil(blogs.meta.total_count / blogs.meta.per_page)"
            :key="i"
            :disabled="blogs.meta.current_page !== i"
            href="#"
            @click="getForPage($event, i, blogs.meta.current_page)"
            aria-current="page"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
            :class="[
              blogs.meta.current_page === i
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 1 ? 'rounded-l-md bg-gray-100 text-gray-700' : '',
              i === Math.ceil(blogs.meta.total_count / blogs.meta.per_page) ? 'rounded-r-md' : '',
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
import BlogListItem from "../../../components/BlogListItem.vue";

const blogs = computed(() => store.state.blogs);
let param = {
  "search": null,
  "order_by": "id",
  "sort": "DESC",
  "per_page": 5,
  "page": 1
}

store.dispatch("getBlogs", param);

function deleteBlog(blog) {
  if (
    confirm(
      `Are you sure you want to delete this blog? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deleteBlog", blog.id).then(() => {
      store.dispatch("getBlogs", param);
    });
  }
}

function getForPage(e, i, currentPage) {
  e.preventDefault();

  if (i === currentPage) {
    return;
  }

  param.page = i
  store.dispatch("getBlogs", param);
}
</script>

<style scoped>

</style>
