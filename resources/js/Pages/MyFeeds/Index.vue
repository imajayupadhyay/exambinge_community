<template>
  <div class="min-h-screen bg-gradient-to-b from-white via-slate-50 to-slate-100 text-gray-800 font-sans">
    <div class="max-w-3xl mx-auto px-4 py-6">
      
      <!-- Heading -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-orange-600 tracking-tight">My Feeds</h1>
      </div>

      <!-- Post Form -->
      <!-- <FeedForm v-if="auth" :user="auth" @created="reloadFeeds" /> -->

      <!-- Divider -->
      <hr class="my-6 border-slate-300" />

      <!-- Feed List -->
      <FeedList :tweets="tweets?.data || []" @updated="reloadFeeds" />

      <!-- Pagination -->
      <div v-if="tweets?.links?.length > 3" class="mt-6 flex justify-center">
        <Pagination :links="tweets.links" />
      </div>
    </div>
  </div>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout.vue'

export default {
  layout: AppLayout
}
</script>

<script setup>
import { ref } from 'vue'
import FeedForm from './Components/FeedForm.vue'
import FeedList from './Components/FeedList.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  tweets: Object,
  auth: Object,
})

const reloadFeeds = () => window.location.reload()
</script>
