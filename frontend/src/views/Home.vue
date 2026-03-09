<template>
  <div>
    <!-- ===== HERO / BANNER (栏目ID=1) ===== -->
    <section class="relative min-h-[520px] md:min-h-[720px] flex items-center bg-gray-800 overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-gray-800/60 to-gray-700/40 z-10"></div>
      <div
        class="absolute inset-0 bg-cover bg-center opacity-60"
        :style="bannerArticle && bannerArticle.cover_image
          ? `background-image:url('/uploads/${bannerArticle.cover_image}')`
          : `background-image:url('/images/hero-bg.jpg')`"
      ></div>

      <div class="container-main relative z-20 py-12 md:py-24 px-4 md:px-8">
        <template v-if="bannerArticle">
          <div class="grid md:grid-cols-2 gap-8 md:gap-10 items-center">
            <!-- 左：标题 -->
            <div>
              <h1 class="banner-title text-3xl md:text-5xl font-bold text-white whitespace-pre-line">
                {{ locale === 'zh' ? (bannerArticle.title || '') : (bannerArticle.title_en || bannerArticle.title || '') }}
              </h1>
            </div>
            <!-- 右：描述 + CTA -->
            <div class="flex flex-col gap-5 md:gap-6">
              <p class="text-gray-200 text-sm md:text-base leading-relaxed">
                {{ locale === 'zh' ? (bannerArticle.description || '') : (bannerArticle.description_en || bannerArticle.description || '') }}
              </p>
              <div>
                <RouterLink to="/products" class="btn-primary text-sm">
                  {{ $t('home.hero.cta') }}
                </RouterLink>
              </div>
            </div>
          </div>
        </template>
        <!-- 无文章时的默认内容 -->
        <template v-else>
          <div class="grid md:grid-cols-2 gap-8 md:gap-10 items-center">
            <div>
              <h1 class="banner-title text-3xl md:text-5xl font-bold text-white whitespace-pre-line">
                {{ $t('home.hero.title') }}
              </h1>
            </div>
            <div class="flex flex-col gap-5 md:gap-6">
              <p class="text-gray-200 text-sm md:text-base leading-relaxed">{{ $t('home.hero.subtitle') }}</p>
              <div>
                <RouterLink to="/products" class="btn-primary text-sm">{{ $t('home.hero.cta') }}</RouterLink>
              </div>
            </div>
          </div>
        </template>
      </div>
    </section>

    <!-- ===== PRODUCTION STRENGTH (栏目ID=2) ===== -->
    <section id="production" class="bg-gray-50 py-12 md:py-16">
      <div class="container-main px-4 md:px-8">
        <h2 class="section-title mb-2">{{ $t('home.production.title') }}</h2>
        <p class="text-gray-500 text-sm mb-8">{{ locale === 'zh' ? '真实生产现场，品质可见' : 'Real production site, quality visible' }}</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
          <div
            v-for="item in productionArticles"
            :key="item.id"
            class="group flex flex-col rounded-xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow"
          >
            <div class="aspect-[4/3] bg-gray-200 overflow-hidden flex-shrink-0">
              <img
                v-if="item.cover_image"
                :src="`/uploads/${item.cover_image}`"
                :alt="item.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div v-else class="w-full h-full flex items-center justify-center bg-gray-200">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
            </div>
            <div class="px-3 py-2.5">
              <p class="text-gray-800 text-xs md:text-sm font-medium line-clamp-2">
                {{ locale === 'zh' ? (item.title || '') : (item.title_en || item.title || '') }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== BRAND PARTNERS (栏目ID=3) ===== -->
    <section class="bg-gray-50 py-12 md:py-16">
      <div class="container-main px-4 md:px-8">
        <h2 class="section-title mb-2">{{ $t('home.brands.title') }}</h2>
        <p class="text-gray-500 text-sm mb-8 md:mb-10">{{ $t('home.brands.subtitle') }}</p>

        <div class="grid grid-cols-3 md:grid-cols-5 gap-3 md:gap-6 items-center">
          <div
            v-for="item in brandArticles"
            :key="item.id"
            class="flex items-center justify-center p-2 md:p-3 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow"
          >
            <img
              v-if="item.cover_image"
              :src="`/uploads/${item.cover_image}`"
              :alt="item.title"
              class="max-h-12 md:max-h-16 max-w-full object-contain"
            />
            <span v-else class="text-xs md:text-sm font-bold text-gray-600 text-center">
              {{ locale === 'zh' ? item.title : (item.title_en || item.title) }}
            </span>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== CERTIFICATES (栏目ID=4, Swiper) ===== -->
    <section id="certificates" class="bg-gray-50 py-12 md:py-16">
      <div class="container-main px-4 md:px-8">
        <h2 class="section-title text-center mb-2">{{ $t('home.certificates.title') }}</h2>
        <p class="text-gray-500 text-sm text-center mb-8 md:mb-10">{{ $t('home.certificates.subtitle') }}</p>

        <!-- 有数据：左右箭头 + Swiper 居中轮播 -->
        <div v-if="certArticles.length > 0" class="max-w-3xl mx-auto relative flex items-center gap-3 md:gap-4">
          <!-- 左箭头 -->
          <button
            @click="certPrev"
            class="flex-shrink-0 w-9 h-9 md:w-11 md:h-11 rounded-full border-2 border-gray-300 hover:border-primary-500 flex items-center justify-center text-gray-500 hover:text-primary-600 transition-colors"
          >
            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>

          <!-- Swiper 区域 -->
          <div class="flex-1 overflow-hidden">
            <div ref="swiperEl" class="swiper">
              <div class="swiper-wrapper">
                <div
                  v-for="item in certArticles"
                  :key="item.id"
                  class="swiper-slide !flex flex-col items-center"
                >
                  <div class="w-32 h-44 md:w-44 md:h-60 bg-gray-100 rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
                    <img
                      v-if="item.cover_image"
                      :src="`/uploads/${item.cover_image}`"
                      :alt="item.title"
                      class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center">
                      <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                      </svg>
                    </div>
                  </div>
                  <p class="text-xs text-gray-600 text-center mt-2 max-w-[128px] md:max-w-[176px] line-clamp-2">
                    {{ locale === 'zh' ? item.title : (item.title_en || item.title) }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- 右箭头 -->
          <button
            @click="certNext"
            class="flex-shrink-0 w-9 h-9 md:w-11 md:h-11 rounded-full border-2 border-gray-300 hover:border-primary-500 flex items-center justify-center text-gray-500 hover:text-primary-600 transition-colors"
          >
            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>

        <!-- 无数据占位 -->
        <div v-else class="max-w-3xl mx-auto flex justify-center gap-4 md:gap-6 flex-wrap">
          <div v-for="i in 4" :key="i" class="flex flex-col items-center gap-3">
            <div class="w-32 h-44 md:w-44 md:h-60 bg-gray-100 rounded-2xl flex items-center justify-center border border-gray-200">
              <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
              </svg>
            </div>
            <p class="text-xs text-gray-400">{{ locale === 'zh' ? `证书 ${i}` : `Cert ${i}` }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== FAQ (栏目ID=5) ===== -->
    <section class="bg-gray-50 py-12 md:py-16 border-t border-gray-100">
      <div class="container-main px-4 md:px-8">
        <h2 class="section-title text-center mb-8 md:mb-12">{{ $t('home.faq.title') }}</h2>

        <div class="max-w-3xl mx-auto">
          <!-- 动态 FAQ（后台数据） -->
          <template v-if="faqArticles.length > 0">
            <div
              v-for="(item, index) in faqArticles"
              :key="item.id"
              class="border-b border-gray-200"
            >
              <button
                @click="toggleFaq(index)"
                class="w-full flex justify-between items-center py-5 text-left gap-4"
              >
                <span :class="['text-base font-semibold leading-snug', openFaq === index ? 'text-primary-600' : 'text-gray-800']">
                  {{ locale === 'zh' ? (item.title || '') : (item.title_en || item.title || '') }}
                </span>
                <svg
                  :class="['w-5 h-5 flex-shrink-0 transition-transform duration-200', openFaq === index ? 'rotate-180 text-primary-600' : 'text-gray-400']"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <Transition name="faq-expand">
                <div v-if="openFaq === index" class="pb-5">
                  <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    {{ locale === 'zh' ? (item.description || '') : (item.description_en || item.description || '') }}
                  </p>
                </div>
              </Transition>
            </div>
          </template>

          <!-- 降级：i18n 静态数据 -->
          <template v-else>
            <div
              v-for="(item, index) in $tm('home.faq.items')"
              :key="index"
              class="border-b border-gray-200"
            >
              <button @click="toggleFaq(index)" class="w-full flex justify-between items-center py-5 text-left gap-4">
                <span :class="['text-base font-semibold leading-snug', openFaq === index ? 'text-primary-600' : 'text-gray-800']">{{ item.q }}</span>
                <svg :class="['w-5 h-5 flex-shrink-0 transition-transform duration-200', openFaq === index ? 'rotate-180 text-primary-600' : 'text-gray-400']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <Transition name="faq-expand">
                <div v-if="openFaq === index" class="pb-5">
                  <p class="text-gray-600 text-sm md:text-base leading-relaxed">{{ item.a }}</p>
                </div>
              </Transition>
            </div>
          </template>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch, nextTick } from 'vue'
import { useAppStore } from '@/stores/app.js'
import api from '@/api/index.js'
import Swiper from 'swiper'
import 'swiper/css'

const appStore = useAppStore()
const locale   = computed(() => appStore.locale)

const openFaq = ref(0)
function toggleFaq(index) {
  openFaq.value = openFaq.value === index ? null : index
}

// ─── 数据 ───
const bannerArticle      = ref(null)
const productionArticles = ref([])
const brandArticles      = ref([])
const certArticles       = ref([])
const faqArticles        = ref([])

async function fetchSection(categoryId) {
  try {
    const { data } = await api.getPublicArticles({ category_id: categoryId, per_page: 50 })
    return data.data || []
  } catch {
    return []
  }
}

// ─── Swiper ───
const swiperEl   = ref(null)
let   swiperInst = null

function initSwiper() {
  if (swiperInst) { swiperInst.destroy(true, true); swiperInst = null }
  if (!swiperEl.value || certArticles.value.length === 0) return
  swiperInst = new Swiper(swiperEl.value, {
    slidesPerView: 'auto',
    spaceBetween: 20,
  })
}

function certPrev() { swiperInst?.slidePrev() }
function certNext() { swiperInst?.slideNext() }

onBeforeUnmount(() => {
  if (swiperInst) swiperInst.destroy(true, true)
})

watch(certArticles, async () => {
  await nextTick()
  initSwiper()
})

onMounted(async () => {
  const [b, p, br, c, f] = await Promise.all([
    fetchSection(1),
    fetchSection(2),
    fetchSection(3),
    fetchSection(4),
    fetchSection(5),
  ])
  bannerArticle.value      = b[0] || null
  productionArticles.value = p
  brandArticles.value      = br
  certArticles.value       = c
  faqArticles.value        = f
})
</script>

<style scoped>
/* Banner 标题行高：强制覆盖 Tailwind text-5xl 默认的 line-height:1 */
.banner-title {
  line-height: 1.4 !important;
}

/* Swiper 卡片数量不满时居中对齐 */
:deep(.swiper-wrapper) {
  justify-content: center;
}

.faq-expand-enter-active,
.faq-expand-leave-active {
  transition: all 0.25s ease;
  overflow: hidden;
}
.faq-expand-enter-from,
.faq-expand-leave-to {
  opacity: 0;
  max-height: 0;
}
.faq-expand-enter-to,
.faq-expand-leave-from {
  opacity: 1;
  max-height: 300px;
}

/* Swiper slide 宽度自适应证书卡片 */
:deep(.swiper-slide) {
  width: auto;
}
</style>
