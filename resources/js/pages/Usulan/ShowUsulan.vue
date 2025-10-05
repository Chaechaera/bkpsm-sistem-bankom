<script setup>
import ProgressStepper from '@/components/ProgressStepper.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  usulans: Array
});
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Usulan Kegiatan</h1>

    <div class="mb-4">
      <Link href="/usulan/create/{{ surat }}" class="bg-blue-500 text-white px-4 py-2 rounded">
      + Buat Usulan Baru
      </Link>
      <Link :href="route('subunitkerja.edit')" class="bg-blue-500 text-white px-4 py-2 rounded">
      + Tambah Kop dan TTD
      </Link>
    </div>

    <table class="w-full border">
      <tr v-for="u in props.usulans" :key="u.id">
        <td class="p-2">{{ u.nama_kegiatan }}</td>
        <td>{{ u.tanggal_pelaksanaan ?? '-' }}</td>
        <td class="p-2">
          <ProgressStepper :currentStatus="u.status" />
        </td>
        <td class="p-2">
          <form method="POST" :action="route('usulan.updateStatus', u.id)">
            <input type="hidden" name="_method" value="PATCH" />
            <select name="status" class="border rounded p-1">
              <option v-if="$page.props.auth.user.role === 'admin'" value="draft">Draft</option>
              <option v-if="$page.props.auth.user.role === 'admin'" value="in_progress">Pelaksanaan</option>
              <option v-if="$page.props.auth.user.role === 'admin'" value="completed">Upload Bukti</option>

              <option v-if="$page.props.auth.user.role === 'superadmin'" value="pending">Pending</option>
              <option v-if="$page.props.auth.user.role === 'superadmin'" value="approved">Approved</option>
              <option v-if="$page.props.auth.user.role === 'superadmin'" value="rejected">Rejected</option>
              <option v-if="$page.props.auth.user.role === 'superadmin'" value="finish">Finish</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded ml-2">Update</button>
          </form>
        </td>
        <td>
          <a :href="`/usulan/${u.id}/detail/create`">Lengkapi</a>
          <!--<a :href="`/usulan/${u.id}/surat`">Download Surat</a>-->
          <a :href="route('usulan.download', u.id)" target="_blank" class="text-green-600 underline">
            Download Surat
          </a>
        </td>
      </tr>
    </table>
  </div>
</template>
