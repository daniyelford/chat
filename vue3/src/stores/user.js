import { defineStore } from 'pinia'
import { sendApi } from '@/utils/api'

export const useUserStore = defineStore('user', {
  state: () => ({
    fullName: '',
    name: '',
    family: '',
    mobile: '',
    wallet: 0,
    tokenScore: 0,
    image: '',
    finger: false,
    rule: false,
    hasMore: false,
    isLoaded: false,
    ruleInfo: [],
    users: [],
    loading: false,
    error: null,
  }),

  actions: {
    async fetchUserInfo() {
      try {
        const res = await sendApi({ action: 'get_user_info', control: 'user' })
        if (res.status === 'success') {
          this.fullName = res.fullName
          this.name = res.name
          this.family = res.family
          this.mobile = res.mobile
          this.wallet = res.wallet
          this.image = res.image
          this.finger = res.finger
          this.tokenScore = res.token_score
          this.rule = Array.isArray(res.rules) && res.rules.length > 0
          this.ruleInfo = res.rules
          this.isLoaded = true
        } else {
          console.warn('User info error:', res)
        }
      } catch (err) {
        console.error('Error fetching user:', err)
      }
    },

    async fetchUsers({ limite = 10, offset = 0 }) {
      this.loading = true
      this.error = null
      try {
        const res = await sendApi({
          action: 'get_users',
          control: 'user',
          data: { limite, offset }
        })
        if (res.status === 'success') {
          this.hasMore = res.has_more
          this.users = res.data
        } else {
          this.error = res.message || 'خطا در دریافت کاربران'
        }
      } catch (err) {
        this.error = 'خطای ارتباط با سرور'
        console.error(err)
      } finally {
        this.loading = false
      }
    },

    async submitUser(userData, edit = null) {
      try {
        const res = await sendApi({
          action: 'user_submit',
          control: 'user',
          data: { data: userData, edit: edit }
        })
        return res
      } catch (err) {
        console.error(err)
      }
    },

    async deleteUser(id) {
      try {
        const res = await sendApi({
          action: 'delete_user',
          control: 'user',
          data: { id }
        })
        if (res.status === 'success') {
          this.users = this.users.filter(u => u.id !== id)
        }
        return res
      } catch (err) {
        console.error(err)
      }
    },
  }
})
