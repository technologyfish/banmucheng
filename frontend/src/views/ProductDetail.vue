<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-24">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Error -->
    <div v-else-if="!product" class="flex flex-col items-center py-24 text-gray-400">
      <p class="text-lg">{{ $t('common.error') }}</p>
      <RouterLink to="/products" class="btn-primary mt-4 text-sm">{{ $t('productDetail.backToList') }}</RouterLink>
    </div>

    <div v-else>
      <!-- Breadcrumb -->
      <div class="bg-white border-b border-gray-100">
        <div class="container-main py-5">
          <nav class="flex items-center gap-2 text-sm text-gray-500">
            <RouterLink to="/" class="hover:text-primary-600">{{ $t('nav.home') }}</RouterLink>
            <span>›</span>
            <RouterLink to="/products" class="hover:text-primary-600">{{ $t('nav.products') }}</RouterLink>
            <span>›</span>
            <span v-if="product.category" class="hover:text-primary-600 cursor-pointer" @click="goToCategory">
              {{ locale === 'zh' ? product.category.name_zh : product.category.name_en }}
            </span>
            <span v-if="product.category">›</span>
            <span class="text-gray-800 font-medium truncate max-w-xs">
              {{ locale === 'zh' ? product.name_zh : product.name_en }}
            </span>
          </nav>
        </div>
      </div>

      <!-- Main Content -->
      <div class="container-main py-8 md:py-12">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">

          <!-- ===== LEFT: Image Gallery ===== -->
          <div>
            <!-- Main Image (click to zoom) -->
            <div
              class="bg-white rounded-2xl overflow-hidden border border-gray-200 aspect-square mb-3 relative group cursor-zoom-in"
              @click="openLightbox(currentImage)"
            >
              <img
                v-if="currentImage"
                :src="`/uploads/${currentImage}`"
                :alt="locale === 'zh' ? product.name_zh : product.name_en"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
              />
              <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">{{ $t('productDetail.noImage') }}</p>
              </div>
              <!-- Zoom icon hint -->
              <div v-if="currentImage" class="absolute bottom-3 right-3 bg-black/40 text-white rounded-lg p-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0zm-3.5 0h-5m2.5-2.5v5"/>
                </svg>
              </div>
            </div>

            <!-- Thumbnails -->
            <div v-if="product.images && product.images.length > 1" class="flex gap-2 overflow-x-auto pb-1">
              <button
                v-for="img in product.images"
                :key="img.id"
                @click="currentImage = img.image_path"
                :class="['flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-colors',
                  currentImage === img.image_path ? 'border-primary-500' : 'border-gray-200 hover:border-gray-400']"
              >
                <img :src="`/uploads/${img.image_path}`" class="w-full h-full object-cover" />
              </button>
            </div>
          </div>

          <!-- ===== RIGHT: Product Details ===== -->
          <div class="flex flex-col gap-5">

            <!-- Product name -->
            <h1 class="text-lg md:text-xl font-bold text-gray-900 leading-snug">
              {{ locale === 'zh' ? product.name_zh : product.name_en }}
            </h1>

            <!-- Slogan -->
            <p class="text-base text-gray-500">开启你的定制之旅</p>

            <!-- Divider -->
            <div class="border-t border-gray-200"></div>

            <!-- Product Params (collapsible) -->
            <div v-if="hasParams" class="rounded-xl border border-gray-200 overflow-hidden">
              <!-- Header with toggle -->
              <button
                type="button"
                @click="paramsOpen = !paramsOpen"
                class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left"
              >
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                  <span class="text-sm font-semibold text-gray-700">产品参数</span>
                </div>
                <svg
                  :class="['w-4 h-4 text-gray-400 transition-transform duration-200', paramsOpen ? 'rotate-180' : '']"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>

              <Transition name="collapse">
                <div v-if="paramsOpen">

                  <!-- Substrates -->
                  <div v-if="product.substrates && product.substrates.length"
                    class="flex items-center gap-0 border-t border-gray-100">
                    <span class="text-xs text-gray-500 bg-gray-50 border-r border-gray-100 px-4 py-2.5 w-24 flex-shrink-0 whitespace-nowrap">
                      {{ $t('productDetail.substrate') }}
                    </span>
                    <div class="flex flex-wrap gap-2 px-4 py-2.5">
                      <button
                        v-for="s in product.substrates" :key="s"
                        @click="selectedSubstrate = s"
                        :class="['param-tag', selectedSubstrate === s ? 'active' : '']"
                      >{{ s }}</button>
                    </div>
                  </div>

                  <!-- Specs -->
                  <div v-if="product.specs && product.specs.length"
                    class="flex items-center gap-0 border-t border-gray-100">
                    <span class="text-xs text-gray-500 bg-gray-50 border-r border-gray-100 px-4 py-2.5 w-24 flex-shrink-0 whitespace-nowrap">
                      {{ $t('productDetail.spec') }}
                    </span>
                    <div class="flex flex-wrap gap-2 px-4 py-2.5">
                      <button
                        v-for="s in product.specs" :key="s"
                        @click="selectedSpec = s"
                        :class="['param-tag', selectedSpec === s ? 'active' : '']"
                      >{{ s }}</button>
                    </div>
                  </div>

                  <!-- Thicknesses -->
                  <div v-if="product.thicknesses && product.thicknesses.length"
                    class="flex items-center gap-0 border-t border-gray-100">
                    <span class="text-xs text-gray-500 bg-gray-50 border-r border-gray-100 px-4 py-2.5 w-24 flex-shrink-0 whitespace-nowrap">
                      {{ $t('productDetail.thickness') }}
                    </span>
                    <div class="flex flex-wrap gap-2 px-4 py-2.5">
                      <button
                        v-for="t in product.thicknesses" :key="t"
                        @click="selectedThickness = t"
                        :class="['param-tag', selectedThickness === t ? 'active' : '']"
                      >{{ t }}</button>
                    </div>
                  </div>

                </div>
              </Transition>
            </div>

            <!-- Description -->
            <div v-if="(locale === 'zh' ? product.description_zh : product.description_en)">
              <p class="text-gray-600 text-sm leading-relaxed">
                {{ locale === 'zh' ? product.description_zh : product.description_en }}
              </p>
            </div>

            <!-- CTA Button -->
            <div class="pt-2">
              <RouterLink
                to="/contact"
                class="inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm px-8 py-3.5 rounded-xl transition-colors"
              >
                tel：+86 13535717734
              </RouterLink>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- ===== Lightbox ===== -->
    <Transition name="lightbox">
      <div
        v-if="lightboxSrc"
        class="fixed inset-0 z-50 bg-black/85 flex items-center justify-center p-4"
        @click.self="closeLightbox"
      >
        <div class="relative max-w-5xl w-full flex items-center justify-center">
          <img
            :src="`/uploads/${lightboxSrc}`"
            class="max-h-[90vh] max-w-full object-contain rounded-lg shadow-2xl select-none"
            @click.stop
          />
          <!-- Close button -->
          <button
            @click="closeLightbox"
            class="absolute top-0 right-0 -mt-10 -mr-2 w-9 h-9 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
          <!-- Prev / Next arrows -->
          <button
            v-if="allImages.length > 1"
            @click="prevImage"
            class="absolute left-0 -ml-12 w-10 h-10 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <button
            v-if="allImages.length > 1"
            @click="nextImage"
            class="absolute right-0 -mr-12 w-10 h-10 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAppStore } from '@/stores/app.js'
