<template>
  <div>
    <TweetItem
      v-for="(tweet, index) in tweets"
      :key="tweet.id"
      v-model:tweet="tweets[index]"
      :defaultAvatar="defaultAvatar"
    />
  </div>
</template>

<script setup>
import TweetItem from './TweetItem.vue'

const props = defineProps({
  tweets: Array,
  defaultAvatar: String
})
</script>
