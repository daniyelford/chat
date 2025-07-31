<template>
  <StaticMapWithMarkers
    :center="center"
    :markers="markers"
  />
</template>
<script setup>
  import StaticMapWithMarkers from '@/components/tooles/places/StaticMapWithMarkers.vue'
  import { usePlaceStore } from '@/stores/place'
  import { onMounted, computed } from 'vue'
  const placeStore = usePlaceStore()
  onMounted(() => {
    placeStore.fetchAllPlaces()
  })
  const markers = computed(() =>
    placeStore.allPlaces.flatMap(place =>
      (place.addresses || []).map(address => ({
        lat: parseFloat(address.lat),
        lon: parseFloat(address.lon),
        address: address.address || '',
        title: place.title || '',
        description: place.description || '',
        medias: place.medias || [],
        categories: place.categories || []
      }))
    )
  )
  const center = computed(() => {
    const cord = placeStore.userCoordinate
    if (cord?.lat && cord?.lon) {
      return { lat: parseFloat(cord.lat), lon: parseFloat(cord.lon) }
    }
    return { lat: 35.6892, lon: 51.3890 }
  })
</script>
