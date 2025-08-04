<template>
  <div style="direction: rtl; text-align: center; max-width: 250px;">
    <h4 style="margin-bottom: 5px;">{{ props.place.title }}</h4>
    <MediaSlider :medias="props.place.medias || []" />
    <ul v-if="props.place.categories?.length"
        style="display:flex;justify-content: center;margin: 5px 0; padding: 0; list-style: none;">
      <li v-for="cat in props.place.categories" :key="cat.id"
          style="background: #f3f3f3; margin: 2px 0; padding: 3px 5px; border-radius: 4px; font-size: 11px;">
        {{ cat.title }}
      </li>
    </ul>
    <p style="margin: 5px 0;">{{ truncateText(props.place.description) }}</p>
    <small>{{ props.place.address }}</small>
  </div>
</template>
<script setup>
    import { defineProps } from 'vue'
    import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
    const props = defineProps({
        place: { type: Object, required: true }
    })
    const truncateText = (text, max = 50) => {
    if (!text) return ''
        return text.length > max ? text.slice(0, max) + '...' : text
    }
</script>