<template>
  <div v-if="store.loading" class="loading">در حال بارگذاری...</div>
  <div v-else-if="items.length" class="cartable-inner">
    <div
      v-for="item in items" :key="item.id" class="cartable">
      <div class="user-info">
        <img v-if="item.news.news_user_image_url" :src="item.news.news_user_image_url" class="user-avatar" />
        <svg v-else class="user-avatar" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z" />
        </svg>
        <strong>{{ item.news.news_user_name }} {{ item.news.news_user_family }}</strong>
        <a v-if="item.news.news_user_phone" :href="`tel:${item.news.news_user_phone}`">📞</a>
      </div>
      <MediaSlider v-if="item.news.media.length" :medias="item.news.media" />
      <p class="news-description">{{ item.news.description }}</p>
      <div class="address" v-if="item.news.address?.address">
        موقعیت: {{ item.news.address.address }}
      </div>
      <RouterLink class="send" v-if="item.id" :to="{ path: `/show-news/${item.id}` }">
        مشاهده
      </RouterLink>
      <div class="report-list">
        <h3>گزارش‌ها:</h3>
        <div v-for="report in item.reports" :key="report.id" class="report-item">
          <div class="user-info">
            <img v-if="report.user.image" :src="report.user.image" class="user-avatar" />
            <svg v-else class="user-avatar" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z" />
            </svg>
            <strong>{{ report.user.name }} {{ report.user.family }}</strong>
            <a v-if="report.user.phone" :href="`tel:${report.user.phone}`">📞</a>
          </div>
          <MediaSlider v-if="report.media.length" :medias="report.media" />
          <p>{{ report.description || 'در حال بررسی ...' }}</p>
          <RouterLink class="send" v-if="report.id" :to="{ path: `/show-cartable/${report.id}` }">
            مشاهده گزارش
          </RouterLink>
        </div>
      </div>
    </div>
    <div v-if="canLoadMore" ref="loadMoreTrigger" class="load-trigger">
      در حال بارگذاری موارد بیشتر...
    </div>
  </div>
  <div v-else class="no-data">
    شما هیچ گزارشی در کارتابل خود ندارید
  </div>
</template>
<script setup>
  import {  computed } from 'vue'
  import { useCartableStore } from '@/stores/cartable'
  import { usePollingWithCompare } from '@/composables/usePollingWithCompare'
  import { useInfiniteScroll } from '@/composables/useInfiniteScroll'
  import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
  const store = useCartableStore()
  const {
    items,
    canLoadMore,
    loadMoreTrigger,
    setupObserver
  } = useInfiniteScroll(async ({ limit, offset }) => {
    if (!store.allItems.length) await store.fetchCartables()
    return store.allItems.slice(offset, offset + limit)
  }, {
    limit: 5,
    total: computed(() => store.allItems.length)
  })
  setupObserver()
  usePollingWithCompare(store.fetchCartables, {
    intervalMs: 10000,
    runOnStart: true,
    isDifferent: (oldList, newList) => {
      return JSON.stringify(store.simplifyNews(oldList)) !== JSON.stringify(store.simplifyNews(newList))
    },
    onChange: (newData) => {
      store.allItems = newData
    }
  })
</script>
<style scoped>
  .loading {
    font-size: 1.1rem;
    padding: 2rem;
    text-align: center;
    color: #555;
  }
  .no-data{
    padding: 20px;
    text-align: center;
    background: #9a0000;
    border-radius: 10px;
    box-shadow: 0 0 10px grey;
    color: white;
    font-size: larger;
    font-weight: bold;
  }
  .cartable-inner {
    direction: rtl;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  .cartable {
    background-color: #ffffff;
    padding: 1rem 1.2rem;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
  }
  .user-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
  }
  .user-info div {
    display: flex;
    gap: 0.75rem;
    align-items: center;
  }
  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ccc;
  }
  .user-info svg {
    width: 20px;
    height: 20px;
  }
  .user-info strong {
    font-weight: bold;
    font-size: 1rem;
    color: #333;
  }
  .user-info small {
    color: #666;
    font-size: 0.9rem;
  }
  .news-description {
    font-size: 1rem;
    margin: 0.5rem 0;
    color: #444;
  }
  .address {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 0.5rem;
    word-break: break-all;
  }
  button {
    display: block;
    margin: 1rem auto 0;
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 0.6rem 1.4rem;
    font-size: 0.95rem;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  button:hover {
    background-color: #0056b3;
  }
  .send {
    display: inline-block;
    width: 100%;
    margin-top: .5rem;
    padding: 10px;
    text-decoration: none;
    background: #007bff;
    font-weight: 500;
    border-radius: 10px;
    text-align: center;
    color: white;
  }
  .send:hover {
    opacity: 0.6;
  }
  .report-item {
    margin-top: 15px;
  }
</style>