<template>
  <div class="category">
    <input
      v-model="categorySearch"
      placeholder="جستجوی دسته‌بندی..."
      class="search-box"
    />
    <div class="category-list">
      <button @click="selectCategory(0)" :class="{ active: props.selectedCategoryId === 0 }">
        همه
      </button>
      <button
        v-for="cat in filteredCategories"
        :key="cat.id"
        :class="{ active: props.selectedCategoryId === cat.id }"
        @click="selectCategory(cat.id)">
        {{ cat.title }}
      </button>
      <div ref="loadTrigger" class="load-trigger" style="margin-top: -10px;"></div>
    </div>
  </div>
</template>
<script setup>
import { ref, computed, onMounted, defineProps, defineEmits } from 'vue'
import { useInfiniteScroll } from '@/composables/useInfiniteScroll'
import { usePlaceStore } from '@/stores/place'

const emit = defineEmits(['update:selectedCategoryId'])

const props = defineProps({
  selectedCategoryId: Number
})

const placeStore = usePlaceStore()
const categorySearch = ref('')

const filteredCategories = computed(() => {
  const q = categorySearch.value.trim().toLowerCase()
  if (!q) return placeStore.allCategories
  return placeStore.allCategories.filter(c =>
    c.title.toLowerCase().includes(q)
  )
})

const selectCategory = (id) => {
  emit('update:selectedCategoryId', id)
}

const {
  loadMoreTrigger: loadTrigger,
  setupObserver
} = useInfiniteScroll(async ({ offset }) => {
  await placeStore.fetchCategoriesPaginated(offset)
  return {
    items: placeStore.allCategories,
    has_more: placeStore.hasMoreCategories,
  }
})

onMounted(() => {
  setupObserver()
})
</script>
<style scoped>
.category {
  height: 50%;
  box-sizing: border-box;
  padding: 10px;
  border-radius: 10px;
  background: #80808045;
  box-shadow: 0 0 5px #290707;
}
.search-box {
  height: 55px;
  width: 100%;
  margin: 5px auto;
  box-sizing: border-box;
  border-radius: 5px;
  outline: none;
  border: 0;
  background: #e5dede7a;
  box-shadow: 0 0 5px;
  direction: rtl;
}
.category-list {
  height: calc(100% - 80px);
  width: 100%;
  overflow: auto;
  display: flex;
  flex-direction: column;
  flex-wrap: nowrap;
  align-items: stretch;
  justify-content: flex-start;
  gap: 2px;
  margin-top: 8px;
}
.category-list button {
  color: white;
  background: #4a6b7540;
  height: 50px;
  padding: 8px;
  border: none;
  outline: none;
  margin-bottom: 5px;
  border-radius: 10px;
  box-shadow: 0 0 5px green;
}
.category-list button.active {
  background-color: darkgreen;
}
</style>