import api from '@/api/index.js'

const route = useRoute()
const router = useRouter()
const appStore = useAppStore()
const locale = computed(() => appStore.locale)

const product = ref(null)
const loading = ref(true)
const currentImage = ref(null)
const selectedSubstrate = ref(null)
const selectedSpec = ref(null)
const selectedThickness = ref(null)
const paramsOpen = ref(true)

// Lightbox
const lightboxSrc = ref(null)
const allImages = computed(() => product.value?.images?.map(i => i.image_path) || (product.value?.cover_image ? [product.value.cover_image] : []))
const lightboxIndex = ref(0)

function openLightbox(src) {
  if (!src) return
  lightboxSrc.value = src
  lightboxIndex.value = allImages.value.indexOf(src)
}
function closeLightbox() {
  lightboxSrc.value = null
}
function prevImage() {
  lightboxIndex.value = (lightboxIndex.value - 1 + allImages.value.length) % allImages.value.length
  lightboxSrc.value = allImages.value[lightboxIndex.value]
  currentImage.value = lightboxSrc.value
}
function nextImage() {
  lightboxIndex.value = (lightboxIndex.value + 1) % allImages.value.length
  lightboxSrc.value = allImages.value[lightboxIndex.value]
  currentImage.value = lightboxSrc.value
}

function handleKeydown(e) {
  if (!lightboxSrc.value) return
  if (e.key === 'Escape') closeLightbox()
  if (e.key === 'ArrowLeft') prevImage()
  if (e.key === 'ArrowRight') nextImage()
}
onMounted(() => window.addEventListener('keydown', handleKeydown))
onUnmounted(() => window.removeEventListener('keydown', handleKeydown))

const hasParams = computed(() => {
  const p = product.value
  return p && ((p.substrates?.length) || (p.specs?.length) || (p.thicknesses?.length))
})

async function fetchProduct() {
  loading.value = true
  try {
    const { data } = await api.getProduct(route.params.id)
    product.value = data

    if (data.images && data.images.length > 0) {
      currentImage.value = data.images[0].image_path
    } else if (data.cover_image) {
      currentImage.value = data.cover_image
    }

    if (data.substrates?.length)  selectedSubstrate.value  = data.substrates[0]
    if (data.specs?.length)       selectedSpec.value       = data.specs[0]
    if (data.thicknesses?.length) selectedThickness.value  = data.thicknesses[0]
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

function goToCategory() {
  if (product.value?.category) {
    router.push(`/products?category_id=${product.value.category.id}`)
  }
}

onMounted(fetchProduct)
</script>

<style scoped>
.tag-option {
  @apply px-3 py-1.5 border border-gray-300 rounded-full text-gray-600 hover:border-gray-500 transition-colors cursor-pointer text-xs;
}
.tag-option.active {
  color: #fff;
  background-color: #595959;
  border-color: #595959;
}
/* param-tag: table-style param buttons */
.param-tag {
  @apply px-3 py-1 border border-gray-200 rounded text-xs text-gray-700 hover:border-gray-400 hover:bg-gray-50 transition-colors cursor-pointer;
}
.param-tag.active {
  color: #fff;
  background-color: #595959;
  border-color: #595959;
}
.btn-primary {
  @apply inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-lg transition-colors;
}
.btn-outline {
  @apply inline-flex items-center border border-gray-300 text-gray-700 hover:border-gray-400 font-medium px-4 py-2 rounded-lg transition-colors;
}

/* Collapse animation */
.collapse-enter-active, .collapse-leave-active {
  transition: all 0.25s ease;
  overflow: hidden;
}
.collapse-enter-from, .collapse-leave-to {
  opacity: 0;
  max-height: 0;
}
.collapse-enter-to, .collapse-leave-from {
  opacity: 1;
  max-height: 500px;
}

/* Lightbox animation */
.lightbox-enter-active, .lightbox-leave-active { transition: opacity 0.2s ease; }
.lightbox-enter-from, .lightbox-leave-to { opacity: 0; }
</style>
