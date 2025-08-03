<template>
  <div class="map-wrapper">
    <div id="multi-marker-map" class="map"></div>
  </div>
</template>
<script setup>
  import { onMounted, ref, defineProps, watch, createApp ,h } from 'vue'
  import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
  import L from 'leaflet'
  import 'leaflet-routing-machine'
  import 'leaflet-routing-machine/dist/leaflet-routing-machine.css'
  import 'leaflet/dist/leaflet.css'
  import { BASE_URL,ORS_API_KEY } from '@/config'
  import { nextTick } from 'vue'
  const props = defineProps({
    userCenter: { type: Object, required: true },
    placeCenter: { type: Object, default: null },
    markers: {
      type: Array,
      default: () => []
    },
    activePlaceId: {
      type: [String, Number],
      default: null
    }
  })
  const map = ref(null)
  const markerLayerGroup = ref(null)
  const leafletMarkers = ref({})
  const routingControl = ref(null)
  const markerIcon = L.icon({
    iconUrl: BASE_URL + '/assets/images/marker.svg',
    iconSize: [32, 32],
    iconAnchor: [16, 32]
  })
  const truncateText = (text, max = 50) => {
    if (!text) return ''
    return text.length > max ? text.slice(0, max) + '...' : text
  }
  const renderMarkers = () => {
    if (!markerLayerGroup.value) return
    leafletMarkers.value = {}
    markerLayerGroup.value.clearLayers()
    props.markers.forEach(m => {
      const leafletMarker = L.marker([m.lat, m.lon], { icon: markerIcon })
      const container = document.createElement('div')
      container.style.maxWidth = '250px'
      const app = createApp({
        render() {
          return h('div', {style:'direction: rtl;text-align: center;'} , [
            h('h4',{style:'margin-bottom: 5px;display: inline-block;'}, m.title || ''),
            h(MediaSlider, {
              medias: m.medias || []
            }),
            m.categories?.length ? 
              h('ul', { style: 'display:flex;justify-content: center;margin: 5px 0; padding: 0; list-style: none;' },
                m.categories.map(c =>
                  h('li', {
                    style: 'background: #f3f3f3; margin: 2px 0; padding: 3px 5px; border-radius: 4px; font-size: 11px;'
                  }, c.title)
                )
              )
              : null ,
            h('p', { style: 'margin: 5px 0;' }, truncateText(m.description) || ''),
            h('small', { style: 'margin: 5px 0;' }, m.address || ''),
          ])
        }
      })
      app.component('MediaSlider', MediaSlider)
      app.mount(container)
      nextTick(() => {
        leafletMarker.bindPopup(container)
        leafletMarker.addTo(markerLayerGroup.value)
      })
      leafletMarkers.value[m.placeId] = leafletMarker
    })
  }
  function createORSRouter(apiKey, profile = 'driving-car') {
    return {
      route: function (waypoints, callback, context, options) {
        const coordinates = waypoints.map(wp => [wp.latLng.lng, wp.latLng.lat])
        if(!options){
          console.log(options);
        }
        if (coordinates[0][0] === coordinates[1][0] && coordinates[0][1] === coordinates[1][1]) {          
          alert('شما در محل هستید')
          console.warn('Route skipped: source and destination are the same.')
          callback.call(context, null, [])
          return
        }
        fetch(`https://api.openrouteservice.org/v2/directions/${profile}/geojson`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': apiKey
          },
          body: JSON.stringify({
            coordinates,
            instructions: false
          })
        })
          .then(res => res.json())
          .then(data => {
            const coords = data.features?.[0]?.geometry?.coordinates || []
            const latlngs = coords.map(([lng, lat]) => L.latLng(lat, lng))
            const summary = data.features?.[0]?.properties?.summary || {}
            const [[startLng, startLat], [endLng, endLat]] = data.metadata?.query?.coordinates || []
            const startCoord = { lat: startLat, lon: startLng }
            const endCoord = { lat: endLat, lon: endLng }
            const route = {
              name: '',
              coordinates: latlngs,
              instructions: [],
              summary: {
                totalDistance: summary.distance ?? 0,
                totalTime: summary.duration ?? 0
              },
              inputWaypoints: [
                L.Routing.waypoint(L.latLng(startCoord.lat, startCoord.lon)),
                L.Routing.waypoint(L.latLng(endCoord.lat, endCoord.lon))
              ],
              waypoints: [
                L.latLng(startCoord.lat, startCoord.lon),
                L.latLng(endCoord.lat, endCoord.lon)
              ]
            }
            callback.call(context, null, [route])
          })
          .catch(err => {
            console.error('Routing error', err)
            callback.call(context, err)
          })
      }
    }
  }
  const drawRoute = (from, to) => {
    if (!map.value || !from || !to) return
    if (from.lat === to.lat && from.lon === to.lon) {
      alert('شما در محل هستید')
      console.warn('Route not drawn: same coordinates')
      return
    }
    if (routingControl.value) {
      map.value.removeControl(routingControl.value)
    }
    routingControl.value = L.Routing.control({
      waypoints: [
        L.latLng(from.lat, from.lon),
        L.latLng(to.lat, to.lon)
      ],
      router: createORSRouter(ORS_API_KEY),
      routeWhileDragging: false,
      draggableWaypoints: false,
      addWaypoints: false,
      show: false,
      createMarker: () => null
    }).addTo(map.value)
  }
  onMounted(() => {
    map.value = L.map('multi-marker-map').setView([props.userCenter.lat, props.userCenter.lon], 13)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map.value)
    markerLayerGroup.value = L.layerGroup().addTo(map.value)
    renderMarkers()
  })
  watch(() => props.markers, () => {
    renderMarkers()
  }, { deep: true })
  watch(() => props.userCenter, (val) => {
    if (val && val.lat && val.lon && map.value) {
      map.value.flyTo([val.lat, val.lon], 13, {
        animate: true,
        duration: 1.2
      })
    }
  }, { immediate: true })
  watch(() => props.placeCenter, (val) => {
    if (val && val.lat && val.lon && map.value) {
      map.value.flyTo([val.lat, val.lon], 13, {
        animate: true,
        duration: 1.2
      })
    }
  }, { immediate: true })
  watch(() => props.activePlaceId, (val) => {
    if (val && leafletMarkers.value[val]) {
      const marker = leafletMarkers.value[val]
      const latlng = marker.getLatLng()
      map.value.flyTo([latlng.lat, latlng.lng], 13, {
        animate: true,
        duration: 1.2
      })
      marker.openPopup()
      drawRoute(props.userCenter, { lat: latlng.lat, lon: latlng.lng })
    }
  })
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