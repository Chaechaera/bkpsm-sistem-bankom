<script setup>
const props = defineProps({
  currentStatus: String
})

const steps = [
  { key: 'draft', label: 'Pengajuan Usulan' },
  { key: 'pending', label: 'Menunggu Verifikasi' },
  { key: 'approved', label: 'Kegiatan Diterima' },
  { key: 'rejected', label: 'Kegiatan Ditolak' },
  { key: 'in_progress', label: 'Pelaksanaan Kegiatan' },
  { key: 'completed', label: 'Bukti Pelaksanaan Telah Diupload' },
  { key: 'finish', label: 'Selesai' },
]
</script>

<template>
  <div class="flex items-center">
    <template v-for="(step, index) in steps" :key="step.key">
      <div class="flex flex-col items-center">
        <div
          class="w-8 h-8 flex items-center justify-center rounded-full"
          :class="{
            'bg-green-600 text-white': step.key === currentStatus,
            'bg-gray-300 text-black': step.key !== currentStatus
          }"
        >
          {{ index + 1 }}
        </div>
        <p class="text-xs mt-1">{{ step.label }}</p>
      </div>
      <div v-if="index < steps.length - 1" class="flex-1 h-0.5 bg-gray-300"></div>
    </template>
  </div>
</template>
