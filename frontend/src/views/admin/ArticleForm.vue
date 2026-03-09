<template>
  <div class="p-8">

    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
      <button @click="goBack" class="text-gray-400 hover:text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <h1 class="text-xl font-bold text-gray-900">{{ isEdit ? '编辑文章' : '新增文章' }}</h1>
    </div>

    <form @submit.prevent="saveArticle" class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">

      <!-- 文章标题（中文） -->
      <div class="p-5">
        <label class="form-label">文章标题 <span class="text-red-500">*</span></label>
        <input v-model="form.title" type="text" required class="input-field" placeholder="请输入文章标题（中文）" />
      </div>

      <!-- 文章标题（英文） -->
      <div class="p-5 bg-blue-50/40">
        <label class="form-label flex items-center gap-2">
          英文文章标题
          <span class="text-xs text-blue-400 font-normal">English</span>
        </label>
        <input v-model="form.title_en" type="text" class="input-field" placeholder="请输入英文文章标题" />
      </div>

      <!-- 文章描述（中文） -->
      <div class="p-5">
        <label class="form-label">文章描述</label>
        <textarea v-model="form.description" rows="3" class="input-field resize-none" placeholder="简短描述，用于列表展示..."></textarea>
      </div>

      <!-- 文章描述（英文） -->
      <div class="p-5 bg-blue-50/40">
        <label class="form-label flex items-center gap-2">
          英文文章描述
          <span class="text-xs text-blue-400 font-normal">English</span>
        </label>
        <textarea v-model="form.description_en" rows="3" class="input-field resize-none" placeholder="请输入英文文章描述..."></textarea>
      </div>

      <!-- 所属分类 -->
      <div class="p-5">
        <label class="form-label">所属分类</label>
        <select v-model="form.category_id" class="input-field">
          <option :value="null">-- 请选择分类 --</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
      </div>

      <!-- 封面图片 -->
      <div class="p-5">
        <label class="form-label">封面图片</label>
        <div class="flex flex-col gap-3">
          <!-- 图片预览（上方） -->
          <div v-if="form.cover_image" class="relative w-36 h-36 flex-shrink-0">
            <div class="w-36 h-36 rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-50">
              <img :src="`/uploads/${form.cover_image}`" class="w-full h-full object-cover" />
            </div>
            <button
              type="button"
              @click="form.cover_image = null"
              class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-sm font-bold flex items-center justify-center hover:bg-red-600 shadow"
            >×</button>
          </div>

          <!-- 上传按钮（下方） -->
          <div
            class="inline-flex self-start items-center gap-2 border border-dashed border-gray-300 rounded-lg px-4 py-2.5 cursor-pointer hover:border-primary-400 hover:bg-blue-50 transition-colors"
            @click="triggerFileInput"
          >
            <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="uploadCover" />
            <span v-if="uploading" class="w-4 h-4 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></span>
            <svg v-else class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <span class="text-sm text-gray-600">{{ uploading ? '上传中...' : (form.cover_image ? '重新上传' : '点击上传封面图') }}</span>
            <span class="text-xs text-gray-400 border-l border-gray-200 pl-2">JPG / PNG / WebP，最大 10MB</span>
          </div>
        </div>
      </div>

      <!-- 文章内容（中文） -->
      <div class="p-5">
        <label class="form-label">文章内容</label>
        <WangEditor v-model="form.content" :height="420" placeholder="请输入文章内容..." class="mt-1" />
      </div>

      <!-- 文章内容（英文） -->
      <div class="p-5 bg-blue-50/40">
        <label class="form-label flex items-center gap-2">
          英文文章内容
          <span class="text-xs text-blue-400 font-normal">English</span>
        </label>
        <WangEditor v-model="form.content_en" :height="420" placeholder="请输入英文文章内容..." class="mt-1" />
      </div>

      <!-- 发布状态 -->
      <div class="p-5">
        <label class="form-label">发布状态</label>
        <div class="flex gap-6 mt-1.5">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" v-model="form.is_active" :value="1" />
            <span class="text-sm text-green-600 font-medium">已发布</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" v-model="form.is_active" :value="0" />
            <span class="text-sm text-gray-500">草稿</span>
          </label>
        </div>
      </div>

      <!-- 前端是否可见 -->
      <div class="p-5">
        <label class="form-label">前端是否可见</label>
        <div class="flex gap-6 mt-1.5">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" v-model="form.is_frontend_visible" :value="1" />
            <span class="text-sm text-green-600 font-medium">可见（前端展示）</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" v-model="form.is_frontend_visible" :value="0" />
            <span class="text-sm text-gray-500">隐藏</span>
          </label>
        </div>
        <p class="text-xs text-gray-400 mt-1">控制该文章是否在前台页面（Banner、生产实力等模块）中展示</p>
      </div>

      <!-- 发布时间 -->
      <div class="p-5">
        <label class="form-label">发布时间</label>
        <input v-model="form.published_at" type="datetime-local" class="input-field" style="width:210px;" />
      </div>

      <!-- 排序 -->
      <div class="p-5">
        <label class="form-label">排序</label>
        <input v-model.number="form.sort_order" type="number" min="0" class="input-field w-40" placeholder="0" />
        <p class="text-xs text-gray-400 mt-1">数字越小越靠前</p>
      </div>

      <!-- 保存按钮 -->
      <div class="p-6 flex flex-col items-center gap-3">
        <p v-if="saveError" class="text-red-500 text-sm flex items-center gap-1.5">
          <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          {{ saveError }}
        </p>
        <p v-if="saveSuccess" class="text-green-600 text-sm flex items-center gap-1.5">
          <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          保存成功！
        </p>
        <button type="submit" :disabled="saving" class="btn-primary px-16 py-2.5 text-base">
          <span v-if="saving" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2 inline-block"></span>
          {{ saving ? '保存中...' : '保存文章' }}
        </button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import WangEditor from '@/components/WangEditor.vue'
