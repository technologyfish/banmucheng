<template>
  <div class="p-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-900">规格管理</h1>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

      <!-- 可选基材 -->
      <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
          <h2 class="font-semibold text-gray-800">可选基材</h2>
          <button @click="openForm('substrate')" class="btn-sm">+ 添加</button>
        </div>
        <OptionList
          :items="options.substrate"
          @edit="openEditForm"
          @delete="deleteOption"
        />
      </div>

      <!-- 可选规格 -->
      <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
          <h2 class="font-semibold text-gray-800">可选规格</h2>
          <button @click="openForm('spec')" class="btn-sm">+ 添加</button>
        </div>
        <OptionList
          :items="options.spec"
          @edit="openEditForm"
          @delete="deleteOption"
        />
      </div>

      <!-- 可选厚度 -->
      <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
          <h2 class="font-semibold text-gray-800">可选厚度</h2>
          <button @click="openForm('thickness')" class="btn-sm">+ 添加</button>
        </div>
        <OptionList
          :items="options.thickness"
          @edit="openEditForm"
          @delete="deleteOption"
        />
      </div>
    </div>

    <!-- Form Modal -->
    <Transition name="modal">
      <div v-if="formVisible" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-5">
            {{ editingItem ? '编辑选项' : `新增${typeLabel}` }}
          </h2>

          <form @submit.prevent="saveOption" class="flex flex-col gap-4">
            <div>
              <label class="text-sm font-medium text-gray-700 mb-1 block">中文值 <span class="text-red-500">*</span></label>
              <input v-model="form.value_zh" type="text" required class="input-field" placeholder="如：包烧板 / 1.22×2.44m / 12mm" />
            </div>
            <div>
              <label class="text-sm font-medium text-gray-700 mb-1 block">英文值 <span class="text-red-500">*</span></label>
              <input v-model="form.value_en" type="text" required class="input-field" placeholder="e.g. Plywood / 1.22×2.44m / 12mm" />
            </div>
            <div>
              <label class="text-sm font-medium text-gray-700 mb-1 block">排序</label>
              <input v-model.number="form.sort_order" type="number" class="input-field" placeholder="0" />
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
import { ref, reactive, computed, onMounted } from 'vue'
import api from '@/api/index.js'

const options = reactive({ substrate: [], spec: [], thickness: [] })
const formVisible = ref(false)
const formType = ref('')
const editingItem = ref(null)
const saving = ref(false)
const formError = ref('')
const form = reactive({ value_zh: '', value_en: '', sort_order: 0 })

const typeLabel = computed(() => {
  const m = { substrate: '基材', spec: '规格', thickness: '厚度' }
  return m[formType.value] || ''
})

async function loadOptions() {
  try {
    const { data } = await api.adminGetOptions()
    options.substrate = data.substrate || []
    options.spec = data.spec || []
    options.thickness = data.thickness || []
  } catch (e) {
    console.error(e)
  }
}

function openForm(type) {
  formType.value = type
  editingItem.value = null
  form.value_zh = ''
  form.value_en = ''
  form.sort_order = 0
  formError.value = ''
  formVisible.value = true
}

function openEditForm(item) {
  formType.value = item.type
  editingItem.value = item
  form.value_zh = item.value_zh
  form.value_en = item.value_en
  form.sort_order = item.sort_order
  formError.value = ''
  formVisible.value = true
}

async function saveOption() {
  saving.value = true
  formError.value = ''
  try {
    if (editingItem.value) {
      await api.adminUpdateOption(editingItem.value.id, {
        value_zh: form.value_zh,
        value_en: form.value_en,
        sort_order: form.sort_order,
      })
    } else {
      await api.adminCreateOption({
        type: formType.value,
        value_zh: form.value_zh,
        value_en: form.value_en,
        sort_order: form.sort_order,
      })
    }
    formVisible.value = false
    await loadOptions()
  } catch (e) {
    formError.value = e.response?.data?.message || '保存失败'
  } finally {
    saving.value = false
  }
}

async function deleteOption(item) {
  if (!confirm(`确定要删除「${item.value_zh}」吗？`)) return
  try {
    await api.adminDeleteOption(item.id)
    await loadOptions()
  } catch (e) {
    alert(e.response?.data?.message || '删除失败')
  }
}

onMounted(loadOptions)
</script>

<!-- Inline sub-component for option list rows -->
<script>
import { defineComponent, h } from 'vue'

const OptionList = defineComponent({
  props: { items: Array },
  emits: ['edit', 'delete'],
  setup(props, { emit }) {
    return () => h('div', { class: 'divide-y divide-gray-50' }, [
      ...(props.items || []).map(item =>
        h('div', { key: item.id, class: 'flex items-center justify-between px-5 py-3 hover:bg-gray-50' }, [
          h('span', { class: 'text-sm text-gray-700' }, item.value_zh),
          h('div', { class: 'flex items-center gap-3' }, [
            h('span', { class: 'text-xs text-gray-400' }, item.value_en),
            h('button', {
              class: 'text-primary-600 hover:text-primary-700 text-xs font-medium',
              onClick: () => emit('edit', item)
            }, '编辑'),
            h('button', {
              class: 'text-red-500 hover:text-red-600 text-xs font-medium',
              onClick: () => emit('delete', item)
            }, '删除'),
          ])
        ])
      ),
      props.items?.length === 0
        ? h('p', { class: 'text-center text-gray-400 text-sm py-6' }, '暂无数据')
        : null,
    ])
  }
})

export default { components: { OptionList } }
</script>

<style scoped>
.btn-primary {
  @apply inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-lg transition-colors;
}
.btn-sm {
  @apply text-xs text-primary-600 hover:text-primary-700 border border-primary-200 hover:border-primary-400 px-3 py-1 rounded-lg transition-colors;
}
.input-field {
  @apply w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-primary-500 transition bg-white;
}
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
