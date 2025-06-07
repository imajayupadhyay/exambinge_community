<!-- resources/js/Components/Pagination.vue -->
<template>
  <div class="flex flex-wrap mt-4">
    <template v-for="(link, index) in links" :key="index">
      <Component
        :is="link.url ? 'Link' : 'span'"
        :href="link.url"
        v-html="link.label"
        class="px-3 py-1 text-sm border rounded mx-1 mb-1 transition"
        :class="{
          'bg-orange-500 text-white font-semibold': link.active,
          'text-gray-700 hover:bg-gray-200': !link.active && link.url,
          'text-gray-400 cursor-not-allowed': !link.url,
        }"
      />
    </template>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  links: Array
})
</script>
