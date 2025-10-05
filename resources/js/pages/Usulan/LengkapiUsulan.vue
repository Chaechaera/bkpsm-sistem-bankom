<script setup>
import { ref } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
  usulankegiatan: Object,
  metodes: Array,
})

const uploadProgress = ref(0)

const form = useForm({
  usulankegiatan_id: props.usulankegiatan.id ?? '',
  latarbelakang_kegiatan: '',
  dasarhukum_kegiatan: '',
  uraian_kegiatan: '',
  maksud_kegiatan: '',
  tujuan_kegiatan: '',
  hasil_kegiatan: '',
  narasumber_kegiatan: '',
  peserta_kegiatan: '',
  alokasianggaran_kegiatan: '',
  metodepelatihan_id: '',
  dokumen: null
})

function onDetailFileChange(event) {
  form.dokumen = event.target.files[0] ?? null
}

function submit() {
  // pastikan props.usulankegiatan ada
  if (!props.usulankegiatan || !props.usulankegiatan.id) {
    console.error('usulankegiatan belum tersedia di props')
    return
  }
  form.post(route('detail.store', props.usulankegiatan.id), {
    onBefore: () => { uploadProgress.value = 0 },
    onProgress: (event) => { uploadProgress.value = Math.round(event.detail.progress) },
    onSuccess: () => { /* redirect ditangani server */ }
  })
}

/**function submit() {
  if (!props.usulankegiatan?.id) return
  form.post(route('detail.store', props.usulankegiatan.id), {
    onBefore: () => { uploadProgress.value = 0 },
    onProgress: (event) => {
      uploadProgress.value = Math.round(event.detail.progress)
    }
  })
}*/
</script>

