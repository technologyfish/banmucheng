<template>
  <div class="p-8">

    <!-- 顶部：标题/面包屑 + 按钮 -->
    <div class="flex items-center justify-between mb-6">
      <!-- 根级显示 h1 大标题，子级显示面包屑 -->
      <template v-if="breadcrumbs.length === 1">
        <h1 class="text-xl font-bold text-gray-900">{{ breadcrumbs[0].name }}</h1>
      </template>
      <nav v-else class="flex items-center flex-wrap gap-x-1 gap-y-0.5 text-sm">
        <template v-for="(crumb, i) in breadcrumbs" :key="crumb.id ?? 'root'">
          <span v-if="i > 0" class="text-gray-300 select-none px-0.5">/</span>
          <button
            @click="goCrumb(crumb)"
            :class="[
              'transition-colors font-medium',
              i < breadcrumbs.length - 1
                ? 'text-primary-600 hover:text-primary-700'
                : 'text-gray-800 cursor-default',
            ]"
          >{{ crumb.name }}</button>
        </template>
      </nav>

      <!-- 操作按钮 -->
      <div class="flex items-center gap-2">
        <button @click="openCatForm()" class="btn-outline text-sm">+ 新增分类</button>
        <RouterLink
          v-if="catId"
          :to="`/admin/articles/create?category_id=${catId}`"
          class="btn-primary text-sm"
        >+ 新增文章</RouterLink>
      </div>
    </div>

    <!-- 统一列表 -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
      <table class="w-full text-sm table-fixed">
        <colgroup>
          <col style="width:60px" /><!-- ID列 -->
          <col /><!-- 名称列自动 -->
          <col style="width:90px" />
          <col style="width:110px" />
        </colgroup>
        <thead>
          <tr class="border-b border-gray-100 bg-gray-50">
            <th class="px-5 py-3 text-left text-gray-500 font-medium">ID</th>
            <th class="px-5 py-3 text-left text-gray-500 font-medium max-w-xs">名称 / 标题</th>
            <th class="px-5 py-3 text-left text-gray-500 font-medium">排序</th>
            <th class="px-5 py-3 text-left text-gray-500 font-medium">操作</th>
          </tr>
        </thead>
        <tbody>
          <!-- 加载中 -->
          <tr v-if="loading">
            <td colspan="4" class="py-10 text-center">
              <div class="inline-block w-5 h-5 border-2 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
            </td>
          </tr>

          <!-- 空状态 -->
          <tr v-else-if="mergedItems.length === 0">
            <td colspan="4" class="py-10 text-center text-gray-400">暂无内容</td>
          </tr>

          <template v-else>
            <!-- ── 分类行 ── -->
            <tr
              v-for="(cat, idx) in subCategories"
              :key="`cat-${cat.id}`"
              class="border-b border-gray-50 hover:bg-gray-50 transition-colors group"
            >
              <!-- ID -->
              <td class="px-5 py-3 text-gray-400 text-xs">{{ cat.id }}</td>
              <!-- 名称（可点击进入子页） -->
              <td class="px-5 py-3">
                <button
                  @click="enterCat(cat)"
                  class="font-medium text-gray-800 hover:text-primary-600 transition-colors flex items-center gap-1.5"
                >
                  <span class="text-xs px-1.5 py-0.5 rounded bg-blue-50 text-blue-500 font-medium flex-shrink-0">分类</span>
                  {{ cat.name }}
                  <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-primary-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </button>
              </td>
              <!-- 排序箭头 -->
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <button @click="reorderCat(cat, 'up')" :disabled="idx === 0 || reorderingCat === cat.id" class="sort-btn" title="上移">
                    <img :src="idx === 0 ? iconUpD : iconUpA" style="width:18px;height:18px;display:block;" alt="上移" />
                  </button>
                  <button @click="reorderCat(cat, 'down')" :disabled="idx === subCategories.length - 1 || reorderingCat === cat.id" class="sort-btn" title="下移">
                    <img :src="idx === subCategories.length - 1 ? iconDownD : iconDownA" style="width:18px;height:18px;display:block;" alt="下移" />
                  </button>
                  <span v-if="reorderingCat === cat.id" class="w-3 h-3 border border-primary-400 border-t-transparent rounded-full animate-spin"></span>
                </div>
              </td>
              <!-- 操作 -->
              <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                  <button @click="openCatForm(cat)" class="text-primary-600 hover:text-primary-700 text-xs font-medium">编辑</button>
                  <button @click="deleteCat(cat.id)" class="text-red-500 hover:text-red-600 text-xs font-medium">删除</button>
                </div>
              </td>
            </tr>

            <!-- ── 文章行 ── -->
            <tr
              v-for="(a, idx) in articles"
              :key="`art-${a.id}`"
              class="border-b border-gray-50 hover:bg-gray-50 transition-colors"
            >
              <!-- ID -->
              <td class="px-5 py-3 text-gray-400 text-xs">{{ a.id }}</td>
              <!-- 标题 -->
              <td class="px-5 py-3">
                <div class="flex items-center gap-2.5">
                  <span class="text-xs px-1.5 py-0.5 rounded bg-orange-50 text-orange-400 font-medium flex-shrink-0">文章</span>
                  <div class="flex items-center gap-2 min-w-0">
                    <div v-if="a.cover_image" class="w-16 h-12 rounded overflow-hidden bg-gray-100 flex-shrink-0">
                      <img :src="`/uploads/${a.cover_image}`" class="w-full h-full object-cover" />
                    </div>
                    <span class="font-medium text-gray-800 line-clamp-1">{{ a.title }}</span>
                  </div>
                </div>
              </td>
              <!-- 排序箭头 -->
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <button @click="reorderArticle(a, 'up')" :disabled="idx === 0 || reorderingArt === a.id" class="sort-btn" title="上移">
                    <img :src="idx === 0 ? iconUpD : iconUpA" style="width:18px;height:18px;display:block;" alt="上移" />
                  </button>
                  <button @click="reorderArticle(a, 'down')" :disabled="idx === articles.length - 1 || reorderingArt === a.id" class="sort-btn" title="下移">
                    <img :src="idx === articles.length - 1 ? iconDownD : iconDownA" style="width:18px;height:18px;display:block;" alt="下移" />
                  </button>
                  <span v-if="reorderingArt === a.id" class="w-3 h-3 border border-primary-400 border-t-transparent rounded-full animate-spin"></span>
                </div>
              </td>
              <!-- 操作 -->
              <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                  <RouterLink :to="`/admin/articles/${a.id}/edit`" class="text-primary-600 hover:text-primary-700 text-xs font-medium">编辑</RouterLink>
                  <button @click="deleteArticle(a.id)" class="text-red-500 hover:text-red-600 text-xs font-medium">删除</button>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>

      <!-- 文章分页 -->
      <div v-if="lastPage > 1" class="flex items-center justify-between px-5 py-3 border-t border-gray-100">
        <p class="text-sm text-gray-500">共 {{ total }} 篇文章</p>
        <div class="flex gap-1">
          <button
            v-for="p in lastPage" :key="p" @click="goPage(p)"
            :class="['w-8 h-8 rounded-lg text-sm transition-colors',
              page === p ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-gray-100']"
          >{{ p }}</button>
        </div>
      </div>
    </div>

    <!-- ═══ 分类表单 Modal ═══ -->
    <Transition name="modal">
      <div v-if="catFormVisible" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-5">{{ editingCatId ? '编辑分类' : '新增分类' }}</h2>
          <form @submit.prevent="saveCat" class="flex flex-col gap-4">
            <div>
              <label class="text-sm font-medium text-gray-700 mb-1 block">分类名称 <span class="text-red-500">*</span></label>
              <input v-model="catForm.name" type="text" required class="input-field" placeholder="分类名称" autofocus />
            </div>
            <p v-if="catFormError" class="text-red-500 text-sm">{{ catFormError }}</p>
            <div class="flex gap-3 mt-1">
              <button type="submit" :disabled="savingCat" class="btn-primary flex-1 justify-center text-sm">
                <span v-if="savingCat" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-1.5"></span>
                保存
              </button>
              <button type="button" @click="catFormVisible = false" class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-lg py-2 text-sm">取消</button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api/index.js'
