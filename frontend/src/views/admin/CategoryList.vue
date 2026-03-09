<template>
  <div class="p-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-900">分类管理</h1>
      <button @click="openForm()" class="btn-primary text-sm">+ 新增分类</button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
      <table class="w-full text-sm table-fixed">
        <colgroup>
          <col style="width:60px" />
          <col />
          <col />
          <col style="width:90px" />
          <col style="width:110px" />
        </colgroup>
        <thead>
          <tr class="border-b border-gray-100 bg-gray-50">
            <th class="px-5 py-3 text-left text-gray-500 font-medium">ID</th>
            <th class="px-5 py-3 text-left text-gray-500 font-medium">中文名称</th>
            <th class="px-5 py-3 text-left text-gray-500 font-medium">英文名称</th>
            <th class="px-5 py-3 text-left text-gray-500 font-medium">排序</th>
            <th class="px-5 py-3 text-left text-gray-500 font-medium">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(cat, idx) in categories" :key="cat.id" class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
            <td class="px-5 py-3 text-gray-400 text-xs">{{ cat.id }}</td>
            <td class="px-5 py-3 font-medium text-gray-800">{{ cat.name_zh }}</td>
            <td class="px-5 py-3 text-gray-600">{{ cat.name_en }}</td>
            <!-- 排序箭头 -->
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <button
                  @click="reorder(cat, 'up')"
                  :disabled="idx === 0 || reorderingId === cat.id"
                  class="sort-btn" title="上移"
                >
                  <img :src="idx === 0 ? iconUpD : iconUpA" style="width:18px;height:18px;display:block;" alt="上移" />
                </button>
                <button
                  @click="reorder(cat, 'down')"
                  :disabled="idx === categories.length - 1 || reorderingId === cat.id"
                  class="sort-btn" title="下移"
                >
                  <img :src="idx === categories.length - 1 ? iconDownD : iconDownA" style="width:18px;height:18px;display:block;" alt="下移" />
                </button>
                <span v-if="reorderingId === cat.id" class="w-3 h-3 border border-primary-400 border-t-transparent rounded-full animate-spin"></span>
              </div>
            </td>
            <td class="px-5 py-3">
              <div class="flex items-center gap-3">
                <button @click="openForm(cat)" class="text-primary-600 hover:text-primary-700 text-xs font-medium">编辑</button>
                <button @click="deleteCategory(cat.id)" class="text-red-500 hover:text-red-600 text-xs font-medium">删除</button>
              </div>
            </td>
          </tr>
          <tr v-if="categories.length === 0">
            <td colspan="5" class="px-5 py-8 text-center text-gray-400">暂无分类</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Form Modal -->
    <Transition name="modal">
      <div v-if="formVisible" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-5">{{ editingId ? '编辑分类' : '新增分类' }}</h2>

          <form @submit.prevent="saveCategory" class="flex flex-col gap-4">
            <div>
              <label class="text-sm font-medium text-gray-700 mb-1 block">中文名称 <span class="text-red-500">*</span></label>
              <input v-model="form.name_zh" type="text" required class="input-field" placeholder="分类中文名称" />
            </div>
            <div>
              <label class="text-sm font-medium text-gray-700 mb-1 block">英文名称 <span class="text-red-500">*</span></label>
              <input v-model="form.name_en" type="text" required class="input-field" placeholder="Category English Name" />
            </div>

            <p v-if="formError" class="text-red-500 text-sm">{{ formError }}</p>

            <div class="flex gap-3 mt-2">
              <button type="submit" :disabled="saving" class="btn-primary flex-1 justify-center text-sm">
                <span v-if="saving" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
                保存
              </button>
              <button type="button" @click="formVisible = false" class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-lg py-2 text-sm transition-colors">取消</button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '@/api/index.js'
import iconUpA   from '@/assets/images/icon-up-a.png'
import iconUpD   from '@/assets/images/icon-up-d.png'
import iconDownA from '@/assets/images/icon-down-a.png'
import iconDownD from '@/assets/images/icon-down-d.png'

const categories  = ref([])
const formVisible = ref(false)
const editingId   = ref(null)
const saving      = ref(false)
const formError   = ref('')
const reorderingId = ref(null)
const form = reactive({ name_zh: '', name_en: '', sort_order: 0 })

async function loadCategories() {
  const { data } = await api.adminGetCategories()
  categories.value = data
}

function openForm(cat = null) {
  editingId.value   = cat?.id ?? null
  form.name_zh      = cat?.name_zh ?? ''
  form.name_en      = cat?.name_en ?? ''
  form.sort_order   = cat?.sort_order ?? 0
  formError.value   = ''
  formVisible.value = true
}

async function saveCategory() {
  saving.value    = true
  formError.value = ''
  try {
    const payload = { name_zh: form.name_zh, name_en: form.name_en, sort_order: form.sort_order, parent_id: null }
    if (editingId.value) {
      await api.adminUpdateCategory(editingId.value, payload)
    } else {
      await api.adminCreateCategory(payload)
    }
    formVisible.value = false
    await loadCategories()
  } catch (e) {
    formError.value = e.response?.data?.message || '保存失败'
  } finally {
    saving.value = false
  }
}

async function deleteCategory(id) {
  if (!confirm('确定要删除该分类吗？')) return
  try {
    await api.adminDeleteCategory(id)
    await loadCategories()
  } catch (e) {
    alert(e.response?.data?.message || '删除失败')
  }
}

async function reorder(cat, direction) {
  reorderingId.value = cat.id
  try {
    await api.adminReorderCategory(cat.id, direction)
  } catch (e) {
    if (e.response?.status !== 400) {
      alert('排序失败：' + (e.response?.data?.message || e.message))
    }
  } finally {
    reorderingId.value = null
    await loadCategories()
  }
}

onMounted(loadCategories)
</script>

<style scoped>
.btn-primary  { @apply inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-lg transition-colors; }
.input-field  { @apply w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-primary-500 transition bg-white; }
.sort-btn     { @apply p-0.5 rounded hover:bg-gray-100 transition-colors disabled:cursor-not-allowed disabled:opacity-50; }
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
