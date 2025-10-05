<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
  kopExists: Boolean,
  ttdExists: Boolean
})

const form = useForm({
  kop_surat: null,
  tanda_tangan: null
})

function submit() {
  form.post(route('subunitkerja.update'), {
    forceFormData: true
  })
}
</script>

<template>
  <Head title="Upload Kop & TTD" />

  <div class="max-w-xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">Upload Kop & Tanda Tangan</h1>

    <div class="mb-4">
      <label class="block font-medium mb-2">Kop Surat</label>
      <input type="file" @change="e => form.kop_surat = e.target.files[0]" />
      <p v-if="props.kopExists" class="text-green-600 mt-1">✅ Sudah ada kop surat</p>
    </div>

    <div class="mb-4">
      <label class="block font-medium mb-2">Tanda Tangan</label>
      <input type="file" @change="e => form.tanda_tangan = e.target.files[0]" />
      <p v-if="props.ttdExists" class="text-green-600 mt-1">✅ Sudah ada tanda tangan</p>
    </div>

    <PrimaryButton @click="submit">Simpan</PrimaryButton>
  </div>
</template>
