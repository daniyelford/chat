<template>
  <div class="place-item">
    <h4>{{ props.place.title }}</h4>
    <MediaSlider v-if="props.place.medias?.length > 0" :medias="props.place.medias" />
    <small class="cat" v-for="category in props.place.categories" :key="category.id">{{ category.title }}</small>
    <p>{{ truncateText(place.description) }}</p>
    <small>{{ truncateText(place.addresses?.[0]?.address) || '' }}</small>
    <div class="actions">
      <a v-if="props.place.addresses?.[0]?.lat && props.place.addresses?.[0]?.lon" @click="$emit('openMap', place)">محل دقیق</a>
      <a v-if="props.highRule" @click="$emit('editPlace', place)">ویرایش</a>
      <a v-if="props.highRule" @click="$emit('deletePlace', place.id)">حذف</a>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import MediaSlider from '@/components/tooles/media/MediaSlider.vue'

const props = defineProps({
  place: Object,
  highRule: Boolean,
})

const truncateText = (text, max = 50) => {
  if (!text) return ''
  return text.length > max ? text.slice(0, max) + '...' : text
}
</script>

<style scoped>
.actions{
  display: flex;
  justify-content: center;
  gap: 2px;
}
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
  width: 100%;
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