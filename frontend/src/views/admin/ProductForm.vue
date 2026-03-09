<template>
  <div class="p-8 overflow-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
      <RouterLink to="/admin/products" class="text-gray-400 hover:text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </RouterLink>
      <h1 class="text-xl font-bold text-gray-900">
        {{ isEdit ? '编辑产品' : '新增产品' }}
      </h1>
    </div>

    <form @submit.prevent="saveProduct" class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">

      <!-- 产品中文名称 -->
      <div class="p-5">
        <label class="form-label">产品中文名称 <span class="text-red-500">*</span></label>
        <input v-model="form.name_zh" type="text" required class="input-field" placeholder="产品中文名称" />
      </div>

      <!-- 产品英文名称 -->
      <div class="p-5">
        <label class="form-label">产品英文名称 <span class="text-red-500">*</span></label>
        <input v-model="form.name_en" type="text" required class="input-field" placeholder="Product English Name" />
      </div>

      <!-- 所属分类 -->
      <div class="p-5">
        <label class="form-label">所属分类</label>
        <select v-model="form.category_id" class="input-field max-w-xs">
          <option :value="null">-- 不选分类 --</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name_zh }}</option>
        </select>
      </div>

      <!-- 产品中文描述 -->
      <div class="p-5">
        <label class="form-label">产品中文描述</label>
        <textarea v-model="form.description_zh" rows="4" class="input-field resize-none" placeholder="产品中文描述..."></textarea>
      </div>

      <!-- 产品英文描述 -->
      <div class="p-5">
        <label class="form-label">产品英文描述</label>
        <textarea v-model="form.description_en" rows="4" class="input-field resize-none" placeholder="Product English description..."></textarea>
      </div>

      <!-- 产品规格选项 -->
      <div class="p-5">
        <label class="form-label mb-4 block">产品规格选项</label>
        <div class="flex flex-col gap-0 rounded-xl border border-gray-100 overflow-hidden">

          <!-- 可选基材 -->
          <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
            <div class="flex items-start gap-4">
              <span class="text-xs font-semibold text-gray-500 w-16 flex-shrink-0 pt-1">可选基材</span>
              <div class="flex-1">
                <div class="flex flex-wrap gap-2">
                  <button v-for="s in substrateOptions" :key="s.id" type="button"
                    @click="toggleOption('substrates', s.value_zh)"
                    :class="['tag-option text-xs', form.substrates.includes(s.value_zh) ? 'active' : '']">
                    {{ s.value_zh }}
                  </button>
                  <span v-if="!substrateOptions.length" class="text-xs text-gray-400">暂无选项</span>
                </div>
                <p v-if="form.substrates.length" class="text-xs text-primary-600 mt-1.5">已选：{{ form.substrates.join('、') }}</p>
              </div>
            </div>
          </div>

          <!-- 可选规格 -->
          <div class="px-4 py-3 bg-white border-b border-gray-100">
            <div class="flex items-start gap-4">
              <span class="text-xs font-semibold text-gray-500 w-16 flex-shrink-0 pt-1">可选规格</span>
              <div class="flex-1">
                <div class="flex flex-wrap gap-2">
                  <button v-for="s in specOptions" :key="s.id" type="button"
                    @click="toggleOption('specs', s.value_zh)"
                    :class="['tag-option text-xs', form.specs.includes(s.value_zh) ? 'active' : '']">
                    {{ s.value_zh }}
                  </button>
                  <span v-if="!specOptions.length" class="text-xs text-gray-400">暂无选项</span>
                </div>
                <p v-if="form.specs.length" class="text-xs text-primary-600 mt-1.5">已选：{{ form.specs.join('、') }}</p>
              </div>
            </div>
          </div>

          <!-- 可选厚度 -->
          <div class="px-4 py-3 bg-gray-50">
            <div class="flex items-start gap-4">
              <span class="text-xs font-semibold text-gray-500 w-16 flex-shrink-0 pt-1">可选厚度</span>
              <div class="flex-1">
                <div class="flex flex-wrap gap-2">
                  <button v-for="t in thicknessOptions" :key="t.id" type="button"
                    @click="toggleOption('thicknesses', t.value_zh)"
                    :class="['tag-option text-xs', form.thicknesses.includes(t.value_zh) ? 'active' : '']">
                    {{ t.value_zh }}
                  </button>
                  <span v-if="!thicknessOptions.length" class="text-xs text-gray-400">暂无选项</span>
                </div>
                <p v-if="form.thicknesses.length" class="text-xs text-primary-600 mt-1.5">已选：{{ form.thicknesses.join('、') }}</p>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- 产品图片 -->
      <div ref="imageSectionRef" class="p-5">
        <label class="form-label">产品图片 <span class="text-red-500">*</span></label>
        <p v-if="imageError" class="text-red-500 text-sm mb-3 flex items-center gap-1">
          <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          {{ imageError }}
        </p>

        <!-- 封面预览 -->
        <div v-if="form.cover_image" class="mb-3">
          <p class="text-xs text-gray-400 mb-1">当前封面</p>
          <div class="w-28 h-28 rounded-xl overflow-hidden border border-gray-200">
            <img :src="`/uploads/${form.cover_image}`" class="w-full h-full object-cover" />
          </div>
        </div>

        <!-- 已上传图片 -->
        <div v-if="existingImages.length" class="flex flex-wrap gap-3 mb-4">
          <div v-for="img in existingImages" :key="img.id" class="relative w-24 h-24 rounded-xl overflow-hidden border border-gray-200 group">
            <img :src="`/uploads/${img.image_path}`" class="w-full h-full object-cover" />
            <button type="button" @click="setCover(img.image_path)"
              :class="['absolute top-1 left-1 text-xs px-1.5 py-0.5 rounded-full transition',
                form.cover_image === img.image_path ? 'bg-primary-600 text-white' : 'bg-white/80 text-gray-600 hover:bg-primary-600 hover:text-white']"
              title="设为封面">★</button>
            <button type="button" @click="removeExistingImage(img)"
              class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
          </div>
        </div>

        <!-- 上传区域 -->
        <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary-400 transition-colors cursor-pointer"
          @click="triggerFileInput" @dragover.prevent @drop.prevent="handleDrop">
          <input ref="fileInput" type="file" accept="image/*" multiple class="hidden" @change="handleFileChange" />
          <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <p class="text-sm text-gray-500">点击或拖拽上传图片</p>
          <p class="text-xs text-gray-400 mt-1">支持 JPG、PNG、WebP，最大 10MB</p>
        </div>

        <!-- 新上传预览 -->
        <div v-if="newImagePreviews.length" class="flex flex-wrap gap-3 mt-4">
          <div v-for="(preview, index) in newImagePreviews" :key="index"
            class="relative w-24 h-24 rounded-xl overflow-hidden border border-gray-200 group">
            <img :src="preview.url" class="w-full h-full object-cover" />
            <div v-if="preview.uploading" class="absolute inset-0 bg-black/40 flex items-center justify-center">
              <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            </div>
            <button v-else type="button" @click="removeNewImage(index)"
              class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
          </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">点击图片上的 ★ 设为封面</p>
      </div>

      <!-- 上架状态 -->
      <div class="p-5">
        <label class="form-label">上架状态</label>
        <div class="flex gap-6 mt-1.5">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" v-model="form.status" :value="1" class="text-primary-600" />
            <span class="text-sm text-green-600 font-medium">上架</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" v-model="form.status" :value="0" />
            <span class="text-sm text-gray-500">下架</span>
          </label>
        </div>
      </div>

      <!-- 排序 -->
      <div class="p-5">
        <label class="form-label">排序</label>
        <input v-model.number="form.sort_order" type="number" min="0" class="input-field w-40" placeholder="0" />
        <p class="text-xs text-gray-400 mt-1">数字越小越靠前</p>
      </div>

      <!-- 保存 -->
      <div class="p-6 flex flex-col items-center gap-3">
        <p v-if="saveError && !imageError" class="text-red-500 text-sm">{{ saveError }}</p>
        <p v-if="saveSuccess" class="text-green-600 text-sm">✓ 保存成功！</p>
        <button type="submit" :disabled="saving || uploading" class="btn-primary px-16 py-2.5 text-base">
          <span v-if="saving || uploading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2 inline-block"></span>
          {{ uploading ? '上传图片中...' : '保存产品' }}
        </button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api/index.js'

