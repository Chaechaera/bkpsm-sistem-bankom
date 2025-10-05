<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <form
      @submit.prevent="submit"
      class="bg-white p-6 rounded-2xl shadow-md w-full max-w-md"
    >
      <h2 class="text-2xl font-bold mb-4 text-center">Register</h2>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">NIP (Opsional)</label>
        <input
          v-model="form.nip"
          type="text"
          placeholder="Masukkan NIP"
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Nama</label>
        <input
          v-model="form.nama"
          type="text"
          placeholder="Masukkan nama"
          required
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Email</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="Masukkan email"
          required
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Password</label>
        <input
          v-model="form.password"
          type="password"
          placeholder="Masukkan password"
          required
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Konfirmasi Password</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          placeholder="Konfirmasi password"
          required
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition"
        :disabled="form.processing"
      >
        Register
      </button>

      <p v-if="form.errors.email" class="text-red-500 text-sm mt-2">
        {{ form.errors.email }}
      </p>
      <p v-if="form.errors.password" class="text-red-500 text-sm">
        {{ form.errors.password }}
      </p>
    </form>
  </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

const form = useForm({
  nip: "",
  nama: "",
  email: "",
  password: "",
  password_confirmation: "",
});

function submit() {
  form.post(route("register"));
}
</script>
