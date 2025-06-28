<template>
  <aside class="md:col-span-3 hidden md:block sticky top-24 animate-fade-in-left">
    <div class="bg-white rounded-xl shadow p-4 space-y-6">
      
      <!-- Preferences -->
      <div v-if="preferences.length">
        <h3 class="text-lg font-semibold mb-2">Your Exam Preferences</h3>
        <ul class="space-y-1">
          <li
            v-for="tag in preferences"
            :key="tag"
            class="text-sm text-orange-600 font-medium hover:underline cursor-pointer"
          >
            #{{ tag }}
          </li>
        </ul>
      </div>

      <!-- Suggested Posts -->
      <div v-if="suggestedPosts.length">
        <h3 class="text-lg font-semibold mb-2">Suggested Posts</h3>
        <ul class="space-y-3">
          <li
            v-for="post in suggestedPosts"
            :key="post.id"
            class="bg-slate-50 p-3 rounded-lg border hover:shadow"
          >
            <div class="flex items-center gap-2 mb-2">
              <img
                :src="post.user.profile_photo_path ? `/storage/${post.user.profile_photo_path}` : defaultAvatar"
                class="w-7 h-7 rounded-full border"
              />
              <span class="text-sm font-semibold text-gray-800">{{ post.user.name }}</span>
            </div>
            <p class="text-sm text-gray-700 truncate">{{ post.content }}</p>
          </li>
        </ul>
      </div>

      <!-- Suggested People -->
      <div v-if="suggestedUsers.length">
        <h3 class="text-lg font-semibold mb-2">Suggested People</h3>
        <ul class="space-y-3">
          <li
            v-for="user in suggestedUsers"
            :key="user.id"
            class="flex justify-between items-center"
          >
            <div class="flex items-center gap-2">
              <img
                :src="user.profile_photo_path ? `/storage/${user.profile_photo_path}` : defaultAvatar"
                class="w-8 h-8 rounded-full border"
              />
              <span class="text-sm font-medium text-gray-800">{{ user.name }}</span>
            </div>
            <button
              @click="followUser(user.id)"
              class="text-xs bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600"
            >
              Follow
            </button>
          </li>
        </ul>
      </div>

    </div>
  </aside>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const preferences = ref([])
const suggestedPosts = ref([])
const suggestedUsers = ref([])
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

const fetchSidebarData = async () => {
  try {
    const res = await axios.get(route('leftsidebar.fetch'))
    preferences.value = res.data.preferences || []
    suggestedPosts.value = res.data.suggestedPosts || []
    suggestedUsers.value = res.data.suggestedUsers || []
  } catch (err) {
    console.error('Sidebar fetch failed:', err)
  }
}

const followUser = async (userId) => {
  try {
    await axios.post(route('follow.toggle', userId))
    suggestedUsers.value = suggestedUsers.value.filter(u => u.id !== userId)
  } catch (err) {
    console.error('Follow failed:', err)
  }
}

onMounted(() => fetchSidebarData())
</script>

<style scoped>
.animate-fade-in-left {
  animation: fade-in-left 0.4s ease-in-out;
}
@keyframes fade-in-left {
  from {
    opacity: 0;
    transform: translateX(-10px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>
