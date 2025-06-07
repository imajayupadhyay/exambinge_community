<template>
  <aside class="md:col-span-3 hidden md:block animate-fade-in-right">
    <div class="bg-white rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold mb-3">Search</h3>
      <input
        type="text"
        placeholder="Search posts or users..."
        class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-orange-400"
      />
    </div>

    <div class="bg-white rounded-xl shadow p-4">
      <h3 class="text-lg font-semibold mb-3">Trending Exams</h3>
      <ul class="text-sm space-y-2 text-gray-700">
        <li v-for="tag in examTags.slice(0, 5)" :key="tag">#{{ tag }}</li>
      </ul>
    </div>
  </aside>
</template>

<script setup>
defineProps({ examTags: Array })
</script>
