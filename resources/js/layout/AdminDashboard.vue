<template>
  <div>
    <h1>Admin Layout</h1>
    <nav>
      <router-link to="/admin">Home</router-link> |
      <router-link to="/admin/about">About</router-link>
    </nav>
    <main>
      <!-- Halaman anak (Home/About) akan muncul di sini -->
      <router-view />
    </main>
  </div>
</template>
