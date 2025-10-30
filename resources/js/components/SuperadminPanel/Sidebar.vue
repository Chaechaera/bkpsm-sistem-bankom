<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const currentRoute = page.url
</script>

<template>
  <aside class="w-64 bg-white shadow-md flex flex-col">
    <!-- Logo -->
    <div class="flex items-center gap-2 p-4 border-b">
      <img src="/logo.png" alt="Logo" class="w-10 h-10" />
      <span class="font-bold text-lg">SIKOMPETEN</span>
    </div>

    <!-- Menu -->
    <nav class="flex-1 p-4 space-y-2">
      <!-- Dashboard -->
      <Link
        :href="route('dashboard')"
        class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium"
        :class="currentRoute.startsWith('/dashboard')
          ? 'bg-indigo-100 text-indigo-600'
          : 'text-gray-700 hover:bg-gray-100'"
      >
        <i class="fa-solid fa-chart-line"></i>
        Dashboard
      </Link>

      <!-- Daftar Usulan Yang Masuk -->
      <Link
        :href="route('usulan.pending')"
        class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium"
        :class="currentRoute.startsWith('/superadmin/usulan/pending')
          ? 'bg-indigo-100 text-indigo-600'
          : 'text-gray-700 hover:bg-gray-100'"
      >
        <i class="fa-solid fa-list"></i>
        Daftar Usulan Yang Masuk
      </Link>

      <!-- Pengakuan PK ASN -->
      <Link
        href="/pengajuan"
        class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium"
        :class="currentRoute.startsWith('/pengajuan')
          ? 'bg-indigo-100 text-indigo-600'
          : 'text-gray-700 hover:bg-gray-100'"
      >
        <i class="fa-solid fa-box"></i>
        Pengakuan PK ASN
      </Link>

      <!-- Other Section -->
      <div class="mt-6 text-xs text-gray-400 uppercase">Other</div>

      <Link
        href="#"
        class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium text-gray-700 hover:bg-gray-100"
      >
        <i class="fa-solid fa-headset"></i>
        Support
      </Link>

      <Link
        href="#"
        class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium text-gray-700 hover:bg-gray-100"
      >
        <i class="fa-solid fa-gear"></i>
        Settings
      </Link>
    </nav>
  </aside>
</template>
