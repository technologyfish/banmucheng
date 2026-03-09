<template>
  <div class="p-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-900">产品列表</h1>
      <RouterLink to="/admin/products/create" class="btn-primary text-sm">+ 新增产品</RouterLink>
    </div>

    <!-- Filters -->
    <div class="flex gap-3 mb-5 flex-wrap">
      <input
        v-model="search"
        @input="debouncedFetch"
        type="text"
        placeholder="搜索产品名称..."
        class="border border-gray-200 rounded-lg px-4 py-2 text-sm outline-none focus:border-primary-500 w-56 bg-white"
      />
      <select v-model="categoryFilter" @change="fetchProducts(true)" class="border border-gray-200 rounded-lg px-4 py-2 text-sm outline-none focus:border-primary-500 bg-white">
        <option value="">全部分类</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name_zh }}</option>
      </select>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
      <table class="w-full text-sm min-w-[700px]">
        <thead>
          <tr class="border-b border-gray-100 bg-gray-50">
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-16">ID</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-16">封面</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium">产品名称</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium">分类</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-20">状态</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-24">排序</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-28">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading">
            <td colspan="7" class="py-10 text-center">
              <div class="inline-block w-6 h-6 border-2 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
            </td>
          </tr>
          <tr v-else-if="products.length === 0">
            <td colspan="7" class="py-10 text-center text-gray-400">暂无产品</td>
          </tr>
          <tr v-for="p in products" :key="p.id" class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 text-gray-400 text-xs">{{ p.id }}</td>
            <td class="px-4 py-3">
              <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100">
                <img v-if="p.cover_image" :src="`/uploads/${p.cover_image}`" class="w-full h-full object-cover" />
              </div>
            </td>
            <td class="px-4 py-3">
              <p class="font-medium text-gray-800">{{ p.name_zh }}</p>
              <p class="text-xs text-gray-400 mt-0.5">{{ p.name_en }}</p>
            </td>
            <td class="px-4 py-3 text-gray-600 text-xs">{{ p.category?.name_zh || '—' }}</td>
            <td class="px-4 py-3">
              <span :class="['inline-block text-xs px-2 py-1 rounded-full font-medium',
                p.status === 1 ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-400']">
                {{ p.status === 1 ? '上架' : '下架' }}
              </span>
            </td>
            <td class="px-4 py-3 text-gray-400 text-xs">{{ p.sort_order }}</td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <RouterLink :to="`/admin/products/${p.id}/edit`" class="text-primary-600 hover:text-primary-700 text-xs font-medium">编辑</RouterLink>
                <button @click="deleteProduct(p.id)" class="text-red-500 hover:text-red-600 text-xs font-medium">删除</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="lastPage > 1" class="flex items-center justify-between px-5 py-3 border-t border-gray-100">
        <p class="text-sm text-gray-500">共 {{ total }} 条</p>
        <div class="flex gap-1">
          <button
            v-for="p in lastPage"
            :key="p"
            @click="goPage(p)"
            :class="['w-8 h-8 rounded-lg text-sm transition-colors',
              page === p ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-gray-100']"
          >{{ p }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/index.js'

const products = ref([])
const categories = ref([])
const loading = ref(false)
const total = ref(0)
const page = ref(1)
const lastPage = ref(1)
const search = ref('')
const categoryFilter = ref('')

let debounceTimer = null
function debouncedFetch() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => fetchProducts(true), 400)
}

async function fetchProducts(reset = false) {
  if (reset) page.value = 1
  loading.value = true
  try {
    const params = { page: page.value, per_page: 20 }
    if (search.value) params.search = search.value
    if (categoryFilter.value) params.category_id = categoryFilter.value
    const { data } = await api.adminGetProducts(params)
    products.value = data.data
    total.value = data.total
    lastPage.value = data.last_page
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

async function deleteProduct(id) {
  if (!confirm('确定要删除该产品吗？此操作不可恢复。')) return
  try {
    await api.adminDeleteProduct(id)
    await fetchProducts()
  } catch (e) {
    alert(e.response?.data?.message || '删除失败')
  }
}

function goPage(p) {
  page.value = p
  fetchProducts()
}

onMounted(async () => {
  const { data } = await api.adminGetCategories()
  categories.value = data
  await fetchProducts()
})
</script>

<style scoped>
.btn-primary {
  @apply inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-lg transition-colors;
}
</style>
