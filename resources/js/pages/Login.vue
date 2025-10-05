<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <form
      @submit.prevent="submit"
      class="bg-black p-6 rounded-2xl shadow-md w-full max-w-sm"
    >
      <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

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

      <button
        type="submit"
        class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition"
      >
        Login
      </button>

      <p v-if="errorMessage" class="text-red-500 text-sm mt-3">
        {{ errorMessage }}
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const form = ref({
  email: "",
  password: "",
});

const errorMessage = ref("");

async function submit() {
  try {
    errorMessage.value = "";
    await axios.post("/login", form.value); // arahkan ke route Laravel `/login`
    window.location.href = "/"; // redirect setelah sukses (ubah sesuai kebutuhan)
  } catch (error) {
    if (error.response && error.response.data) {
      errorMessage.value = error.response.data.message || "Login gagal!";
    } else {
      errorMessage.value = "Terjadi kesalahan!";
    }
  }
}
</script>
