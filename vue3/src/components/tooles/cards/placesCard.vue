<template>
  <div class="place-item">
    <h4>{{ props.place.title }}</h4>
    <MediaSlider v-if="props.place.medias?.length > 0" :medias="props.place.medias" />
    <small class="cat" v-for="category in props.place.categories" :key="category.id">{{ category.title }}</small>
    <p>{{ truncateText(place.description) }}</p>
    <small>{{ truncateText(place.addresses?.[0]?.address) || '' }}</small>
    <a v-if="props.place.addresses?.[0]?.lat && props.place.addresses?.[0]?.lon" @click="$emit('openMap', place)">محل دقیق</a>
    <a v-if="props.userAccountId === 1 || props.userAccountId === 2" @click="$emit('editPlace', place)">ویرایش</a>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import MediaSlider from '@/components/tooles/media/MediaSlider.vue'

const props = defineProps({
  place: Object,
  userAccountId: Number,
})

const truncateText = (text, max = 50) => {
  if (!text) return ''
  return text.length > max ? text.slice(0, max) + '...' : text
}
</script>

<style scoped>
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
h4, p, small {
  margin: 0 0 5px 0;
}
small {
  display: block;
}
.place-item a {
  width: 50%;
  display: inline-block;
  padding: 10px;
  background: yellow;
  border-radius: 10px;
  box-sizing: border-box;
  text-align: center;
  cursor: pointer;
}
.cat {
  padding: 3px 5px;
  background: lightgrey;
  border-radius: 5px;
  display: inline-block;
  margin: 5px;
}
</style>

<!-- <BaseModal v-if="props.showAddPlace" @close="emit('close-add-place')">
  <PlaceAddCard
    :user-account-id="props.userAccountId"
    :place="props.editingPlace"
    :user-center="props.userCenter"
    @done="emit('close-add-place')"
  /> -->
  <!-- const props = defineProps({
places: Array,
userAccountId: Number,
userCenter: Object,
showAddPlace: Boolean,
editingPlace: Object
}) -->
<!-- </BaseModal> -->


<!-- import placeCardsList from '@/components/tooles/cards/placeCardsList.vue' -->

    <!-- 

// import placeCards from '@/components/tooles/cards/placeCardsList.vue'
// import SinglePlaceMap from '../places/SinglePlaceMap.vue'
  // import AddPlaceForm from '@/components/dashboard/pagesContent/AddPlaceForm.vue'

// import PlaceForm from './PlaceForm.vue'
// import PlaceMap from './PlaceMap.vue'

// :places="filteredPlaces"
// :user-account-id="placeStore.userAccountId"
// :user-center="userCenter"
// :show-add-place="showAddPlace"
// :editing-place="editingPlace"
// :place-load-trigger-ref="placeLoadTrigger"
// @open-map-modal="openMapModal"
// @edit-place="editPlace"
// @close-add-place="handleCloseAddPlace"





    <BaseModal :show="showMapModal" @close="showMapModal = false">
      <SinglePlaceMap
      v-if="selectedPlace && selectedPlace.lat && selectedPlace.lon && userCenter && userCenter.lat && userCenter.lon"
      :user-center="userCenter"
      :place="selectedPlace"
      />
    </BaseModal>
    <BaseModal :show="showAddPlace" @close="handleCloseAddPlace">
      <AddPlaceForm :editPlace="editingPlace" @done="handleCloseAddPlace" />
    </BaseModal> -->