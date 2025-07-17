import { defineStore } from 'pinia'
export const useUserStore = defineStore('user', {
  state: () => ({
    fullName: '',
    name: '',
    family: '',
    mobile: '',
    wallet: 0,
    tokenScore:0,
    image: '',
    finger:false,
    rule:false,
    isLoaded: false,
    ruleInfo:[]
  }),
  actions: {
    async fetchUserInfo() {
      try {
        const { sendApi } = await import('@/utils/api')
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
  },
  getters: {
    isLoggedIn: (state) => !!state.name || !!state.image,
  },
})
