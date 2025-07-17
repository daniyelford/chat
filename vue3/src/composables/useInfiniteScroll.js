import { ref, watch, onBeforeUnmount } from 'vue';

export function useInfiniteScroll(fetchFn, {
  limit = 10,
  total = null,
  immediate = true,
} = {}) {
  const items = ref([]);
  const loading = ref(false);
  const offset = ref(0);
  const canLoadMore = ref(true);
  const loadMoreTrigger = ref(null);
  let observer = null;

  if (total && typeof total === 'object' && 'value' in total) {
    watch(total, (newVal) => {
      canLoadMore.value = items.value.length < newVal;
    });
  }

  const loadMore = async () => {
    if (loading.value || !canLoadMore.value) return;

    loading.value = true;

    try {
      const result = await fetchFn({ limit, offset: offset.value });
      const newItems = Array.isArray(result) ? result : [];

      items.value.push(...newItems);
      offset.value += newItems.length;

      const totalValue = (typeof total === 'object' && total !== null && 'value' in total)
        ? total.value
        : (typeof total === 'number' ? total : null);

      canLoadMore.value =
        !(newItems.length < limit || (typeof totalValue === 'number' && items.value.length >= totalValue));
    } catch (error) {
      console.error('Error loading more items:', error);
    } finally {
      loading.value = false;
    }
  };

  const setupObserver = () => {
    if (observer) observer.disconnect();

    observer = new IntersectionObserver(async ([entry]) => {
      if (entry.isIntersecting && canLoadMore.value && !loading.value) {
        await loadMore();
      }
    }, {
      root: null,
      rootMargin: '0px',
      threshold: 0.5,
    });

    watch(loadMoreTrigger, (el) => {
      if (el) {
        observer.observe(el);
      }
    }, { immediate: true });
  };

  onBeforeUnmount(() => {
    if (observer) observer.disconnect();
  });

  if (immediate) loadMore();

  return {
    items,
    loading,
    canLoadMore,
    loadMore,
    loadMoreTrigger,
    setupObserver,
  };
}
