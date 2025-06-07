<template>
  <div>
    <TweetItem
      v-for="(tweet, index) in tweets"
      :key="tweet.id"
      :tweet="tweet"
      :defaultAvatar="defaultAvatar"
      @update:tweet="(updatedTweet) => updateTweet(index, updatedTweet)"
      @add-retweet="handleRetweet"
    />
  </div>
</template>

<script setup>
import TweetItem from './TweetItem.vue'

const emit = defineEmits(['prepend-tweet'])

const props = defineProps({
  tweets: Array,
  defaultAvatar: String,
})

// Replace a tweet in-place when it's updated (likes/replies)
const updateTweet = (index, updatedTweet) => {
  props.tweets[index] = { ...updatedTweet }
}

// Add new retweet to top and emit to parent for global sync
const handleRetweet = (tweet) => {
  props.tweets.unshift(tweet)
  emit('prepend-tweet', tweet)
}
</script>