const route = useRoute()
const router = useRouter()

const isEdit = computed(() => !!route.params.id)

const categories = ref([])
const existingImages = ref([])
const newImagePreviews = ref([])
const fileInput = ref(null)
const imageSectionRef = ref(null)
const saving = ref(false)
const uploading = ref(false)
const saveError = ref('')
const imageError = ref('')
const saveSuccess = ref(false)

const substrateOptions = ref([])
const specOptions = ref([])
const thicknessOptions = ref([])

const form = reactive({
  name_zh: '',
  name_en: '',
  category_id: null,
  description_zh: '',
  description_en: '',
  substrates: [],
  specs: [],
  thicknesses: [],
  cover_image: null,
  status: 1,
  sort_order: 0,
})

function dedup(arr) {
  const seen = new Set()
  return (arr || []).filter(item => {
    if (seen.has(item.value_zh)) return false
    seen.add(item.value_zh)
    return true
  })
}

async function loadOptions() {
  try {
    const { data } = await api.adminGetOptions()
    substrateOptions.value = dedup(data.substrate)
    specOptions.value      = dedup(data.spec)
    thicknessOptions.value = dedup(data.thickness)
  } catch (e) {
    // fallback to empty
  }
}

function toggleOption(field, value) {
  const arr = form[field]
  const idx = arr.indexOf(value)
  if (idx >= 0) arr.splice(idx, 1)
  else arr.push(value)
}

