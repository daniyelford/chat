import { defineStore } from 'pinia'
import { sendApi } from '@/utils/api'
import { useUserStore } from '@/stores/user'
export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: [],
    unreadCount: 0,
    lastId: 0,
    showList: false
  }),
  actions: {
    async fetchNotifications({ limit = 10, offset = 0 } = {}) {
      const userStore = useUserStore()
      if (!userStore.isLoggedIn) return { items: [], has_more: false }
      const res = await sendApi({
        action: 'get_notifications',
        control: 'user',
        data: { limit, offset }
      })
      if (res.status === 'success' && Array.isArray(res.data)) {
        const newData = res.data
        if (offset === 0) {
          this.notifications = [...newData]
        } else {
          const existingIds = new Set(this.notifications.map(n => n.id))
          const filteredNew = newData.filter(n => !existingIds.has(n.id))
          this.notifications.push(...filteredNew)
        }
        this.unreadCount = this.notifications.filter(n => n.is_read === 'dont').length
        this.lastId = Math.max(0, ...this.notifications.map(n => parseInt(n.id)))
        return {
          items: newData,
          has_more: res.has_more === true,
        }
      }
      return { items: [], has_more: false }
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
    pushNotification(newNotif) {
      const exists = this.notifications.some(n => n.id === newNotif.id)
      if (!exists) {
        this.notifications.unshift(newNotif)
        if (newNotif.is_read === 'dont') {
          this.unreadCount++
        }
        this.lastId = Math.max(this.lastId, parseInt(newNotif.id))
      }
    },
    toggle() {
      this.showList = !this.showList
    },
    close() {
      this.showList = false
    }
  },
  persist: true,
})