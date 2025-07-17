import { defineStore } from 'pinia'
import { ref } from 'vue'
export const useMenuStore = defineStore('menu', () => {
  const isOpen = ref(false)
  function toggle() {
    isOpen.value = !isOpen.value
  }
  function close() {
    isOpen.value = false
  }
  return { isOpen, toggle, close }
})
