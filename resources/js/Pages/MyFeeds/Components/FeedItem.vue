<template>
  <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm transition hover:shadow-md duration-300">
    <div class="flex justify-between items-start">
      <!-- Avatar + Info -->
      <div class="flex items-start gap-3">
        <img
          :src="tweet.user?.avatar || '/images/default-avatar.png'"
          alt="Avatar"
          class="w-10 h-10 rounded-full object-cover ring-1 ring-orange-400"
        />
        <div>
          <h2 class="text-base font-semibold text-gray-800">{{ tweet.user?.name || 'Unknown User' }}</h2>
          <p class="text-xs text-gray-500">{{ new Date(tweet.created_at).toLocaleString() }}</p>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="flex gap-2">
        <button @click="openEditModal = true" title="Edit">
          <svg class="h-5 w-5 text-blue-600 hover:text-blue-800 transition duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 20h9" />
            <path d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
          </svg>
        </button>

        <button @click="deleteTweet" title="Delete">
          <svg class="h-5 w-5 text-red-500 hover:text-red-700 transition duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="3 6 5 6 21 6" />
            <path d="M19 6l-2 14H7L5 6" />
            <path d="M10 11v6" />
            <path d="M14 11v6" />
            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Tweet Content -->

    <div class="mt-3 text-gray-700 text-sm leading-relaxed" v-html="tweet.content"></div>
    <div v-if="tweet.image" class="mt-3">
  <img :src="tweet.image" class="max-h-72 rounded-xl border shadow" />
</div>



    <!-- Edit Modal -->
    <EditModal v-if="openEditModal" :tweet="tweet" @close="openEditModal = false" @updated="onUpdated" />
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import EditModal from './EditModal.vue'

const props = defineProps({ tweet: Object })
const emit = defineEmits(['updated'])

const openEditModal = ref(false)

const deleteTweet = () => {
  if (confirm('Are you sure you want to delete this tweet?')) {
    router.delete(route('myfeeds.destroy', props.tweet.id), {
      onSuccess: () => emit('updated')
    })
  }
}

const onUpdated = () => {
  emit('updated')
  openEditModal.value = false
}
</script>