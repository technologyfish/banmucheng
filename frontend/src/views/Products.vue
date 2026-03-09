<template>
  <div class="bg-gray-50 min-h-screen">
    <div class="container-main py-10">

      <div class="flex flex-col lg:flex-row gap-6">
        <!-- ===== LEFT SIDEBAR FILTER ===== -->
        <aside class="w-full lg:w-60 flex-shrink-0">
          <!-- Mobile filter toggle -->
          <button
            @click="filterOpen = !filterOpen"
            class="lg:hidden w-full flex items-center justify-between bg-white rounded-xl border border-gray-200 px-4 py-3 mb-3 text-sm font-medium"
          >
            <span>{{ $t('products.filter') }}</span>
            <svg :class="['w-4 h-4 transition-transform', filterOpen ? 'rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <div :class="['flex flex-col gap-4', filterOpen || 'hidden lg:flex']">
            <!-- Categories -->
            <div class="bg-white rounded-xl border border-gray-200 p-4">
              <h3 class="font-semibold text-sm text-gray-700 mb-3">{{ $t('products.allCategories') }}</h3>
              <ul class="flex flex-col gap-1">
                <li>
                  <button
                    @click="selectCategory(null)"
                    :class="['w-full text-left text-sm px-2 py-2 rounded-lg transition-colors',
                      !filters.category_id ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50']"
                  >
                    {{ $t('products.allCategories') }}
                  </button>
                </li>
                <li v-for="cat in categories" :key="cat.id">
                  <button
                    @click="selectCategory(cat.id)"
                    :class="['w-full text-left text-sm px-2 py-2 rounded-lg transition-colors',
                      filters.category_id === cat.id ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50']"
                  >
                    {{ locale === 'zh' ? cat.name_zh : cat.name_en }}
                  </button>
                  <!-- Sub-categories -->
                  <ul v-if="cat.children && cat.children.length" class="pl-3 mt-1 flex flex-col gap-1">
                    <li v-for="child in cat.children" :key="child.id">
                      <button
                        @click="selectCategory(child.id)"
                        :class="['w-full text-left text-xs px-2 py-1.5 rounded-lg transition-colors',
                          filters.category_id === child.id ? 'bg-primary-50 text-primary-600' : 'text-gray-500 hover:bg-gray-50']"
                      >
                        {{ locale === 'zh' ? child.name_zh : child.name_en }}
                      </button>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </aside>

        <!-- ===== RIGHT PRODUCT LIST ===== -->
        <div class="flex-1">
          <!-- Stats bar -->
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-500">{{ $t('products.total', { total: totalCount }) }}</p>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="flex justify-center py-16">
            <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
          </div>

          <!-- Empty -->
          <div v-else-if="products.length === 0" class="flex flex-col items-center py-16 text-gray-400">
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p>{{ $t('products.noProducts') }}</p>
          </div>

          <!-- Grid -->
          <div v-else class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            <RouterLink
              v-for="product in products"
              :key="product.id"
              :to="`/products/${product.id}`"
              class="bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-md transition-shadow group"
            >
              <!-- Product image -->
              <div class="aspect-square bg-gray-100 overflow-hidden">
                <img
                  v-if="product.cover_image"
                  :src="`/uploads/${product.cover_image}`"
                  :alt="locale === 'zh' ? product.name_zh : product.name_en"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
                <div v-else class="w-full h-full flex items-center justify-center">
                  <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
              </div>

              <!-- Info -->
              <div class="p-3">
                <p v-if="product.category" class="text-xs text-gray-400 mb-1">
                  {{ locale === 'zh' ? product.category.name_zh : product.category.name_en }}
                </p>
                <h3 class="text-sm font-medium text-gray-900 line-clamp-2">
                  {{ locale === 'zh' ? product.name_zh : product.name_en }}
                </h3>
              </div>
            </RouterLink>
          </div>

          <!-- Load More -->
          <div v-if="hasMore && !loading" class="flex justify-center mt-8">
            <button @click="loadMore" class="btn-outline text-sm">
              {{ $t('products.loadMore') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAppStore } from '@/stores/app.js'
import api from '@/api/index.js'

const appStore = useAppStore()
const locale = computed(() => appStore.locale)
const route = useRoute()

const products = ref([])
const categories = ref([])
const loading = ref(false)
const totalCount = ref(0)
const currentPage = ref(1)
const lastPage = ref(1)
const filterOpen = ref(false)
const hasMore = computed(() => currentPage.value < lastPage.value)

const filters = reactive({
  search: '',
  category_id: null,
})

let debounceTimer = null
function debouncedFetch() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => fetchProducts(true), 400)
}

async function fetchProducts(reset = false) {
  if (reset) {
    currentPage.value = 1
    products.value = []
  }
  loading.value = true
  try {
    const params = {
      page: currentPage.value,
      per_page: 12,
    }
    if (filters.search)      params.search = filters.search
    if (filters.category_id) params.category_id = filters.category_id

    const { data } = await api.getProducts(params)
    if (reset) {
      products.value = data.data
    } else {
      products.value.push(...data.data)
    }
    totalCount.value = data.total
    lastPage.value = data.last_page
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

async function fetchCategories() {
  const { data } = await api.getCategories()
  categories.value = data
}

function selectCategory(id) {
  filters.category_id = id
  fetchProducts(true)
}


function resetFilters() {
  filters.search = ''
  filters.category_id = null
  fetchProducts(true)
}

async function loadMore() {
  currentPage.value++
  await fetchProducts(false)
}

onMounted(() => {
  // Support category_id from query param
  if (route.query.category_id) {
    filters.category_id = parseInt(route.query.category_id)
  }
  fetchCategories()
  fetchProducts(true)
})
</script>
