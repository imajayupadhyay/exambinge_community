<template>
  <div class="flex justify-between items-center p-4 bg-white shadow rounded-lg mb-4">
    <div class="flex items-center gap-4">
      <img
        :src="user.profile_photo_path ? `/storage/${user.profile_photo_path}` : defaultAvatar"
        class="w-12 h-12 rounded-full border border-orange-500 object-cover"
      />
      <div>
        <div class="font-medium text-gray-800">{{ user.name }}</div>
        <div class="text-sm text-gray-500 capitalize">{{ user.role }}</div>
      </div>
    </div>
    <button
      @click="unfollowUser"
      class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600 transition"
    >
      Unfollow
    </button>
  </div>
</template>

<script setup>
import axios from 'axios'

const props = defineProps({ user: Object })
const emit = defineEmits(['unfollowed'])
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

const unfollowUser = async () => {
  try {
    await axios.post(route('follow.toggle', props.user.id))
    emit('unfollowed', props.user.id)
  } catch (error) {
    console.error('Failed to unfollow:', error)
  }
}
</script>
