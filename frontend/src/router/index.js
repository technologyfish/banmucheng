import { createRouter, createWebHistory } from 'vue-router'
import { useAppStore } from '@/stores/app.js'

const routes = [
  {
    path: '/',
    component: () => import('@/layouts/PublicLayout.vue'),
    children: [
      { path: '',          name: 'home',          component: () => import('@/views/Home.vue') },
      { path: 'products',  name: 'products',      component: () => import('@/views/Products.vue') },
      { path: 'products/:id', name: 'product-detail', component: () => import('@/views/ProductDetail.vue') },
      { path: 'contact',   name: 'contact',       component: () => import('@/views/Contact.vue') },
    ],
  },
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    children: [
      { path: '',       redirect: '/admin/products' },
      { path: 'login',  name: 'admin-login',           component: () => import('@/views/admin/Login.vue') },
      { path: 'products',          name: 'admin-products',       component: () => import('@/views/admin/ProductList.vue'),  meta: { requiresAuth: true } },
      { path: 'products/create',   name: 'admin-product-create', component: () => import('@/views/admin/ProductForm.vue'),  meta: { requiresAuth: true } },
      { path: 'products/:id/edit', name: 'admin-product-edit',   component: () => import('@/views/admin/ProductForm.vue'),  meta: { requiresAuth: true } },
      { path: 'categories',        name: 'admin-categories',     component: () => import('@/views/admin/CategoryList.vue'), meta: { requiresAuth: true } },
      { path: 'options',             name: 'admin-options',            component: () => import('@/views/admin/OptionsManager.vue'),      meta: { requiresAuth: true } },
      { path: 'article-categories',         name: 'admin-article-categories',    component: () => import('@/views/admin/ArticleCategoryList.vue'), meta: { requiresAuth: true } },
      { path: 'article-categories/:catId', name: 'admin-article-category',      component: () => import('@/views/admin/ArticleCategoryList.vue'), meta: { requiresAuth: true } },
      { path: 'articles/create',           name: 'admin-article-create',        component: () => import('@/views/admin/ArticleForm.vue'),          meta: { requiresAuth: true } },
      { path: 'articles/:id/edit',         name: 'admin-article-edit',          component: () => import('@/views/admin/ArticleForm.vue'),          meta: { requiresAuth: true } },
      { path: 'settings',                  name: 'admin-settings',              component: () => import('@/views/admin/Settings.vue'),             meta: { requiresAuth: true } },
    ],
  },
  { path: '/:pathMatch(.*)*', redirect: '/' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition
    if (to.hash) return { el: to.hash, behavior: 'smooth' }
    return { top: 0 }
  },
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('admin_token')
    if (!token) {
      return next({ name: 'admin-login' })
    }
  }
  next()
})

export default router
