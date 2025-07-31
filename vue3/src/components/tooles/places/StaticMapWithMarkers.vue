<template>
  <div class="map-wrapper">
    <div id="multi-marker-map" class="map"></div>
  </div>
</template>
<script setup>
  import { onMounted, ref, defineProps, watch, createApp ,h } from 'vue'
  import L from 'leaflet'
  import MediaSlider from '../media/MediaSlider.vue'
  import 'leaflet/dist/leaflet.css'
  import { BASE_URL } from '@/config'
  const props = defineProps({
    center: { type: Object, required: true },
    markers: {
      type: Array,
      default: () => []
    }
  })
  const map = ref(null)
  const markerLayerGroup = ref(null)
  const markerIcon = L.icon({
    iconUrl: BASE_URL + '/assets/images/marker.svg',
    iconSize: [32, 32],
    iconAnchor: [16, 32]
  })
  onMounted(() => {
    map.value = L.map('multi-marker-map').setView([props.center.lat, props.center.lon], 13)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map.value)
    markerLayerGroup.value = L.layerGroup().addTo(map.value)
    renderMarkers()
  })
  watch(() => props.markers, () => {
    renderMarkers()
  }, { deep: true })
  watch(() => props.center, (val) => {
    if (val && val.lat && val.lon && map.value) {
      map.value.flyTo([val.lat, val.lon], 13, {
        animate: true,
        duration: 1.2
      })
    }
  }, { immediate: true })
  const truncateText = (text, max = 50) => {
    if (!text) return ''
    return text.length > max ? text.slice(0, max) + '...' : text
  }
  const renderMarkers = () => {
    if (!markerLayerGroup.value) return
    markerLayerGroup.value.clearLayers()
    props.markers.forEach(m => {
      const leafletMarker = L.marker([m.lat, m.lon], { icon: markerIcon })
      const container = document.createElement('div')
      container.style.maxWidth = '250px'
      const app = createApp({
        render() {
          return h('div', {style:'direction: rtl;text-align: center;'} , [
            h('strong',{style:'margin-bottom: 10px;display: inline-block;'}, m.title || ''),
            h(MediaSlider, {
              medias: m.medias || []
            }),
            m.categories?.length ? 
              h('ul', { style: 'display:flex;justify-content: center;margin: 5px 0; padding: 0; list-style: none;' },
                m.categories.map(c =>
                  h('li', {
                    style: 'background: #f3f3f3; margin: 2px 0; padding: 4px 8px; border-radius: 4px; font-size: 13px;'
                  }, c.title)
                )
              )
              : null ,
            h('p', { style: 'margin: 5px 0;' }, truncateText(m.description) || ''),
            h('p', { style: 'margin: 5px 0;' }, m.address || ''),
          ])
        }
      })
      app.component('MediaSlider', MediaSlider)
      app.mount(container)
      leafletMarker.bindPopup(container)
      leafletMarker.addTo(markerLayerGroup.value)
    })
  }
</script>
<style scoped>
  .map-wrapper {
    width: 100%;
    height: 100%;
    position: relative;
  }
  .map {
    width: 100%;
    height: 100%;
    border-radius: 8px;
  }
</style>
