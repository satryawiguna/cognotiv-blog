<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ route.params.id ? model.title : "Create a Blog" }}
        </h1>
        <div class="flex">
          <TButton v-if="route.params.id" color="red" @click="deleteBlog()">
            Delete
          </TButton>
        </div>
      </div>
    </template>
    <div v-if="blogLoading" class="flex justify-center">Loading...</div>
    <form v-else @submit.prevent="saveBlog" class="animate-fade-in-down">
      <Alert v-if="errorMessages">
        <div class="flex flex-column">
          <ul>
            <li class="font-bold">{{ errorMessages.message }}</li>
            <li v-for="error in errorMessages.errors">{{ error }}</li>
          </ul>
        </div>
        <span
          @click="errorMessages = ''"
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
      <input
        type="hidden"
        name="author"
        id="author"
        v-model="model.author"/>
      <div class="shadow sm:rounded-md sm:overflow-hidden">
        <!-- Blog Fields -->
        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
          <!-- Category -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category"
                    id="category"
                    v-model="model.category">
                <option v-for="(blogCategory) in blogCategories.data" v-bind:value="blogCategory.id" v-bind:key="blogCategory.id">{{ blogCategory.title }}</option>
            </select>
          </div>
          <!--/ Category -->

          <!-- Title -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700"
            >Title</label
            >
            <input
              type="text"
              name="title"
              id="title"
              v-model="model.title"
              autocomplete="blog_title"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <!--/ Title -->

          <!-- Content -->
          <div>
            <label for="about" class="block text-sm font-medium text-gray-700">
              Content
            </label>
            <div class="mt-1">
              <textarea
                id="content"
                name="content"
                rows="5"
                v-model="model.content"
                autocomplete="blog_content"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                placeholder="Describe your content"
              />
            </div>
          </div>
          <!-- Content -->

          <!-- Publish Date -->
          <div>
            <label
              for="published_date"
              class="block text-sm font-medium text-gray-700"
            >Published Date</label
            >
            <input
              type="date"
              name="published_date"
              id="published_date"
              v-model="model.published_date"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <!--/ Publish Date -->

          <!-- Status -->
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Satus</label>
            <select name="status"
                    id="status"
                    v-model="model.status">
              <option v-for="(item) in status" v-bind:value="item.value" v-bind:key="item.value">{{ item.text }}</option>
            </select>
          </div>
          <!--/ Status -->
        </div>
        <!--/ Blog Fields -->

        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
          <TButton>
            Save
          </TButton>
        </div>
      </div>
    </form>
  </PageComponent>
</template>

<script setup>
import PageComponent from "../../../components/PageComponent.vue";
import TButton from "../../../components/core/TButton.vue";
import {useRoute,useRouter} from "vue-router";
import {computed, onMounted, ref, watch} from "vue";
import store from "../../../store/index.js";
import Alert from "../../../components/Alert.vue";

const router = useRouter();
const route = useRoute();

const blogCategories = computed(() => store.state.blogCategories);
const blogLoading = computed(() => store.state.currentBlog.loading);
const status = [
  { value:"published", text:"Published" },
  { value:"draft", text:"Draft" },
  { value:"pending", text:"Pending" },
]
const user = computed(() => store.state.user.data)

let model = ref({
  category: "",
  author: route.params.id ? "" : store.state.user.data.id,
  status: "",
  title: "",
  content: "",
  published_date: ""
});
let errorMessages = ref('')

store.dispatch("getAllBlogCategories");

watch(
  () => store.state.currentBlog.data,
  (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal))
    };
  }
);


if (route.params.id) {
  store.dispatch("getBlog", { id: route.params.id });
}

function saveBlog() {
  let action = "created";

  if (model.value.id) {
    action = "updated";
  }

  store.dispatch("saveBlog", { ...model.value })
    .then(({ data }) => {
      router.push({
        name: "Blog"
      });
    })
    .catch(err => {
      let errors = []

      for (let item in err.response.data.errors) {
        err.response.data.errors[item].map(_item => {
          errors.push(_item)
        })
      }

      errorMessages.value = {
        message: err.response.data.message,
        errors: errors
      };
    });
}

function deleteBlog() {
  if (
    confirm(
      `Are you sure you want to delete this blog? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deleteBlog", model.value.id).then(() => {
      router.push({
        name: "Blogs",
      });
    });
  }
}

</script>

<style scoped>

</style>
