<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  usulankegiatan: Object
})

const note = ref('')

function submit(action) {
  const actionLabel = action === 'approve' ? 'menyetujui' : 'menolak'
  const formattedAction = action === 'approve' ? 'approved' : 'rejected'

  Swal.fire({
    title: `Yakin ingin ${actionLabel} usulan ini?`,
    text: 'Tindakan ini tidak dapat dibatalkan setelah dikonfirmasi.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: action === 'approve' ? '#16a34a' : '#d33',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, lanjutkan',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      router.patch(`/superadmin/usulan/${props.usulankegiatan.id}/review`, {
        action: formattedAction,
        note: note.value
      }, {
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: `Usulan telah ${formattedAction === 'approved' ? 'disetujui' : 'ditolak'}.`,
          }).then(() => {
            router.visit('/superadmin/usulan/pending')
          })
        },
        onError: (error) => {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat memproses tindakan ini.'
          })
          console.error(error)
        }
      })
    }
  })
}
</script>

<template>
  <div class="p-6">
    <h2 class="text-xl font-bold mb-4">{{ props.usulankegiatan.nama_kegiatan }}</h2>
    <p><strong>Sub Unit:</strong> {{ props.usulankegiatan.subunitkerja?.sub_unitkerja }}</p>
    <p><strong>Unit:</strong> {{ props.usulankegiatan.subunitkerja?.unitkerja?.kode_unitkerja }}</p>

    <textarea v-model="note" class="border rounded w-full mt-4 p-2" placeholder="Catatan review..."></textarea>

    <div class="mt-4 flex gap-2">
      <button @click="submit('approve')" class="bg-green-600 text-white px-4 py-2 rounded">Setujui</button>
      <button @click="submit('reject')" class="bg-red-600 text-white px-4 py-2 rounded">Tolak</button>
    </div>

    <!-- Bagian Dokumen -->
    <div v-if="props.usulankegiatan.laporankegiatan" class="mt-8 border-t pt-4">
      <h3 class="text-lg font-semibold mb-2">📂 Dokumen Kegiatan</h3>
      <div>
        <div v-if="props.usulankegiatan.laporankegiatan.dokumenpendukung_kegiatan">
          <a :href="`/storage/${props.usulankegiatan.laporankegiatan.dokumenpendukung_kegiatan}`" target="_blank"
            class="text-blue-600 underline">
            Lihat Dokumen Pendukung
          </a>
        </div>
        <div v-if="props.usulankegiatan.laporankegiatan.dokumenPK_kegiatan" class="mt-2">
          <a :href="`/storage/${props.usulankegiatan.laporankegiatan.dokumenPK_kegiatan}`" target="_blank"
            class="text-blue-600 underline">
            Lihat Dokumen PK
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
