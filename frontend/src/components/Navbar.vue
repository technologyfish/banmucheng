<template>
  <header class="sticky top-0 z-50 bg-white shadow-sm">
    <div class="container-main px-4 md:px-8">
      <div class="flex items-center justify-between h-16 md:h-20">
        <!-- Logo -->
        <RouterLink to="/" class="flex items-center gap-2 flex-shrink-0">
          <img :src="logoImg" alt="板木诚" class="h-9 md:h-12 w-auto object-contain" />
        </RouterLink>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-10">
          <template v-for="item in navItems" :key="item.to">

            <!-- Contact Us: popover trigger -->
            <div v-if="item.contact" class="relative" ref="contactTrigger">
              <button
                @click.stop="toggleContact"
                :class="['text-sm font-medium transition-colors duration-200 hover:text-primary-600 pb-1 flex items-center gap-1',
                  contactOpen ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700']"
              >
                {{ $t(item.label) }}
                <svg :class="['w-3 h-3 transition-transform duration-200', contactOpen ? 'rotate-180' : '']"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>

              <!-- Popover bubble -->
              <Transition name="popover">
                <div
                  v-if="contactOpen"
                  class="absolute top-full right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden"
                  style="filter: drop-shadow(0 8px 24px rgba(0,0,0,0.12))"
                  @click.stop
                >
                  <!-- Arrow -->
                  <div class="absolute -top-2 right-6 w-4 h-4 bg-white border-l border-t border-gray-100 rotate-45"></div>

                  <!-- Rows -->
                  <div class="px-5 py-3 flex flex-col gap-3">

                    <a :href="`tel:${s('contact_tel')}`" class="contact-row group">
                      <span class="ci bg-blue-50 text-blue-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                      </span>
                      <div>
                        <p class="cl">{{ isEn ? 'Tel' : '电话' }}</p>
                        <p class="cv group-hover:text-primary-600">{{ s('contact_tel') || '+86 13535717734' }}</p>
                      </div>
                    </a>

                    <a :href="`https://wa.me/${waLink}`" target="_blank" class="contact-row group">
                      <span class="ci bg-green-50 text-green-500">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                      </span>
                      <div>
                        <p class="cl">WhatsApp</p>
                        <p class="cv group-hover:text-green-600">{{ s('contact_whatsapp') || '+86 13535717734' }}</p>
                      </div>
                    </a>

                    <div class="contact-row">
                      <span class="ci bg-green-50 text-green-500">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M8.691 2.188C3.891 2.188 0 5.476 0 9.53c0 2.212 1.17 4.203 3.002 5.55a.59.59 0 01.213.665l-.39 1.48c-.019.07-.048.141-.048.213 0 .163.13.295.29.295a.326.326 0 00.167-.054l1.903-1.114a.864.864 0 01.717-.098 10.16 10.16 0 002.837.403c.276 0 .543-.027.811-.05-.857-2.578.157-4.972 1.932-6.446 1.703-1.415 3.882-1.98 5.853-1.838-.576-3.583-4.196-6.348-8.596-6.348zM5.785 5.991c.642 0 1.162.529 1.162 1.18a1.17 1.17 0 01-1.162 1.178A1.17 1.17 0 014.623 7.17c0-.651.52-1.18 1.162-1.18zm5.813 0c.642 0 1.162.529 1.162 1.18a1.17 1.17 0 01-1.162 1.178 1.17 1.17 0 01-1.162-1.178c0-.651.52-1.18 1.162-1.18zm5.34 2.867c-1.797-.052-3.746.512-5.28 1.786-1.72 1.428-2.687 3.72-1.78 6.22.942 2.453 3.666 4.229 6.884 4.229.826 0 1.622-.12 2.361-.336a.722.722 0 01.598.082l1.584.926a.272.272 0 00.14.047c.134 0 .24-.111.24-.247 0-.06-.023-.12-.038-.177l-.327-1.233a.582.582 0 01-.023-.156.49.49 0 01.201-.398C23.024 18.48 24 16.82 24 14.98c0-3.21-2.931-5.837-7.062-6.122zm-3.518 3.274c.535 0 .969.44.969.982a.976.976 0 01-.969.983.976.976 0 01-.969-.983c0-.542.434-.982.969-.982zm4.703 0c.535 0 .969.44.969.982a.976.976 0 01-.969.983.976.976 0 01-.969-.983c0-.542.434-.982.969-.982z"/>
                        </svg>
                      </span>
                      <div>
                        <p class="cl">{{ isEn ? 'WeChat' : '微信' }}</p>
                        <p class="cv">{{ s('contact_wechat') || '13535717734' }}</p>
                      </div>
                    </div>

                    <a :href="`mailto:${s('contact_email')}`" class="contact-row group">
                      <span class="ci bg-blue-50 text-blue-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                      </span>
                      <div>
                        <p class="cl">{{ isEn ? 'E-mail' : '邮箱' }}</p>
                        <p class="cv group-hover:text-primary-600">{{ s('contact_email') || '995754135@qq.com' }}</p>
                      </div>
                    </a>

                    <div class="contact-row">
                      <span class="ci bg-orange-50 text-orange-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                      </span>
                      <div>
                        <p class="cl">{{ isEn ? 'Address' : '地址' }}</p>
                        <p class="cv leading-relaxed">{{ s('contact_address') || '广东省佛山市南海区丹灶镇明沙南路22号' }}</p>
                      </div>
                    </div>

                  </div>
                </div>
              </Transition>
            </div>

            <!-- Normal nav items -->
            <RouterLink
              v-else
              :to="item.to"
              :class="['text-sm font-medium transition-colors duration-200 hover:text-primary-600 pb-1',
                isActive(item.to) ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700']"
            >
              {{ $t(item.label) }}
            </RouterLink>

          </template>

          <!-- Language Switch -->
          <div class="flex items-center gap-1 ml-2 border border-gray-200 rounded-full px-3 py-1">
            <button
              @click="switchLocale('zh')"
              :class="['text-xs font-medium transition-colors', locale === 'zh' ? 'text-primary-600' : 'text-gray-400 hover:text-gray-600']"
            >中</button>
            <span class="text-gray-300 text-xs">|</span>
            <button
              @click="switchLocale('en')"
              :class="['text-xs font-medium transition-colors', locale === 'en' ? 'text-primary-600' : 'text-gray-400 hover:text-gray-600']"
            >EN</button>
          </div>
        </nav>

        <!-- Mobile menu button -->
        <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-gray-600">
          <svg v-if="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <Transition name="slide-down">
      <div v-if="mobileOpen" class="md:hidden bg-white border-t border-gray-100 shadow-lg">
        <nav class="container-main py-4 flex flex-col gap-1">
          <template v-for="item in navItems" :key="item.to">
            <!-- Contact Us in mobile: expand inline -->
            <div v-if="item.contact">
              <button
                @click="mobileContactOpen = !mobileContactOpen"
                :class="['w-full px-3 py-3 rounded-lg text-sm font-medium transition-colors text-left flex items-center justify-between',
                  mobileContactOpen ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50']"
              >
                {{ $t(item.label) }}
                <svg :class="['w-4 h-4 transition-transform', mobileContactOpen ? 'rotate-180' : '']"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <div v-if="mobileContactOpen" class="mx-3 mb-2 rounded-xl bg-gray-50 px-4 py-3 flex flex-col gap-3">
                <a :href="`tel:${s('contact_tel')}`" class="flex items-center gap-2 text-sm text-gray-700">
                  <span class="text-blue-500 font-medium">{{ isEn ? 'Tel' : '电话' }}：</span>{{ s('contact_tel') || '+86 13535717734' }}
                </a>
                <a :href="`https://wa.me/${waLink}`" target="_blank" class="flex items-center gap-2 text-sm text-gray-700">
                  <span class="text-green-500 font-medium">WhatsApp：</span>{{ s('contact_whatsapp') || '+86 13535717734' }}
                </a>
                <div class="flex items-center gap-2 text-sm text-gray-700">
                  <span class="text-green-500 font-medium">{{ isEn ? 'WeChat' : '微信' }}：</span>{{ s('contact_wechat') || '13535717734' }}
                </div>
                <a :href="`mailto:${s('contact_email')}`" class="flex items-center gap-2 text-sm text-gray-700">
                  <span class="text-blue-500 font-medium">{{ isEn ? 'E-mail' : '邮箱' }}：</span>{{ s('contact_email') || '995754135@qq.com' }}
                </a>
                <div class="flex items-start gap-2 text-sm text-gray-700">
                  <span class="text-orange-400 font-medium flex-shrink-0">{{ isEn ? 'Address' : '地址' }}：</span>
                  <span>{{ s('contact_address') || '广东省佛山市南海区丹灶镇明沙南路22号' }}</span>
                </div>
              </div>
            </div>
            <!-- Normal items -->
            <RouterLink
              v-else
              :to="item.to"
              @click="mobileOpen = false"
              :class="['px-3 py-3 rounded-lg text-sm font-medium transition-colors',
                isActive(item.to) ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50']"
            >
              {{ $t(item.label) }}
            </RouterLink>
          </template>
          <!-- Language in mobile -->
          <div class="flex gap-3 px-3 pt-3 border-t border-gray-100 mt-2">
            <button @click="switchLocale('zh')" :class="['text-sm font-medium', locale === 'zh' ? 'text-primary-600' : 'text-gray-400']">中文</button>
            <button @click="switchLocale('en')" :class="['text-sm font-medium', locale === 'en' ? 'text-primary-600' : 'text-gray-400']">English</button>
          </div>
        </nav>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { ref, computed, reactive, onMounted, onBeforeUnmount } from 'vue'
