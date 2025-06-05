<template>
  <div class="mb-6 relative">
    <input
      v-model="query"
      @input="performSearch"
      type="text"
      placeholder="Search users, exams, or tweets..."
      class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-orange-400 focus:ring-2"
    />
    <div v-if="results" class="absolute bg-white shadow rounded mt-1 w-full z-10 p-4 text-sm max-h-60 overflow-y-auto">
      <div v-if="results.users.length">
        <strong>Users:</strong>
        <ul>
          <li v-for="u in results.users" :key="u.id">{{ u.name }} ({{ u.role }})</li>
        </ul>
      </div>
      <div v-if="results.exams.length" class="mt-2">
        <strong>Exam Tags:</strong>
        <ul>
          <li v-for="e in results.exams" :key="e">{{ e }}</li>
        </ul>
      </div>
      <div v-if="results.tweets.length" class="mt-2">
        <strong>Posts:</strong>
        <ul>
          <li v-for="t in results.tweets" :key="t.id">{{ t.content.slice(0, 50) }}...</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const query = ref('')
const results = ref(null)

const performSearch = async () => {
  if (query.value.length < 2) {
    results.value = null
    return
  }

  const { data } = await axios.get(route('search'), {
    params: { q: query.value }
  })

  results.value = data
}
</script>
