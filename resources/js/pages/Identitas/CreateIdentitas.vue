<script setup>
import { useForm, Head } from '@inertiajs/vue3'

const form = useForm({
  nomor_surat: '',
  tanggal_surat: '',
  perihal: '',
  lampiran: null,
})

function onFileChange(event) {
  form.lampiran = event.target.files[0] ?? null
}

function submit() {
  form.post(route('identitassurat.store'), {
    onStart: () => {},
    onSuccess: () => {},
    onError: () => {}
  })
}
</script>

<template>
  <Head title="Buat Identitas Surat" />
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Buat Identitas Surat</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <!-- Nomor Surat -->
      <div>
        <label class="block font-medium">Nomor Surat</label>
        <input
          v-model="form.nomor_surat"
          class="border p-2 w-full"
          placeholder="Masukkan nomor surat"
        />
        <div v-if="form.errors.nomor_surat" class="text-red-600 text-sm">
          {{ form.errors.nomor_surat }}
        </div>
      </div>

      <!-- Tanggal Surat -->
      <div>
        <label class="block font-medium">Tanggal Surat</label>
        <input
          type="date"
          v-model="form.tanggal_surat"
          class="border p-2 w-full"
        />
        <div v-if="form.errors.tanggal_surat" class="text-red-600 text-sm">
          {{ form.errors.tanggal_surat }}
        </div>
      </div>

      <!-- Perihal -->
      <div>
        <label class="block font-medium">Perihal</label>
        <input
          v-model="form.perihal"
          class="border p-2 w-full"
          placeholder="Masukkan perihal"
        />
        <div v-if="form.errors.perihal" class="text-red-600 text-sm">
          {{ form.errors.perihal }}
        </div>
      </div>

      <!-- Lampiran -->
      <div>
        <label class="block font-medium">Lampiran</label>
        <input type="file" @change="onFileChange" />
        <div v-if="form.errors.lampiran" class="text-red-600 text-sm">
          {{ form.errors.lampiran }}
        </div>
      </div>

      <!-- Submit -->
      <div>
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded"
        >
          Simpan Identitas
        </button>
      </div>
    </form>
  </div>
</template>
