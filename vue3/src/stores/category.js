import { defineStore } from 'pinia'
import { ref } from 'vue'
import { sendApi } from '@/utils/api'
export const useCategoryStore = defineStore('category', () => {
  const allCategories = ref([])
  const categoryLoading = ref(false)
  const categoryListLoading = ref(false)
  const hasMoreCategories = ref(true)
  const fetchCategoriesPaginated = async (offset = 0, limit = 10) => {
    categoryListLoading.value = true
    try {
      const res = await sendApi({
        control: 'category',
        action: 'all_category',
        data: { offset, limit }
      })
      if (res.status === 'success' && Array.isArray(res.data)) {
        if (offset === 0) {
          allCategories.value = res.data
        } else {
          allCategories.value.push(...res.data)
        }
        hasMoreCategories.value = res.has_more
      }
    } catch (error) {
      console.error('fetchCategoriesPaginated error:', error)
    } finally {
      categoryListLoading.value = false
    }
  }
  const submitCategory = async (categoryData, edit = null) => {
    try {
      const res = await sendApi({
        control: 'category',
        action: 'add_category',
        data: {data:categoryData,edit:edit}
      })
      if (res.status === 'success' && res?.id) {
        if (!edit) {
          allCategories.value.unshift(res.data)
        } else {
          const index = allCategories.value.findIndex(c => Number(c.id) === Number(edit))
          if (index !== -1) {
            allCategories.value[index] = res.data
          } else {
            allCategories.value.unshift(res.data)
          }
        }
      }
      return res
    } catch (error) {
      console.error('submitCategory error:', error)
      return { status: 'error', message: 'خطا در ارسال درخواست' }
    }
  }
  const resetCategories = () => {
    allCategories.value = []
    hasMoreCategories.value = true
  }
  const deleteCategory = async (id) => {
    try {
      const res = await sendApi({
        control: 'category',
        action: 'delete_category',
        data: { id }
      })
      if (res.status === 'success') {
        allCategories.value = allCategories.value.filter(c => Number(c.id) !== Number(id))
        return true
      }
      return false
    } catch (error) {
      console.error('deleteCategory error:', error)
      return { status: 'error', message: 'خطا در حذف دسته‌بندی' }
    }
  }
  return {
    allCategories,
    categoryLoading,
    categoryListLoading,
    hasMoreCategories,
    fetchCategoriesPaginated,
    submitCategory,
    resetCategories,
    deleteCategory
  }
})
