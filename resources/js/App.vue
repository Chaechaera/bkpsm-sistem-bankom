<template>
  <div class="">
    <Navbar />
    <router-view></router-view>
  </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue';
</script>

<style>
h2 {
  color: green;
}
</style>