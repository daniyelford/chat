import { defineStore } from 'pinia'
import { ref } from 'vue'
import { sendApi } from '@/utils/api'
export const usePlaceStore = defineStore('place', () => {
  const allPlaces = ref([])
  const allCategories = ref([])
  const categories = ref([])
  const userCoordinate = ref([])
  const userAccountId = ref(null)
  const categoryLoading = ref(false)
  const placeOffset = ref(0)
  const categoryOffset = ref(0)
  const hasMorePlaces = ref(true)
  const hasMoreCategories = ref(true)
  const fetchCategoriesPaginated = async (offset = 0, limit = 20) => {
    const res = await sendApi({
      control: 'place',
      action: 'get_places_category',
      data: { offset:offset, limit:limit }
    })
    if (res.status === 'success') {
      if (Array.isArray(res.data)) {
        if (offset === 0) allCategories.value = res.data
        else allCategories.value.push(...res.data)
        hasMoreCategories.value = res.data.has_more
        categoryOffset.value += res.data.length
      }
    }
  }
  const fetchPlacesPaginated = async ({ offset = 0, category_id = null, limit = 10 }) => {
    const res = await sendApi({
      control: 'place',
      action: 'get_places',
      data: { offset:offset, category_id:category_id, limit:limit }
    })
    if (res.status === 'success') {
      if (Array.isArray(res.data)) {
        if (offset === 0) allPlaces.value = res.data
        else allPlaces.value.push(...res.data)
        hasMorePlaces.value = res.data.has_more
        if (res.cord) userCoordinate.value = res.cord
        userAccountId.value= res.user_account_id
        placeOffset.value += res.data.length
      }
    }
  }
  const fetchPlacebyId = async (id) => {
    const res = await sendApi({
      control: 'place',
      action: 'get_places',
      data: { id:id }
    })
    if (res.status === 'success' && Array.isArray(res.data)) {
      return res.data
    }
    return []
  }
  const fetchCategories = async (query) => {
    categoryLoading.value = true
    try {
      const res = await sendApi({ control: 'place', action: 'get_places_category' , data: { search: query } })
      if (res.status === 'success' && Array.isArray(res.data)) {
        categories.value=res.data
      } else {
        console.error('خطا در دریافت داده‌ها:', res.message)
      }
    } catch (e) {
      console.error('خطا در ارتباط:', e)
    }finally {
      categoryLoading.value = false
    }
  }
  const submitPlace = async (placeData, edit = null) => {
    try {
      const res = await sendApi({
        control: 'place',
        action: 'add_place',
        data: placeData
      })
      if (res.status === 'success' && res.data?.id) {
        const id = res.data.id
        const newPlace = await fetchPlacebyId(id)
        if (!edit) {
          allPlaces.value.unshift(newPlace)
        } else {
          const index = allPlaces.value.findIndex(p => Number(p.id) === Number(edit))
          if (index !== -1) {
            allPlaces.value[index] = newPlace
          } else {
            allPlaces.value.unshift(newPlace)
          }
        }
      }
      return res
    } catch (error) {
      console.error('submitPlace error:', error)
      return { status: 'error', message: 'خطا در ارسال درخواست' }
    }
  }
  const resetPlaces = () => {
    allPlaces.value = []
    placeOffset.value = 0
    hasMorePlaces.value = true
  }

  return {
    allPlaces,
    categories,
    allCategories,
    userCoordinate,
    userAccountId,
    categoryLoading,
    placeOffset,
    categoryOffset,
    hasMorePlaces,
    hasMoreCategories,
    fetchCategoriesPaginated,
    fetchPlacesPaginated,
    fetchCategories,
    submitPlace,
    resetPlaces
  }
})