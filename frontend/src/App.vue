<template>
  <RouterView />
</template>

<script setup>
import { watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { useAppStore } from '@/stores/app.js'

const { locale } = useI18n()
const appStore = useAppStore()

watch(() => appStore.locale, (val) => {
  locale.value = val
}, { immediate: true })
</script>
