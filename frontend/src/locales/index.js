import { createI18n } from 'vue-i18n'
import zh from './zh.js'
import en from './en.js'

const i18n = createI18n({
  legacy: false,
  locale: localStorage.getItem('locale') || 'zh',
  fallbackLocale: 'zh',
  messages: { zh, en },
})

export default i18n
