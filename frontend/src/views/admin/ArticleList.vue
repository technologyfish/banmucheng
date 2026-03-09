<template>
  <div class="p-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-900">文章列表</h1>
      <RouterLink to="/admin/articles/create" class="btn-primary text-sm">+ 新增文章</RouterLink>
    </div>

    <!-- Filters -->
    <div class="flex gap-3 mb-5 flex-wrap">
      <input
        v-model="search"
        @input="debouncedFetch"
        type="text"
        placeholder="搜索文章标题..."
        class="border border-gray-200 rounded-lg px-4 py-2 text-sm outline-none focus:border-primary-500 w-56 bg-white"
      />
      <select v-model="categoryFilter" @change="fetchArticles(true)"
        class="border border-gray-200 rounded-lg px-4 py-2 text-sm outline-none focus:border-primary-500 bg-white">
        <option value="">全部分类</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
      </select>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
      <table class="w-full text-sm min-w-[700px]">
        <thead>
          <tr class="border-b border-gray-100 bg-gray-50">
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-12">ID</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-16">封面</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-72">标题</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-24">分类</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-32">发布时间</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-20">状态</th>
            <th class="px-4 py-3 text-left text-gray-500 font-medium w-24">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading">
            <td colspan="7" class="py-10 text-center">
              <div class="inline-block w-6 h-6 border-2 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
            </td>
          </tr>
          <tr v-else-if="articles.length === 0">
            <td colspan="7" class="py-10 text-center text-gray-400">暂无文章</td>
          </tr>
          <tr v-for="a in articles" :key="a.id" class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 text-gray-400 text-xs">{{ a.id }}</td>
            <td class="px-4 py-3">
              <div class="w-12 h-9 rounded-lg overflow-hidden bg-gray-100">
                <img v-if="a.cover_image" :src="`/uploads/${a.cover_image}`" class="w-full h-full object-cover" />
              </div>
            </td>
            <td class="px-4 py-3">
              <p class="font-medium text-gray-800 line-clamp-1">{{ a.title }}</p>
              <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ a.description }}</p>
            </td>
            <td class="px-4 py-3 text-gray-500 text-xs">{{ a.category?.name || '—' }}</td>
            <td class="px-4 py-3 text-gray-400 text-xs">{{ formatDate(a.published_at) }}</td>
            <td class="px-4 py-3">
              <span :class="['text-xs px-2 py-1 rounded-full font-medium',
                a.is_active ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-400']">
                {{ a.is_active ? '已发布' : '草稿' }}
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <RouterLink :to="`/admin/articles/${a.id}/edit`" class="text-primary-600 hover:text-primary-700 text-xs font-medium">编辑</RouterLink>
                <button @click="deleteArticle(a.id)" class="text-red-500 hover:text-red-600 text-xs font-medium">删除</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="lastPage > 1" class="flex items-center justify-between px-5 py-3 border-t border-gray-100">
        <p class="text-sm text-gray-500">共 {{ total }} 篇</p>
        <div class="flex gap-1">
          <button
            v-for="p in lastPage" :key="p" @click="goPage(p)"
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

const articles = ref([])
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
  debounceTimer = setTimeout(() => fetchArticles(true), 400)
}

async function fetchArticles(reset = false) {
  if (reset) page.value = 1
  loading.value = true
  try {
    const params = { page: page.value, per_page: 20 }
    if (search.value) params.search = search.value
    if (categoryFilter.value) params.category_id = categoryFilter.value
    const { data } = await api.adminGetArticles(params)
    articles.value = data.data
    total.value = data.total
    lastPage.value = data.last_page
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

async function deleteArticle(id) {
  if (!confirm('确定要删除该文章吗？')) return
  try {
    await api.adminDeleteArticle(id)
    await fetchArticles()
  } catch (e) {
    alert(e.response?.data?.message || '删除失败')
  }
}

function goPage(p) {
  page.value = p
  fetchArticles()
}

function formatDate(dt) {
  if (!dt) return '—'
  return new Date(dt).toLocaleDateString('zh-CN', { year: 'numeric', month: '2-digit', day: '2-digit' })
}

onMounted(async () => {
  const { data } = await api.adminGetArticleCategories()
  categories.value = data
  await fetchArticles()
})
</script>

<style scoped>
.btn-primary { @apply inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-lg transition-colors; }
</style>
