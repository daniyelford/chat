<template>
  <div class="containers" v-if="placeStore?.allPlaces?.length>0">
    <PlaceCategory v-model:selectedCategoryId="selectedCategoryId"/>
    <div class="info">
      <div v-if="placeStore?.highRule" class="addPlace">
        <a @click="showAddPlace=true">add place</a>
      </div>
      <placesCard
      v-for="place in filteredPlaces"
      :key="place.id"
      :place="place"
      :highRule="placeStore?.highRule"
      @editPlace="editPlace"
      @deletePlace="deletePlace"
      @openMap="openMapModal"
      />
      <div ref="placeLoadTrigger" class="load-trigger" style="margin-top: -750px;"></div>
    </div>
    <BaseModal :show="showMapModal" @close="showMapModal = false">
      <SinglePlaceMap
      v-if="selectedPlace && selectedPlace.lat && selectedPlace.lon && userCenter && userCenter.lat && userCenter.lon"
      :user-center="userCenter"
      :place="selectedPlace"
      />
    </BaseModal>
    <BaseModal :show="showAddPlace" @close="handleCloseAddPlace">
      <AddPlaceForm :editPlace="editingPlace" @done="handleCloseAddPlace" />
    </BaseModal>
  </div>
  <div v-else-if="placeStore.categoryListLoading || placeStore.placeListLoading">
      <div class="tiny-loader"></div>
  </div>
  <div v-else class="error">
    محل نزدیکی برای ارائه به شما وجود ندارد
  </div>
</template>
<script setup>
  import {  computed, ref, watch, onMounted } from 'vue'
  import { usePlaceStore } from '@/stores/place'
  import { useInfiniteScroll } from '@/composables/useInfiniteScroll'
  import placesCard from '@/components/tooles/cards/placesCard.vue'
  import BaseModal from '@/components/tooles/modal/BaseModal.vue'
  import SinglePlaceMap from '@/components/tooles/places/SinglePlaceMap.vue'
  import AddPlaceForm from '@/components/dashboard/pagesContent/AddPlaceForm.vue'
  import PlaceCategory from '@/components/tooles/category/PlaceCategory.vue'
  const placeStore = usePlaceStore()
  const showMapModal = ref(false)
  const showAddPlace = ref(false)
  const editingPlace = ref(null)
  const selectedPlace=ref(null)
  const selectedCategoryId = ref(null)
  const userCenter = computed(() => {
    const cord = placeStore.userCoordinate
    if (cord?.lat && cord?.lon) {
      return { lat: parseFloat(cord.lat), lon: parseFloat(cord.lon) }
    }
    return { lat: 35.6892, lon: 51.3890 }
  })
  const filteredPlaces = computed(() => {
    if (!selectedCategoryId.value || Number(selectedCategoryId.value)===0) return placeStore.allPlaces
    return placeStore.allPlaces.filter(place =>
      (place.categories || []).some(cat => Number(cat.id) === Number(selectedCategoryId.value))
    )
  })
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
  const handleCloseAddPlace = async () => {
    showAddPlace.value = false
    editingPlace.value = null
    placeStore.resetPlaces()
    await placeStore.fetchPlacesPaginated({ offset: 0, category_id: selectedCategoryId.value })
  }
  const editPlace = (place) => {
    if (placeStore.highRule) {
      editingPlace.value = place
      showAddPlace.value = true
    } else {
      alert("شما مجوز ویرایش این مکان را ندارید.")
    }
  }
  const deletePlace = (id) => {
    if (placeStore.highRule) {
      placeStore.deletePlace(id)
    } else {
      alert("شما مجوز ویرایش این مکان را ندارید.")
    }
  }
  const {
    loadMoreTrigger: placeLoadTrigger,
    setupObserver: setupPlaceObserver,
    reset: resetPlaces
  } = useInfiniteScroll(async () => {
    await placeStore.fetchPlacesPaginated({ category_id: selectedCategoryId.value })
    return {
      items: placeStore.allPlaces,
      has_more: placeStore.hasMorePlaces,
    }
  })
  onMounted(() => {
    setupPlaceObserver()
  })
  watch(showAddPlace, (val) => {
    if (!val) {
      editingPlace.value = null
    }
  })
  watch(selectedCategoryId, async () => {
    resetPlaces()
    await placeStore.fetchPlacesPaginated({ offset: 0, category_id: selectedCategoryId.value })
    setupPlaceObserver()
  })
</script>
<style scoped>
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
  .error{
    width: 100%;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    background-color: red;
    color: white;
    font-size: large;
    font-weight: bolder;
    box-sizing: border-box;
  }
  .tiny-loader {
    width: 20px;
    height: 20px;
    border: 2px solid #ccc;
    border-top-color: #333;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 10px auto;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>