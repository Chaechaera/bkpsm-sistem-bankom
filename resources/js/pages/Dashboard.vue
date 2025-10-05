<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head } from '@inertiajs/vue3';

// pastikan nama prop sama dengan yang dikirim server
const props = defineProps({
  auth: Object
});

const role = props.auth?.user?.role ?? null;
</script>

<template>
  <Head title="Dashboard" />

  <SidebarLayout>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Dashboard — {{ role === 'superadmin' ? 'Superadmin' : role === 'admin' ? 'Admin' : 'User' }}
        </h2>
      </template>

      <div class="mt-6">
        <!-- Blok khusus superadmin -->
        <div v-if="role === 'superadmin'" class="p-4 bg-red-50 rounded-xl shadow mb-4">
          <h3 class="font-semibold">Area Superadmin</h3>
          <p>Konten khusus Superadmin</p>
        </div>

        <!-- Blok khusus admin -->
        <div v-else-if="role === 'admin'" class="p-4 bg-blue-50 rounded-xl shadow mb-4">
          <h3 class="font-semibold">Area Admin</h3>
          <p>Konten khusus Admin</p>
        </div>

        <!-- Konten umum -->
        <div class="p-4 bg-gray-50 rounded-xl shadow">
          <h3 class="font-semibold">Statistik Umum</h3>
          <p>Ini terlihat oleh semua role yang diizinkan.</p>
        </div>
      </div>
    </AuthenticatedLayout>
  </SidebarLayout>
</template>


<!--<template>

    <Head title="Dashboard" />

    <SidebarLayout>
        <AuthenticatedLayout>
            <!-- Dashboard Content
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard Admin
                </h2>
            </template>

            <!-- Cards
            <div class="mt-6">
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-orange-50 p-6 rounded-xl shadow">
                        <h3 class="font-semibold text-gray-700">Total Usulan</h3>
                        <p class="text-3xl font-bold text-gray-900">5</p>
                        <p class="text-sm text-red-500">+2 dari bulan lalu</p>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-xl shadow">
                        <h3 class="font-semibold text-gray-700">Disetujui</h3>
                        <p class="text-3xl font-bold text-gray-900">3</p>
                        <p class="text-sm text-blue-500">60% approval rate</p>
                    </div>

                    <div class="bg-pink-50 p-6 rounded-xl shadow">
                        <h3 class="font-semibold text-gray-700">Sertifikat</h3>
                        <p class="text-3xl font-bold text-gray-900">2</p>
                        <p class="text-sm text-pink-500">Sertifikat Diterbitkan</p>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </SidebarLayout>

</template>-->