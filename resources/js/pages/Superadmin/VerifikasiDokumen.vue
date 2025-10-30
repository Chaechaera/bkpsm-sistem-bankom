<script setup>
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  usulankegiatan: Object
})

function verifikasi(status) {
  const title =
    status === 'verified'
      ? 'Apakah kamu yakin ingin memverifikasi laporan ini?'
      : 'Apakah kamu yakin ingin menolak laporan ini?'

  Swal.fire({
    title,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, lanjutkan',
    cancelButtonText: 'Batal',
  }).then((result) => {
    if (result.isConfirmed) {
      router.put(`/superadmin/usulan/${props.usulankegiatan.id}/verifikasi`, {
        status: status,
      })
    }
  })
}
</script>

<template>
  <div class="p-8 max-w-3xl mx-auto bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">
      Verifikasi Dokumen Laporan
    </h2>

    <div class="mb-4">
      <p><strong>Nama Kegiatan:</strong> {{ props.usulankegiatan.nama_kegiatan }}</p>
      <p><strong>Lokasi:</strong> {{ props.usulankegiatan.lokasi_kegiatan }}</p>
      <p><strong>Unit:</strong> {{ props.usulankegiatan.subunitkerja?.unitkerja?.kode_unitkerja }}</p>
      <p>
        <strong>Status Usulan:</strong>
        <span
          class="px-2 py-1 rounded text-white"
          :class="{
            'bg-blue-600': props.usulankegiatan.statususulan_kegiatan === 'approved',
            'bg-yellow-600': props.usulankegiatan.statususulan_kegiatan === 'in_progress',
            'bg-green-600': props.usulankegiatan.statususulan_kegiatan === 'finish',
          }"
        >
          {{ props.usulankegiatan.statususulan_kegiatan }}
        </span>
      </p>
    </div>

    <div class="mt-6 border-t pt-4">
      <h3 class="text-lg font-semibold mb-3 text-gray-700">📂 Dokumen Laporan</h3>
      <ul class="list-disc ml-6 space-y-2">
        <li
          v-if="props.usulankegiatan.laporankegiatan?.dokumenpendukung_kegiatan"
        >
          <a
            :href="`/storage/${props.usulankegiatan.laporankegiatan.dokumenpendukung_kegiatan}`"
            target="_blank"
            class="text-blue-600 underline"
          >
            Dokumen Pendukung
          </a>
        </li>
        <li v-else class="text-gray-500">Tidak ada dokumen pendukung</li>

        <li
          v-if="props.usulankegiatan.laporankegiatan?.dokumenPK_kegiatan"
        >
          <a
            :href="`/storage/${props.usulankegiatan.laporankegiatan.dokumenPK_kegiatan}`"
            target="_blank"
            class="text-blue-600 underline"
          >
            Dokumen Pengembangan Kompetensi (PK)
          </a>
        </li>
        <li v-else class="text-gray-500">Tidak ada dokumen PK</li>
      </ul>
    </div>

    <div class="mt-8 flex gap-3">
      <button
        @click="verifikasi('verified')"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition"
      >
        ✅ Verifikasi
      </button>
      <button
        @click="verifikasi('report_rejected')"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition"
      >
        ❌ Tolak Laporan
      </button>
    </div>
  </div>
</template>
