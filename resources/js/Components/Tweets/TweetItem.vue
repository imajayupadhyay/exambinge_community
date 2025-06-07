<template>
  <div class="bg-white rounded-xl shadow p-5 mb-6 animate-fade-in transition-all">
    <!-- USER INFO -->
    <div class="flex items-center justify-between mb-3">
      <div class="flex items-center">
        <img
          :src="tweet.user.profile_photo_path ? `/storage/${tweet.user.profile_photo_path}` : defaultAvatar"
          class="w-11 h-11 rounded-full border-2 border-orange-400 mr-3"
        />
        <div>
          <div class="font-semibold text-gray-800 flex items-center gap-2">
            {{ tweet.user.name }}
            <span v-if="tweet.user.role === 'teacher'" class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">
              🎓 Teacher
            </span>
          </div>
          <div class="text-xs text-gray-500">{{ new Date(tweet.created_at).toLocaleString() }}</div>
        </div>
      </div>

      <!-- Follow Button -->
      <FollowButton
  v-if="tweet.user.id !== currentUser?.id"
  :user-id="tweet.user.id"
  :initial-is-following="tweet.user.is_followed"
/>

    </div>

    <!-- CONTENT -->
    <div class="text-gray-800 whitespace-pre-wrap text-base leading-relaxed">
      {{ tweet.content }}
    </div>

    <!-- EXAM TAGS -->
    <div v-if="tweet.exam_tag" class="mt-3 flex flex-wrap gap-2">
      <span
        v-for="tag in tweet.exam_tag.split(',')"
        :key="tag"
        class="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full"
      >
        #{{ tag.trim() }}
      </span>
    </div>

    <!-- ACTIONS -->
    <div class="mt-4 flex items-center gap-6 text-gray-700 text-sm">
      <button @click.prevent="likeTweet(tweet.id)" class="flex items-center gap-2 hover:text-red-600 transition">
        <span class="text-lg">{{ tweet.is_liked ? '❤️' : '🤍' }}</span>
        <span>{{ tweet.likes_count }}</span>
      </button>

      <button @click="toggleReply" class="flex items-center gap-2 hover:text-blue-500 transition">
        💬 <span>{{ tweet.replies.length }}</span>
      </button>

      <button @click="retweet(tweet.id)" class="flex items-center gap-2 hover:text-green-600 transition">
        🔁 <span>{{ tweet.retweets.length }}</span>
      </button>
    </div>

    <!-- REPLY BOX -->
    <div v-if="isReplying" class="mt-3">
      <textarea
        v-model="localReplyContent"
        placeholder="Write your reply..."
        rows="3"
        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm"
      ></textarea>
      <div class="text-right mt-2">
        <button
          @click="submitReply"
          class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 text-sm rounded shadow"
        >
          Reply
        </button>
      </div>
    </div>

    <!-- REPLIES -->
    <div v-if="tweet.replies?.length" class="mt-4 border-t pt-4 space-y-3">
      <div
        v-for="reply in tweet.replies"
        :key="reply.id"
        class="text-sm text-gray-700"
      >
        <strong>{{ reply.user.name }}:</strong> {{ reply.content }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import FollowButton from '@/Pages/Users/Components/FollowButton.vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  tweet: Object,
  defaultAvatar: String,
})

const emit = defineEmits(['update:tweet', 'add-retweet'])

const localTweet = ref(JSON.parse(JSON.stringify(props.tweet)))
const isReplying = ref(false)
const localReplyContent = ref('')

const currentUser = usePage().props.auth?.user || null

watch(() => props.tweet, (newVal) => {
  localTweet.value = JSON.parse(JSON.stringify(newVal))
})

const likeTweet = async (tweetId) => {
  try {
    const res = await axios.post(route('tweets.like', tweetId))
    localTweet.value.likes_count = res.data.likes_count
    localTweet.value.is_liked = res.data.is_liked
    emit('update:tweet', localTweet.value)
  } catch (err) {
    console.error('Like failed:', err)
  }
}

const toggleReply = () => {
  isReplying.value = !isReplying.value
  localReplyContent.value = ''
}

const submitReply = async () => {
  try {
    const res = await axios.post(route('tweets.reply', localTweet.value.id), {
      content: localReplyContent.value,
    })
    localTweet.value.replies.push(res.data.reply)
    emit('update:tweet', localTweet.value)
    localReplyContent.value = ''
    isReplying.value = false
  } catch (err) {
    console.error('Reply failed:', err)
  }
}

const retweet = async (tweetId) => {
  try {
    const res = await axios.post(route('tweets.retweet', tweetId))
    emit('add-retweet', res.data.retweeted_tweet)
  } catch (err) {
    console.error('Retweet failed:', err)
  }
}
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fade-in {
  animation: fade-in 0.4s ease-in-out;
}
</style>
