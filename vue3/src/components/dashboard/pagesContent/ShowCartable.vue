<template>
  <div v-if="!item">در حال بارگذاری یا موردی یافت نشد</div>
  <div v-else class="cartable-details">
    <section class="news-section">
      <div class="news-header">
        <div v-if="item.has_rule" class="user-info over-news">
          <img v-if="item.news.news_user_image_url" :src="item.news.news_user_image_url" class="avatar" />
          <svg v-else class="avatar" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z" />
          </svg>
          <div class="user">
            <strong>{{ item.news.news_user_name }} {{ item.news.news_user_family }}</strong>
            <a v-if="item.news.news_user_phone" :href="`tel:${item.news.news_user_phone}`">
              📞
            </a>
          </div>
        </div>
      </div>
      <MediaSlider v-if="item.news.media?.length" :medias="item.news.media" />
      <h3 style="margin: 10px;">{{ item.news.description }}</h3>
      <div class="address" v-if="item.news.address?.address">
        {{ item.news.address.address }}
      </div>
    </section>
    <button class="add" v-if="item.has_rule" @click="openModal">✏️</button>
    <h2>پیوست</h2>
    <section class="report-section">
      <div class="report-header">
        <div v-if="!item.has_rule" class="user-info">
          <img v-if="item.user?.image" :src="item.user.image" class="avatar" />
          <svg v-else class="avatar" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z" />
          </svg>
          <div class="user">
            <strong>{{ item.user.name }} {{ item.user.family }}</strong>
            <a v-if="item.user?.phone" :href="`tel:${item.user.phone}`">📞</a>
          </div>
        </div>
      </div>
      <div class="report-content">
        <MediaSlider v-if="item.report?.media?.length" :medias="item.report.media" />
        <h3>{{ item.report?.description || 'بدون پیوست' }}</h3>
        <div class="times">
          <small>ثبت: {{ moment(item.report?.created_at).format('jYYYY/jMM/jDD') }}</small>
          <small>اجرا: {{ moment(item.report?.run_time).format('jYYYY/jMM/jDD') }}</small>
        </div>
      </div>
    </section>
    <div v-if="isModalOpen" class="modal-overlay">
      <div class="modal">
        <h3>پیوست گزارش</h3>
        <UploaderManyMedia 
          :url="'report_media/'"
          :toAction="'report'"
          v-model="item.report.media"
          @done="handleUploadResult" 
        />
        <form @submit.prevent="submitForm">
          <textarea v-model="description" rows="4" placeholder="توضیحات..." />
          <div class="modal-actions">
            <button type="submit">ثبت</button>
            <button @click.prevent="isModalOpen = false">بستن</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script setup>
  import { ref, defineProps, watch } from 'vue'
  import { useCartableStore } from '@/stores/cartable'
  import { usePollingWithCompare } from '@/composables/usePollingWithCompare'
  import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
  import UploaderManyMedia from '@/components/tooles/upload/UploaderManyMedia.vue'
  import moment from 'moment-jalaali'
  const props = defineProps({ id: Number })
  const store = useCartableStore()
  const item = ref(null)
  const isModalOpen = ref(false)
  const description = ref('')
  const mediaIds = ref([])
  const fetchSingleItem = async () => {
    return await store.getCartableById(props.id)
  }
  usePollingWithCompare(fetchSingleItem, {
    intervalMs: 26000,
    runOnStart: true,
    onChange: (data) => {
      item.value = data
    }
  })
  const openModal = () => {
    if (item.value) {
      description.value = item.value.report?.description || ''
      mediaIds.value = item.value.report?.media?.map(m => m.id) || []
      isModalOpen.value = true
    }
  }
  const handleUploadResult = (uploaded) => {
    mediaIds.value = uploaded.map(m => m.id)
  }
  const submitForm = async () => {
    const success = await store.updateReport(props.id, description.value, mediaIds.value)
    if (success) {
      item.value = await store.getCartableById(props.id)
      isModalOpen.value = false
    }
  }
  watch(() => props.id, async () => {
    item.value = await fetchSingleItem()
  })
</script>
<style scoped>
  .add{
    background: #9cf0a4;
    outline: none;
    padding: 10px;
    border: none;
    border-radius: 5px;
    float: left;
  }
  .address{
    margin: 0 10px 20px 10px;
    word-break: break-all;
  }
  .user,.times{
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
    align-items: center;
  }
  .times{
    flex-direction: row;
  }
  .user{
    width: 100%;
  }
  .cartable-details {
    padding: 1rem;
    direction: rtl;
  }
  .news-section, .report-section {
    margin-bottom: 2rem;
    border: 1px solid #eee;
    border-radius: 10px;
    overflow: hidden;
  }
  .news-header,.report-header {
    background: rgb(255, 242, 218);
    padding: 10px;
    margin-bottom: 0.7rem;
  }
  .report-content {
    padding: 0 10px 15px;
  }
  .user-info {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }
  .modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .modal {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    max-height: 400px;
    overflow-y: auto;
  }
  .modal-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
  }
  .modal-actions button {
    width: 100%;
    font-size: 16px;
    padding: 7px;
    margin: 0 5px;
    border-radius: 5px;
    outline: none;
    border: none;
    color: white;
    background-color: rgb(117, 4, 4);
  }
  .modal-actions button:first-child{
    background-color: rgb(4, 88, 4);
  }
  textarea {
    width: 100%;
    margin-top: 1rem;
    outline: none;
    padding: 10px;
    border-radius: 10px;
    box-sizing: border-box;
  }
</style>