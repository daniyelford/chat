import { defineStore } from 'pinia'
import { ref } from 'vue'
import { sendApi } from '@/utils/api'
export const useCartableStore = defineStore('cartable', () => {
  const allItems = ref([])
  const rule = ref(true)
  const loading = ref(true)
  const normalizeCartableEntry = ({ report, news }) => {
    const reportInfo = report?.report_info || {}
    return {
      id: news?.id || null,
      description: news?.description || '',
      created_at: news?.created_at || '',
      updated_at: news?.updated_at || '',
      status: news?.status || '',
      privacy: news?.privacy || '',
      self: news?.self || false,
      location: {
        city: news?.city || null,
        lat: news?.lat || null,
        lon: news?.lon || null,
        address: news?.address || null,
      },
      category: Array.isArray(news?.categories)
        ? news.categories.map(c => ({
            id: c.id,
            title: c.title,
          }))
        : [],
      user: {
        id: news?.user_account_id || null,
        name: news?.name || '',
        family: news?.family || '',
        phone: news?.phone || '',
        image: news?.user_image_url || null,
      },
      medias: Array.isArray(news?.media)
        ? news.media.map(m => ({
            type: m.type,
            url: m.url,
          }))
        : [],
      reports: [
        {
          id: report?.id || null,
          news_id: reportInfo?.news_id || news?.id || null,
          description: reportInfo?.description || '',
          run_time: reportInfo?.run_time || null,
          created_at: reportInfo?.created_at || '',
          updated_at: reportInfo?.updated_at || '',
          status: reportInfo?.status || '',
          location: {
            city: report?.location?.city || null,
            address: report?.location?.address || null,
            lat: report?.location?.lat || null,
            lon: report?.location?.lon || null,
          },
          reporter: {
            id: report?.reporter?.user_account_id || null,
            self: report?.reporter?.self || false,
            name: report?.reporter?.name || '',
            family: report?.reporter?.family || '',
            phone: report?.reporter?.phone || '',
            image: report?.reporter?.user_image_url || null,
          },
          media: Array.isArray(report?.report_media) ? 
            report.report_media.map(m => ({
              id: m.id,
              type: m.type,
              url: m.url,
            })) : [],
        },
      ],
    }
  }
  const fetchCartables = async ({ limit = 10, offset = 0 } = {}) => {
    loading.value = true
    try {
      const res = await sendApi({ control: 'news' , action: 'get_cartables' , data: { limit, offset } })
      if (res.status === 'success'&& Array.isArray(res.data)) {
        allItems.value = res.data.map(item => normalizeCartableEntry(item))
        rule.value = !!res.rule
        return
      } else {
        alert('خطا در دریافت اطلاعات: ' + res.message)
      }
    } catch (e) {
      alert('خطا در ارتباط با سرور: ' + e.message)
    } finally {
      loading.value = false
    }
  }
  const getCartableById = async (id) => {
    try {
      const res = await sendApi({
        control: 'news',
        action: 'get_cartable_by_id',
        data: { id : id }
      })
      if (res.status === 'success' && res.data) {
        rule.value = !!res.rule
        return normalizeCartableEntry(res.data)
      } else {
        alert('خطا در دریافت جزئیات: ' + res.message)
        return null
      }
    } catch (e) {
      alert('خطا در ارتباط با سرور: ' + e.message)
      return null
    } finally {
      loading.value = false
    }
  }
  const updateReport = async (id, description, mediaIds = []) => {
    try {
      const res = await sendApi({
        control: 'news',
        action: 'edit_report',
        data: {
          id,
          description,
          media_id: mediaIds
        }
      })

      if (res.status === 'success') {
        await fetchCartables()
        return true
      } else {
        alert('خطا: ' + res.message)
        return false
      }
    } catch (e) {
      alert('خطا در ارسال گزارش: ' + e.message)
      return false
    }
  }
  return {
    allItems,
    loading,
    rule,
    fetchCartables,
    getCartableById,
    updateReport
  }
})
