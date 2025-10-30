<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'

const props = defineProps({
  usulankegiatan: Object,
  laporankegiatan: Object,
})

const form = useForm({
  statususulan_kegiatan: '',
  dokumenpendukung_kegiatan: null,
  dokumenPK_kegiatan: null,
});

// progress awal
const progress = ref(
  props.usulankegiatan?.statususulan_kegiatan === 'approved'
    ? 'in_progress'
    : props.usulankegiatan?.statususulan_kegiatan || ''
)

// file input
const dokumenPendukung = ref(null)
const dokumenKompetensi = ref(null)

// form data untuk in_progress (usulan)
const formUsulan = useForm({
  statususulan_kegiatan: progress.value,
})

// handler file input
function handleDokumenPendukung(e) {
  if (e.target.files && e.target.files.length > 0) {
    dokumenPendukung.value = e.target.files[0]
  }
}

function handleDokumenKompetensi(e) {
  if (e.target.files && e.target.files.length > 0) {
    dokumenKompetensi.value = e.target.files[0]
  }
}

// submit function
function submit() {
  if (!progress.value) return

  Swal.fire({
    title: 'Memperbarui...',
    text: 'Mohon tunggu sebentar.',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  })

  if (progress.value === 'in_progress') {
    formUsulan.statususulan_kegiatan = 'in_progress'

    router.patch(`/usulan/${props.usulankegiatan.id}/update-progress`, formUsulan,
      {
        onSuccess: () => {
          Swal.close()
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Status kegiatan diperbarui ke Sedang Dilaksanakan.',
          })
        },
        onError: (errors) => {
          Swal.close()
          console.error(errors)
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat memperbarui status kegiatan.',
          })
        },
      }
    )
  } else if (progress.value === 'completed') {
    if (!props.laporankegiatan || !props.laporankegiatan.id) {
      Swal.close()
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Data laporan kegiatan tidak ditemukan.',
      })
      return
    }

    const formData = new FormData()
    formData.append('_method', 'PATCH') // <--- tambahkan ini!

    formData.append('statususulan_kegiatan', progress.value)
    if (dokumenPendukung.value)
      formData.append('dokumenpendukung_kegiatan', dokumenPendukung.value)
    if (dokumenKompetensi.value)
      formData.append('dokumenPK_kegiatan', dokumenKompetensi.value)

    router.post(`/laporan/${props.laporankegiatan.id}/update-progress`, formData, {
      forceFormData: true,
      onSuccess: () => {
        Swal.close()
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Kegiatan selesai dan dokumen berhasil diunggah.',
        })
      },
      onError: (errors) => {
        Swal.close()
        console.error(errors)
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: 'Terjadi kesalahan saat memperbarui progress kegiatan.',
        })
      },
    })
  }
}
</script>

<template>
  <div class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow space-y-6">
    <h2 class="text-xl font-bold text-gray-800">Update Progress Kegiatan</h2>

    <div>
      <label class="block font-semibold mb-1">Nama Kegiatan</label>
      <p class="text-gray-700">{{ props.usulankegiatan?.nama_kegiatan }}</p>
    </div>

    <div>
      <label class="block font-semibold mb-1">Progress Kegiatan</label>
      <select v-model="progress" class="border rounded p-2 w-full">
        <option value="in_progress">Sedang Dilaksanakan</option>
        <option value="completed">Selesai Dilaksanakan</option>
      </select>
    </div>

    <div v-if="progress === 'completed'" class="mt-4">
      <label class="block font-semibold mb-2">Upload Dokumen Pendukung</label>
      <input type="file" @change="handleDokumenPendukung" class="border p-2 rounded w-full" />
      <p class="text-xs text-gray-500 mt-1">
        Format: PDF, DOC, atau Gambar (max 2MB)
      </p>

      <label class="block font-semibold mt-4 mb-2">
        Upload Dokumen Pengembangan Kompetensi
      </label>
      <input type="file" @change="handleDokumenKompetensi" class="border p-2 rounded w-full" />
      <p class="text-xs text-gray-500 mt-1">
        Format: PDF, DOC, atau Gambar (max 2MB)
      </p>
    </div>

    <div class="flex justify-end mt-6">
      <button @click="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Simpan Perubahan
      </button>
    </div>
  </div>
</template>
