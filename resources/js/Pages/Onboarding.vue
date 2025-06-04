<template>
  <div class="min-h-screen bg-gray-100 flex justify-center items-start py-10 px-4 font-sans">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-md p-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-8">Welcome, {{ user.name }} 👋</h1>

      <!-- Role Selection -->
      <div class="mb-10">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">Are you a Teacher or a Student?</h2>
        <div class="grid grid-cols-2 gap-5">
          <div
            @click="role = 'student'"
            class="relative p-6 rounded-xl border cursor-pointer flex flex-col items-center text-center transition hover:shadow"
            :class="role === 'student' ? 'border-orange-500 ring-2 ring-orange-400 bg-orange-50' : 'border-gray-300 bg-white'"
          >
            <svg class="w-10 h-10 text-orange-500 mb-2" fill="none" stroke="currentColor" stroke-width="1.5"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 14c-1.5 0-4.5.75-4.5 2.25v.75h9v-.75C16.5 14.75 13.5 14 12 14z" />
              <circle cx="12" cy="8" r="3" />
            </svg>
            <p class="font-medium text-gray-800">Student</p>
            <div v-if="role === 'student'" class="absolute top-2 right-2 bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">
              Selected
            </div>
          </div>

          <div
            @click="role = 'teacher'"
            class="relative p-6 rounded-xl border cursor-pointer flex flex-col items-center text-center transition hover:shadow"
            :class="role === 'teacher' ? 'border-orange-500 ring-2 ring-orange-400 bg-orange-50' : 'border-gray-300 bg-white'"
          >
            <svg class="w-10 h-10 text-orange-500 mb-2" fill="none" stroke="currentColor" stroke-width="1.5"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 14c-2.25 0-6.75 1.125-6.75 3.375v.375h13.5v-.375C18.75 15.125 14.25 14 12 14z" />
              <circle cx="12" cy="8" r="3" />
            </svg>
            <p class="font-medium text-gray-800">Teacher</p>
            <div v-if="role === 'teacher'" class="absolute top-2 right-2 bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">
              Selected
            </div>
          </div>
        </div>
      </div>

      <!-- Exam Preferences -->
      <div class="mb-10">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">Select your exam preferences:</h2>
        <div class="flex flex-wrap gap-3">
          <button
            v-for="tag in tags"
            :key="tag"
            type="button"
            @click="toggleTag(tag)"
            :class="[
              'px-4 py-2 rounded-full border text-sm transition duration-150',
              selected.includes(tag)
                ? 'bg-orange-500 text-white border-orange-500'
                : 'bg-white text-gray-700 border-gray-300 hover:border-orange-400 hover:text-orange-500'
            ]"
          >
            {{ tag }}
          </button>
        </div>
      </div>

      <!-- Submit -->
      <div class="text-right mt-12">
        <button
          @click="savePrefs"
          :disabled="selected.length === 0"
          :class="[
            'font-semibold py-2 px-6 rounded-lg shadow-md transition duration-200',
            selected.length === 0
              ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
              : 'bg-orange-500 text-white hover:bg-orange-600'
          ]"
        >
          Save & Continue
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const user = usePage().props.auth.user
const role = ref('student')
const selected = ref([])
const tags = ['UPSC', 'SSC', 'Banking', 'Railway', 'Defence']

const toggleTag = (tag) => {
  selected.value.includes(tag)
    ? selected.value.splice(selected.value.indexOf(tag), 1)
    : selected.value.push(tag)
}

const savePrefs = () => {
  router.post('/save-preferences', {
    preferences: selected.value,
    role: role.value,
  })
}
</script>
