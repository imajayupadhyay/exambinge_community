<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-6">

      <!-- LEFT SIDEBAR -->
      <aside class="md:col-span-3 hidden md:block">
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

      <!-- MAIN FEED -->
      <main class="md:col-span-6 col-span-12">
        <!-- TWEET FORM -->
        <form @submit.prevent="submitTweet" class="mb-8 p-6 bg-white border rounded-xl shadow">
          <h2 class="text-xl font-semibold text-gray-800 mb-3">What's on your mind?</h2>
          <textarea
            v-model="form.content"
            placeholder="Write your thoughts here..."
            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-orange-400 text-gray-800"
            rows="4"
          ></textarea>

          <!-- Exam Tags -->
          <div class="mt-4">
            <label class="block text-sm text-gray-700 mb-1">Select Exam Tag(s):</label>
            <Multiselect
              v-model="form.exam_tags"
              :options="examTags"
              :multiple="true"
              placeholder="Choose exams"
              class="mt-1"
            />
          </div>

          <div class="text-right mt-6">
            <button
              type="submit"
              class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded font-semibold shadow transition"
              :disabled="form.processing"
            >
              Post
            </button>
          </div>
        </form>

        <!-- TWEET LIST -->
        <div
          v-for="tweet in tweets"
          :key="tweet.id"
          class="bg-white rounded-xl shadow p-4 mb-6"
        >
          <div class="flex items-center mb-2">
            <img
              :src="tweet.user.profile_photo_path ? `/storage/${tweet.user.profile_photo_path}` : defaultAvatar"
              class="w-10 h-10 rounded-full border mr-3"
            />
            <div>
              <div class="font-semibold text-gray-800">
                {{ tweet.user.name }}
                <span
                  v-if="tweet.user.role === 'teacher'"
                  class="ml-2 text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full"
                >
                  🎓 Teacher
                </span>
              </div>
              <div class="text-xs text-gray-500">{{ new Date(tweet.created_at).toLocaleString() }}</div>
            </div>
          </div>

          <div class="text-gray-800 whitespace-pre-wrap">{{ tweet.content }}</div>

          <div v-if="tweet.exam_tag" class="mt-3 flex flex-wrap gap-2">
            <span
              v-for="tag in tweet.exam_tag.split(',')"
              :key="tag"
              class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded-full"
            >
              #{{ tag }}
            </span>
          </div>
        </div>
      </main>

      <!-- RIGHT SIDEBAR -->
      <aside class="md:col-span-3 hidden md:block">
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
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import Multiselect from 'vue-multiselect'
import { defineProps } from 'vue'

const props = defineProps({
  tweets: Array,
  examTags: Array,
  auth: Object,
})

const preferences = props.auth.user.preferences?.split(',') || []
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

const form = useForm({
  content: '',
  exam_tags: [],
})

const submitTweet = () => {
  form.post(route('tweets.store'), {
    onSuccess: () => form.reset(),
  })
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style scoped>
</style>
