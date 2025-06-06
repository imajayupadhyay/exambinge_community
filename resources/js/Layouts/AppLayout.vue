<template>
  <div class="min-h-screen bg-gray-100 text-gray-900 font-sans">
    <!-- HEADER -->
    <header class="bg-white shadow sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <!-- Logo -->
        <h1 class="text-2xl font-bold text-orange-500">ExamBinge</h1>

        <!-- Desktop Search Bar -->
        <div class="hidden lg:block w-1/2">
          <SearchBar class="w-full" />
        </div>

        <!-- Desktop Nav -->
        <div class="hidden md:flex items-center gap-5">
          <Link href="/tweets" class="text-gray-700 hover:text-orange-500 font-medium text-sm">Feed</Link>
          <Link href="/profile" class="text-gray-700 hover:text-orange-500 font-medium text-sm">Profile</Link>

          <div class="flex items-center gap-2">
            <img
              :src="user?.avatar || defaultAvatar"
              alt="avatar"
              class="w-9 h-9 rounded-full border object-cover border-orange-400"
            />
            <span class="text-sm font-medium text-gray-800">{{ user?.name }}</span>
          </div>

          <form @submit.prevent="logout">
            <button
              type="submit"
              class="text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 px-3 py-1.5 rounded transition"
            >
              Logout
            </button>
          </form>
        </div>

        <!-- Mobile Menu Icon -->
        <div class="md:hidden">
          <button @click="mobileOpen = true">
            <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </header>

    <!-- Mobile Slide-In Menu -->
    <transition name="slide">
      <div
        v-if="mobileOpen"
        class="fixed top-0 right-0 w-72 h-full bg-white shadow-lg z-50 p-5 flex flex-col gap-4"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-orange-500">Menu</h2>
          <button @click="mobileOpen = false">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <SearchBar />

        <Link href="/tweets" class="block text-gray-800 font-medium hover:text-orange-500">Feed</Link>
        <Link href="/profile" class="block text-gray-800 font-medium hover:text-orange-500">Profile</Link>

        <div class="flex items-center gap-2 mt-2">
          <img
            :src="user?.avatar || defaultAvatar"
            alt="avatar"
            class="w-8 h-8 rounded-full border object-cover border-orange-400"
          />
          <span class="text-sm font-medium text-gray-800">{{ user?.name }}</span>
        </div>

        <form @submit.prevent="logout">
          <button
            type="submit"
            class="mt-3 w-full bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded text-sm font-semibold"
          >
            Logout
          </button>
        </form>
      </div>
    </transition>

    <!-- Page Content -->
    <main class="py-6 px-4">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'
import SearchBar from '@/Pages/Tweets/Components/SearchBar.vue'
import { ref } from 'vue'

const user = usePage().props.auth?.user || null
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

const logoutForm = useForm({})
const logout = () => logoutForm.post(route('logout'))

const mobileOpen = ref(false)
</script>

<style scoped>
.slide-enter-active, .slide-leave-active {
  transition: all 0.3s ease;
}
.slide-enter-from {
  transform: translateX(100%);
}
.slide-enter-to {
  transform: translateX(0%);
}
.slide-leave-from {
  transform: translateX(0%);
}
.slide-leave-to {
  transform: translateX(100%);
}
</style>
