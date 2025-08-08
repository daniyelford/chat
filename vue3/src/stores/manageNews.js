import { defineStore } from 'pinia'
import { ref } from 'vue'
import { sendApi } from '@/utils/api'
export const useManageNewsStore = defineStore('manageNews', () => {
  const newsList = ref([])
  const loading = ref(true)
  let pollingInterval = null
  const simplifyNews = (newsArray) =>
    newsArray.map(item => ({
      id: item.id,
      description: item.description,
      status: item.status,
      show_status : item.show_status,
      report: !!item.report,
      mediaCount: Array.isArray(item.media) ? item.media.length : 0,
      categoryCount: Array.isArray(item.category) ? item.category.length : 0,
      address: (item.city ?? '') + (item.address ?? '')
    }))

  const loadNews = async () => {
    try {
      const res = await sendApi({ control: 'news', action: 'user_news' })
      if (res.status === 'success') {
        const newList = res.data || []
        const isSame =
          JSON.stringify(simplifyNews(newsList.value)) ===
          JSON.stringify(simplifyNews(newList))

        if (!isSame) {
          newsList.value = newList
        }

        loading.value = false
      } else {
        alert('خطا در بارگذاری اخبار: ' + res.message)
      }
    } catch (error) {
      alert('خطا در ارتباط با سرور: ' + error.message)
    }
  }
  const restoreNews = async (id) => {
    try {
      const res = await sendApi({ control: 'news', action: 'restore_news', data: { id: id } })
      if (res.status === 'success') {
        return true
      } else {
        alert('خطا در پخش مجدد خبر: ' + res.message) 
        return false
      }
    } catch (error) {
      alert('خطا در ارتباط با سرور: ' + error.message)
      return false
    }
  }
  const deleteNews = async (id) => {
    try {
      const res = await sendApi({ control: 'news', action: 'delete_news', data: { id: id } })
      if (res.status === 'success') {
        return true
      } else {
        alert('خطا در حذف خبر: ' + res.message)
        return false
      }
    } catch (error) {
      alert('خطا در ارتباط با سرور: ' + error.message)
      return false
    }
  }
  const toggleShowStatus = (id) => {
    const index = newsList.value.findIndex(item => item.id === id)
    if (index !== -1) {
      const oldItem = newsList.value[index]
      const updatedItem = {
        ...oldItem,
        show_status: oldItem.show_status === 'do' ? 'dont' : 'do'
      }
      newsList.value.splice(index, 1, updatedItem)
    }
  }
  const startPolling = () => {
    if (pollingInterval) return
    loadNews()
    pollingInterval = setInterval(() => {
      loadNews()
    }, 10000)
  }
  const stopPolling = () => {
    if (pollingInterval) {
      clearInterval(pollingInterval)
      pollingInterval = null
    }
  }
  return {
    newsList,
    loading,
    loadNews,
    deleteNews,
    restoreNews,
    startPolling,
    stopPolling,
    toggleShowStatus,
  }
})

 