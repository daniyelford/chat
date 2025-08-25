<template>
  <button class="showCategoryBtn" @click="showCategory = true">انتخاب دسته‌بندی</button>
  <transition name="slide-down">
    <div v-if="showCategory" class="drawer top">
      <button class="close" @click="showCategory = false">✖</button>
      <PlaceCategory v-model:selectedCategoryId="selectedCategoryId" />
    </div>
  </transition>
  <div class="info" v-if="placeStore?.allPlaces?.length>0">
    <div v-if="placeStore?.highRule" class="addPlace">
      <a @click="showAddPlace=true">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="35px" viewBox="0,0,256,256"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.33333,5.33333)"><path d="M44,24c0,11.045 -8.955,20 -20,20c-11.045,0 -20,-8.955 -20,-20c0,-11.045 8.955,-20 20,-20c11.045,0 20,8.955 20,20z" fill="#118a17"></path><path d="M22,15h4v18h-4z" fill="#ffffff"></path><path d="M15,22h18v4h-18z" fill="#ffffff"></path></g></g></svg>
      </a>
    </div>
    <div :class="placeStore?.highRule?'manager-places':'places'">
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
  </div>
  <div v-else-if="placeStore.categoryListLoading || placeStore.placeListLoading">
      <div class="tiny-loader"></div>
  </div>
  <div v-else class="errorp">
    <span>
      محل نزدیکی برای ارائه به شما وجود ندارد
    </span>
    <br>
    <a v-if="placeStore?.highRule" @click="showAddPlace=true" class="addP">افزودن محل</a>
  </div>
  <button class="showCityBtn" @click="showCity = true">انتخاب شهر</button>
  <transition name="slide-up">
    <div v-if="showCity" class="drawer bottom">
      <button class="close" @click="showCity = false">✖</button>
      <PlaceCity v-model:selectedCityId="selectedCityId" />
    </div>
  </transition>
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
  import PlaceCity from '@/components/tooles/city/PlaceCity.vue'
  const placeStore = usePlaceStore()
  const showMapModal = ref(false)
  const showAddPlace = ref(false)
  const showCategory = ref(false)
  const showCity = ref(false)
  const editingPlace = ref(null)
  const selectedPlace=ref(null)
  const selectedCityId = ref(null)
  const selectedCategoryId = ref(null)
  const userCenter = computed(() => {
    const cord = placeStore.userCoordinate
    if (cord?.lat && cord?.lon) {
      return { lat: parseFloat(cord.lat), lon: parseFloat(cord.lon) }
    }
    return { lat: 35.6892, lon: 51.3890 }
  })
  const filteredPlaces = computed(() => {
    let places = placeStore.allPlaces
    if (selectedCategoryId.value && Number(selectedCategoryId.value) !== 0) {
      places = places.filter(place =>
        (place.categories || []).some(cat => Number(cat.id) === Number(selectedCategoryId.value))
      )
    }
    if (selectedCityId.value && Number(selectedCityId.value) !== 0) {
      places = places.filter(place => Number(place.addresses[0].id) === Number(selectedCityId.value))
    }
    return places
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
    await placeStore.fetchPlacesPaginated({ offset: 0, category_id: selectedCategoryId.value, city_id: selectedCityId.value })
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
  watch([selectedCategoryId, selectedCityId], async () => {
    resetPlaces()
    await placeStore.fetchPlacesPaginated({ 
      offset: 0, 
      category_id: selectedCategoryId.value,
      city_id: selectedCityId.value
    })
    setupPlaceObserver()
  })

</script>
<style scoped>
  .showCategoryBtn , .showCityBtn{
    width: auto;
    position: fixed;
    left: 0;
    right: 0;
    height: 60px;
    border: none;
    outline: none;
    background: greenyellow;
  }
  .showCategoryBtn{
    top: 60px;
    border-radius: 0 0 500px 500px;

  }
  .showCityBtn{
    border-radius: 500px 500px 0 0;
    bottom: 0px;
  }
  .drawer {
    position: fixed;
    left: 0;
    right: 0;
    background: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    z-index: 999;
    max-height: 70vh;
    overflow-y: auto;
  }
  .drawer.top {
    top: 60px;
    border-radius: 0 0 12px 12px;
  }
  .drawer.bottom {
    bottom: 0;
    border-radius: 12px 12px 0 0;
  }
  .slide-down-enter-active,
  .slide-down-leave-active {
    transition: all 0.3s ease;
  }
  .slide-down-enter-from,
  .slide-down-leave-to {
    transform: translateY(-100%);
  }
  .slide-up-enter-active,
  .slide-up-leave-active {
    transition: all 0.3s ease;
  }
  .slide-up-enter-from,
  .slide-up-leave-to {
    transform: translateY(100%);
  }
  .close {
    float: left;
    background: none;
    border: none;
    font-size: 1.2rem;
  }
  .addPlace{
    width: 100%;
  }
  .addPlace a{
    margin-left: 20px;
  }
  .addP{
    display: block;
    margin: 5px auto;
    width: 50%;
    padding: 8px;
    background-color: green;
    border-radius: 10px;
    cursor: pointer;
  }
  .places{
    height: 100%;
  }
  .manager-places{
    height: 90%;
  }
  .places,.manager-places{
    gap: 10px;
    overflow: auto;
    display: flex;
    flex-direction: row-reverse;
    flex-wrap: wrap;
    align-content: flex-start;
    justify-content: center;
    align-items: center;
  }
  .info{
    position: fixed;
    top: 120px;
    bottom: 60px;
    left: 30px;
    right: 30px;
    height: auto;
    width: auto;
    padding: 10px;
    border-radius: 60px;
    overflow: hidden;
    box-sizing: border-box;
    background: #80808045;
    box-shadow: 0 0 5px #290707;
  }
  .errorp{
    text-align: center;
    background-color: #dc6a6a;
    position: fixed;
    bottom: calc(50% - 150px);
    top: calc(50% - 150px);
    left: calc(50% - 150px);
    right: calc(50% - 150px);
    height: 300px;
    overflow: hidden;
    width: 300px;
    font-size: x-large;
    border-radius: 50%;
    font-weight: bolder;
    box-sizing: border-box;
    box-shadow: 0 0 20px #1e1212;
  }
  .errorp span{
    display: block;
    background: #fff;
    top: 40%;
    height: 20%;
    box-sizing: border-box;
    color: #000;
    position: relative;
    padding-top: 5%;
  }
  .tiny-loader {
    width: 20px;
    height: 20px;
    border: 2px solid #ccc;
    border-top-color: #333;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 60px auto;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>