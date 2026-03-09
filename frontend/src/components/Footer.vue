<template>
  <footer class="bg-white border-t border-gray-200 py-10">
    <div class="container-main px-4 md:px-8">
      <div class="flex flex-col md:flex-row justify-between items-start gap-6">
        <!-- 品牌 & 版权 -->
        <div>
          <div class="text-xl font-bold text-gray-900 mb-2">
            {{ t('footer_brand') || '板木诚' }}
          </div>
          <p class="text-sm text-gray-400">
            {{ t('footer_copyright') || $t('footer.rights') }}
          </p>
        </div>

        <!-- 联系信息 -->
        <div class="flex flex-col gap-2">
          <p v-if="t('footer_address')" class="text-sm text-gray-600">
            {{ isEn ? 'Address' : '地址' }}：{{ t('footer_address') }}
          </p>
          <p v-if="t('footer_phone')" class="text-sm text-gray-600">
            {{ isEn ? 'Tel' : '电话' }}：{{ t('footer_phone') }}
          </p>
          <p v-if="t('footer_email')" class="text-sm text-gray-600">
            {{ isEn ? 'Email' : '邮箱' }}：{{ t('footer_email') }}
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { reactive, computed, onMounted } from 'vue'
import { useAppStore } from '@/stores/app.js'
import api from '@/api/index.js'

const appStore = useAppStore()
const isEn = computed(() => appStore.locale === 'en')

const settings = reactive({})

/** 按当前语言取对应字段，英文优先用 _en，无则 fallback 中文 */
function t(key) {
  if (isEn.value) {
    return settings[key + '_en'] || settings[key] || ''
  }
  return settings[key] || ''
}

onMounted(async () => {
  try {
    const { data } = await api.getSettings()
    Object.assign(settings, data)
  } catch {
    // 接口异常保持空值，模板 fallback 处理
  }
})
</script>
