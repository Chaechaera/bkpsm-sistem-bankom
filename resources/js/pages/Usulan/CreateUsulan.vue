<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
  identitassurats: Array,
  subunitkerjas: String,
  carapelatihans: Array,
  createdby: String,
})

const form = useForm({
  subunitkerja_id: '',
  identitassurat_id: '',
  nama_kegiatan: '',
  lokasi_kegiatan: '',
  carapelatihan_id: '',
  tanggal_pelaksanaan: '',
  statususulan_kegiatan: '',
  created_by: '',
})

const draft = () => {
  form.statususulan_kegiatan = 'draft'
  form.post(route('usulan.store'), {
    onSuccess: () => { console.log('Usulan berhasil disimpan pada draft') }
  })
};

const submit = () => {
  form.statususulan_kegiatan = 'submit'
  form.post(route('usulan.store'), {
    onSuccess: () => { console.log('Usulan berhasil diajukan') }
  });
};
</script>

<template>

  <Head title="Buat Usulan Kegiatan" />

  <form @submit.prevent="submit">
    <div class="max-w-4xl mx-auto p-6">
      <h1 class="text-2xl font-semibold mb-4">Buat Usulan Kegiatan</h1>

      <div class="mt-4">
        <InputLabel class="block" value="Sub Unit Kerja" />
        <TextInput id="subunitkerjas" type="text" class="mt-1 block w-full" v-model="props.subunitkerjas" readonly />
        <input type="hidden" v-model="form.subunitkerja_id" />
      </div>

      <!--<div class="mb-4">
        <label class="block">Subunit Kerja</label>
        <input type="text" class="border p-2 w-full bg-gray-100" :value="props.subunits" readonly />
      </div>-->

      <div class="mt-4">
        <InputLabel for="identitassurat_id" value="Identitas Surat" />
        <select id="identitassurat_id" name="identitassurat_id" v-model="form.identitassurat_id"
          class="mt-1 block w-full" required>
          <option disabled value="">-- Pilih Identitas Surat --</option>
          <option v-for="i in identitassurats" :key="i.id" :value="i.id">
            {{ i.nomor_surat }} / {{ i.tanggal_surat }} / {{ i.perihal }}
          </option>
        </select>
        <InputError class="mt-2" :message="form.errors.identitassurat_id" />
      </div>

      <div class="mt-4">
        <InputLabel for="nama_kegiatan" value="Nama Kegiatan" />
        <TextInput id="nama_kegiatan" type="text" class="mt-1 block w-full" v-model="form.nama_kegiatan" required
          autofocus autocomplete="nama_kegiatan" />
        <InputError class="mt-2" :message="form.errors.nama_kegiatan" />
      </div>

      <div class="mt-4">
        <InputLabel for="lokasi_kegiatan" value="Lokasi Kegiatan" />
        <TextInput id="lokasi_kegiatan" type="text" class="mt-1 block w-full" v-model="form.lokasi_kegiatan" required
          autofocus autocomplete="lokasi_kegiatan" />
        <InputError class="mt-2" :message="form.errors.lokasi_kegiatan" />
      </div>

      <div class="mt-4">
        <InputLabel for="carapelatihan_id" value="Cara Pelatihan" />
        <select id="carapelatihan_id" name="carapelatihan_id" v-model="form.carapelatihan_id" class="mt-1 block w-full"
          required>
          <option disabled value="">-- Pilih Cara Pelatihan Dilakukan --</option>
          <option v-for="c in carapelatihans" :key="c.id" :value="c.id">
            {{ c.cara_pelatihan }}
          </option>
        </select>
        <InputError class="mt-2" :message="form.errors.carapelatihan_id" />
      </div>

      <div class="mt-4">
        <InputLabel class="block" for="tanggal_pelaksanaan" value="Tanggal Pelaksanaan" />
        <TextInput id="tanggal_pelaksanaan" type="date" class="mt-1 block w-full" v-model="form.tanggal_pelaksanaan"
          required autofocus autocomplete="tanggal_pelaksanaan" />
        <InputError class="mt-2" :message="form.errors.tanggal_pelaksanaan" />
      </div>

      <div class="mt-4">
        <InputLabel class="block" value="Created By" />
        <TextInput id="createdby" type="text" class="mt-1 block w-full" v-model="props.createdby" readonly />
        <input type="hidden" v-model="form.created_by" />
      </div>

      <div class="mt-4 flex items-center justify-end">
        <!-- Tombol Simpan Draft -->
        <PrimaryButton type="button" class="ms-4 bg-gray-500 hover:bg-gray-600"
          :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="draft">
          Simpan Draft
        </PrimaryButton>

        <!-- Tombol Kirim Usulan -->
        <PrimaryButton type="submit" class="ms-4 bg-blue-600 hover:bg-blue-700"
          :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Kirim Usulan
        </PrimaryButton>
      </div>
    </div>
  </form>
</template>
