<template>
  <div class="wang-editor-wrap border border-gray-200 rounded-lg overflow-hidden">
    <div ref="toolbarRef" class="wang-toolbar"></div>
    <div ref="editorRef" class="wang-body" :style="{ height: height + 'px', overflowY: 'auto' }"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { createEditor, createToolbar } from '@wangeditor/editor'
import '@wangeditor/editor/dist/css/style.css'

const props = defineProps({
  modelValue: { type: String, default: '' },
  height:     { type: Number,  default: 420 },
  placeholder:{ type: String,  default: '请输入内容...' },
})
const emit = defineEmits(['update:modelValue'])

const toolbarRef = ref(null)
const editorRef  = ref(null)
let editor = null
let isSelf = false   // prevent watch loop

onMounted(() => {
  editor = createEditor({
    selector:  editorRef.value,
    html:      props.modelValue || '<p></p>',
    config: {
      placeholder: props.placeholder,
      onChange(ed) {
        isSelf = true
        emit('update:modelValue', ed.getHtml())
        isSelf = false
      },
    },
    mode: 'default',
  })

  createToolbar({
    editor,
    selector: toolbarRef.value,
    config: {},
    mode: 'default',
  })
})

onBeforeUnmount(() => {
  editor?.destroy()
  editor = null
})

// Sync external value changes (e.g. when editing an existing article)
watch(() => props.modelValue, (val) => {
  if (!isSelf && editor && val !== editor.getHtml()) {
    editor.setHtml(val || '<p></p>')
  }
})
</script>

<style>
.wang-editor-wrap .wang-toolbar {
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
}
.wang-editor-wrap .wang-body {
  background: #fff;
}
.wang-editor-wrap .w-e-text-container [data-slate-editor] {
  padding: 14px 16px;
  font-size: 14px;
  line-height: 1.8;
  font-family: 'PingFang SC', 'Microsoft YaHei', sans-serif;
}
/* placeholder */
.wang-editor-wrap .w-e-text-placeholder {
  top: 14px;
  left: 16px;
  font-size: 14px;
  color: #9ca3af;
}
</style>
