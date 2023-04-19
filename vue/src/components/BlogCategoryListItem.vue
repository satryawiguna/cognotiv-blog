<template>
  <tr class="border-b dark:border-neutral-300">
    <td class="whitespace-nowrap px-6 py-4 font-medium">{{blogCategory.id}}</td>
    <td class="whitespace-nowrap px-6 py-4">{{blogCategory.title}}</td>
    <td class="whitespace-nowrap px-6 py-4">{{blogCategory.slug}}</td>
    <td class="whitespace-nowrap px-6 py-4">
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
