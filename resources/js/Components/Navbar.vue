<template>
  <header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <!-- Logo -->
      <h1 class="text-xl font-bold text-orange-600 tracking-wide">EB WebApp</h1>

      <!-- Desktop Navigation -->
      <nav class="hidden md:flex space-x-6 items-center">
        <SearchBar class="w-64" />
        <a href="/" class="text-gray-700 hover:text-orange-600 font-medium">Home</a>
        <a href="/dashboard" class="text-gray-700 hover:text-orange-600 font-medium">Dashboard</a>
        <a href="/login" class="text-gray-700 hover:text-orange-600 font-medium">Login</a>
      </nav>

      <!-- Mobile Hamburger -->
      <div class="md:hidden">
        <button @click="isOpen = !isOpen" class="text-gray-700 focus:outline-none">
          <svg v-if="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Dropdown -->
    <div v-if="isOpen" class="md:hidden px-4 pb-3 space-y-2 bg-white border-t">
      <SearchBar class="w-full" />
      <a href="/" class="block text-gray-700 hover:text-orange-600 font-medium">Home</a>
      <a href="/dashboard" class="block text-gray-700 hover:text-orange-600 font-medium">Dashboard</a>
      <a href="/login" class="block text-gray-700 hover:text-orange-600 font-medium">Login</a>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'
import SearchBar from '@/Components/Tweets/SearchBar.vue'

const isOpen = ref(false)
</script>
