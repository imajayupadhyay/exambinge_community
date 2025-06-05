<template>
  <AppLayout>
    <div class="max-w-5xl mx-auto p-6 animate-fade-in">
      <!-- PROFILE CARD -->
      <div class="bg-white shadow-xl rounded-xl p-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div class="flex items-center gap-4">
          <img
            :src="user.profile_photo_path ? `/storage/${user.profile_photo_path}` : defaultAvatar"
            class="w-16 h-16 rounded-full border-2 border-orange-500 object-cover"
          />
          <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ user.name }}</h2>
            <p class="text-sm text-gray-600">{{ user.email }}</p>
            <span
              v-if="user.role === 'teacher'"
              class="inline-block text-xs text-white bg-blue-600 px-2 py-1 mt-1 rounded-full"
            >
              🎓 Teacher
            </span>
          </div>
        </div>
        <!-- Completion Bar -->
        <div class="w-full md:w-56">
          <p class="text-sm text-gray-700 font-medium mb-1">Profile Completion</p>
          <div class="w-full bg-gray-200 h-3 rounded-full">
            <div :style="{ width: completion + '%' }" class="h-3 bg-orange-500 rounded-full transition-all"></div>
          </div>
          <p class="text-xs text-gray-500 mt-1">{{ completion }}% Complete</p>
        </div>
      </div>

      <!-- EDIT FORM -->
      <div class="bg-white shadow-lg rounded-xl mt-8 p-6 animate-fade-in-up">
        <h3 class="text-lg font-bold mb-4">Update Profile Info</h3>
        <form @submit.prevent="submitForm" enctype="multipart/form-data">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input v-model="form.name" type="text" placeholder="Full Name" class="input" />
            <input v-model="form.email" type="email" placeholder="Email" class="input" />
            <input v-model="form.phone" type="text" placeholder="Phone Number" class="input" />
            <input v-model="form.age" type="number" placeholder="Age" class="input" />
            <input v-model="form.city" type="text" placeholder="City" class="input" />
            <input type="file" @change="onFileChange" class="input" accept="image/*" />
          </div>
          <div v-if="form.errors" class="text-red-500 text-sm mt-2" v-for="(error, key) in form.errors" :key="key">
            {{ error }}
          </div>
          <button type="submit" class="mt-4 bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg shadow">
            Save Changes
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage, useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const user = page.props.user || page.props.auth.user
const defaultAvatar = 'https://ui-avatars.com/api/?name=EB+User&background=f97316&color=fff'

const completion = ref(75)
const photo = ref(null)

const form = useForm({
  name: user.name || '',
  email: user.email || '',
  phone: user.phone || '',
  age: user.age || '',
  city: user.city || '',
  profile_photo: null,
})

const onFileChange = (e) => {
  photo.value = e.target.files[0]
  form.profile_photo = photo.value
}

const submitForm = () => {
  const formData = new FormData()
  formData.append('name', form.name)
  formData.append('email', form.email)
  formData.append('phone', form.phone)
  formData.append('age', form.age)
  formData.append('city', form.city)
  if (photo.value) {
    formData.append('profile_photo', photo.value)
  }
  formData.append('_method', 'PATCH')

  router.post(route('profile.update'), formData, {
    forceFormData: true,
    onSuccess: () => alert('Profile updated successfully!'),
    onError: () => alert('Error updating profile. Check your input.'),
  })
}
</script>

<style scoped>
.input {
  @apply w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400;
}

@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.4s ease-out;
}
.animate-fade-in-up {
  animation: fade-in 0.6s ease-out;
}
</style>