import iconUpA   from '@/assets/images/icon-up-a.png'
import iconUpD   from '@/assets/images/icon-up-d.png'
import iconDownA from '@/assets/images/icon-down-a.png'
import iconDownD from '@/assets/images/icon-down-d.png'

const route  = useRoute()
const router = useRouter()

const catId = computed(() => route.params.catId ? Number(route.params.catId) : null)

const breadcrumbs   = ref([{ id: null, name: '栏目列表' }])
const subCategories = ref([])
const articles      = ref([])
const loading       = ref(false)
const total         = ref(0)
const page          = ref(1)
const lastPage      = ref(1)
const reorderingCat = ref(null)
const reorderingArt = ref(null)

const mergedItems = computed(() => [...subCategories.value, ...articles.value])

// 分类表单
const catFormVisible = ref(false)
const editingCatId   = ref(null)
const savingCat      = ref(false)
const catFormError   = ref('')
const catForm        = reactive({ name: '', sort_order: 0 })

// ─── 导航 ───
function goCrumb(crumb) {
  router.push(crumb.id === null ? '/admin/article-categories' : `/admin/article-categories/${crumb.id}`)
}
function enterCat(cat) {
  router.push(`/admin/article-categories/${cat.id}`)
}

// ─── 加载数据 ───
async function loadAll() {
  loading.value = true
  try {
    await Promise.all([loadBreadcrumbs(), loadSubCategories()])
    if (catId.value) await loadArticles()
  } finally {
    loading.value = false
  }
}

