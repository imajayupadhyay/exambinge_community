<template>
  <input
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    type="text"
    placeholder="Search followers..."
    class="w-full p-2 border rounded-md shadow-sm focus:ring-orange-400 focus:outline-none"
  />
</template>

<script setup>
defineProps(['modelValue'])
defineEmits(['update:modelValue'])
</script>
