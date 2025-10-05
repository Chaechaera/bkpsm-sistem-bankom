<script setup>
const props = defineProps({
  latestUsulan: Object
})

const steps = [
  { key: 'draft', label: 'Pengajuan Usulan' },
  { key: 'pending', label: 'Menunggu Verifikasi' },
  { key: 'approved', label: 'Kegiatan Diterima' },
  { key: 'rejected', label: 'Kegiatan Ditolak' },
  { key: 'in_progress', label: 'Pelaksanaan Kegiatan' },
  { key: 'completed', label: 'Bukti Pelaksanaan Telah Diupload' },
  { key: 'finish', label: 'Selesai' }
]

function currentStepIndex() {
  return steps.findIndex(s => s.key === props.latestUsulan?.statususulan_kegiatan)
}
</script>

<template>
  <div class="p-6">
    <h2 class="text-xl font-bold mb-4">Tracking Usulan Terbaru</h2>

    <div v-if="latestUsulan">
      <p><b>Nama Kegiatan:</b> {{ latestUsulan.nama_kegiatan }}</p>
      <p><b>Status Saat Ini:</b> {{ latestUsulan.statususulan_kegiatan }}</p>

      <!-- Stepper -->
      <div class="flex items-center mt-6">
        <template v-for="(step, index) in steps" :key="step.key">
          <div
            class="rounded-full h-10 w-10 flex items-center justify-center"
            :class="index <= currentStepIndex()
              ? 'bg-green-600 text-white'
              : 'bg-gray-300 text-gray-700'">
            {{ index + 1 }}
          </div>
          <span class="ml-2">{{ step.label }}</span>
          <div v-if="index < steps.length - 1"
               class="flex-1 border-t-2 mx-2"
               :class="index < currentStepIndex() ? 'border-green-600' : 'border-gray-300'"></div>
        </template>
      </div>
    </div>

    <div v-else class="text-gray-500">
      Belum ada usulan kegiatan yang diajukan.
    </div>
  </div>
</template>
