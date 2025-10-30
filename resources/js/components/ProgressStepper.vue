<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentStatus: String
})

const steps = [
  { key: 'draft', label: 'Pengajuan Usulan Kegiatan' },
  { key: 'pending', label: 'Menunggu Verifikasi Usulan Kegiatan' },
  { key: 'approved', label: 'Usulan Kegiatan Diterima' },
  { key: 'rejected', label: 'Usulan Kegiatan Ditolak' },
  { key: 'in_progress', label: 'Pelaksanaan Kegiatan' },
  { key: 'completed', label: 'Upload Bukti Pelaksanaan Kegiatan' },
  { key: 'in_review', label: 'Peninjauan Pengakuan JP' },
  { key: 'finish', label: 'Selesai dan Sertifikat Dapat Diakses' },
]

// 🔹 Filter step supaya hanya salah satu antara "approved" atau "rejected" yang muncul
const filteredSteps = computed(() => {
  // kalau status sekarang rejected, tampilkan "rejected", sembunyikan "approved"
  if (props.currentStatus === 'rejected') {
    return steps.filter(step => step.key !== 'approved')
  }
  // kalau status sekarang approved atau setelahnya, sembunyikan "rejected"
  return steps.filter(step => step.key !== 'rejected')
})

// cari index posisi current status
const currentIndex = computed(() => steps.findIndex(step => step.key === props.currentStatus))
</script>

<template>
  <div class="flex items-center w-full">
    <template v-for="(step, index) in filteredSteps" :key="step.key">
      <div class="flex flex-col items-center">
        <div
          class="w-8 h-8 flex items-center justify-center rounded-full font-semibold transition-all duration-300"
          :class="{
            'bg-green-600 text-white': index === currentIndex,   // step aktif
            'bg-cyan-800 text-white': index < currentIndex,      // step sudah dilewati
            'bg-gray-300 text-black': index > currentIndex       // step belum tercapai
          }"
        >
          {{ index + 1 }}
        </div>
        <p class="text-xs mt-1 text-center">{{ step.label }}</p>
      </div>

      <!-- garis antar step -->
      <div
        v-if="index < steps.length - 1"
        class="flex-1 h-0.5 transition-all duration-300"
        :class="{
          'bg-cyan-800': index < currentIndex,    // garis untuk step sudah dilewati
          'bg-gray-300': index >= currentIndex    // garis untuk step berikutnya
        }"
      ></div>
    </template>
  </div>
</template>