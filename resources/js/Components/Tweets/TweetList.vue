<template>
  <div>
    <TweetItem
      v-for="(tweet, index) in localTweets"
      :key="tweet.id"
      v-model:tweet="localTweets[index]"
      :defaultAvatar="defaultAvatar"
      @add-retweet="prependTweet"
    />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import TweetItem from './TweetItem.vue'

const emit = defineEmits(['prepend-tweet'])

const props = defineProps({
  tweets: Array,
  defaultAvatar: String,
})

const localTweets = ref([...props.tweets])

watch(() => props.tweets, (newVal) => {
  localTweets.value = [...newVal]
})

const prependTweet = (tweet) => {
  localTweets.value.unshift(tweet)
  emit('prepend-tweet', tweet)
}
</script>
