<template>
  <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
    <td class="py-3 px-3 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{blogCategory.id}}</td>
    <td class="py-3 px-3 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{blogCategory.title}}</td>
    <td class="py-3 px-3 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{blogCategory.slug}}</td>
    <td class="py-3 px-3 text-sm font-medium text-right whitespace-nowrap">
      <TButton :to="{ name: 'EditBlogCategory', params: { id: blogCategory.id }}" link>
        Edit
      </TButton>
      <TButton v-if="blogCategory.id" @click="emit('delete', blogCategory)" link color="red">
        Delete
      </TButton>
    </td>
  </tr>
</template>

<script setup>
import TButton from "./core/TButton.vue";

const {blogCategory} = defineProps({
  blogCategory: Object,
});

const emit = defineEmits(["delete", "edit"]);
</script>

<style></style>
