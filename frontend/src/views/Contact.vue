<template>
  <div>
    <!-- Page Header -->
    <section class="bg-[#EEF0FF] py-12">
      <div class="container-main">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ $t('contact.title') }}</h1>
        <p class="text-gray-500">{{ $t('contact.subtitle') }}</p>
      </div>
    </section>

    <section class="py-14 bg-white">
      <div class="container-main">
        <div class="max-w-xl flex flex-col gap-6">

          <!-- 电话 -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ isEn ? 'Tel' : '电话' }}</h3>
              <a :href="`tel:${s('contact_tel')}`" class="text-primary-600 text-sm hover:underline">
                {{ s('contact_tel') || '+86 13535717734' }}
              </a>
            </div>
          </div>

          <!-- WhatsApp -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 text-sm mb-1">WhatsApp</h3>
              <a
                :href="`https://wa.me/${waLink}`"
                target="_blank"
                class="text-green-600 text-sm hover:underline"
              >
                {{ s('contact_whatsapp') || '+86 13535717734' }}
              </a>
            </div>
          </div>

          <!-- 微信 -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-primary-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M8.691 2.188C3.891 2.188 0 5.476 0 9.53c0 2.212 1.17 4.203 3.002 5.55a.59.59 0 01.213.665l-.39 1.48c-.019.07-.048.141-.048.213 0 .163.13.295.29.295a.326.326 0 00.167-.054l1.903-1.114a.864.864 0 01.717-.098 10.16 10.16 0 002.837.403c.276 0 .543-.027.811-.05-.857-2.578.157-4.972 1.932-6.446 1.703-1.415 3.882-1.98 5.853-1.838-.576-3.583-4.196-6.348-8.596-6.348zM5.785 5.991c.642 0 1.162.529 1.162 1.18a1.17 1.17 0 01-1.162 1.178A1.17 1.17 0 014.623 7.17c0-.651.52-1.18 1.162-1.18zm5.813 0c.642 0 1.162.529 1.162 1.18a1.17 1.17 0 01-1.162 1.178 1.17 1.17 0 01-1.162-1.178c0-.651.52-1.18 1.162-1.18zm5.34 2.867c-1.797-.052-3.746.512-5.28 1.786-1.72 1.428-2.687 3.72-1.78 6.22.942 2.453 3.666 4.229 6.884 4.229.826 0 1.622-.12 2.361-.336a.722.722 0 01.598.082l1.584.926a.272.272 0 00.14.047c.134 0 .24-.111.24-.247 0-.06-.023-.12-.038-.177l-.327-1.233a.582.582 0 01-.023-.156.49.49 0 01.201-.398C23.024 18.48 24 16.82 24 14.98c0-3.21-2.931-5.837-7.062-6.122zm-3.518 3.274c.535 0 .969.44.969.982a.976.976 0 01-.969.983.976.976 0 01-.969-.983c0-.542.434-.982.969-.982zm4.703 0c.535 0 .969.44.969.982a.976.976 0 01-.969.983.976.976 0 01-.969-.983c0-.542.434-.982.969-.982z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ isEn ? 'WeChat' : '微信' }}</h3>
              <span class="text-gray-700 text-sm">{{ s('contact_wechat') || '13535717734' }}</span>
            </div>
          </div>

          <!-- 邮箱 -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ isEn ? 'E-mail' : '邮箱' }}</h3>
              <a :href="`mailto:${s('contact_email')}`" class="text-primary-600 text-sm hover:underline">
                {{ s('contact_email') || '995754135@qq.com' }}
              </a>
            </div>
          </div>

          <!-- 地址 -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ isEn ? 'Address' : '地址' }}</h3>
              <p class="text-gray-600 text-sm">{{ s('contact_address') || '广东省佛山市南海区丹灶镇明沙南路22号' }}</p>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { reactive, computed, onMounted } from 'vue'
import { useAppStore } from '@/stores/app.js'
import api from '@/api/index.js'

const appStore = useAppStore()
const isEn = computed(() => appStore.locale === 'en')

const settings = reactive({})

/** 按当前语言取值，英文优先 _en，fallback 中文 */
function s(key) {
  if (isEn.value) {
    return settings[key + '_en'] || settings[key] || ''
  }
  return settings[key] || ''
}

/** WhatsApp link: strip +, spaces */
const waLink = computed(() => {
  const raw = settings['contact_whatsapp'] || '8613535717734'
  return raw.replace(/[^0-9]/g, '')
})

onMounted(async () => {
  try {
    const { data } = await api.getSettings()
    Object.assign(settings, data)
  } catch {
    // fallback to hardcoded defaults in template
  }
})
</script>
