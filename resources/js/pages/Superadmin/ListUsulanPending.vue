<script setup>
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ 
  usulans: Array 
});

function reviewUsulan(id) {
  router.visit(`/superadmin/usulan/${id}/review`)
}

function verifikasiDokumen(id) {
  router.visit(`/superadmin/usulan/${id}/verifikasi-dokumen`)
}

</script>

<template>
    <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Daftar Usulan Menunggu Verifikasi</h1>

    <table class="w-full border border-gray-300 rounded-lg">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 text-left">Nama Kegiatan</th>
          <th class="p-2 text-left">Sub Unit Kerja</th>
          <th class="p-2 text-left">Unit Kerja</th>
          <th class="p-2 text-left">Surat Usulan</th>
          <th class="p-2 text-left">Status</th>
          <th class="p-2 text-center">Aksi</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="u in usulans" :key="u.id" class="border-t">
          <td class="p-2">{{ u.nama_kegiatan }}</td>
          <td class="p-2">{{ u.subunitkerja?.sub_unitkerja ?? '-' }}</td>
          <td class="p-2">{{ u.subunitkerja?.unitkerja?.kode_unitkerja ?? '-' }}</td>
          <td class="p-2">
              <a
                :href="route('usulan.download', u.id)"
                target="_blank"
                class="text-green-600 underline hover:text-green-700"
              >
                Lihat Surat
              </a>
            </td>

          <!-- Status warna dinamis -->
          <td
            class="p-2 capitalize font-semibold"
            :class="{
              'text-yellow-600': u.statususulan_kegiatan === 'pending',
              'text-green-600': u.statususulan_kegiatan === 'approved',
              'text-blue-600': u.statususulan_kegiatan === 'in_progress',
              'text-purple-600': u.statususulan_kegiatan === 'completed',
              'text-gray-500': u.statususulan_kegiatan === 'draft',
              'text-red-600': u.statususulan_kegiatan === 'rejected'
            }"
          >
            {{ u.statususulan_kegiatan }}
          </td>

          <!-- Tombol aksi -->
          <td class="p-2 text-center space-x-2">
            <!-- Tombol Review muncul hanya saat pending -->
            <button
              v-if="u.statususulan_kegiatan === 'pending'"
              @click="reviewUsulan(u.id)"
              class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
            >
              Review
            </button>

            <!-- Tombol Cek Dokumen muncul hanya saat completed -->
            <button
              v-if="u.statususulan_kegiatan === 'completed'"
              @click="verifikasiDokumen(u.id)"
              class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
            >
              Cek Dokumen
            </button>
          </td>
          
          <!--<td class="p-2 text-center">
            <button
              @click="reviewUsulan(u.id)"
              class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
            >
              Review
            </button>
          </td>-->
        </tr>
      </tbody>
    </table>
  </div>
</template>
