import { defineStore } from "pinia";
import { sendApi } from "@/utils/api";
export const useSecurityStore = defineStore('security', {
  state: () => ({
    isAuthenticated: false,
    hasMobile: false,
    loading: false,
  }),
  actions: {
    async checkAuth() {
      this.loading = true;
      try {
        const res = await sendApi({
          action: "check_auth",
          control: "security",
        });
        this.isAuthenticated = res.status === "success";
        return this.isAuthenticated;
      } finally {
        this.loading = false;
      }
    },
    async checkHasMobile() {
      this.loading = true;
      try {
        const res = await sendApi({
          action: "check_has_mobile",
          control: "security",
        });
        this.hasMobile = res.status === "success";
        return this.hasMobile;
      } finally {
        this.loading = false;
      }
    },
    async checkOnlyAuth() {
      const isAuth = await this.checkAuth();
      return isAuth;
    },
    reset() {
      this.isAuthenticated = false;
      this.hasMobile = false;
      this.loading = false;
    },
  },
});