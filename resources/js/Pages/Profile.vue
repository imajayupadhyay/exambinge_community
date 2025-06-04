<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto px-4">
      <!-- USER CARD -->
      <div class="bg-white rounded-xl shadow p-6 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <img :src="user.avatar || defaultAvatar" class="w-16 h-16 rounded-full border border-orange-500" />
          <div>
            <h2 class="text-xl font-semibold">{{ user.name }}</h2>
            <p class="text-sm text-gray-600">{{ user.email }}</p>
            <span v-if="user.role === 'teacher'" class="inline-block text-xs text-white bg-blue-600 px-2 py-1 mt-1 rounded">
              🎓 Teacher
            </span>
          </div>
        </div>
        <!-- PROFILE COMPLETION -->
        <div class="text-right">
          <p class="text-sm font-semibold text-gray-700 mb-1">Profile Completion</p>
          <div class="bg-gray-200 w-40 h-3 rounded-full overflow-hidden">
            <div class="bg-orange-500 h-3 rounded-full transition-all duration-300" :style="{ width: completion + '%' }"></div>
          </div>
          <p class="text-xs text-gray-500 mt-1">{{ completion }}% Complete</p>
        </div>
      </div>

      <!-- USER INFO FORM -->
      <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">Your Information</h3>
        <form @submit.prevent="updateInfo">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input v-model="form.phone" type="text" placeholder="Phone Number" class="input" />
            <input v-model="form.age" type="number" placeholder="Age" class="input" />
            <input v-model="form.city" type="text" placeholder="City" class="input" />
            <input v-model="form.email" type="email" placeholder="Email" class="input" />
          </div>
          <button class="mt-4 bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Save Info</button>
        </form>
      </div>

      <!-- EXAM PREFERENCES -->
      <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">Exam Preferences</h3>
        <div class="flex flex-wrap gap-2">
          <span v-for="pref in user.preferences" :key="pref" class="bg-orange-100 text-orange-700 text-sm px-3 py-1 rounded-full flex items-center gap-2">
            {{ pref }}
            <button @click="removePreference(pref)" class="text-xs text-red-500 hover:underline">x</button>
          </span>
        </div>
      </div>

      <!-- USER POSTS -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-bold mb-4">Your Posts</h3>
        <div v-if="posts.length">
          <div v-for="post in posts" :key="post.id" class="border-b py-4">
            <p class="text-gray-800">{{ post.content }}</p>
            <div class="text-sm text-gray-500 mt-2 flex gap-4">
              <button @click="editPost(post)" class="hover:underline text-blue-500">Edit</button>
              <button @click="deletePost(post.id)" class="hover:underline text-red-500">Delete</button>
            </div>
          </div>
        </div>
        <p v-else class="text-gray-500">No posts yet.</p>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const user = usePage().props.auth.user
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

// Sample data for now
const form = ref({
  phone: '',
  age: '',
  city: '',
  email: user.email,
})

const completion = ref(75) // You can calculate based on filled fields

const posts = ref([
  { id: 1, content: 'My first tweet!' },
  { id: 2, content: 'Loving the EB WebApp!' }
])

const updateInfo = () => {
  alert('User info updated!') // Replace with actual call
}

const removePreference = (pref) => {
  alert(`Remove: ${pref}`) // Replace with actual logic
}

const editPost = (post) => {
  alert(`Edit Post: ${post.content}`) // Replace with actual modal/form
}

const deletePost = (id) => {
  posts.value = posts.value.filter(p => p.id !== id)
}
</script>

<style scoped>
.input {
  @apply w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400;
}
</style>
