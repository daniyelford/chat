import { defineStore } from 'pinia'
import { sendApi } from '@/utils/api'
import { ref } from 'vue'
export const useNewsStore = defineStore('news', ()=> {
  const cards = ref([])
  const more = ref(false)
  const lastUpdate = ref(null)
  const isLoaded = ref(false)
  const fetchNews = async ({ limit = 10, offset = 0, append = false } = {}) => {
    const res = await sendApi({
      action: 'get_news',
      control: 'news',
      data: { limit, offset }
    })
    if (res.status === 'success') {
      const newsObj = res.data || {}
      const items = Object.values(newsObj)
      const newCards = items.map(item => ({
        id: item.id,
        description: item.description,
        created_at: item.created_at,
        status: item.status,
        privacy: item.privacy,
        self:item.self,
        location: {
          city: item.city || null,
          lat: item.lat || item.address_lat || null,
          lon: item.lon || item.address_lon || null,
          address: item.address || null,
        },
        category: Array.isArray(item.categories) ? item.categories.map(c => ({
          id: c.id,
          title: c.title
        })) : [],
        user: {
          id: item.user_account_id || null,
          name: item.name || '',
          family: item.family || '',
          phone: item.phone || '',
          image: item.user_image_url || null,
        },
        medias: Array.isArray(item.media) ? item.media.map(m => ({
          type: m.type,
          url: m.url
        })): [],
        reports: Array.isArray(item.report_list) ? item.report_list.map(report => ({
          id: report.id,
          description: report.report_info?.description ?? '',
          run_time: report.report_info?.run_time ?? null,
          created_at: report.report_info?.created_at ?? '',
          updated_at: report.report_info?.updated_at ?? '',
          status: report.report_info?.status ?? '',
          location: {
            city: report.location?.city ?? null,
            address: report.location?.address ?? null,
            lat: report.location?.lat ?? null,
            lon: report.location?.lon ?? null,
          },
          reporter: {
            id: report.reporter?.user_account_id ?? null,
            name: report.reporter?.name ?? '',
            family: report.reporter?.family ?? '',
            phone: report.reporter?.phone ?? '',
            image: report.reporter?.user_image_url ?? null,
          },
          media: Array.isArray(report.report_media) ? report.report_media.map(m => ({
            type: m.type,
            url: m.url
          })): []
        })) : []
      }))
      const filteredCards = newCards.filter(card => card.user.id)
      cards.value = append ? [...cards.value, ...filteredCards] : [...filteredCards]
      more.value = res.has_more
      lastUpdate.value = new Date().toISOString()
      isLoaded.value = true
      return true
    }
    return false
  }
  const fetchNewsById = async (id) => {
    const res = await sendApi({
      action: 'get_news_by_id',
      control: 'news',
      data: { id }
    })
    if (res.status === 'success') {
      const item = res.data
      return {
        id: item.id,
        description: item.description,
        created_at: item.created_at,
        status:item.status,
        privacy:item.privacy,
        self:item.self,
        location: {
          city: item.city,
          lat: item.lat,
          lon: item.lon,
          address: item.address,
        },
        category: Array.isArray(item.categories) ? item.categories.map(category => ({
          id: category.id,
          title: category.title
        })) : [],
        user: {
          id: item.user_account_id,
          name: item.name,
          family: item.family,
          phone: item.phone,
          image: item.user_image_url,
        },
        medias: Array.isArray(item.media) ? item.media.map(media => ({
          type: media.type,
          url: media.url
        })) : [],
        reports: Array.isArray(item.report_list) ? item.report_list.map(report => ({
          id: report.id,
          description: report.report_info?.description ?? '',
          run_time: report.report_info?.run_time ?? null,
          created_at: report.report_info?.created_at ?? '',
          updated_at: report.report_info?.updated_at ?? '',
          status: report.report_info?.status ?? '',
          location: {
            city: report.location?.city ?? null,
            address: report.location?.address ?? null,
            lat: report.location?.lat ?? null,
            lon: report.location?.lon ?? null,
          },
          reporter: {
            id: report.reporter?.user_account_id ?? null,
            name: report.reporter?.name ?? '',
            family: report.reporter?.family ?? '',
            phone: report.reporter?.phone ?? '',
            image: report.reporter?.user_image_url ?? null,
          },
          media: Array.isArray(report.report_media) ? report.report_media.map(m => ({
            type: m.type,
            url: m.url
          })): []
        })) : []
      }
    }
    return null
  }
  const fetchLatestNewsRaw =async (limit = 5, offset = 0) => {
    const res = await sendApi({
      action: 'get_news',
      control: 'news',
      data: { limit, offset }
    })
    return res.status === 'success' ? res.data : []
  }
  const scheduleNewsRunTime = async (news_id, run_time) => {
    const res = await sendApi({
      action: 'add_news_to_list',
      control: 'news',
      data: {
        news_id,
        run_time: run_time ?? null,
      }
    })
    return res
  }
  const addNews = async (newsData) => {
    const res = await sendApi({
      control: 'news',
      action: 'add_news',
      data: {
        ...newsData,
        category_id: newsData.category_id.map(c => c.id),
      }
    })
    if (res.status === 'success') {
      const newCard = await fetchNewsById(res.id)
      if (newCard) {
        cards.value.unshift(newCard)
      }
    }
    return res
  }
  const reset = () => {
    cards.value = []
    more.value = false
    isLoaded.value = false
  }
  return {
    cards,
    more,
    lastUpdate,
    isLoaded,
    fetchNews,
    fetchNewsById,
    fetchLatestNewsRaw,
    scheduleNewsRunTime,
    addNews,
    reset
  }
})