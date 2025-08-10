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
    allCategories: [],
    allRules: [],
    status:'inactive',
    banTime:null
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
          this.status = res.user_status
          this.banTime = res.user_ban
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
        if (res.status === 'success' && Array.isArray(res.data)) {
          this.hasMore = res.has_more
          if (offset === 0) {
            this.users = res.data
          } else {
            this.users.push(...res.data)
          }
          this.allCategories = res.all_category
          this.allRules = res.all_rule
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
        if (res.status === 'success' && res?.id) {
          if (!edit) {
            this.users.unshift(res.data)
          } else {
            const index = this.users.findIndex(c => Number(c.id) === Number(edit.user_id))
            if (index !== -1) {
              this.users[index] = res.data
            } else {
              this.users.unshift(res.data)
            }
          }
        }
      } catch (err) {
        console.error(err)
      }
    },
    async enableUser(id) {
      try {
        const res = await sendApi({
          action: 'enable_user',
          control: 'user',
          data: { id }
        })
        if (res.status === 'success') {
          return true
        }
        return false
      } catch (err) {
        console.error(err)
      }
    },
    async disableUser(id) {
      try {
        const res = await sendApi({
          action: 'disable_user',
          control: 'user',
          data: { id }
        })
        if (res.status === 'success') {
          return true
        }
        return false
      } catch (err) {
        console.error(err)
      }
    },
  }
})
