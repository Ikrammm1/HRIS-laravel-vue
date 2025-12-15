<template>
  <div class="flex h-screen bg-gray-100">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Topbar />

      <!-- <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4">
        <MainContent />
      </main> -->
        <main>
            <slot />
        </main>

      <Footer />
    </div>
  </div>
</template>

<script setup>
import Sidebar from '@/Layouts/sidebar.vue';
import Topbar from '@/Layouts/topbar.vue';
import Footer from '@/Layouts/footer.vue';
</script>