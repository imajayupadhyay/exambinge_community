<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-6">

      <!-- LEFT: Filters -->
      <aside class="md:col-span-3 hidden md:block">
        <div class="bg-white rounded-xl shadow p-4">
          <h3 class="text-lg font-semibold mb-4">Your Exam Filters</h3>
          <ul class="space-y-2">
            <li
              v-for="tag in tags"
              :key="tag"
              class="text-sm text-gray-700 hover:text-orange-500 cursor-pointer transition"
            >
              #{{ tag }}
            </li>
          </ul>
        </div>
      </aside>

      <!-- CENTER: Tweet Composer & Feed -->
      <main class="md:col-span-6 col-span-12">
        <!-- Tweet Composer -->
        <form @submit.prevent="submitTweet" class="mb-8 p-6 bg-white border rounded-xl shadow">
          <h2 class="text-xl font-semibold text-gray-800 mb-3">What's on your mind?</h2>
          <textarea
            v-model="form.content"
            placeholder="Write your thoughts here..."
            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-orange-400 text-gray-800"
            rows="4"
          ></textarea>

          <div class="mt-4">
            <label class="block text-sm text-gray-700 mb-1">Choose an exam tag (optional):</label>
            <select
              v-model="form.exam_tag"
              class="w-full border border-gray-300 rounded-md p-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
              <option value="">-- None --</option>
              <option v-for="tag in tags" :key="tag" :value="tag">{{ tag }}</option>
            </select>
          </div>

          <div class="text-right mt-6">
            <button
              type="submit"
              class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded font-semibold shadow transition"
              :disabled="form.processing"
            >
              Post Tweet
            </button>
          </div>
        </form>

        <!-- Tweet Feed -->
        <div v-for="tweet in tweets" :key="tweet.id" class="bg-white rounded-xl shadow p-4 mb-6">
          <div class="flex justify-between items-center mb-2">
            <div class="font-semibold text-gray-800">
              {{ tweet.user.name }}
              <span v-if="tweet.user.role === 'teacher'" class="ml-2 text-xs bg-orange text-white px-2 py-0.5 rounded-full">Teacher</span>
            </div>
            <div class="text-xs text-gray-500">{{ new Date(tweet.created_at).toLocaleString() }}</div>
          </div>
          <div class="text-gray-700 whitespace-pre-line">{{ tweet.content }}</div>
          <div v-if="tweet.exam_tag" class="text-sm text-orange mt-2">#{{ tweet.exam_tag }}</div>
        </div>
      </main>

      <!-- RIGHT: Trends + Search -->
      <aside class="md:col-span-3 hidden md:block">
        <div class="bg-white rounded-xl shadow p-4 mb-6">
          <h3 class="text-lg font-semibold mb-3">Search Users</h3>
          <input
            type="text"
            placeholder="Search by name..."
            class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-orange-400"
          />
        </div>

        <div class="bg-white rounded-xl shadow p-4">
          <h3 class="text-lg font-semibold mb-3">Trending</h3>
          <ul class="text-sm space-y-2 text-gray-700">
            <li>#UPSC</li>
            <li>#SSC</li>
            <li>#Banking</li>
          </ul>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({ tweets: Array })

const form = useForm({
  content: '',
  exam_tag: '',
})

const tags = ['UPSC', 'SSC', 'Banking', 'Railway', 'Defence']

const submitTweet = () => {
  form.post(route('tweets.store'), {
    onSuccess: () => form.reset(),
  })
}
</script>
