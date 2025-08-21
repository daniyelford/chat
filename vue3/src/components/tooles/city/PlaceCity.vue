<template>
  <div class="city">
    <input
      v-model="citySearch"
      placeholder="جستجوی شهر..."
      class="search-box"
    />
    <div class="city-list">
      <button
        @click="selectCity(0)"
        :class="{ active: props.selectedCityId === 0 }"
      >
        همه شهرها
      </button>
      <button
        v-for="city in filteredCities"
        :key="city.id"
        :class="{ active: props.selectedCityId === city.id }"
        @click="selectCity(city.id)"
      >
        {{ city.city }}
      </button>
      <div ref="loadTrigger" class="load-trigger" style="margin-top: -10px;"></div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, defineProps, defineEmits } from 'vue'
import { useInfiniteScroll } from '@/composables/useInfiniteScroll'
import { usePlaceStore } from '@/stores/place'

const emit = defineEmits(['update:selectedCityId'])
const props = defineProps({ selectedCityId: Number })

const placeStore = usePlaceStore()
const citySearch = ref('')

const filteredCities = computed(() => {
  const q = citySearch.value.trim().toLowerCase()
  if (!q) return placeStore.allCities
  return placeStore.allCities.filter(c =>
    c.city.toLowerCase().includes(q)
  )
})

const selectCity = (id) => {
  emit('update:selectedCityId', id)
}

const { loadMoreTrigger: loadTrigger, setupObserver } = useInfiniteScroll(async ({ offset }) => {
  await placeStore.fetchCitiesPaginated(offset)
  return {
    items: placeStore.allCities,
    has_more: placeStore.hasMoreCities,
  }
})

onMounted(() => {
  setupObserver()
})
</script>
<style scoped>
.city {
  height: calc(50% - 30px);
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
.city-list {
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
.city-list button {
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
.city-list button.active {
  background-color: darkgreen;
}
</style>