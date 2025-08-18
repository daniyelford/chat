<template>
    <div class="map-wrapper">
        <div id="map" class="map"></div>
    </div>
</template>
<script setup>
    import { onMounted , ref , defineEmits , defineProps } from 'vue'
    import { BASE_URL } from '@/config'
    import L from 'leaflet'
    import 'leaflet/dist/leaflet.css'
    const emit = defineEmits(['pick'])
    const map = ref(null)
    const markerIcon= BASE_URL+'/assets/images/marker.svg'
    const marker = ref(null)
    const props = defineProps({
        center: Object,
        editMarker: Object,
    })
    onMounted(() => {
        map.value = L.map('map').setView([35.6892, 51.3890], 13)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map.value)
         setTimeout(() => {
            map.value.invalidateSize()
        }, 300)
        if (navigator.geolocation) {
            navigator.permissions.query({ name: "geolocation" }).then((result) => {    
            if (result.state === "granted") {
                navigator.geolocation.watchPosition((pos) => {
                    map.value.flyTo([pos.coords.latitude, pos.coords.longitude], 13, {
                        animate: true,
                        duration: 1.2,
                    })
                }, (err) => {
                    console.error("خطا در دریافت موقعیت:", err)
                    if (props.center?.lat && props.center?.lon) {
                       map.value.flyTo([props.center.lat, props.center.lon], 13, {
                           animate: true,
                           duration: 1.2,
                       })
                   }
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 0
                })
            }})
        }
        if (marker.value && map.value.hasLayer(marker.value)){
            map.value.removeLayer(marker.value)
        }
        if(props.editMarker?.lat && (props.editMarker.lng || props.editMarker.lon)){
            const lon= props.editMarker.lon ?? props.editMarker.lng
            marker.value = L.marker([props.editMarker?.lat, lon], {
                icon: L.icon({
                    iconUrl: markerIcon,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                }),
            }).addTo(map.value)
        }
        map.value.on('click', (e) => {
            const { lat, lng } = e.latlng
            if (marker.value && map.value.hasLayer(marker.value)){
                map.value.removeLayer(marker.value)
            }
            marker.value = L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: markerIcon,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                })
            }).addTo(map.value)
            emit('pick', { lat, lng })
        })
    })
</script>
<style scoped>
    .map-wrapper {
        width: 65%;
        height: 300px;
        display: inline-block;
        margin-top: 1rem;
    }
    .map {
        width: 100%;
        height: 100%;
        border-radius: 8px;
        z-index: 9;
    }
    @media screen and (max-width: 600px) {
        .map-wrapper {
            width: 100%;
            height: 200px;
        }
    }
</style>