<template>
  <aside class="w-56 flex-shrink-0 flex flex-col min-h-screen select-none" style="background:#2c3e50">

    <!-- 品牌 Logo -->
    <div class="px-5 py-4 border-b border-white/10 flex items-center gap-3">
      <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
        </svg>
      </div>
      <div class="min-w-0">
        <p class="text-white text-sm font-semibold leading-tight truncate">后台管理系统</p>
        <p class="text-white/40 text-xs leading-tight mt-0.5">板木诚</p>
      </div>
    </div>

    <nav class="flex-1 py-2 flex flex-col">

      <!-- 产品分类 -->
      <RouterLink to="/admin/categories" :class="navCls('/admin/categories')">
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        产品分类
      </RouterLink>

      <!-- 产品管理 -->
      <RouterLink to="/admin/products" :class="navCls('/admin/products')">
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        产品管理
      </RouterLink>

      <!-- 规格管理 -->
      <RouterLink to="/admin/options" :class="navCls('/admin/options')">
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
        规格管理
      </RouterLink>

      <!-- 文章管理 -->
      <RouterLink to="/admin/article-categories" :class="navCls('/admin/article-categories')">
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        文章管理
      </RouterLink>

      <!-- 通用设置 -->
      <RouterLink to="/admin/settings" :class="navCls('/admin/settings')">
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        通用设置
      </RouterLink>

    </nav>
  </aside>
</template>

<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()

function isActive(to) {
  if (to === '/admin/products') return route.path === '/admin/products' || route.path.startsWith('/admin/products/')
  if (to === '/admin/article-categories') return route.path.startsWith('/admin/article-categor') || route.path.startsWith('/admin/articles')
  return route.path.startsWith(to)
}

function navCls(to) {
  const active = isActive(to)
  return [
    'flex items-center gap-3 px-5 py-2.5 text-sm font-medium transition-colors relative',
    active
      ? 'text-white bg-white/15 border-l-[3px] border-blue-400'
      : 'text-white/65 hover:text-white hover:bg-white/8 border-l-[3px] border-transparent',
  ]
}
</script>

<style scoped>
.icon { @apply w-4 h-4 flex-shrink-0; }
</style>
