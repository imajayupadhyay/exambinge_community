<template>
  <div class="bg-white shadow-md rounded-lg p-4 mb-6 animate-fade-in">
    <!-- USER INFO -->
    <div class="flex items-center mb-4">
      <img
        :src="tweet.user.profile_photo_path ? `/storage/${tweet.user.profile_photo_path}` : defaultAvatar"
        class="w-10 h-10 rounded-full border-2 border-orange-500 mr-3"
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

    <!-- CONTENT -->
    <p class="text-gray-800 whitespace-pre-wrap">{{ tweet.content }}</p>

    <!-- TAGS -->
    <div v-if="tweet.exam_tag" class="mt-3 flex flex-wrap gap-2">
      <span
        v-for="tag in tweet.exam_tag.split(',')"
        :key="tag"
        class="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full"
      >
        #{{ tag.trim() }}
      </span>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="mt-4 flex justify-between text-sm text-gray-600">
      <!-- LIKE -->
      <button
        @click="likeTweet(tweet.id)"
        class="flex items-center gap-1 hover:text-red-500 transition"
      >
        <span>{{ tweet.is_liked ? '❤️' : '🤍' }}</span>
        <span>{{ tweet.likes_count }}</span>
      </button>

      <!-- COMMENT -->
      <button
        @click="toggleReply"
        class="flex items-center gap-1 hover:text-orange-500 transition"
      >
        💬 <span>{{ tweet.replies.length }}</span>
      </button>

      <!-- RETWEET -->
      <button
        @click="retweet(tweet.id)"
        class="flex items-center gap-1 hover:text-blue-500 transition"
      >
        🔁 <span>{{ tweet.retweets.length }}</span>
      </button>
    </div>

    <!-- REPLY BOX -->
    <div v-if="isReplying" class="mt-4">
      <textarea
        v-model="localReplyContent"
        class="w-full border rounded-md p-2 focus:outline-orange-400 text-sm"
        rows="3"
        placeholder="Write your reply..."
      ></textarea>
      <div class="text-right mt-2">
        <button
          @click="submitReply"
          class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600 text-sm"
        >
          Reply
        </button>
      </div>
    </div>

    <!-- REPLIES -->
    <div v-if="tweet.replies.length" class="mt-4 border-t pt-4">
      <div
        v-for="reply in tweet.replies"
        :key="reply.id"
        class="text-sm text-gray-700 mb-2 border-b pb-2"
      >
        <strong>{{ reply.user.name }}</strong>: {{ reply.content }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  tweet: Object,
  defaultAvatar: String
})

const emit = defineEmits(['update:tweet', 'add-retweet'])

const localTweet = ref(JSON.parse(JSON.stringify(props.tweet)))

watch(() => props.tweet, (val) => {
  localTweet.value = JSON.parse(JSON.stringify(val))
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
  } catch (err) {
    console.error('Like error:', err)
  }
}

const submitReply = async () => {
  try {
    const response = await axios.post(route('tweets.reply', localTweet.value.id), {
      content: localReplyContent.value,
    })
    localTweet.value.replies.push(response.data.reply)
    emit('update:tweet', localTweet.value)
    isReplying.value = false
    localReplyContent.value = ''
  } catch (err) {
    console.error('Reply error:', err)
  }
}

const retweet = async (tweetId) => {
  try {
    const response = await axios.post(route('tweets.retweet', tweetId))
    emit('add-retweet', response.data.retweeted_tweet)
  } catch (err) {
    console.error('Retweet error:', err)
  }
}
</script>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.4s ease-in-out;
}
</style>
