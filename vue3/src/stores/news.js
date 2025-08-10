import { defineStore } from 'pinia'
import { sendApi } from '@/utils/api'
import { ref } from 'vue'
import moment from 'jalali-moment'
export const useNewsStore = defineStore('news', ()=> {
  const cards = ref([])
  const more = ref(false)
  const lastUpdate = ref(null)
  const isLoaded = ref(false)
  const hasRule = ref(false)
  const highRule = ref(false)
  const events = ref([])
  function normalizeNewsItem(item) {
    return {
      id: item.id,
      description: item.description,
      created_at: item.created_at,
      status: item.status,
      show_status: item.show_status,
      privacy: item.privacy,
      self: item.self,
      location: {
        address_id: item.address_id || null,
        city: item.city || null,
        lat: item.lat || item.address_lat || null,
        lon: item.lon || item.address_lon || null,
        address: item.address || null,
      },
      category: Array.isArray(item.categories)
        ? item.categories.map(c => ({
            id: c.id,
            title: c.title,
          }))
        : [],
      user: {
        id: item.user_account_id || null,
        name: item.name || '',
        family: item.family || '',
        phone: item.phone || '',
        image: item.user_image_url || null,
      },
      medias: Array.isArray(item.media)
        ? item.media.map(m => ({
            id: m.id,
            type: m.type,
            url: m.url,
          }))
        : [],
      reports: Array.isArray(item.report_list)
        ? item.report_list.map(report => ({
            id: report.id,
            news_id:item.id,
            description: report.report_info?.description ?? '',
            run_time: report.report_info?.run_time ?? null,
            created_at: report.report_info?.created_at ?? '',
            updated_at: report.report_info?.updated_at ?? '',
            status: report.report_info?.status ?? '',
            location: {
              address_id: report.location?.address_id ?? null,
              city: report.location?.city ?? null,
              address: report.location?.address ?? null,
              lat: report.location?.lat ?? null,
              lon: report.location?.lon ?? null,
            },
            reporter: {
              id: report.reporter?.user_account_id ?? null,
              self: report.reporter?.self ?? false,
              name: report.reporter?.name ?? '',
              family: report.reporter?.family ?? '',
              phone: report.reporter?.phone ?? '',
              image: report.reporter?.user_image_url ?? null,
            },
            media: Array.isArray(report.report_media)
              ? report.report_media.map(m => ({
                  id: m.id,
                  type: m.type,
                  url: m.url,
                }))
              : [],
          }))
        : [],
    }
  }
  const fetchNews = async ({ limit = 10, offset = 0, append = false } = {}) => {
    const res = await sendApi({
      action: 'get_news',
      control: 'news',
      data: { limit, offset }
    })
    if (res.status === 'success') {
      const newsObj = res.data || {}
      const items = Array.isArray(newsObj) ? newsObj : Object.values(newsObj)
      const newCards = items.map(normalizeNewsItem)
      const existingIds = new Set(cards.value.map(card => card.id))
      const filteredCards = newCards.filter(card => card.user.id && !existingIds.has(card.id))
      cards.value = append ? [ ...cards.value,...filteredCards] : [...filteredCards]
      more.value = res.has_more
      lastUpdate.value = new Date().toISOString()
      isLoaded.value = true
      hasRule.value=res.rule === true
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
      hasRule.value = res.rule === true
      return normalizeNewsItem(res.data)
    }
    return null
  }
  const fetchLatestNewsRaw =async (limit = 10, offset = 0) => {
    const res = await sendApi({
      action: 'get_news',
      control: 'news',
      data: { limit, offset }
    })
    return res.status === 'success' ? (res.data || []).map(normalizeNewsItem) : []
  }
  const scheduleNewsRunTime = async (news_id, report_id, run_time) => {
    const res = await sendApi({
      action: 'add_news_to_list',
      control: 'news',
      data: {
        news_id,
        report_id: report_id ?? null,
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
        category_id:
        Array.isArray(newsData.category_id)
        ? newsData.category_id.map(c => c.id)
        : (newsData.category_id ? [newsData.category_id.id] : [])
      }
    })
    if (res.status === 'success') {
      const newCard = await fetchNewsById(res.id)
      if (newCard) {
        const index = cards.value.findIndex(card =>  Number(card.id) ===  Number(newCard.id))
        if (index !== -1) {
          cards.value[index] = newCard
        } else {
          cards.value.unshift(newCard)
        }
      }
    }
    return res
  }
  const fetchAddNewsData = async () => {
    const res = await sendApi({
      control: 'news',
      action: 'add_data'
    })
    if (res.status === 'success') {
      highRule.value = res.high_rule??false
      return {
        address: res.address,
        rule: res.rule,
        coordinate: {
          lat: res.coordinate?.lat,
          lon: res.coordinate?.lon,
        },
        highRule: res.high_rule,
        category: res.category ?? [],
        myCategory: res.my_category??[]
      }
    }
    return null
  }
  const getRandomColor = () => {
    const colors = ['#29B6F6', '#FF7043', '#66BB6A', '#AB47BC', '#FFA726', '#26C6DA', '#EC407A']
    return colors[Math.floor(Math.random() * colors.length)]
  }
  const loadEvents = async () => {
    const res = await sendApi({ control: 'news', action: 'get_news_for_month' })
    if (res.status === 'success') {
      const newsList = Array.isArray(res.data) ? res.data : []
      const allReports = newsList.flatMap(rawItem => {
        const news = normalizeNewsItem(rawItem)
        return news.reports.map(report => {
          const runTime = report.run_time
          const start = moment(runTime)
          const end = moment(runTime).add(2, 'hours')
          return {
            id: report.id,
            title: news.description?.slice(0, 20) || 'بدون عنوان',
            startDateTime: start,
            endDateTime: end,
            color: getRandomColor(),
            raw: {
              news,
              reports:[report]
            }
          }
        })
      })
      events.value = allReports
    }
  }
  const reset = () => {
    cards.value = []
    more.value = false
    isLoaded.value = false
    hasRule.value=false
  }
  const newsRestore = async (id) => {
      const res = await sendApi({ control: 'news', action: 'restore_news', data: { id: id } })
      if (res.status === 'success') {
        return true
      } else {
        alert(res.message)
        return false
      }
  }
  const newsDelete = async (id) => {
    const res = await sendApi({ control: 'news', action: 'delete_news', data: { id: id } })
    if (res.status === 'success') {
        return true
      } else {
        alert(res.message)
        return false
      }
  }
  return {
    cards,
    more,
    lastUpdate,
    isLoaded,
    hasRule,
    highRule,
    events,
    normalizeNewsItem,
    fetchNews,
    fetchNewsById,
    fetchLatestNewsRaw,
    scheduleNewsRunTime,
    addNews,
    reset,
    fetchAddNewsData,
    loadEvents,
    newsRestore,
    newsDelete
  }
})