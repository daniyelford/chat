<template>
  <div class="map-wrapper">
    <div id="static-map" class="map"></div>
  </div>
</template>

<script setup>
import { onMounted, ref, defineProps, watch } from 'vue'
import { BASE_URL } from '@/config'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const map = ref(null)
const markerLayer = ref(null)
const markerIconUrl = BASE_URL + '/assets/images/marker.svg'

const props = defineProps({
  center: Object, // { lat: Number, lon: Number }
  markers: Array  // [{ lat: Number, lon: Number }]
})

onMounted(() => {
  map.value = L.map('static-map', {
    zoomControl: false,
    dragging: false,
    scrollWheelZoom: false,
    doubleClickZoom: false,
    boxZoom: false,
    keyboard: false,
    tap: false,
    touchZoom: false,
  }).setView([props.center.lat, props.center.lon], 13)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
  }).addTo(map.value)

  markerLayer.value = L.layerGroup().addTo(map.value)
  drawMarkers()
})

const drawMarkers = () => {
  markerLayer.value.clearLayers()
  props.markers.forEach(pos => {
    L.marker([pos.lat, pos.lon], {
      icon: L.icon({
        iconUrl: markerIconUrl,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
      })
    }).addTo(markerLayer.value)
  })
}

watch(() => props.markers, drawMarkers, { deep: true })
watch(() => props.center, (val) => {
  if (val && map.value) {
    map.value.setView([val.lat, val.lon], 13)
  }
})
</script>

<style scoped>
.map-wrapper {
  width: 100%;
  height: 300px;
  margin-top: 1rem;
}
.map {
  width: 100%;
  height: 100%;
  border-radius: 8px;
  z-index: 9;
}
</style>
