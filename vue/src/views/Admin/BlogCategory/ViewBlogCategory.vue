<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ route.params.id ? model.title : "Create a Blog Category" }}
        </h1>

        <div class="flex">
          <TButton v-if="route.params.id" color="red" @click="deleteBlogCategory()">
            Delete
          </TButton>
        </div>
      </div>
    </template>
    <div v-if="blogCategoryLoading" class="flex justify-center">Loading...</div>
    <form v-else @submit.prevent="saveBlogCategory" class="animate-fade-in-down">
      <div class="shadow sm:rounded-md sm:overflow-hidden">
        <!-- Blog Category Fields -->
        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
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
              autocomplete="blog_category_title"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <!--/ Title -->

        </div>
        <!--/ Blog Category Fields -->

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
import {computed, ref, watch} from "vue";
import store from "../../../store/index.js";

const router = useRouter();
const route = useRoute();

const blogCategoryLoading = computed(() => store.state.currentBlogCategory.loading);

let model = ref({
  title: ""
});

watch(
  () => store.state.currentBlogCategory.data,
  (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal)),
      status: !!newVal.status,
    };
  }
);

if (route.params.id) {
  store.dispatch("getBlogCategory", route.params.id);
}

function saveBlogCategory() {
  let action = "created";

  if (model.value.id) {
    action = "updated";
  }

  store.dispatch("saveBlogCategory", { ...model.value }).then(({ data }) => {
    store.commit("notify", {
      type: "success",
      message: "Blog category was successfully " + action,
    });

    router.push({
      name: "BlogCategory"
    });
  });
}

function deleteBlogCategory() {
  if (
    confirm(
      `Are you sure you want to delete this category? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deleteBlogCategory", model.value.id).then(() => {
      router.push({
        name: "BlogCategories",
      });
    });
  }
}

</script>

<style scoped>

</style>
