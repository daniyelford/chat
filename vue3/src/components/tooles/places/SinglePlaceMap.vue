<script setup>
  import { ref, onMounted, watch, createApp, defineProps, nextTick, onUnmounted } from 'vue'
  import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
  import PlacePopup from '@/components/tooles/places/PlacePopup.vue'
  import L from 'leaflet'
  import 'leaflet/dist/leaflet.css'
  import 'leaflet-routing-machine'
  import 'leaflet-routing-machine/dist/leaflet-routing-machine.css'
  import { BASE_URL, ORS_API_KEY } from '@/config'
  const props = defineProps({
    userCenter: { type: Object, required: true },
    place: { type: Object, required: true }
  })
  const mapId = `map-${Math.random().toString(36).substring(2, 10)}`
  const map = ref(null)
  const markerRef = ref(null)
  const routingControl = ref(null)
  const markerIcon = L.icon({
    iconUrl: BASE_URL + '/assets/images/marker.svg',
    iconSize: [32, 32],
    iconAnchor: [16, 32]
  })
  const userMarkerRef = ref(null)
  const userCircleRef = ref(null)
  let watchId = null
  function startLiveLocation() {
    if (!("geolocation" in navigator)) {
      console.warn("مرورگر از Geolocation پشتیبانی نمی‌کند.")
      return
    }
    navigator.permissions.query({ name: "geolocation" }).then((result) => {
      if (result.state === "granted") {
        watchId = navigator.geolocation.watchPosition(
          (pos) => {
            const lat = pos.coords.latitude
            const lon = pos.coords.longitude
            const acc = pos.coords.accuracy
            console.log('ok');
            if (!userMarkerRef.value) {
              userMarkerRef.value = L.marker([lat, lon], {
                icon: L.icon({
                  iconUrl: BASE_URL + "/assets/images/user-marker.svg",
                  iconSize: [24, 24],
                  iconAnchor: [12, 12]
                })
              }).addTo(map.value)
              userCircleRef.value = L.circle([lat, lon], {
                radius: acc,
                color: "#136aec",
                fillColor: "#136aec",
                fillOpacity: 0.2
              }).addTo(map.value)
              map.value.setView([lat, lon], 15)
            } else {
              userMarkerRef.value.setLatLng([lat, lon])
              userCircleRef.value.setLatLng([lat, lon])
              userCircleRef.value.setRadius(acc)
            }
            console.log('ok');
            drawRoute({ lat, lon }, props.place)
          },
          (err) => {
            console.warn("Geolocation error:", err)
          },
          { enableHighAccuracy: true }
        )
      }
    })
  }
  function createORSRouter(apiKey, profile = 'driving-car') {
    return {
      route(waypoints, callback, context) {
        const coordinates = waypoints.map(wp => {
          const latlng = wp.latLng || L.latLng(wp.lat, wp.lng)
          return [latlng.lng, latlng.lat]
        })
        if (coordinates.length < 2) {
          callback.call(context, new Error('مبدا و مقصد مشخص نیستند.'), [])
          return
        }
        fetch(`https://api.openrouteservice.org/v2/directions/${profile}/geojson`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': apiKey
          },
          body: JSON.stringify({ coordinates })
        })
        .then(res => res.json())
        .then(data => {
          const coords = data.features?.[0]?.geometry?.coordinates || []
          const summary = data.features?.[0]?.properties?.summary || {}
    
          if (!coords.length) {
            callback.call(context, new Error('مسیر یافت نشد.'), [])
            return
          }
          const latlngs = coords.map(([lng, lat]) => L.latLng(lat, lng))
          const inputWaypoints = waypoints.map(wp =>
            L.Routing.waypoint(wp.latLng || L.latLng(wp.lat, wp.lng))
          )
          const route = {
            name: '',
            instructions: [],
            coordinates: latlngs,
            summary: {
              totalDistance: summary.distance??0,
              totalTime: summary.duration??0
            },
            inputWaypoints,
            waypoints: inputWaypoints.map(w => w.latLng)
          }
    
          callback.call(context, null, [route])
        })
        .catch(err => {
          console.error('Routing error:', err)
          callback.call(context, err)
        })
      }
    }
  }
  function drawRoute(from, to) {
    if (!map.value || !from?.lat || !from?.lon || !to?.lat || !to?.lon) {
      console.warn('مختصات نامعتبر است.')
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
      addWaypoints: false,
      draggableWaypoints: false,
      routeWhileDragging: false,
      show: false,
      createMarker: () => null
    }).addTo(map.value)
  }
  function initMap() {
    if (map.value) return
    map.value = L.map(mapId).setView([props.place.lat, props.place.lon], 13)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map.value)
    const container = document.createElement('div')
    container.style.maxWidth = '250px'
    const app = createApp(PlacePopup, { place: props.place })
    app.component('MediaSlider', MediaSlider)
    app.mount(container)
    markerRef.value = L.marker([props.place.lat, props.place.lon], { icon: markerIcon })
      .addTo(map.value)
      .bindPopup(container)
      .openPopup()
    drawRoute(props.userCenter, props.place)
  }
  watch(
    () => props.userCenter,
    val => {
      if (map.value && val?.lat && val?.lon) {
        drawRoute(val, props.place)
      }
    },
    { immediate: true }
  )
  watch(
    () => props.place,
    val => {
      if (val?.lat && val?.lon && map.value) {
        map.value.setView([val.lat, val.lon], 13)
        if (markerRef.value) {
          markerRef.value.setLatLng([val.lat, val.lon])
        }
        drawRoute(props.userCenter, val)
      }
    },
    { immediate: true }
  )
  onMounted(async () => {
    await nextTick()
    const el = document.getElementById(mapId)
    if (!el) {
      console.warn('Map container not found:', mapId)
      return
    }
    initMap()
    startLiveLocation()
    onUnmounted(() => {
      if (watchId !== null) {
        navigator.geolocation.clearWatch(watchId)
      }
    })
  })
</script>
<template>
  <div :id="mapId" style="width: 100%; height: 540px;"></div>
</template>
<style scoped>
  .map-wrapper {
    width: 100%;
    height: 400px;
    position: relative;
  }
  .map {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    z-index: 1;
  }
</style>