async function loadBreadcrumbs() {
  if (!catId.value) {
    breadcrumbs.value = [{ id: null, name: '栏目列表' }]
    return
  }
  try {
    const { data } = await api.adminGetArticleCategory(catId.value)
    breadcrumbs.value = [{ id: null, name: '栏目列表' }, ...(data.breadcrumbs || [])]
  } catch {
    breadcrumbs.value = [{ id: null, name: '栏目列表' }]
  }
}

async function loadSubCategories() {
  try {
    const { data } = await api.adminGetArticleCategories(
      catId.value !== null ? { parent_id: catId.value } : {}
    )
    subCategories.value = catId.value === null
      ? data.filter(c => !c.parent_id)
      : data
  } catch (e) {
    console.error(e)
  }
}

async function loadArticles(reset = false) {
  if (!catId.value) return
  if (reset) page.value = 1
  try {
    const { data } = await api.adminGetArticles({ category_id: catId.value, page: page.value, per_page: 50 })
    articles.value = data.data
    total.value    = data.total
    lastPage.value = data.last_page
  } catch (e) {
    console.error(e)
  }
}

function goPage(p) { page.value = p; loadArticles() }

// ─── 分类 CRUD ───
function openCatForm(cat = null) {
  editingCatId.value   = cat?.id ?? null
  catForm.name         = cat?.name ?? ''
  catForm.sort_order   = cat?.sort_order ?? 0
  catFormError.value   = ''
  catFormVisible.value = true
}

async function saveCat() {
  savingCat.value    = true
  catFormError.value = ''
  try {
    const payload = { ...catForm }
    if (catId.value !== null && !editingCatId.value) payload.parent_id = catId.value
    if (editingCatId.value) {
      await api.adminUpdateArticleCategory(editingCatId.value, payload)
    } else {
      await api.adminCreateArticleCategory(payload)
    }
    catFormVisible.value = false
    await loadSubCategories()
  } catch (e) {
    catFormError.value = e.response?.data?.message || '保存失败'
  } finally {
    savingCat.value = false
  }
}

async function deleteCat(id) {
  if (!confirm('确定要删除该分类吗？')) return
  try {
    await api.adminDeleteArticleCategory(id)
    await loadSubCategories()
  } catch (e) {
    alert(e.response?.data?.message || '删除失败')
  }
}

async function reorderCat(cat, direction) {
  reorderingCat.value = cat.id
  try {
    await api.adminReorderArticleCategory(cat.id, direction)
  } catch (e) {
    if (e.response?.status !== 400) {
      alert('排序失败：' + (e.response?.data?.message || e.message))
    }
  } finally {
    reorderingCat.value = null
    await loadSubCategories()
  }
}

// ─── 文章 CRUD / 排序 ───
async function deleteArticle(id) {
  if (!confirm('确定要删除该文章吗？')) return
  try {
    await api.adminDeleteArticle(id)
    await loadArticles()
  } catch (e) {
    alert(e.response?.data?.message || '删除失败')
  }
}

async function reorderArticle(a, direction) {
  reorderingArt.value = a.id
  try {
    await api.adminReorderArticle(a.id, direction)
  } catch (e) {
    if (e.response?.status !== 400) {
      alert('排序失败：' + (e.response?.data?.message || e.message))
    }
  } finally {
    reorderingArt.value = null
    await loadArticles()
  }
}

// ─── 路由变化 ───
watch(() => route.params.catId, () => {
  page.value = 1
  articles.value = []
  subCategories.value = []
  loadAll()
})

onMounted(loadAll)
</script>

<style scoped>
.btn-primary { @apply inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-lg transition-colors; }
.btn-outline  { @apply inline-flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium px-4 py-2 rounded-lg transition-colors; }
.input-field  { @apply w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-primary-500 transition bg-white; }
.sort-btn     { @apply p-0.5 rounded hover:bg-gray-100 transition-colors disabled:cursor-not-allowed disabled:opacity-50; }
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
