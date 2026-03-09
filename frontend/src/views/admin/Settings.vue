<template>
  <div class="p-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-900">通用设置</h1>
    </div>

    <div v-if="loading" class="flex justify-center py-16">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <template v-else>

      <!-- ===== 底部信息设置 ===== -->
      <form @submit.prevent="save" class="max-w-2xl">
        <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">

          <div class="px-5 py-4">
            <h2 class="text-base font-bold text-gray-900">底部信息设置</h2>
            <p class="text-xs text-gray-400 mt-0.5">用于网站底部 Footer 显示</p>
          </div>

          <!-- 底部品牌名称 -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">底部品牌名称</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.footer_brand" type="text" class="input-field" placeholder="如：板木诚" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.footer_brand_en" type="text" class="input-field" placeholder="e.g. Banmucheng" />
              </div>
            </div>
          </div>

          <!-- 版权文案 -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">版权文案</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.footer_copyright" type="text" class="input-field" placeholder="如：© 2024 板木诚 版权所有" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.footer_copyright_en" type="text" class="input-field" placeholder="e.g. © 2024 Banmucheng. All rights reserved." />
              </div>
            </div>
          </div>

          <!-- 联系地址（Footer） -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">联系地址</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.footer_address" type="text" class="input-field" placeholder="详细地址" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.footer_address_en" type="text" class="input-field" placeholder="e.g. No.22 Mingsha South Road, Foshan..." />
              </div>
            </div>
          </div>

          <!-- 联系电话（Footer） -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">联系电话</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.footer_phone" type="text" class="input-field" placeholder="电话号码" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.footer_phone_en" type="text" class="input-field" placeholder="e.g. +86 135 3571 7734" />
              </div>
            </div>
          </div>

          <!-- 联系邮箱（Footer） -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">联系邮箱</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.footer_email" type="email" class="input-field" placeholder="邮箱地址" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.footer_email_en" type="email" class="input-field" placeholder="Same or different email for EN" />
              </div>
            </div>
          </div>

          <!-- 保存 -->
          <div class="p-5 flex items-center gap-4">
            <button type="submit" :disabled="saving" class="btn-primary px-8">
              <span v-if="saving" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2 inline-block"></span>
              保存底部设置
            </button>
            <p v-if="success" class="text-green-600 text-sm flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              保存成功
            </p>
            <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
          </div>
        </div>
      </form>

      <!-- ===== 联系我们设置 ===== -->
      <form @submit.prevent="saveContact" class="max-w-2xl mt-8">
        <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">

          <div class="px-5 py-4">
            <h2 class="text-base font-bold text-gray-900">联系我们设置</h2>
            <p class="text-xs text-gray-400 mt-0.5">用于「联系我们」页面显示</p>
          </div>

          <!-- 电话 -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">电话</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.contact_tel" type="text" class="input-field" placeholder="+86 13535717734" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.contact_tel_en" type="text" class="input-field" placeholder="+86 13535717734" />
              </div>
            </div>
          </div>

          <!-- WhatsApp -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">WhatsApp</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.contact_whatsapp" type="text" class="input-field" placeholder="+86 13535717734" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.contact_whatsapp_en" type="text" class="input-field" placeholder="+86 13535717734" />
              </div>
            </div>
          </div>

          <!-- 微信 -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">微信</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.contact_wechat" type="text" class="input-field" placeholder="微信号" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.contact_wechat_en" type="text" class="input-field" placeholder="WeChat ID" />
              </div>
            </div>
          </div>

          <!-- 邮箱 -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">邮箱</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.contact_email" type="email" class="input-field" placeholder="邮箱地址" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.contact_email_en" type="email" class="input-field" placeholder="email address" />
              </div>
            </div>
          </div>

          <!-- 地址 -->
          <div class="p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">地址</p>
            <div class="flex flex-col gap-3">
              <div>
                <label class="form-label">中文</label>
                <input v-model="form.contact_address" type="text" class="input-field" placeholder="详细地址（中文）" />
              </div>
              <div class="bg-blue-50/50 rounded-xl p-3">
                <label class="form-label">英文 <span class="text-blue-500 text-xs font-normal ml-1">English</span></label>
                <input v-model="form.contact_address_en" type="text" class="input-field" placeholder="Detailed address (English)" />
              </div>
            </div>
          </div>

          <!-- 保存 -->
          <div class="p-5 flex items-center gap-4">
            <button type="submit" :disabled="savingContact" class="btn-primary px-8">
              <span v-if="savingContact" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2 inline-block"></span>
              保存联系设置
            </button>
            <p v-if="successContact" class="text-green-600 text-sm flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              保存成功
            </p>
            <p v-if="errorContact" class="text-red-500 text-sm">{{ errorContact }}</p>
          </div>
        </div>
      </form>

    </template>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '@/api/index.js'

const loading = ref(true)

// Footer form
const saving  = ref(false)
const success = ref(false)
const error   = ref('')

// Contact form
const savingContact  = ref(false)
const successContact = ref(false)
const errorContact   = ref('')

const form = reactive({
  // footer
  footer_brand:        '',
  footer_brand_en:     '',
  footer_copyright:    '',
  footer_copyright_en: '',
  footer_address:      '',
  footer_address_en:   '',
  footer_phone:        '',
  footer_phone_en:     '',
  footer_email:        '',
  footer_email_en:     '',
  // contact page
  contact_tel:         '',
  contact_tel_en:      '',
  contact_whatsapp:    '',
  contact_whatsapp_en: '',
  contact_wechat:      '',
  contact_wechat_en:   '',
  contact_email:       '',
  contact_email_en:    '',
  contact_address:     '',
  contact_address_en:  '',
})

async function loadSettings() {
  loading.value = true
  try {
    const { data } = await api.adminGetSettings()
    data.forEach(item => {
      if (item.key in form) form[item.key] = item.value || ''
    })
  } catch (e) {
    error.value = '加载失败'
  } finally {
    loading.value = false
  }
}

/** 保存底部设置 */
async function save() {
  saving.value = true
  success.value = false
  error.value   = ''
  const footerKeys = ['footer_brand','footer_brand_en','footer_copyright','footer_copyright_en',
    'footer_address','footer_address_en','footer_phone','footer_phone_en','footer_email','footer_email_en']
  const payload = {}
  footerKeys.forEach(k => { payload[k] = form[k] })
  try {
    await api.adminSaveSettings(payload)
    success.value = true
    setTimeout(() => { success.value = false }, 3000)
  } catch (e) {
    error.value = e.response?.data?.message || '保存失败'
  } finally {
    saving.value = false
  }
}

/** 保存联系我们设置 */
async function saveContact() {
  savingContact.value = true
  successContact.value = false
  errorContact.value   = ''
  const contactKeys = ['contact_tel','contact_tel_en','contact_whatsapp','contact_whatsapp_en',
    'contact_wechat','contact_wechat_en','contact_email','contact_email_en',
    'contact_address','contact_address_en']
  const payload = {}
  contactKeys.forEach(k => { payload[k] = form[k] })
  try {
    await api.adminSaveSettings(payload)
    successContact.value = true
    setTimeout(() => { successContact.value = false }, 3000)
  } catch (e) {
    errorContact.value = e.response?.data?.message || '保存失败'
  } finally {
    savingContact.value = false
  }
}

onMounted(loadSettings)
</script>

<style scoped>
.btn-primary {
  @apply inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-lg transition-colors;
}
.form-label {
  @apply text-sm font-medium text-gray-600 mb-1.5 block;
}
.input-field {
  @apply w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-primary-500 transition bg-white;
}
</style>
