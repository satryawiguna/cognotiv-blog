<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ model.title }}
        </h1>
        <div class="flex">
          <TButton :to="`/admin/blog`" color="green">
            Back
          </TButton>
        </div>
      </div>
    </template>
    <div v-if="blogLoading" class="flex justify-center">Loading...</div>
    <div class="mb-5">
      <small>
        <strong>Published:</strong> {{model.published_date}}<br />
        <strong>Author:</strong> {{model.author_name}}
      </small>
    </div>
    <p>{{model.content}}</p>

    <form @submit.prevent="saveComment" class="animate-fade-in-down mt-5">
      <input
        type="hidden"
        name="author"
        id="author"
        v-model="model.author"/>
      <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
          <!-- Comment -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Comment</label>
            <input
              type="text"
              name="comment"
              id="comment"
              v-model="comment.body"
              autocomplete="comment_body"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <!--/ Comment -->
        </div>
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

const router = useRouter();
const route = useRoute();

const blogLoading = computed(() => store.state.currentBlog.loading);
const user = computed(() => store.state.user);

let model = ref({
  category: "",
  author: route.params.id ? "" : store.state.user.data.id,
  status: "",
  title: "",
  content: "",
  published_date: ""
})

let comment = ref({
  commentable_type: "App\\Models\\Blog",
  commentable_id: "",
  author: store.state.user.data.id,
  body: ""
})

watch(
  () => store.state.currentBlog.data,
  (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal))
    };
  }
);


if (route.params.id) {
  store.dispatch("getBlog", { id: route.params.id, relations: 'comments' });
}

function saveComment() {
  store.dispatch("saveComment", { ...comment.value })
    .then(({ data }) => {
      // router.push({
      //   name: "Blog"
      // });
    });
}
</script>

<style scoped>

</style>
