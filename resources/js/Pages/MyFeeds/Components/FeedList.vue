<template>
  <div class="space-y-6">
    <FeedItem
      v-for="tweet in tweets"
      :key="tweet.id"
      :tweet="tweet"
      @updated="emit('updated')"
    />
  </div>
</template>

<script setup>
import FeedItem from './FeedItem.vue'

const props = defineProps({
  tweets: Array
})

const emit = defineEmits(['updated'])
</script>