function setCover(path) {
  form.cover_image = path
}

function triggerFileInput() {
  fileInput.value?.click()
}

function handleFileChange(e) {
  const files = Array.from(e.target.files)
  addFiles(files)
  e.target.value = ''
}

function handleDrop(e) {
  const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'))
  addFiles(files)
}

function addFiles(files) {
  files.forEach(file => {
    const url = URL.createObjectURL(file)
    newImagePreviews.value.push({ url, file, path: null, uploading: false })
  })
  if (files.length) imageError.value = ''
}

function removeNewImage(index) {
  URL.revokeObjectURL(newImagePreviews.value[index].url)
  newImagePreviews.value.splice(index, 1)
}

async function removeExistingImage(img) {
  if (!confirm('确定要删除这张图片吗？')) return
  try {
    await api.adminDeleteImage(img.id)
    existingImages.value = existingImages.value.filter(i => i.id !== img.id)
    if (form.cover_image === img.image_path) {
      form.cover_image = existingImages.value[0]?.image_path || null
    }
  } catch (e) {
    alert('删除失败')
  }
}

async function uploadPendingImages() {
  const pending = newImagePreviews.value.filter(p => !p.path)
  if (!pending.length) return []

  uploading.value = true
  const paths = []

  for (const preview of pending) {
    preview.uploading = true
    try {
      const fd = new FormData()
      fd.append('file', preview.file)
      const { data } = await api.uploadImage(fd)
      preview.path = data.path
      preview.uploading = false
      paths.push(data.path)
    } catch (e) {
      preview.uploading = false
      console.error('Upload failed', e)
    }
  }

  uploading.value = false
  return paths
}

async function saveProduct() {
  // 图片必填校验
  imageError.value = ''
  const hasImages = existingImages.value.length > 0 || newImagePreviews.value.length > 0
  if (!hasImages) {
    imageError.value = '请至少上传一张产品图片'
    imageSectionRef.value?.scrollIntoView({ behavior: 'smooth', block: 'center' })
    return
  }

  saving.value = true
  saveError.value = ''
  saveSuccess.value = false

  try {
    const newPaths = await uploadPendingImages()

    const allExistingPaths = existingImages.value.map(i => i.image_path)
    if (!form.cover_image && newPaths.length > 0) {
      form.cover_image = newPaths[0]
    } else if (!form.cover_image && allExistingPaths.length > 0) {
      form.cover_image = allExistingPaths[0]
    }

    const payload = {
      name_zh: form.name_zh,
      name_en: form.name_en,
      category_id: form.category_id,
      description_zh: form.description_zh,
      description_en: form.description_en,
      substrates: form.substrates,
      specs: form.specs,
      thicknesses: form.thicknesses,
      cover_image: form.cover_image,
      status: form.status,
      sort_order: form.sort_order,
    }

    if (isEdit.value) {
      payload.new_image_ids = newPaths
      await api.adminUpdateProduct(route.params.id, payload)
    } else {
      payload.image_ids = newPaths
      await api.adminCreateProduct(payload)
    }

    saveSuccess.value = true
    setTimeout(() => router.push('/admin/products'), 800)
  } catch (e) {
    saveError.value = e.response?.data?.message || '保存失败，请重试'
  } finally {
    saving.value = false
  }
}

async function loadProduct() {
  const res = await fetch(`/api/products/${route.params.id}`)
  const product = await res.json()

  form.name_zh        = product.name_zh        || ''
  form.name_en        = product.name_en        || ''
  form.category_id    = product.category_id    || null
  form.description_zh = product.description_zh || ''
  form.description_en = product.description_en || ''
  form.substrates     = product.substrates     || []
  form.specs          = product.specs          || []
  form.thicknesses    = product.thicknesses    || []
  form.cover_image    = product.cover_image    || null
  form.status         = product.status         ?? 1
  form.sort_order     = product.sort_order     ?? 0
  existingImages.value = product.images        || []
}

onMounted(async () => {
  const [catRes] = await Promise.all([
    api.adminGetCategories(),
    loadOptions(),
  ])
  categories.value = catRes.data
  if (isEdit.value) {
    await loadProduct()
  }
})
</script>

<style scoped>
.btn-primary {
  @apply inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-lg transition-colors;
}
.form-label {
  @apply text-sm font-medium text-gray-700 mb-1 block;
}
.input-field {
  @apply w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-primary-500 transition bg-white;
}
.tag-option {
  @apply px-3 py-1.5 border border-gray-200 rounded-full text-gray-600 hover:border-primary-400 hover:text-primary-600 transition-colors cursor-pointer;
}
.tag-option.active {
  @apply bg-primary-600 text-white border-primary-600;
}
</style>
