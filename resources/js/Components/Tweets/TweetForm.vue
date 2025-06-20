<template>
  <div>
    <form @submit.prevent="submitTweet" class="mb-8 p-6 bg-white border rounded-xl shadow animate-fade-in-up">
      <h2 class="text-xl font-semibold text-gray-800 mb-3">What's on your mind?</h2>

      <textarea
        v-model="form.content"
        placeholder="Write your thoughts here..."
        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-orange-400 text-gray-800"
        rows="4"
      ></textarea>

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

    <!-- PROGRESS BAR -->
    <div v-if="showProgress" class="h-1 bg-orange-200 overflow-hidden rounded-full mt-2">
      <div class="h-full bg-orange-500 animate-progress"></div>
    </div>
  </div>
</template>

<script setup>
import Multiselect from 'vue-multiselect'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'
import { ref } from 'vue'

const emit = defineEmits(['tweet-posted'])
const props = defineProps({ examTags: Array })

const form = useForm({
  content: '',
  exam_tags: [],
})

const showProgress = ref(false)

const submitTweet = async () => {
  try {
    showProgress.value = true
    const response = await axios.post(route('tweets.store'), {
      content: form.content,
      exam_tags: form.exam_tags,
    })

    form.reset()

    if (response.data.tweet) {
      emit('tweet-posted', response.data.tweet)

      // simulate delay then reload page
      setTimeout(() => {
        window.location.reload()
      }, 1500) // 1.5 seconds after progress bar shows
    }
  } catch (error) {
    console.error('Tweet post failed:', error)
    showProgress.value = false
  }
}
</script>

<style scoped>
@keyframes progress {
  0% {
    width: 0%;
  }
  100% {
    width: 100%;
  }
}
.animate-progress {
  animation: progress 1.5s ease-in-out forwards;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
