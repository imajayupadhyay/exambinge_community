<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-6 relative animate-fade-in-up">
      <button @click="$emit('close')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
      </button>

      <h2 class="text-xl font-bold text-gray-800 mb-4">Edit Tweet</h2>

      <form @submit.prevent="updateTweet">
        <textarea v-model="form.content" class="w-full bg-gray-100 border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none shadow-inner min-h-[80px]" />

        <div class="mt-4 flex justify-end gap-3">
          <button type="button" @click="$emit('close')" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</button>
          <button type="submit" class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg shadow-sm">Save</button>
        </div>
        <p v-if="form.errors.content" class="text-sm text-red-500 mt-2">{{ form.errors.content }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({ tweet: Object })
const emit = defineEmits(['close', 'updated'])

const form = useForm({ content: props.tweet.content })

const updateTweet = () => {
  form.patch(route('myfeeds.update', props.tweet.id), {
    onSuccess: () => emit('updated')
  })
}
</script>

<style scoped>
@keyframes fade-in-up {
  0% { opacity: 0; transform: translateY(30px); }
  100% { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up { animation: fade-in-up 0.3s ease-out; }
</style>
