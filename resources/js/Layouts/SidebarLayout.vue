<script setup>
import SidebarAdmin from '@/components/AdminPanel/Sidebar.vue';
import SidebarSuperAdmin from '@/components/SuperadminPanel/Sidebar.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const role = computed(() => page.props.auth.user.role);
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <component :is="role === 'superadmin' ? SidebarSuperAdmin : SidebarAdmin" />

    <!-- Main content -->
    <main class="flex-1 p-8">
      <slot />
    </main>
  </div>
</template>
