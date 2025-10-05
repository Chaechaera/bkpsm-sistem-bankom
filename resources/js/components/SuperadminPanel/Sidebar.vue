<template>
  <aside class="w-64 bg-white shadow-md flex flex-col">
    <!-- Logo -->
    <div class="flex items-center gap-2 p-4 border-b">
      <img src="/logo.png" alt="Logo" class="w-10 h-10" />
      <span class="font-bold text-lg">SIKOMPETEN</span>
    </div>

    <!-- Menu -->
    <nav class="flex-1 p-4 space-y-2">
      <a
        href="/dashboard"
        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-100 text-indigo-600 font-medium"
      >
        <i class="fa-solid fa-chart-line"></i>
        Dashboard
      </a>
      <a
        href="/usulan"
        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700"
      >
        <i class="fa-solid fa-list"></i>
        Daftar Usulan Yang Masuk
      </a>
      <a
        href="/pengajuan"
        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700"
      >
        <i class="fa-solid fa-box"></i>
        Pengakuan PK ASN
      </a>

      <!-- Other -->
      <div class="mt-6 text-xs text-gray-400 uppercase">Other</div>
      <a
        href="#"
        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700"
      >
        <i class="fa-solid fa-headset"></i>
        Support
      </a>
      <a
        href="#"
        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700"
      >
        <i class="fa-solid fa-gear"></i>
        Settings
      </a>
    </nav>
  </aside>
</template>
