<template>
    <StaticMapWithMarkers
  :center="{ lat: 35.6892, lon: 51.3890 }"
  :markers="[
    { lat: 35.7, lon: 51.4 },
    { lat: 35.68, lon: 51.41 }
  ]"
/>

</template>
<script setup>
    import StaticMapWithMarkers from '@/components/tooles/places/StaticMapWithMarkers.vue';
</script>