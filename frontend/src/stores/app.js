import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/index.js'

export const useAppStore = defineStore('app', () => {
  const locale = ref(localStorage.getItem('locale') || 'zh')
  const adminToken = ref(localStorage.getItem('admin_token') || null)
  const adminUser = ref(null)

  function setLocale(lang) {
    locale.value = lang
    localStorage.setItem('locale', lang)
  }

  function setAdminToken(token) {
    adminToken.value = token
    if (token) {
      localStorage.setItem('admin_token', token)
    } else {
      localStorage.removeItem('admin_token')
    }
  }

  function setAdminUser(user) {
    adminUser.value = user
  }

  function logout() {
    setAdminToken(null)
    adminUser.value = null
  }

  return { locale, adminToken, adminUser, setLocale, setAdminToken, setAdminUser, logout }
})
