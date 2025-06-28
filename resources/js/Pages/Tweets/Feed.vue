<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-6">
      <div class="md:col-span-3 col-span-12 sticky top-24 self-start h-fit">
      <LeftSidebar />
</div>



      <main class="md:col-span-6 col-span-12">
        <!-- <SearchBar /> -->
        <TweetForm :form="form" :examTags="examTags" @tweet-posted="prependTweet" />

        <InfiniteScroll :loading="loadingMore" @load-more="loadMoreTweets">
          <TweetList
            :tweets="tweets"
            :defaultAvatar="defaultAvatar"
            @prepend-tweet="prependTweet"
          />
        </InfiniteScroll>
      </main>

     <div class="md:col-span-3 col-span-12 sticky top-24 self-start h-fit">
  <RightSidebar :examTags="examTags" />
</div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import LeftSidebar from '@/Components/Tweets/LeftSidebar.vue'
import RightSidebar from '@/Components/Tweets/RightSidebar.vue'
import TweetForm from '@/Components/Tweets/TweetForm.vue'
import TweetList from '@/Components/Tweets/TweetList.vue'
import InfiniteScroll from '@/Components/Shared/InfiniteScroll.vue'
import SearchBar from './Components/SearchBar.vue'
import { useForm } from '@inertiajs/vue3'
import { defineProps, ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  tweets: Array,
  examTags: Array,
  auth: Object,
  nextPageUrl: String,
})

const tweets = ref([...props.tweets])
const nextPage = ref(props.nextPageUrl)
const loadingMore = ref(false)

const prependTweet = (tweet) => {
  if (!tweets.value.find(t => t.id === tweet.id)) {
    tweets.value.unshift(tweet)
  }
}

const loadMoreTweets = async () => {
  if (!nextPage.value || loadingMore.value) return
  loadingMore.value = true
  try {
    const res = await axios.get(nextPage.value, {
      headers: { Accept: 'application/json' }
    })
    if (res.data.tweets && res.data.tweets.length > 0) {
      tweets.value.push(...res.data.tweets)
      nextPage.value = res.data.nextPage
    } else {
      nextPage.value = null
    }
  } catch (err) {
    console.error('Scroll fetch error:', err)
  } finally {
    loadingMore.value = false
  }
}

const preferences = props.auth.user.preferences?.split(',') || []
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

const form = useForm({
  content: '',
  exam_tags: [],
})
</script>
