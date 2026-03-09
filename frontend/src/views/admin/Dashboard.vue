<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-8 bg-gray-50">
      <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ $t('admin.nav.dashboard') }}</h1>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div v-for="stat in stats" :key="stat.label" class="bg-white rounded-xl border border-gray-200 p-6">
          <p class="text-sm text-gray-500 mb-2">{{ stat.label }}</p>
          <p class="text-3xl font-bold text-gray-900">{{ stat.value }}</p>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">快捷操作</h2>
        <div class="flex flex-wrap gap-3">
          <RouterLink to="/admin/products/create" class="btn-primary text-sm">+ 新增产品</RouterLink>
          <RouterLink to="/admin/categories" class="btn-outline text-sm">管理分类</RouterLink>
          <RouterLink to="/" target="_blank" class="btn-outline text-sm">查看前台网站 ↗</RouterLink>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminSidebar from '@/components/AdminSidebar.vue'
import api from '@/api/index.js'

const stats = ref([
  { label: '产品总数', value: '—' },
  { label: '分类总数', value: '—' },
  { label: '上架产品', value: '—' },
])

onMounted(async () => {
  try {
    const [productsRes, catsRes] = await Promise.all([
      api.adminGetProducts({ per_page: 1 }),
      api.adminGetCategories(),
    ])
    stats.value[0].value = productsRes.data.total
    stats.value[1].value = catsRes.data.length
    const onlineRes = await api.adminGetProducts({ per_page: 1, status: 1 })
    stats.value[2].value = onlineRes.data.total
  } catch (e) {
    // silent
  }
})
</script>
