import { defineStore } from 'pinia'
import { sendApi } from '@/utils/api'
export const useNewsStore = defineStore('news', {
  state: () => ({
    cards: [],
    total:0,
    lastUpdate: null,
    isLoaded: false,
    hasRule: false
  }),
  actions: {
    async fetchNews({ limit = 10, offset = 0, append = false } = {}) {
      const res = await sendApi({
        action: 'get_news',
        control: 'news',
        data: { limit, offset }
      })
      
      if (res.status === 'success') {
        const newCards = res.data.map(item => ({
          id: item.id,
          description: item.description,
          created_at: item.created_at,
          location: {
            city: item.city || null,
            lat: item.address_lat || null,
            lon: item.address_lon || null,
            address: item.address || null,
          },
          category: Array.isArray(item.category)
            ? item.category.map(c => ({
                id: c.id,
                title: c.title
              }))
            : [],
          user: {
            id: item.user_account_id || null,
            name: item.user_name || '',
            family: item.user_family || '',
            phone: item.user_phone || '',
            image: item.user_image_url || null,
          },
          medias: Array.isArray(item.media)
            ? item.media.map(m => ({
                type: m.type,
                url: m.url
              }))
            : []
        }))

        // حذف کارت‌هایی که user.id ندارند (ایمنی بیشتر)
        const filteredCards = newCards.filter(card => card.user.id)

        // افزودن به لیست
        this.cards = append ? [...this.cards, ...filteredCards] : [...filteredCards, ...this.cards]

        // سایر مقادیر
        this.total = res.count_all || filteredCards.length
        this.lastUpdate = new Date().toISOString()
        this.isLoaded = true
        this.hasRule = res.rule ?? false
        return filteredCards
      }
      return []
    },
    async fetchNewsById(id) {
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
          location: {
            city: item.city,
            lat: item.address_lat,
            lon: item.address_lon,
            address: item.address,
          },
          category: Array.isArray(item.category) ? item.category.map(category => ({
            id: category.id,
            title: category.title
          })) : [],
          user: {
            id: item.user_account_id,
            name: item.user_name,
            family: item.user_family,
            phone: item.user_phone,
            image: item.user_image_url,
          },
          medias: Array.isArray(item.media) ? item.media.map(media => ({
            type: media.type,
            url: media.url
          })) : []
        }
      }
      return null
    },
    async fetchLatestNewsRaw(limit = 5, offset = 0) {
      const res = await sendApi({
        action: 'get_news',
        control: 'news',
        data: { limit, offset }
      })
      return res.status === 'success' ? res.data : []
    },
    async scheduleNewsRunTime(news_id, run_time) {
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
  }
})
