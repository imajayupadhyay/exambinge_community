<template>
  <div class="mb-8 p-6 bg-white border border-gray-200 rounded-2xl shadow-xl animate-fade-in-up space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">What's on your mind?</h2>

    <!-- RICH TEXT EDITOR -->
    <div ref="editorRoot" class="border border-gray-300 rounded-lg overflow-hidden"></div>

    <!-- EXAM TAGS -->
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-2">Exam Tags:</label>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="tag in availableTags"
          :key="tag"
          :class="[
            'px-3 py-1 rounded-full text-sm font-medium transition',
            form.exam_tags.includes(tag)
              ? 'bg-orange-500 text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-orange-100 hover:text-orange-600',
          ]"
          @click.prevent="toggleTag(tag)"
        >
          #{{ tag }}
        </button>
      </div>
    </div>

    <!-- IMAGE UPLOAD -->
    <div class="border border-dashed border-gray-300 p-4 rounded-lg relative bg-gray-50 hover:bg-gray-100 transition">
      <label class="block text-sm font-semibold text-gray-700 mb-2">Attach Image:</label>
      <input
        type="file"
        accept="image/*"
        @change="handleImageUpload"
        class="absolute inset-0 opacity-0 cursor-pointer"
      />
      <div class="text-gray-500 text-sm">Drag & drop or click to upload image</div>
      <div v-if="imagePreview" class="mt-4">
        <img :src="imagePreview" class="max-h-64 rounded border shadow" />
      </div>
    </div>

    <!-- ACTIONS -->
    <div class="flex justify-end items-center gap-4">
      <button
        @click="submitTweet"
        :disabled="form.processing"
        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded-full shadow-lg transition"
      >
        🚀 Post
      </button>
    </div>

    <!-- PROGRESS -->
    <div v-if="showProgress" class="h-1 bg-orange-200 rounded-full overflow-hidden">
      <div class="h-full bg-orange-500 animate-progress"></div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import Editor from '@toast-ui/editor'

const emit = defineEmits(['tweet-posted'])

const availableTags = ['UPSC', 'SSC', 'Banking', 'Railway', 'Defence']

const form = useForm({
  content: '',
  exam_tags: [],
})

const imageFile = ref(null)
const imagePreview = ref(null)
const showProgress = ref(false)

let editorInstance = null
const editorRoot = ref(null)

onMounted(() => {
  editorInstance = new Editor({
    el: editorRoot.value,
    height: '200px',
    initialEditType: 'wysiwyg',
    previewStyle: 'vertical',
    usageStatistics: false,
  })
})

const toggleTag = (tag) => {
  const index = form.exam_tags.indexOf(tag)
  if (index > -1) {
    form.exam_tags.splice(index, 1)
  } else {
    form.exam_tags.push(tag)
  }
}

const handleImageUpload = (e) => {
  const file = e.target.files[0]
  if (file) {
    imageFile.value = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const submitTweet = async () => {
  try {
    showProgress.value = true
    const formData = new FormData()
    formData.append('content', editorInstance.getMarkdown())
    form.exam_tags.forEach(tag => formData.append('exam_tags[]', tag))
    if (imageFile.value) formData.append('image', imageFile.value)

    const response = await axios.post(route('tweets.store'), formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    form.reset()
    imageFile.value = null
    imagePreview.value = null
    editorInstance.setMarkdown('')

    if (response.data.tweet) {
      emit('tweet-posted', response.data.tweet)
      setTimeout(() => window.location.reload(), 1500)
    }
  } catch (err) {
    console.error('Tweet post failed:', err)
    showProgress.value = false
  }
}
</script>

<style scoped>
@keyframes progress {
  0% {
    width: 0%;
  }
  100% {
    width: 100%;
  }
}
.animate-progress {
  animation: progress 1.5s ease-in-out forwards;
}
</style>

<style>
@import '@toast-ui/editor/dist/toastui-editor.css';
</style>
