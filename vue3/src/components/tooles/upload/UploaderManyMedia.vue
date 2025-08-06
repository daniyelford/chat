<template>
  <div :class="props.HasStylePlace?'place-drop-area':'drop-area'" @click="fileInput.click()" @dragover.prevent @dragenter.prevent @drop.prevent="handleDrop">
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256"><g fill="#031c66" fill-rule="evenodd" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(0.05905,0.05905)"><path d="M161,824h192v192v1209v1209h3735v-2418h-2490v-192h2490h192v0v2610v192h-192h-3735h-192v0v-2610zM1333,3280l1277,-1125l503,359l646,-479l192,186v1060h-2618zM611,643v1093v181v199c0,0 26,312 346,312c321,0 381,-251 381,-359v-292v-934c0,-34 28,-61 61,-61v0c34,0 61,28 61,61v934v296c0,0 0,4 0,11c0,267 -159,487 -467,487c-482,0 -504,-460 -504,-482v-173h-1v-1235v-101c0,-5 1,-9 2,-14v0c0,0 44,-316 312,-328c227,-10 365,192 365,398v474v772c0,66 -4,135 -4,206c0,0 -17,156 -181,156c-229,0 -233,-161 -224,-332v0v-988c0,-34 28,-61 61,-61v0c34,0 61,28 61,61v679v309c0,60 -9,197 79,197c74,0 85,-78 85,-193v-160v-721v-454c0,0 -9,-213 -201,-213c-192,0 -232,145 -232,276z"></path></g></g></svg>
    <input type="file" multiple ref="fileInput" class="hidden-input" @change="handleFiles" />
  </div>
  <div v-if="uploading" class="uploading-box">
    <p>در حال آماده‌سازی فایل‌ها برای ارسال...</p>
    <progress :value="progress" min="0" max="100" class="progress-bar"></progress>
  </div>
  <div v-if="mediaList.length > 0">
    <div class="preview-list">
      <div v-for="(item, i) in mediaList" :key="item.id || i" class="preview-item">
        <button @click="deleteMedia(item, i)" class="delete-button">×</button>
        <img v-if="item.type === 'image'" :src="item.url" class="preview-image" />
        <video v-else-if="item.type === 'video'" :src="item.url" controls class="preview-video" />
      </div>
    </div>
  </div>
