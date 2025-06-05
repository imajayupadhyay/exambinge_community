<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-6">
      <LeftSidebar :preferences="preferences" />

      <main class="md:col-span-6 col-span-12">
        <TweetForm
          :form="form"
          :examTags="examTags"
          @tweet-posted="handleTweetPosted"
        />
        <TweetList :tweets="tweets" :defaultAvatar="defaultAvatar" />
      </main>

      <RightSidebar :examTags="examTags" />
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import LeftSidebar from '@/Components/Tweets/LeftSidebar.vue'
import RightSidebar from '@/Components/Tweets/RightSidebar.vue'
import TweetForm from '@/Components/Tweets/TweetForm.vue'
import TweetList from '@/Components/Tweets/TweetList.vue'

import { useForm } from '@inertiajs/vue3'
import { defineProps, ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  tweets: Array,
  examTags: Array,
  auth: Object,
})

const tweets = ref([...props.tweets])
const preferences = props.auth.user.preferences?.split(',') || []
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

const form = useForm({
  content: '',
  exam_tags: [],
})

// When a new tweet is posted, insert it at the top
const handleTweetPosted = (tweet) => {
  tweets.value.unshift(tweet)
}
</script>
