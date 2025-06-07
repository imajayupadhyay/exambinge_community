<template>
  <button
    @click="toggleFollow"
    :disabled="processing"
    class="px-4 py-1.5 text-sm font-medium rounded-full border transition-all duration-200"
    :class="isFollowing
      ? 'bg-orange-100 text-orange-600 border-orange-300 hover:bg-orange-200'
      : 'bg-orange-500 text-white border-orange-500 hover:bg-orange-600'"
  >
    {{ isFollowing ? 'Following' : 'Follow' }}
  </button>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  userId: {
    type: Number,
    required: true
  },
  initialIsFollowing: {
    type: Boolean,
    required: true
  }
})

const isFollowing = ref(props.initialIsFollowing)
const processing = ref(false)

watch(() => props.initialIsFollowing, (newVal) => {
  isFollowing.value = newVal
})

const toggleFollow = async () => {
  if (processing.value) return
  processing.value = true

  try {
    const response = await axios.post(route('follow.toggle', props.userId))
    isFollowing.value = !isFollowing.value // Optimistic toggle
  } catch (error) {
    console.error('Follow toggle failed:', error)
    alert('Something went wrong. Please try again.')
  } finally {
    processing.value = false
  }
}
</script>