import { useRoute } from 'vue-router'
import { useAppStore } from '@/stores/app.js'
import { useI18n } from 'vue-i18n'
import logoImg from '@/assets/images/logo.png'
import apiService from '@/api/index.js'

const route = useRoute()
const appStore = useAppStore()
const { locale: i18nLocale } = useI18n()
const mobileOpen = ref(false)
const contactOpen = ref(false)
const mobileContactOpen = ref(false)

const locale = computed(() => appStore.locale)
const isEn = computed(() => appStore.locale === 'en')

const settings = reactive({})

function s(key) {
  if (isEn.value) return settings[key + '_en'] || settings[key] || ''
  return settings[key] || ''
}

const waLink = computed(() => {
  const raw = settings['contact_whatsapp'] || '8613535717734'
  return raw.replace(/[^0-9]/g, '')
})

onMounted(async () => {
  try {
    const { data } = await apiService.getSettings()
    Object.assign(settings, data)
  } catch { /* fallback */ }
  document.addEventListener('click', onOutsideClick)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', onOutsideClick)
})

function onOutsideClick() {
  contactOpen.value = false
}

const navItems = [
  { to: '/',              label: 'nav.home' },
  { to: '/products',      label: 'nav.products' },
  { to: '/#production',   label: 'nav.production' },
  { to: '/#certificates', label: 'nav.certificates' },
  { to: '/contact',       label: 'nav.contact', contact: true },
]

function isActive(to) {
  if (to === '/') return route.path === '/'
  if (to.startsWith('/#')) return false
  return route.path.startsWith(to)
}

function toggleContact() {
  contactOpen.value = !contactOpen.value
}

function switchLocale(lang) {
  appStore.setLocale(lang)
  i18nLocale.value = lang
  mobileOpen.value = false
}
</script>

<style scoped>
.slide-down-enter-active, .slide-down-leave-active { transition: all 0.2s ease; }
.slide-down-enter-from, .slide-down-leave-to { opacity: 0; transform: translateY(-8px); }

/* Popover animation */
.popover-enter-active, .popover-leave-active { transition: all 0.18s ease; }
.popover-enter-from, .popover-leave-to { opacity: 0; transform: translateY(-6px) scale(0.97); }

/* Contact row helpers */
.contact-row {
  @apply flex items-start gap-3 cursor-default;
}
a.contact-row {
  @apply cursor-pointer;
}
.ci {
  @apply w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5;
}
.cl {
  @apply text-xs text-gray-400 leading-tight;
}
.cv {
  @apply text-sm text-gray-800 font-medium transition-colors;
}
</style>