import api from '@/api/index.js'

const route  = useRoute()
const router = useRouter()
const isEdit = computed(() => !!route.params.id)

const categories = ref([])
const fileInput  = ref(null)
const saving     = ref(false)
const uploading  = ref(false)
const saveError  = ref('')
const saveSuccess = ref(false)

// 获取本地时间字符串 (yyyy-MM-ddTHH:mm)，避免 toISOString() 转成 UTC
function localNow() {
  const d = new Date()
  const pad = n => String(n).padStart(2, '0')
  return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`
}

// 从 URL query 获取默认分类（从分类页进入时携带）
const defaultCategoryId = route.query.category_id ? Number(route.query.category_id) : null

const form = reactive({
  title:                '',
  title_en:             '',
  description:          '',
  description_en:       '',
  category_id:          defaultCategoryId,
  content:              '',
  content_en:           '',
  cover_image:          null,
  published_at:         localNow(),
  is_active:            1,
  is_frontend_visible:  1,
  sort_order:           0,
})

function triggerFileInput() {
  fileInput.value?.click()
}

async function uploadCover(e) {
  const file = e.target.files[0]
  if (!file) return
  uploading.value = true
  try {
    const fd = new FormData()
    fd.append('file', file)
    const { data } = await api.uploadImage(fd)
    form.cover_image = data.path
  } catch {
    alert('上传失败')
  } finally {
    uploading.value = false
    e.target.value  = ''
  }
}

function goBack() {
  const catId = form.category_id || defaultCategoryId
  if (catId) {
    router.push(`/admin/article-categories/${catId}`)
  } else {
    router.push('/admin/article-categories')
  }
}

async function saveArticle() {
  saving.value    = true
  saveError.value = ''
  saveSuccess.value = false
  try {
    const payload = { ...form }
    if (isEdit.value) {
      await api.adminUpdateArticle(route.params.id, payload)
    } else {
      await api.adminCreateArticle(payload)
    }
    saveSuccess.value = true
    setTimeout(() => goBack(), 900)
  } catch (e) {
    saveError.value = e.response?.data?.message || '保存失败，请重试'
  } finally {
    saving.value = false
  }
}

async function loadArticle() {
  const { data } = await api.adminGetArticle(route.params.id)
  form.title          = data.title          || ''
  form.title_en       = data.title_en       || ''
  form.description    = data.description    || ''
  form.description_en = data.description_en || ''
  form.category_id    = data.category_id    || null
  form.content        = data.content        || ''
  form.content_en     = data.content_en     || ''
  form.cover_image    = data.cover_image    || null
  form.published_at   = data.published_at
    ? (() => { const d = new Date(data.published_at); const pad = n => String(n).padStart(2,'0'); return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}` })()
    : localNow()
  form.is_active           = data.is_active ? 1 : 0
  form.is_frontend_visible = data.is_frontend_visible !== false ? 1 : 0
  form.sort_order          = data.sort_order || 0
}

onMounted(async () => {
  const { data } = await api.adminGetArticleCategories({})
  categories.value = data
  if (isEdit.value) await loadArticle()
})
</script>

<style scoped>
.btn-primary { @apply inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors; }
.form-label  { @apply text-sm font-medium text-gray-700 mb-2 block; }
.input-field { @apply w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-primary-500 transition bg-white; }
</style>

