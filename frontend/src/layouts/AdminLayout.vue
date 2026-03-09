<template>
  <!-- Login page: no sidebar -->
  <div v-if="isLoginPage" class="min-h-screen bg-white">
    <RouterView />
  </div>

  <!-- Admin pages: sidebar + top bar -->
  <div v-else class="flex min-h-screen bg-gray-100">
    <AdminSidebar />

    <div class="flex-1 flex flex-col min-w-0">
      <!-- Top bar -->
      <header class="bg-white border-b border-gray-200 h-12 flex items-center justify-end px-6 flex-shrink-0">
        <button
          @click="logout"
          class="flex items-center gap-2 text-sm text-gray-500 hover:text-red-500 transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          退出登录
        </button>
      </header>

      <!-- Page content -->
      <main class="flex-1 overflow-auto">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAppStore } from '@/stores/app.js'
import AdminSidebar from '@/components/AdminSidebar.vue'

const route = useRoute()
const router = useRouter()
const appStore = useAppStore()

const isLoginPage = computed(() => route.name === 'admin-login')

function logout() {
  appStore.logout()
  router.push({ name: 'admin-login' })
}
</script>
