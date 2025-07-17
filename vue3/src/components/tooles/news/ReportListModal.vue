<template>
  <div v-if="show" class="modal-overlay">
    <div class="modal-content">
      <button @click="$emit('close')" class="modal-close">×</button>
      <div class="modal-item user" v-if="props.event?.news?.user_name || props.event?.news?.user_family || props.event?.news?.user_image_url">
        <div class="user-info">
          <img
            v-if="props.event?.news?.user_image_url"
            :src="props.event?.news?.user_image_url"
            alt="عکس گزارش‌دهنده"
            class="media-img"
          />
          <svg v-else class="media-img" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/>
          </svg>
          <span>
            {{ [props.event?.news?.user_name, props.event?.news?.user_family].filter(Boolean).join(' ') || '---' }}
          </span>
          <a v-if="props.event?.news?.user_phone" :href="'tel:'+props.event?.news?.user_phone">
            📞
          </a>
        </div>
      </div>
      <div class="modal-item" v-if="props.event?.news?.news_media?.length">
        <MediaSlider :medias="props.event.news.news_media" />
      </div>      
      <div class="modal-item" v-if="props.event?.news?.category?.length">
        <span v-for="cat in props.event.news.category" :key="cat.id" class="tag">{{ cat.title }}</span>
      </div>
      <div class="modal-item">
        <p>{{ props.event?.news?.description || '' }}</p>
        <p>{{ props.event?.news?.address?.address || '' }}</p>
        <p>{{ getStatus(props.event?.news?.status) }}</p>
      </div>
      <div v-if="props.event?.report">
        <div v-if="props.event?.report?.reporter" class="report">
          <img v-if="props.event?.report?.reporter?.user_image_url" :src="props.event?.report?.reporter?.user_image_url">
          <svg v-else class="media-img" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/>
          </svg>
          <span>
            {{ props.event?.report?.reporter?.name || '' }} {{ props.event?.report?.reporter?.family || '' }}
          </span>
          <a v-if="props.event?.report?.reporter?.phone" :href="'tel:'+props.event?.report?.reporter?.phone">
            📞
          </a>
        </div>
        <div class="modal-item" v-if="props.event?.report?.report_media?.length">
          <MediaSlider :medias="props.event.report.report_media" />
        </div>
        <RouterLink v-if="props.event?.report?.id" :to="{ path: `/show-cartable/${props.event.report.id}` }" class="done">
          بررسی
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'
import MediaSlider from '@/components/tooles/media/MediaSlider.vue'

const props = defineProps({
  show: Boolean,
  event: Object
})
defineEmits(['close'])

const getStatus = (status) => {
  switch (status) {
    case 'seen': return 'در حال پیگیری'
    case 'checking': return 'در حال بررسی'
    case 'done': return 'انجام شده'
    default: return 'نامشخص'
  }
}
</script>
<style scoped>
  .done {
    padding: 10px;
    background: #7fffd4;
    color: black;
    text-decoration: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 15px;
    font-weight: 700;
    display: inline-block;
    text-align: center;
    box-sizing: border-box;
    width: 100%;
  }
  .user-info{
    display: flex;
    align-items: center;
    gap: 25px;
  }
  .tooles{
    width: 25px;
    height: 25px;
  }
  .user{
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: nowrap;
  }
  .modal-overlay {
    inset: 0;
    z-index: 9999;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
  }
  .modal-content {
    border-radius: 1rem;
    max-width: 600px;
    width: 80%;
    direction: rtl;
    padding: 1.5rem;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    background-color: #fff;
  }
  p {
    word-break: break-all;
  }
  .modal-title {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    font-weight: bold;
  }
  .modal-close {
    top: 0.75rem;
    left: 0.75rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #666;
    cursor: pointer;
    position: absolute;
  }
  .modal-close:hover {
    color: #e53935;
  }
  .modal-item {
    margin-bottom: 0.75rem;
    direction: rtl;
  }
  .media-container {
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
    display: flex;
  }
  .media-img {
    height: 50px;
    border-radius: 50px;
    object-fit: cover;
    border: 1px solid #ddd;
    width: 50px;
  }
  .report{
    display: flex;
    align-items: center;
    gap: 20px;
    flex-direction: row-reverse;
    margin: 10px 0;
  }
</style>