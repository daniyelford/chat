import { defineStore } from 'pinia'
import { ref } from 'vue'
import { sendApi } from '@/utils/api'
export const usePlaceStore = defineStore('place', () => {
  const allPlaces = ref([])
  const userCoordinate = ref([])
  const fetchAllPlaces = async () => {
    try {
      const res = await sendApi({ control: 'place', action: 'get_places' })
      if (res.status === 'success' && Array.isArray(res.data)) {
        allPlaces.value = res.data
        userCoordinate.value = res.cord
    } else {
        console.error('خطا در دریافت داده‌ها:', res.message)
      }
    } catch (e) {
      console.error('خطا در ارتباط:', e)
    }
  }
  return {
    allPlaces,
    userCoordinate,
    fetchAllPlaces
  }
})