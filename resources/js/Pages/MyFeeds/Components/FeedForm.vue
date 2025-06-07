<template>
  <form @submit.prevent="submit" class="bg-white rounded-2xl shadow-md p-4 transition-all duration-300 border border-gray-200">
    <div class="flex items-start space-x-3">
      <!-- User Avatar -->
      <img
        :src="user.profile_photo_url"
        alt="Profile"
        class="w-10 h-10 rounded-full object-cover ring-2 ring-orange-400 shadow-sm transition-transform duration-300 hover:scale-105"
      />

      <!-- Input -->
      <div class="flex-1">
        <textarea
          v-model="form.content"
          placeholder="What's on your mind?"
          class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all resize-none shadow-inner min-h-[80px]"
          rows="3"
        ></textarea>

        <div class="flex justify-between items-center mt-3">
          <span v-if="form.errors.content" class="text-red-500 text-sm">{{ form.errors.content }}</span>

          <button
            type="submit"
            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-full shadow-md font-semibold transition-all duration-300 active:scale-95 flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 19V6M5 12l7-7 7 7"/>
            </svg>
            Post
          </button>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3' // ✅ Correct package
import { ref } from 'vue'

const props = defineProps({
  user: Object
})

const emit = defineEmits(['created'])

const form = useForm({
  content: '',
})

const submit = () => {
  form.post(route('myfeeds.store'), {
    onSuccess: () => {
      form.reset()
      emit('created')
    },
  })
}
</script>
    