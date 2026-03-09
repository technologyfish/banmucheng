import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

// Fix: @wangeditor/editor-for-vue@1.x does `import Vue from 'vue'` (default)
// which Vue 3 ESM bundler doesn't support. Rewrite to namespace import.
const wangEditorVueFix = {
  name: 'wang-editor-vue-fix',
  transform(code, id) {
    if (id.includes('@wangeditor/editor-for-vue')) {
      return code.replace(/import\s+(\w+)\s+from\s*["']vue["']/, 'import * as $1 from "vue"')
    }
  },
}

export default defineConfig({
  plugins: [wangEditorVueFix, vue()],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src'),
    },
  },
  optimizeDeps: {
    exclude: ['@wangeditor/editor-for-vue'],
  },
  server: {
    port: 3000,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
      '/uploads': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },
})
