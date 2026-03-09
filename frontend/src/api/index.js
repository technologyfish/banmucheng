import axios from 'axios'

const api = axios.create({
  baseURL: '/api',
  timeout: 15000,
  headers: { 'Content-Type': 'application/json' },
})

// Request interceptor: attach admin token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('admin_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Response interceptor
api.interceptors.response.use(
  (res) => res,
  (err) => {
    if (err.response?.status === 401) {
      localStorage.removeItem('admin_token')
      if (window.location.pathname.startsWith('/admin') && window.location.pathname !== '/admin/login') {
        window.location.href = '/admin/login'
      }
    }
    return Promise.reject(err)
  }
)

export default {
  // Public
  getCategories: ()                  => api.get('/categories'),
  getProducts: (params)              => api.get('/products', { params }),
  getProduct: (id)                   => api.get(`/products/${id}`),
  getPublicArticles: (params)        => api.get('/articles', { params }),

  // Admin auth
  adminLogin: (data)                 => api.post('/admin/login', data),
  adminMe: ()                        => api.get('/admin/me'),

  // Admin categories
  adminGetCategories: ()                        => api.get('/admin/categories'),
  adminCreateCategory: (data)                   => api.post('/admin/categories', data),
  adminUpdateCategory: (id, data)               => api.put(`/admin/categories/${id}`, data),
  adminDeleteCategory: (id)                     => api.delete(`/admin/categories/${id}`),
  adminReorderCategory: (id, direction)         => api.post(`/admin/categories/${id}/reorder`, { direction }),

  // Admin products
  adminGetProducts: (params)         => api.get('/admin/products', { params }),
  adminCreateProduct: (data)         => api.post('/admin/products', data),
  adminUpdateProduct: (id, data)     => api.put(`/admin/products/${id}`, data),
  adminDeleteProduct: (id)           => api.delete(`/admin/products/${id}`),
  adminDeleteImage: (id)             => api.delete(`/admin/product-images/${id}`),
  adminSortImages: (data)            => api.post('/admin/product-images/sort', data),

  // Admin article categories
  adminGetArticleCategories: (params)          => api.get('/admin/article-categories', { params }),
  adminGetArticleCategory: (id)                => api.get(`/admin/article-categories/${id}`),
  adminCreateArticleCategory: (data)           => api.post('/admin/article-categories', data),
  adminUpdateArticleCategory: (id, data)       => api.put(`/admin/article-categories/${id}`, data),
  adminDeleteArticleCategory: (id)             => api.delete(`/admin/article-categories/${id}`),
  adminReorderArticleCategory: (id, direction) => api.post(`/admin/article-categories/${id}/reorder`, { direction }),

  // Admin articles
  adminGetArticles: (params)              => api.get('/admin/articles', { params }),
  adminGetArticle: (id)                   => api.get(`/admin/articles/${id}`),
  adminCreateArticle: (data)              => api.post('/admin/articles', data),
  adminUpdateArticle: (id, data)          => api.put(`/admin/articles/${id}`, data),
  adminDeleteArticle: (id)                => api.delete(`/admin/articles/${id}`),
  adminReorderArticle: (id, direction)    => api.post(`/admin/articles/${id}/reorder`, { direction }),

  // Admin options (substrate / spec / thickness)
  adminGetOptions: ()                => api.get('/admin/options'),
  adminCreateOption: (data)          => api.post('/admin/options', data),
  adminUpdateOption: (id, data)      => api.put(`/admin/options/${id}`, data),
  adminDeleteOption: (id)            => api.delete(`/admin/options/${id}`),

  // Upload
  uploadImage: (formData)            => api.post('/admin/upload', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  }),

  // Settings (public)
  getSettings: ()                    => api.get('/settings'),

  // Admin settings
  adminGetSettings: ()               => api.get('/admin/settings'),
  adminSaveSettings: (data)          => api.post('/admin/settings', data),
}