<template>

  <Head title="Lengkapi Usulan" />

  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Lengkapi Usulan Kegiatan</h1>

    <!-- Tampilkan data usulan (readonly) -->
    <div class="mb-6 p-4 border rounded bg-gray-50">
      <h3 class="font-semibold mb-2">Kegiatan yang Diusulkan</h3>
      <div class="mt-4">
        <InputLabel class="block" value="Nama Kegiatan" />
        <TextInput id="namakegiatan" type="text" class="mt-1 block w-full"
          :model-value="props.usulankegiatan?.nama_kegiatan ?? ''" readonly />
      </div>
      <div class="mt-4">
        <InputLabel class="block" value="Lokasi Kegiatan" />
        <TextInput id="lokasikegiatan" type="text" class="mt-1 block w-full"
          :model-value="props.usulankegiatan?.lokasi_kegiatan ?? ''" readonly />
      </div>
      <div class="mt-4">
        <InputLabel class="block" value="Tanggal Pelaksanaan" />
        <TextInput id="tanggalpelaksanaan" type="text" class="mt-1 block w-full"
          :model-value="props.usulankegiatan?.tanggal_pelaksanaan ?? ''" readonly />
      </div>
    </div>

    <!-- Identitas Surat (jika ada) -->
    <div v-if="props.usulankegiatan && props.usulankegiatan.identitassurat" class="mb-6 p-4 border rounded bg-gray-50">
      <h3 class="font-semibold mb-2">Identitas Surat</h3>
      <div class="mt-4">
        <InputLabel class="block" value="Nomor Surat" />
        <TextInput id="nomorsurat" type="text" class="mt-1 block w-full"
          :model-value="props.usulankegiatan.identitassurat.nomor_surat ?? ''" readonly />
      </div>
      <div class="mt-4">
        <InputLabel class="block" value="Tanggal Surat" />
        <TextInput id="tanggalsurat" type="text" class="mt-1 block w-full"
          :model-value="props.usulankegiatan.identitassurat.tanggal_surat ?? ''" readonly />
      </div>
      <div class="mt-4">
        <InputLabel class="block" value="Perihal" />
        <TextInput id="perihal" type="text" class="mt-1 block w-full"
          :model-value="props.usulankegiatan.identitassurat.perihal ?? ''" readonly />
      </div>
    </div>

    <!-- Form Detail -->
    <div class="p-4 border rounded">
      <h3 class="font-semibold mb-2">Detail Kegiatan</h3>
      <div class="mt-4">
        <InputLabel class="block" value="Latar Belakang" />
        <textarea id="latarbelakang" type="text" class="mt-1 block w-full"
          v-model="form.latarbelakang_kegiatan"></textarea>
      </div>
      <div class="mt-4">
        <InputLabel class="block" value="Dasar Hukum" />
        <textarea id="dasarhukum" type="text" class="mt-1 block w-full" v-model="form.dasarhukum_kegiatan"></textarea>
      </div>
      <div class="mt-4">
        <InputLabel class="block" value="Uraian Kegiatan" />
        <textarea id="uraiankegiatan" type="text" class="mt-1 block w-full" v-model="form.uraian_kegiatan"></textarea>
      </div>
      <div class="mt-4">
        <InputLabel class="block" value="Maksud Kegiatan" />
        <textarea id="maksudkegiatan" type="text" class="mt-1 block w-full" v-model="form.maksud_kegiatan"></textarea>
      </div>
      <div class="mt-4">
        <InputLabel class="block" value="Tujuan Kegiatan" />
        <textarea id="tujuankegiatan" type="text" class="mt-1 block w-full" v-model="form.tujuan_kegiatan"></textarea>
      </div>
      <div class="mt-4">
        <InputLabel class="block" value="Hasil Kegiatan" />
        <textarea id="hasilkegiatan" type="text" class="mt-1 block w-full" v-model="form.hasil_kegiatan"></textarea>
      </div>
      <div class="mt-4">
        <InputLabel for="narasumberkegiatan" value="Narasumber Kegiatan" />
        <TextInput id="narasumberkegiatan" type="text" class="mt-1 block w-full" v-model="form.narasumber_kegiatan"
          required autofocus autocomplete="narasumberkegiatan" />
        <InputError class="mt-2" :message="form.errors.narasumber_kegiatan" />
      </div>
      <div class="mt-4">
        <InputLabel for="pesertakegiatan" value="Peserta Kegiatan" />
        <TextInput id="pesertakegiatan" type="text" class="mt-1 block w-full" v-model="form.peserta_kegiatan" required
          autofocus autocomplete="pesertakegiatan" />
        <InputError class="mt-2" :message="form.errors.peserta_kegiatan" />
      </div>
      <div class="mt-4">
        <InputLabel for="alokasianggaran" value="Alokasi Anggaran" />
        <TextInput id="alokasianggaran" type="text" class="mt-1 block w-full" v-model="form.alokasianggaran_kegiatan"
          required autofocus autocomplete="alokasianggaran" />
        <InputError class="mt-2" :message="form.errors.alokasianggaran_kegiatan" />
      </div>
      <div class="mt-4">
        <InputLabel for="metodepelatihan_id" value="Metode Pelatihan" />
        <select id="metodepelatihan_id" name="metodepelatihan_id" v-model="form.metodepelatihan_id"
          class="mt-1 block w-full" required>
          <option disabled value="">-- Pilih Metode Pelatihan Kegiatan --</option>
          <option v-for="m in metodes" :key="m.id" :value="m.id">
            {{ m.metode_pelatihan }}
          </option>
        </select>
        <InputError class="mt-2" :message="form.errors.metodepelatihan_id" />
      </div>
      <div class="mt-4">
        <input type="file" @change="onDetailFileChange" />
      </div>

      <div v-if="uploadProgress > 0" class="mt-3">
        <div>Upload: {{ uploadProgress }}%</div>
        <div class="w-full bg-gray-200 h-2 rounded">
          <div :style="{ width: uploadProgress + '%' }" class="h-2 bg-blue-500 rounded"></div>
        </div>
      </div>

      <PrimaryButton class="mt-4" @click="submit">Kirim Usulan</PrimaryButton>
    </div>
  </div>
</template>