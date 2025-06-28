<template>
  <AppLayout :auth="auth">
    <div class="max-w-3xl mx-auto py-10 px-4">
      <h1 class="text-2xl font-bold text-orange-600 mb-6">Your Followers & Following</h1>

      <SearchBar v-model="search" class="mb-6" />

      <div v-if="filteredUsers.length">
        <FollowerCard
          v-for="user in filteredUsers"
          :key="user.id"
          :user="user"
          @unfollowed="removeUser"
        />
      </div>
      <div v-else class="text-gray-500 text-center">No matching users found.</div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import FollowerCard from './Components/FollowerCard.vue'
import SearchBar from './Components/SearchBar.vue'

const props = defineProps({
  auth: Object,
  users: Array,
})

const search = ref('')

const filteredUsers = computed(() => {
  return props.users.filter((u) =>
    u.name.toLowerCase().includes(search.value.toLowerCase())
  )
})

const removeUser = (id) => {
  const index = props.users.findIndex(u => u.id === id)
  if (index !== -1) props.users.splice(index, 1)
}
</script>
