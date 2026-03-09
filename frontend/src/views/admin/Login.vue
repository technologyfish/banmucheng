<template>
  <div class="min-h-screen bg-white flex items-center justify-center p-6">
    <div class="w-full max-w-sm">

      <!-- Form card -->
      <div class="border border-gray-200 rounded-2xl p-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6 text-center">登录</h2>

        <form @submit.prevent="handleLogin" class="flex flex-col gap-4">
          <div>
            <label class="text-xs font-medium text-gray-500 mb-1.5 block">
              {{ $t('admin.login.username') }}
            </label>
            <input
              v-model="form.username"
              type="text"
              required
              autocomplete="username"
              placeholder="请输入用户名"
              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-gray-400 transition bg-white"
            />
          </div>

          <div>
            <label class="text-xs font-medium text-gray-500 mb-1.5 block">
              {{ $t('admin.login.password') }}
            </label>
            <input
              v-model="form.password"
              type="password"
              required
              autocomplete="current-password"
              placeholder="请输入密码"
              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-gray-400 transition bg-white"
            />
          </div>

          <Transition name="fade">
            <p v-if="errorMsg" class="text-red-500 text-xs flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ errorMsg }}
            </p>
          </Transition>

          <button
            type="submit"
            :disabled="loading"
            class="mt-2 w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors disabled:opacity-60"
          >
            <span v-if="loading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            {{ loading ? '登录中...' : $t('admin.login.submit') }}
          </button>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAppStore } from '@/stores/app.js'
import api from '@/api/index.js'

const router = useRouter()
const appStore = useAppStore()

const form = reactive({ username: '', password: '' })
const loading = ref(false)
const errorMsg = ref('')

async function handleLogin() {
  loading.value = true
  errorMsg.value = ''
  try {
    const { data } = await api.adminLogin(form)
    appStore.setAdminToken(data.token)
    appStore.setAdminUser(data.admin)
    router.push('/admin/products')
  } catch (e) {
    errorMsg.value = e.response?.data?.message || '登录失败'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
