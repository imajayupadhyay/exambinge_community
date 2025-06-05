<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-6">

      <!-- LEFT SIDEBAR -->
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

      <!-- MAIN FEED -->
      <main class="md:col-span-6 col-span-12">
        <!-- TWEET FORM -->
        <form @submit.prevent="submitTweet" class="mb-8 p-6 bg-white border rounded-xl shadow animate-fade-in-up">
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

        <!-- TWEETS LIST -->
        <div
          v-for="tweet in tweets"
          :key="tweet.id"
          class="bg-white rounded-xl shadow p-4 mb-6 animate-fade-in"
        >
          <!-- USER INFO -->
          <div class="flex items-center mb-2">
            <img
              :src="tweet.user.profile_photo_path ? `/storage/${tweet.user.profile_photo_path}` : defaultAvatar"
              class="w-10 h-10 rounded-full border mr-3"
            />
            <div>
              <div class="font-semibold text-gray-800 flex items-center gap-2">
                {{ tweet.user.name }}
                <span
                  v-if="tweet.user.role === 'teacher'"
                  class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full"
                >
                  🎓 Teacher
                </span>
              </div>
              <div class="text-xs text-gray-500">{{ new Date(tweet.created_at).toLocaleString() }}</div>
            </div>
          </div>

          <!-- CONTENT -->
          <div class="text-gray-800 whitespace-pre-wrap">{{ tweet.content }}</div>

          <!-- Exam Tags -->
          <div v-if="tweet.exam_tag" class="mt-3 flex flex-wrap gap-2">
            <span
              v-for="tag in tweet.exam_tag.split(',')"
              :key="tag"
              class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded-full"
            >
              #{{ tag }}
            </span>
          </div>

          <!-- ACTIONS -->
          <div class="mt-4 flex items-center gap-6 text-sm text-gray-600">
            <button @click.prevent="likeTweet(tweet.id)" class="flex items-center space-x-1 hover:text-orange-500 transition">
              <span v-if="tweet.is_liked">❤️</span>
              <span v-else>🤍</span>
              <span>{{ tweet.likes_count }}</span>
            </button>

            <button @click="toggleReply(tweet.id)" class="hover:text-orange-500 transition">💬 {{ tweet.replies.length }}</button>
            <button @click="retweet(tweet.id)" class="hover:text-orange-500 transition">🔁 {{ tweet.retweets.length }}</button>
          </div>

          <!-- REPLY BOX -->
          <div v-if="replyingTo === tweet.id" class="mt-3">
            <textarea
              v-model="replyContent"
              placeholder="Write your reply..."
              class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-orange-400"
              rows="3"
            ></textarea>
            <div class="text-right mt-2">
              <button
                @click="submitReply(tweet.id)"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded shadow"
              >
                Reply
              </button>
            </div>
          </div>

          <!-- REPLIES -->
          <div v-if="tweet.replies && tweet.replies.length > 0" class="mt-4 border-t pt-4">
            <div
              v-for="reply in tweet.replies"
              :key="reply.id"
              class="text-sm text-gray-700 mb-2 border-b pb-2"
            >
              <strong>{{ reply.user.name }}:</strong> {{ reply.content }}
            </div>
          </div>
        </div>
      </main>

      <!-- RIGHT SIDEBAR -->
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
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import Multiselect from 'vue-multiselect'
import { defineProps, ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  tweets: Array,
  examTags: Array,
  auth: Object,
})

const tweets = ref(JSON.parse(JSON.stringify(props.tweets))) // Make reactive
const preferences = props.auth.user.preferences?.split(',') || []
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

const form = useForm({
  content: '',
  exam_tags: [],
})

const replyingTo = ref(null)
const replyContent = ref('')

const submitTweet = () => {
  form.post(route('tweets.store'), {
    onSuccess: () => {
      form.reset()
      // Optional: Refresh feed or notify
    }
  })
}

const likeTweet = (tweetId) => {
  router.post(route('tweets.like', tweetId), {
    preserveScroll: true
  })
}

const toggleReply = (tweetId) => {
  replyingTo.value = replyingTo.value === tweetId ? null : tweetId
  replyContent.value = ''
}

const submitReply = (tweetId) => {
  axios.post(route('tweets.reply', tweetId), {
    content: replyContent.value,
  })
  .then(response => {
    const reply = response.data.reply
    const tweet = tweets.value.find(t => t.id === tweetId)
    if (tweet) {
      tweet.replies = tweet.replies || []
      tweet.replies.push(reply)
    }
    replyContent.value = ''
    replyingTo.value = null
  })
  .catch(error => {
    console.error('Reply failed:', error)
  })
}

const retweet = (tweetId) => {
  router.post(route('tweets.retweet', tweetId), {
    preserveScroll: true
  })
}
</script>


<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.4s ease-out;
}
.animate-fade-in-up {
  animation: fade-in 0.5s ease-out;
}
.animate-fade-in-left {
  animation: fade-in 0.5s ease-out;
}
.animate-fade-in-right {
  animation: fade-in 0.5s ease-out;
}
</style>
