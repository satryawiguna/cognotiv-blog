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
      <div class="shadow sm:rounded-md sm:overflow-hidden">
        <!-- Blog Fields -->
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
              autocomplete="survey_title"
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
                rows="3"
                v-model="model.content"
                autocomplete="blog_content"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                placeholder="Describe your content"
              />
            </div>
          </div>
          <!-- Content -->

          <!-- Expire Date -->
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
          <!--/ Expire Date -->


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
import {computed, ref, watch} from "vue";
import store from "../../../store/index.js";

const router = useRouter();
const route = useRoute();

const blogLoading = computed(() => store.state.currentBlog.loading);

let model = ref({
  title: ""
});

watch(
  () => store.state.currentBlog.data,
  (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal)),
      status: !!newVal.status,
    };
  }
);

if (route.params.id) {
  store.dispatch("getBlog", route.params.id);
}

function saveBlog() {
  let action = "created";

  if (model.value.id) {
    action = "updated";
  }

  store.dispatch("saveBlog", { ...model.value }).then(({ data }) => {
    store.commit("notify", {
      type: "success",
      message: "Blog was successfully " + action,
    });

    router.push({
      name: "Blog"
    });
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