</template>
<script setup>
  import { ref, defineProps, defineEmits, watch, defineExpose } from 'vue'
  import { sendApi } from '@/utils/api'
  const fileInput = ref(null)
  const selectedFilesBase64 = ref([])
  const progress = ref(0)
  const uploading = ref(false)

  const props = defineProps({
    url: String,
    toAction: String,
    modelValue: {
      type: Array,
      default: () => []
    },
    initialMedias: {
      type: Array,
      default: () => []
    },
    HasStylePlace:{
      type: Boolean,
      default: false
    }
  })
  const emit = defineEmits(['update:modelValue', 'done'])
  const mediaList = ref([...props.initialMedias])
  const handleDrop = (e) => {
    const files = e.dataTransfer.files
    handleFiles({ target: { files } })
  }
  const handleFiles = async (e) => {
    const files = Array.from(e.target.files)
    if (!files.length) return
    selectedFilesBase64.value = []
    uploading.value = true
    progress.value = 0
    let count = 0
    for (const file of files) {
      await new Promise((resolve) => {
        const reader = new FileReader()
        reader.onload = () => {
          const base64 = reader.result
          selectedFilesBase64.value.push(base64)
          count++
          progress.value = Math.round((count / files.length) * 100)
          resolve()
        }
        reader.readAsDataURL(file)
      })
    }
    await uploadFiles()
    uploading.value = false
    fileInput.value.value = null
  }
  const uploadFiles = async () => {
    try {
      const response = await sendApi({
        control: 'upload',
        action: 'upload_many_media',
        data: {
          url: props.url,
          data: selectedFilesBase64.value,
          toAction: props.toAction,
        },
      })
      if (response.status === 'success') {
        const uploaded = response.data
        const existingIds = mediaList.value.map(m => m.id)
        const newItems = uploaded.filter(m => !existingIds.includes(m.id))
        mediaList.value.push(...newItems)
        selectedFilesBase64.value = []
        // فقط شناسه ها رو برای v-model ارسال کن
        emit('update:modelValue', mediaList.value.map(m => m.id))
        emit('done', mediaList.value)
      } else {
        alert('آپلود با خطا مواجه شد: ' + response.message)
      }
    } catch (err) {
      alert('خطا در ارسال: ' + err.message)
    }
  }
  const deleteMedia = async (media, index) => {
    try {
      const res = await sendApi({
        control: 'upload',
        action: 'delete_media_by_id',
        data: { id: media.id },
      })
      if (res.status === 'success') {
        mediaList.value.splice(index, 1)
        emit('update:modelValue', mediaList.value.map(m => m.id))
        emit('done', mediaList.value)
      } else {
        alert('حذف فایل با خطا مواجه شد: ' + res.message)
      }
    } catch (err) {
      alert('خطا در حذف: ' + err.message)
    }
  }
  const reset = () => {
    selectedFilesBase64.value = []
    progress.value = 0
    uploading.value = false
    if (fileInput.value) {
      fileInput.value.value = null
    }
    mediaList.value = []
    emit('update:modelValue', [])
    emit('done', [])
  }
  defineExpose({ reset })
  watch(() => props.initialMedias, (newVal) => {
    if (!Array.isArray(newVal)) return
    mediaList.value = [...newVal]
  }, { immediate: true })
  watch(() => props.modelValue, (newVal) => {
    if (!Array.isArray(newVal)) return
    const existingIds = mediaList.value.map(m => m.id)
    const missing = newVal.filter(id => !existingIds.includes(id))
    if (missing.length > 0) {
      const extras = props.initialMedias.filter(m => missing.includes(m.id))
      mediaList.value.push(...extras)
    }
  }, { immediate: true })
</script>
<style scoped>
  .place-drop-area{
    border: unset;
    cursor: pointer;
    text-align: center;
    width: 100%;
    height: 100px;
    border-radius: 10px;
    background-color: aliceblue;
  }
  svg{
    width: 100%;
    height: 100%;
  }
  .drop-area {
    box-sizing: border-box;
    position: fixed;
    text-align: center;
    cursor: pointer;
    bottom: 0;
    left: 0;
    width: 45px;
    padding: 3px;
    height: 45px;
    border-top-left-radius: 15px;
    border: unset;
    background: #e9e9a8;
  }
  .hidden-input {
    display: none;
  }
  .select-button {
    padding: 0.4rem 0.8rem;
    cursor: pointer;
    margin-top: 0.5rem;
  }
  .uploading-box {
    margin-top: 1rem;
  }
  .progress-bar {
    width: 100%;
  }
  .preview-list {
    flex-wrap: wrap;
    justify-content: flex-end;
    display: flex;
    overflow-y: auto;
    background: lightgrey;
    align-items: stretch;
    align-content: flex-start;
    z-index: 9999;
  }
  .preview-item {
    position: relative;
    flex-direction: column;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: .5rem;
    padding: 0.5rem;
    width: 20%;
    height: 60px;
    background-color: #f9fafb;
    justify-content: center;
    display: flex;
  }
  .preview-image {
    max-height: 100%;
    object-fit: contain;
    border: 1px solid #ccc;
    max-width: 100%;
  }
  .preview-video {
    max-height: 100%;
    max-width: 100%;
  }
  .file-label {
    color: #4b5563;
    font-size: 0.875rem;
  }
  .file-name {
    color: #9ca3af;
    max-width: 100px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.75rem;
  }
  .delete-button {
    top: 4px;
    z-index: 9;
    right: 4px;
    background-color: #ef4444;
    color: white;
    border: none;
    border-radius: 9999px;
    width: 20px;
    height: 20px;
    font-size: 12px;
    line-height: 20px;
    text-align: center;
    cursor: pointer;
    position: absolute;
  }
</style>