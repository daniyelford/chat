import { useInfiniteScroll } from '@vueuse/core'
import { ref } from 'vue'
export function scroll(fetchFn) {
  const el = ref(null)
  const list = ref([])
  let offset = 0
  let hasMore = true
  const loadMore = async () => {
    if (!hasMore) return
    const res = await fetchFn({ offset })
    const items = res.items || []
    list.value.push(...items)
    offset += items.length
    hasMore = res.has_more
  }
  useInfiniteScroll(el, loadMore, { distance: 50 })
  return { el, list, loadMore }
}