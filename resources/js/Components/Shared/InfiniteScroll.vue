<template>
  <div ref="scrollContainer">
    <slot />
    <div v-if="loading" class="text-center py-4 text-sm text-gray-500">
      Loading more tweets...
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const emit = defineEmits(['load-more'])
const props = defineProps({
  loading: Boolean,
})

const scrollContainer = ref(null)

const handleScroll = () => {
  const bottomReached = window.innerHeight + window.scrollY >= document.body.offsetHeight - 100
  if (bottomReached && !props.loading) {
    emit('load-more')
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>
