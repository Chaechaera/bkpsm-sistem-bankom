<script setup>
import ProgressStepper from '@/components/ProgressStepper.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  usulans: Array
});

function deleteUsulan(id) {
  if (confirm("Apakah kamu yakin ingin menghapus usulan ini?")) {
    router.delete(route('usulan.destroy', id));
  }
}
</script>

<template>
  <div class="p-6 space-y-6">

    <!-- Judul Halaman -->
    <h1 class="text-2xl font-bold mb-4">Daftar Usulan Kegiatan</h1>

    <!-- Bagian Progress Stepper (dipisah dari tabel) -->
    <div class="bg-gray-50 p-4 rounded-xl shadow-sm border">
      <h2 class="text-lg font-semibold mb-3">Progress Pengajuan</h2>
      <!-- tampilkan stepper umum -->
      <ProgressStepper :currentStatus="props.usulans.length ? props.usulans[0].statususulan_kegiatan : 'pending'" />
    </div>

    <!-- Tombol Aksi -->
    <div class="flex gap-3 mt-4">
      <Link href="/usulan/create/{{ surat }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
      + Buat Usulan Baru
      </Link>

      <Link :href="route('subunitkerja.edit')" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
      + Tambah Kop dan TTD
      </Link>
    </div>

    <!-- Tabel Daftar Usulan -->
    <div class="overflow-x-auto">
      <table class="w-full border border-gray-300 rounded-lg mt-4 text-sm">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="p-2 text-left">Nomor Identitas Surat</th>
            <th class="p-2 text-left">Nama Kegiatan</th>
            <th class="p-2 text-left">Tanggal Pelaksanaan</th>
            <th class="p-2 text-left">File Surat Usulan</th>
            <th class="p-2 text-left">Status Usulan</th>
            <th class="p-2 text-left">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="u in props.usulans" :key="u.id" class="border-t hover:bg-gray-50 transition">
            <!-- Nomor Surat -->
            <td class="p-2">{{ u.identitassurat?.nomor_surat ?? '-' }}</td>

            <!-- Nama Kegiatan -->
            <td class="p-2 font-medium">{{ u.nama_kegiatan }}</td>

            <!-- Tanggal -->
            <td class="p-2">{{ u.tanggal_pelaksanaan ?? '-' }}</td>

            <!-- File Surat -->
            <td class="p-2">
              <a :href="route('usulan.download', u.id)" target="_blank"
                class="text-green-600 underline hover:text-green-700">
                Lihat Surat
              </a>
            </td>

            <!-- Status -->
            <td class="p-2 capitalize font-semibold">
              <span :class="{
                'text-yellow-600': u.statususulan_kegiatan === 'pending',
                'text-green-600': u.statususulan_kegiatan === 'approved',
                'text-blue-600': u.statususulan_kegiatan === 'in_progress',
                'text-purple-600': u.statususulan_kegiatan === 'completed',
                'text-gray-500': u.statususulan_kegiatan === 'draft',
                'text-red-600': u.statususulan_kegiatan === 'rejected'
              }">
                {{ u.statususulan_kegiatan ? u.statususulan_kegiatan.replace('_', ' ') : '-' }}
              </span>
            </td>

            <!-- Aksi -->
            <td class="p-2 space-x-2">
              <!-- Lengkapi -->
              <Link :href="`/usulan/${u.id}/detail/create`" class="text-blue-600 hover:underline">
              Lengkapi
              </Link>

              <!-- Update -->
              <Link
                v-if="$page.props.auth.user.role === 'admin' && u.statususulan_kegiatan === 'approved', 'in_progress'"
                :href="route('usulan.editProgress', u.id)" class="text-indigo-600" :class="{
                  'hover:underline': u.statususulan_kegiatan !== 'rejected',
                  'opacity-50 cursor-not-allowed pointer-events-none': u.statususulan_kegiatan === 'rejected'
                }">
              Update Progress
              </Link>

<!-- Lihat Surat Balasan -->
<button
  v-if="u.laporankegiatan?.balasanlaporankegiatan?.file_path"
  @click="window.open(route('usulan.previewBalasan', u.laporankegiatan.balasanlaporankegiatan.id), '_blank')"
  class="text-green-600 hover:underline"
>
  Lihat Surat Balasan
</button>

<!-- Lihat Sertifikat -->
<button
  v-if="u.laporankegiatan?.balasanlaporankegiatan?.sertifikat?.file_path"
  @click="window.open(route('usulan.previewSertifikat', u.laporankegiatan.balasanlaporankegiatan.sertifikat.id), '_blank')"
  class="text-blue-600 hover:underline"
>
  Lihat Sertifikat
</button>

              <!-- Hapus -->
              <button @click="deleteUsulan(u.id)" class="text-red-600 hover:underline">
                Hapus
              </button>
            </td>
          </tr>

          <!-- Jika tidak ada data -->
          <tr v-if="!props.usulans.length">
            <td colspan="6" class="text-center text-gray-500 p-4">
              Tidak ada data usulan kegiatan.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
