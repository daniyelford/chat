// stores/notification.js
import { defineStore } from 'pinia'
import { sendApi } from '@/utils/api'
import { useUserStore } from '@/stores/user'

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: [],
    unreadCount: 0,
    lastId: 0,
    totalCount: 0,
  }),

  actions: {
    async fetchNotifications({ limit = 10, offset = 0 } = {}) {
      const userStore = useUserStore()
      if (!userStore.isLoggedIn) return []

      const res = await sendApi({
        action: 'get_notifications',
        control: 'user',
        data: { limit, offset }
      })

      if (res.status === 'success' && res.data?.data) {
        const newData = res.data.data

        // فقط وقتی offset صفره، لیست اصلی رو جایگزین کن
        if (offset === 0) {
          this.notifications = [...newData]
        } else {
          // برای infinite scroll، آیتم‌های جدید رو اضافه کن (بدون تکرار)
          const existingIds = new Set(this.notifications.map(n => n.id))
          const filteredNew = newData.filter(n => !existingIds.has(n.id))
          this.notifications.push(...filteredNew)
        }

        this.totalCount = parseInt(res.data.total) || this.notifications.length

        const unread = this.notifications.filter(n => n.is_read === 'dont')
        this.unreadCount = unread.length

        this.lastId = Math.max(0, ...this.notifications.map(n => parseInt(n.id)))

        return newData
      }

      return []
    },

    markAsRead(id) {
      const notif = this.notifications.find(n => n.id === id)
      if (notif && notif.is_read === 'dont') {
        notif.is_read = 'seen'
        this.unreadCount = this.notifications.filter(n => n.is_read === 'dont').length

        sendApi({
          action: 'read_notifications',
          control: 'user',
          data: id
        })
      }
    },

    pushNotification(notif) {
      const exists = this.notifications.some(n => n.id === notif.id)
      if (!exists) {
        this.notifications.unshift(notif)
        if (notif.is_read === 'dont') {
          this.unreadCount++
        }
        this.lastId = Math.max(this.lastId, parseInt(notif.id))
        this.totalCount++
      }
    },
  },

  persist: true
})
