<template>
  <div class="containers" v-if="placeStore?.allPlaces?.length>0">
    <div class="category">
      <input v-model="categorySearch" placeholder="جستجوی دسته‌بندی..." class="search-box" />
      <div class="category-list">
        <button @click="selectedCategoryId = null">همه</button>
        <button
          v-for="cat in filteredCategories"
          :key="cat.id"
          :class="{ active: selectedCategoryId === cat.id }"
          @click="selectedCategoryId = cat.id">
          {{ cat.title }}
        </button>
      </div>
    </div>
    <div class="info">
      <div v-for="place in filteredPlaces" :key="place.id" class="place-item">
        <h4>
          {{ place.title }}
        </h4>
        <MediaSlider v-if="place.medias?.length>0" :medias="place.medias"/>
        <small class="cat" v-for="category in place.categories" :key="category.id">
          {{ category.title }}
        </small>
        <p>{{ truncateText(place.description) }}</p>
        <small>
          {{ truncateText(place.addresses?.[0]?.address) || '' }}
        </small>
        <a v-if="place.addresses?.[0]?.lat && place.addresses?.[0]?.lon" @click="openMapModal(place)">محل دقیق</a>
      </div>
    </div>
  </div>
  <BaseModal :show="showMapModal" @close="showMapModal = false">
    <SinglePlaceMap
    v-if="selectedPlace && selectedPlace.lat && selectedPlace.lon && userCenter && userCenter.lat && userCenter.lon"
    :user-center="userCenter"
    :place="selectedPlace"
    />
  </BaseModal>
</template>
<script setup>
  import { onMounted, computed, ref } from 'vue'
  import { usePlaceStore } from '@/stores/place'
  import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
  import BaseModal from '@/components/tooles/modal/BaseModal.vue'
  import SinglePlaceMap from '@/components/tooles/places/SinglePlaceMap.vue'
  const placeStore = usePlaceStore()
  const showMapModal = ref(false)
  const selectedPlace=ref(null)
  const selectedCategoryId = ref(null)
  const categorySearch = ref('')
  const userCenter = computed(() => {
    const cord = placeStore.userCoordinate
    if (cord?.lat && cord?.lon) {
      return { lat: parseFloat(cord.lat), lon: parseFloat(cord.lon) }
    }
    return { lat: 35.6892, lon: 51.3890 }
  })
  const allCategories = computed(() => {
    const set = new Map()
    placeStore.allPlaces.forEach(place => {
      (place.categories || []).forEach(category => {
        if (!set.has(Number(category.id))) {
          set.set(Number(category.id), category)
        }
      })
    })
    return Array.from(set.values())
  })
  const filteredCategories = computed(() => {
    const q = categorySearch.value.trim().toLowerCase()
    if (!q) return allCategories.value
    return allCategories.value.filter(c => c.title.toLowerCase().includes(q))
  })
  const filteredPlaces = computed(() => {
    if (!selectedCategoryId.value) return placeStore.allPlaces
    return placeStore.allPlaces.filter(place =>
      (place.categories || []).some(cat => Number(cat.id) === Number(selectedCategoryId.value))
    )
  })
  const truncateText = (text, max = 50) => {
    if (!text) return ''
    return text.length > max ? text.slice(0, max) + '...' : text
  }
  const openMapModal = (item) => {
    const address = item?.addresses?.[0]
    const lat = parseFloat(address?.lat)
    const lon = parseFloat(address?.lon)
    if (isNaN(lat) || isNaN(lon)) return
    selectedPlace.value = {
      ...address,
      title: item.title || 'موقعیت',
      description: item.description || '',
      categories: item.categories || [],
      medias: item.medias || [],
      address: address.address || ''
    }
    showMapModal.value = true
  }
  onMounted(() => {
    placeStore.fetchAllPlaces()
  })
</script>
<style>
  .containers{
    padding-bottom: .9%;
    overflow: hidden;
    height: 98%;
    display: flex;
    gap: 2%;
    flex-direction: row-reverse;
    align-items: stretch;
    justify-content: center;
    flex-wrap: nowrap;
  }
  .category{
    height: 100%;
    float: right;
    width: 29%;
  }
  .category input{
    height: 30px;
    width: 100%;
    margin: 5px auto;
    box-sizing: border-box;
    border-radius: 5px;
    outline: none;
    border: 0;
    background: #e5dede;
    box-shadow: 0 0 5px;
    direction: rtl;
  }
  .category-list{
    height: calc(100% - 30px);
    width: 100%;
    overflow: auto;
    display: flex;
    flex-direction: column;
    flex-wrap: nowrap;
    align-items: stretch;
    justify-content: flex-start;
    gap: 2px;
  }
  .info{
    height: 100%;
    gap: 10px;
    overflow: auto;
    width: 69%;
    display: flex;
    flex-direction: row-reverse;
    flex-wrap: wrap;
    align-content: flex-start;
    justify-content: center;
    align-items: center;
  }
  .category-list button {
    color: white;
    background: #4a6b75;
    padding: 8px;
    border: none;
    outline: none;
    border-radius: 10px;
    box-shadow: 0 0 5px green;
  }
  .place-item {
    position: sticky;
    top: 0;
    padding: 10px;
    box-sizing: border-box;
    background: #bcebbc;
    border-radius: 10px;
    width: 100%;
    text-align: center;
  }
  h4,p,small{
    margin: 0 0 5px 0;
  }
  .place-item a {
    width: 100%;
    display: inline-block;
    padding: 10px;
    background: yellow;
    border-radius: 10px;
    box-sizing: border-box;
    text-align: center;
  }
  .cat{
    padding: 3px 5px;
    background: lightgrey;
    border-radius: 5px;
    display: inline-block;
    margin: 5px;
  }
</style>