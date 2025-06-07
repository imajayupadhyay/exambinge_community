<template>
  <aside class="md:col-span-3 hidden md:block animate-fade-in-left">
    <div class="bg-white rounded-xl shadow p-4">
      <h3 class="text-lg font-semibold mb-4">Your Exam Preferences</h3>
      <ul class="space-y-2">
        <li
          v-for="tag in preferences"
          :key="tag"
          class="text-sm text-gray-700 hover:text-orange-500 cursor-pointer transition"
        >
          #{{ tag }}
        </li>
      </ul>
    </div>
  </aside>
</template>

<script setup>
defineProps({ preferences: Array })
</script>
