<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg p-6 relative animate-fade-in-up space-y-4">
      <button @click="$emit('close')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✖</button>

      <h2 class="text-xl font-bold text-gray-800">Edit Your Post</h2>

      <!-- Toast UI Editor -->
      <div ref="editorRoot" class="border border-gray-300 rounded-lg overflow-hidden"></div>

      <!-- Image Upload -->
      <div class="border border-dashed border-gray-300 p-3 rounded-lg bg-gray-50 relative">
        <input
          type="file"
          accept="image/*"
          @change="handleImageUpload"
          class="absolute inset-0 opacity-0 cursor-pointer"
        />
        <p class="text-sm text-gray-500">Click or drag image to replace</p>

        <div v-if="imagePreview" class="mt-3">
          <img :src="imagePreview" class="max-h-48 rounded shadow" />
        </div>
        <div v-else-if="tweet.image" class="mt-3">
          <img :src="tweet.image" class="max-h-48 rounded shadow" />
        </div>
      </div>

      <!-- Actions -->
      <div class="flex justify-end gap-3 pt-2">
        <button @click="$emit('close')" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
        <button
          @click="updateTweet"
          class="px-5 py-2 bg-orange-500 text-white rounded-lg shadow hover:bg-orange-600"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Editor from '@toast-ui/editor'

const props = defineProps({ tweet: Object })
const emit = defineEmits(['close', 'updated'])

const editorRoot = ref(null)
let editorInstance = null

const imageFile = ref(null)
const imagePreview = ref(null)

onMounted(() => {
  editorInstance = new Editor({
    el: editorRoot.value,
    height: '200px',
    initialEditType: 'wysiwyg',
    initialValue: props.tweet.content || '',
    previewStyle: 'vertical',
    usageStatistics: false,
  })
})

const handleImageUpload = (e) => {
  const file = e.target.files[0]
  if (file) {
    imageFile.value = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const updateTweet = () => {
  const form = useForm({
    content: editorInstance.getMarkdown(),
    image: imageFile.value,
    _method: 'patch', // 👈 method spoofing for Laravel PATCH route
  })

  form.post(route('myfeeds.update', props.tweet.id), {
    forceFormData: true, // 👈 required for file upload
    preserveScroll: true,
    onSuccess: () => emit('updated'),
  })
}
</script>

<style scoped>
@keyframes fade-in-up {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
  animation: fade-in-up 0.3s ease-out;
}
</style>

<style>
@import '@toast-ui/editor/dist/toastui-editor.css';
</style>
