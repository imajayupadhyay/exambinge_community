<template>
  <div class="min-h-screen bg-gray-100 text-gray-900 font-sans">
    <!-- Header -->
    <header class="bg-white shadow sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <h1 class="text-xl font-bold text-orange-500">EB WebApp</h1>

        <!-- Right Menu -->
        <div class="flex items-center gap-4">
          <Link href="/tweets" class="text-gray-700 hover:text-orange-500 font-medium text-sm transition">
            Feed
          </Link>
          <Link href="/profile" class="text-gray-700 hover:text-orange-500 font-medium text-sm transition">
            Profile
          </Link>

          <!-- Authenticated User Info -->
          <div class="flex items-center gap-2">
            <img
              :src="user?.avatar || defaultAvatar"
              alt="avatar"
              class="w-8 h-8 rounded-full object-cover border border-orange-400"
            />
            <span class="text-sm font-medium text-gray-800">{{ user?.name }}</span>
          </div>

          <!-- Logout -->
          <form @submit.prevent="logout" method="POST">
            <button
              type="submit"
              class="text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 px-3 py-1.5 rounded transition"
            >
              Logout
            </button>
          </form>
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <main class="py-8 px-4">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'

const user = usePage().props.auth?.user || null
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

const logoutForm = useForm({})
const logout = () => logoutForm.post(route('logout'))
</script>
