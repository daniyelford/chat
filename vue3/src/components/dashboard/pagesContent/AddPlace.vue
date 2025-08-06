<template>
  <div v-if="isAuthorized">
    <h1>افزودن مکان جدید</h1>
    <form @submit.prevent="submitForm">
      <input v-model="place.title" placeholder="عنوان مکان" />
      <textarea v-model="place.description" placeholder="توضیحات" />
      <input v-model="place.lat" placeholder="عرض جغرافیایی (lat)" />
      <input v-model="place.lon" placeholder="طول جغرافیایی (lon)" />
      <button type="submit">ثبت مکان</button>
    </form>
  </div>
  <div v-else>
    <h2>⛔ دسترسی غیرمجاز</h2>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '@/stores/auth' // فرضی
import { sendApi } from '@/utils/api' // فرضی

const auth = useAuthStore()

const isAuthorized = computed(() => auth.user?.id === 1)

const place = ref({
  title: '',
  description: '',
  lat: '',
  lon: '',
})

async function submitForm() {
  const res = await sendApi({
    control: 'places',
    action: 'add',
    data: place.value
  })
  if (res.status === 'success') {
    alert('مکان با موفقیت اضافه شد')
  } else {
    alert('خطا در ثبت مکان')
  }
}
</script>
