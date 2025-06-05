<template>
  <div class="bg-white rounded-xl shadow p-4 mb-6 animate-fade-in">
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

    <!-- TAGS -->
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

      <button @click="toggleReply" class="hover:text-orange-500 transition">
        💬 {{ tweet.replies.length }}
      </button>

      <button @click="retweet(tweet.id)" class="hover:text-orange-500 transition">
        🔁 {{ tweet.retweets.length }}
      </button>
    </div>

    <!-- REPLY BOX -->
    <div v-if="isReplying" class="mt-3">
      <textarea
        v-model="localReplyContent"
        placeholder="Write your reply..."
        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-orange-400"
        rows="3"
      ></textarea>
      <div class="text-right mt-2">
        <button
          @click="submitReply"
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
</template>

<script setup>

import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  tweet: Object,
  defaultAvatar: String,
})

const emit = defineEmits(['update:tweet'])

const localTweet = ref(JSON.parse(JSON.stringify(props.tweet)))

watch(() => props.tweet, (newVal) => {
  localTweet.value = JSON.parse(JSON.stringify(newVal))
})

const isReplying = ref(false)
const localReplyContent = ref('')

const toggleReply = () => {
  isReplying.value = !isReplying.value
  localReplyContent.value = ''
}

const likeTweet = async (tweetId) => {
  try {
    const response = await axios.post(route('tweets.like', tweetId))
    localTweet.value.likes_count = response.data.likes_count
    localTweet.value.is_liked = response.data.is_liked
    emit('update:tweet', localTweet.value)
  } catch (error) {
    console.error('Like failed:', error)
  }
}

const submitReply = () => {
  axios.post(route('tweets.reply', localTweet.value.id), {
    content: localReplyContent.value,
  })
  .then(response => {
    localTweet.value.replies.push(response.data.reply)
    emit('update:tweet', localTweet.value)
    localReplyContent.value = ''
    isReplying.value = false
  })
  .catch(err => {
    console.error('Reply failed:', err)
  })
}

const retweet = async (tweetId) => {
  try {
    const response = await axios.post(route('tweets.retweet', tweetId))
    const newTweet = response.data.retweeted_tweet

    // Emit new retweet tweet to parent
    emit('add-retweet', newTweet)
  } catch (error) {
    console.error('Retweet failed:', error)
  }
}



</script>


<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.4s ease-out;
}
</style>
