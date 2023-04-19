<template>
  <div
    class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[470px]"
  >
    <h4 class="mt-4 text-lg font-bold">{{ blog.title }}</h4>
    <div v-html="blog.slug" class="overflow-hidden flex-1"></div>

    <p v-html="blog.content" class="overflow-hidden flex"></p>

    <hr class="my-5" />

    <span v-html="blog.category" class="overflow-hidden font-bold"></span>
    <span v-html="blog.status" class="overflow-hidden"></span>
    <span v-html="blog.author" class="overflow-hidden"></span>
    <span v-html="blog.publish_date" class="overflow-hidden text-sm"></span>

    <div class="flex justify-between items-center mt-3">
      <TButton :to="{ name: 'EditBlog', params: { id: blog.id } }">
        Edit
      </TButton>
      <div class="flex items-center">
        <TButton :to="`/admin/blog/${blog.id}`" link>
          Preview
        </TButton>
        <TButton v-if="blog.id" @click="emit('delete', blog)" link color="red">
          Delete
        </TButton>
      </div>
    </div>
  </div>
</template>

<script setup>
import TButton from "./core/TButton.vue";
const { blog } = defineProps({
  blog: Object,
});
const emit = defineEmits(["delete", "edit"]);
</script>

<style></style>
