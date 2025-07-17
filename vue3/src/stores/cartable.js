import { defineStore } from 'pinia'
import { ref } from 'vue'
import { sendApi } from '@/utils/api'
export const useCartableStore = defineStore('cartable', () => {
  const allItems = ref([])
  const rule = ref(true)
  const loading = ref(true)
  const simplifyNews = (items) => Array.isArray(items) ? items.map(item => ({
    id: item.id,
    status: item.status,
    updated_at: item.updated_at
  })): []
  const fetchCartables = async () => {
    try {
      const res = await sendApi({ control: 'news', action: 'get_cartables' })
      if (res.status === 'success') {
        const rawGroups = res.data || []
        allItems.value = rawGroups.map(group => {
          const news = group.news
          const reports = group.report_list || []
          return {
            id: news.id,
            status: news.status,
            updated_at: news.updated_at || '',
            news: {
              title: news.title || '',
              description: news.description || '',
              media: (news.news_media || []).map(m => ({
                id: m.id,
                url: m.url,
                type: m.type
              })),
              address: {
                address: news.address?.address || '',
                lat: news.address?.lat || null,
                long: news.address?.lon || null
              },
              news_user_name: news.user_name || '',
              news_user_family: news.user_family || '',
              news_user_phone: news.user_phone || '',
              news_user_image_url: news.user_image_url || null,
              category: news.category || []
            },
            reports: reports.map(r => ({
              id: r.id,
              description: r.description || '',
              media: (r.report_media || []).map(m => ({
                id: m.id,
                url: m.url,
                type: m.type
              })),
              user: {
                name: r.user_name || '',
                family: r.user_family || '',
                phone: r.user_phone || '',
                image: r.user_image_url || null
              }
            }))
          }
        })
        rule.value = !!res.rule
      } else {
        alert('خطا در دریافت اطلاعات: ' + res.message)
      }
    } catch (e) {
      alert('خطا در ارتباط با سرور: ' + e.message)
    }
    loading.value = false
  }
  const getCartableById = async (id) => {
    try {
      const res = await sendApi({
        control: 'news',
        action: 'get_cartable_by_id',
        data: { id }
      })
      if (res.status === 'success' && res.data) {
        const item = res.data
        return {
          id: item.id,
          has_rule: res.rule ?? false,
          user: {
            name: item.reporter_name || '',
            family: item.reporter_family || '',
            phone: item.reporter_phone || '',
            image: item.reporter_user_image_url || null
          },
          news: {
            description: item.news_description || '',
            media: (item.news_media || []).map(m => ({
              id: m.id,
              url: m.url,
              type: m.type
            })),
            address: {
              address: item.address || '',
              lat: item.address_lat || null,
              long: item.address_lon || null
            },
            news_user_name: item.news_user_name || '',
            news_user_family: item.news_user_family || '',
            news_user_phone: item.news_user_phone || '',
            news_user_image_url: item.news_user_image_url || null
          },
          report: {
            description: item.report_description || '',
            media: (item.report_media || []).map(m => ({
              id: m.id,
              url: m.url,
              type: m.type
            })),
            created_at: item.created_at || '',
            run_time: item.run_time || ''
          },
          category: (item.category || []).map(c => ({
            id: c.id,
            title: c.title
          })),
          status: item.status || '',
          run_time: item.run_time || '',
          created_at: item.created_at || '',
          updated_at: item.updated_at || ''
        }
      } else {
        alert('خطا در دریافت جزئیات: ' + res.message)
        return null
      }
    } catch (e) {
      alert('خطا در ارتباط با سرور: ' + e.message)
      return null
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
        await fetchCartables() // رفرش لیست
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
    updateReport,
    simplifyNews
  }
})
