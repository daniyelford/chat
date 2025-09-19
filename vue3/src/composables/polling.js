import { useIntervalFn } from '@vueuse/core'
import { ref, onMounted, onBeforeUnmount } from 'vue'
export function polling(fetchFn, {
    intervalMs = 6000,
    isDifferent = (oldData, newData) => {
      if (!Array.isArray(oldData) || !Array.isArray(newData)) return true
      if (oldData.length !== newData.length) return true
      return newData.some((item, i) => JSON.stringify(item) !== JSON.stringify(oldData[i]))
    },
    onChange = () => {},
} = {}) {
  const lastData = ref(null)
  const { pause, resume } = useIntervalFn(async () => {
    try {
      const fresh = await fetchFn()
      if (isDifferent(lastData.value, fresh)) {
        lastData.value = fresh
        onChange(fresh)
      }
    } catch (e) {
      console.error('Polling error:', e)
    }
  }, intervalMs, { immediate: true })
  onMounted(resume)
  onBeforeUnmount(pause)
}