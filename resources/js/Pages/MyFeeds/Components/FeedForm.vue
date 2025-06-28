<template>
  <form @submit.prevent="submit" class="bg-white rounded-2xl shadow p-6 space-y-5 border border-gray-200">
    <div ref="editorRoot" class="border border-gray-300 rounded-lg overflow-hidden"></div>

    <!-- Image -->
    <div class="border border-dashed border-gray-300 p-3 rounded-lg bg-gray-50 relative">
      <input
        type="file"
        accept="image/*"
        @change="handleImageUpload"
        class="absolute inset-0 opacity-0 cursor-pointer"
      />
      <p class="text-sm text-gray-500">Attach image (optional)</p>
      <div v-if="imagePreview" class="mt-3">
        <img :src="imagePreview" class="max-h-48 rounded shadow" />
      </div>
    </div>

    <div class="flex justify-end">
      <button
        type="submit"
        class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-full font-semibold shadow"
        :disabled="form.processing"
      >
        🚀 Post
      </button>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import Editor from '@toast-ui/editor'

const props = defineProps({ user: Object })
const emit = defineEmits(['created'])

const imageFile = ref(null)
const imagePreview = ref(null)
let editorInstance = null
const editorRoot = ref(null)

const form = useForm({
  content: '',
})

onMounted(() => {
  editorInstance = new Editor({
    el: editorRoot.value,
    height: '200px',
    initialEditType: 'wysiwyg',
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

const submit = () => {
  const formData = new FormData()
  formData.append('content', editorInstance.getMarkdown())
  if (imageFile.value) formData.append('image', imageFile.value)

  axios.post(route('myfeeds.store'), formData)
    .then(() => {
      emit('created')
      editorInstance.setMarkdown('')
      imageFile.value = null
      imagePreview.value = null
    })
}
</script>

<style>
@import '@toast-ui/editor/dist/toastui-editor.css';
</style>
