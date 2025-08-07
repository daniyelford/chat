import { ref, watch, onBeforeUnmount } from 'vue'
export function useInfiniteScroll(fetchFn, {
  immediate = true,
} = {}) {
  const loading = ref(false)
  const offset = ref(0)
  const canLoadMore = ref(true)
  const loadMoreTrigger = ref(null)
  let observer = null
  const loadMore = async () => {
    if (loading.value || !canLoadMore.value) return
    loading.value = true
    try {
      const result = await fetchFn({ offset: offset.value })
      const newCount = Array.isArray(result?.items) ? result.items.length : 0
      const hasMore = result?.has_more === true
      offset.value += newCount
      canLoadMore.value = hasMore
    } catch (e) {
      console.error(e)
    } finally {
      loading.value = false
    }
  }
  const setupObserver = () => {
    if (observer) observer.disconnect()
    observer = new IntersectionObserver(async ([entry]) => {
      if (entry.isIntersecting && canLoadMore.value && !loading.value) {
        await loadMore()
      }
    }, {
      threshold: 0.5,
    })
    watch(loadMoreTrigger, (el) => {
      if (el) observer.observe(el)
    }, { immediate: true })
  }
  const reset = () => {
    canLoadMore.value = true
    offset.value = 0
    loading.value = false
    if (observer) observer.disconnect()
    setupObserver()
  }
  onBeforeUnmount(() => {
    if (observer) observer.disconnect()
  })
  if (immediate) loadMore()
  return {
    loading,
    canLoadMore,
    loadMoreTrigger,
    reset,
    setupObserver,
  }
}