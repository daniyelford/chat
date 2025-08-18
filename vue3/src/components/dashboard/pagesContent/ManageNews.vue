<template>
  <div class="news-wrapper">
    <div v-if="store.loading" class="loading">
      <div class="tiny-loader"></div>
    </div>
    <div v-else>
      <div v-if="store.newsList.length === 0" class="no-news">
        شما هنوز خبری ثبت نکردید.
      </div>
      <ul class="news-list">
        <li
          v-for="news in store.newsList"
          :key="news.id"
          v-memo="[news.show_status]"
          class="news-item">
          <div class="media-list" v-if="news.media?.length">
            <MediaSlider :medias="news.media" />
          </div>
          <div class="news-header">
            <p v-if="news.category?.length" class="category">
              <span
                v-for="cat in news.category"
                :key="cat.id"
                class="category-item"
              >
                {{ cat.title }}
              </span>
            </p>
          </div>
          <h3 class="news-title">{{ news.description }}</h3>
          <p v-if="news.city || news.address" class="address">
            موقعیت: {{ news.city || '' }} - {{ news.address || '' }}
          </p>
          <small v-if="news.status==='checking'" class="news-status" style="display: flex;color: red;align-items: center;justify-content: center;">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="25px" viewBox="0,0,256,256"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(2.56,2.56)"><path d="M65.253,58.161c0,0 -4.973,-4.754 -6.503,-6.215c-1.53,-1.461 0,-2.969 0,-2.969c0,0 2.24,-2.141 6.976,-6.668c4.736,-4.527 5.502,-11.121 5.502,-11.121l0.002,-8.519l-21.001,-1.748l-21.001,1.749v8.519c0,0 0.724,6.594 5.46,11.121c4.736,4.527 6.976,6.668 6.976,6.668c0,0 1.53,1.508 0,2.969c-1.53,1.461 -6.503,6.215 -6.503,6.215c0,0 -5.015,4.754 -4.934,12.338v8.461h19.979h20.021v-8.461c0.041,-7.585 -4.974,-12.339 -4.974,-12.339z" fill="#d0cfb7"></path><path d="M70.228,79.961h-40c-0.553,0 -1,-0.448 -1,-1v-8.461c-0.086,-7.919 5.028,-12.858 5.246,-13.064l6.5,-6.213c0.363,-0.348 0.368,-0.6 0.37,-0.683c0.007,-0.368 -0.278,-0.744 -0.39,-0.858l-6.958,-6.649c-4.941,-4.725 -5.731,-11.451 -5.763,-11.735c-0.004,-0.037 -0.006,-0.073 -0.006,-0.109v-8.519c0,-0.52 0.398,-0.954 0.917,-0.997l21.001,-1.748c0.055,-0.005 0.111,-0.005 0.166,0l21.001,1.748c0.519,0.043 0.917,0.477 0.917,0.997l-0.002,8.519c0,0.039 -0.002,0.077 -0.007,0.115c-0.033,0.284 -0.864,7.007 -5.804,11.729l-6.975,6.667c-0.093,0.096 -0.378,0.472 -0.371,0.84c0.002,0.083 0.007,0.335 0.37,0.683l6.504,6.216c0.259,0.247 5.326,5.175 5.284,13.066v8.456c0,0.552 -0.447,1 -1,1zM31.228,77.961h38v-7.461c0.037,-7.075 -4.616,-11.568 -4.663,-11.613c-0.002,-0.001 -0.003,-0.002 -0.004,-0.003l-6.502,-6.215c-0.81,-0.773 -0.985,-1.58 -0.989,-2.121c-0.01,-1.272 0.877,-2.184 0.978,-2.283l6.987,-6.679c4.194,-4.008 5.108,-9.855 5.193,-10.463l0.002,-7.533l-20.001,-1.665l-20.001,1.665v7.536c0.079,0.592 0.954,6.448 5.151,10.46l6.977,6.667c0.111,0.111 0.998,1.022 0.988,2.294c-0.004,0.541 -0.18,1.347 -0.989,2.121l-6.502,6.215c-0.049,0.047 -4.701,4.574 -4.625,11.605z" fill="#000000"></path><path d="M69.184,77.461l-0.349,-0.285l-17.107,-13.977v-17.827c-0.034,-4.133 2.427,-6.743 3.327,-7.581l8.859,-8.006c3.102,-2.802 3.149,-7.324 3.151,-7.324h-34.098c0,0.044 0.11,4.575 3.151,7.324l8.955,8.094c0.805,0.75 2.69,3.359 2.656,7.493l-0.053,17.816l-16.828,14.273z" fill="#ebe5b1"></path><path d="M69.184,77.961h-38.336c-0.21,0 -0.397,-0.131 -0.47,-0.328c-0.073,-0.197 -0.014,-0.418 0.146,-0.554l16.651,-14.123l0.052,-17.586c0.031,-3.767 -1.62,-6.308 -2.497,-7.126l-8.948,-8.089c-3.227,-2.917 -3.316,-7.648 -3.316,-7.695c0,-0.276 0.224,-0.5 0.5,-0.5h34.099c0.276,0 0.5,0.224 0.5,0.5c0,0.007 0,0.014 0,0.021c-0.015,0.512 -0.229,4.885 -3.315,7.674l-8.859,8.006c-0.957,0.891 -3.193,3.388 -3.162,7.206v17.594l17.272,14.112c0.164,0.134 0.226,0.356 0.154,0.555c-0.07,0.2 -0.259,0.333 -0.471,0.333zM32.211,76.961h35.571l-16.37,-13.375c-0.116,-0.095 -0.184,-0.237 -0.184,-0.387v-17.827c-0.035,-4.211 2.427,-6.96 3.486,-7.947l8.865,-8.011c2.315,-2.091 2.836,-5.205 2.952,-6.453h-33.028c0.124,1.217 0.661,4.383 2.95,6.453l8.954,8.094c1.067,0.995 2.855,3.764 2.821,7.868l-0.052,17.814c0,0.146 -0.065,0.285 -0.177,0.38z" fill="#000000"></path><path d="M71.362,23.961h-43.267c-2.677,0 -4.866,-2.19 -4.866,-4.866v-2.267c0,-2.677 2.19,-4.866 4.866,-4.866h43.267c2.677,0 4.866,2.19 4.866,4.866v2.267c0,2.676 -2.19,4.866 -4.866,4.866z" fill="#806349"></path><path d="M71.362,24.961h-43.267c-3.234,0 -5.866,-2.631 -5.866,-5.866v-2.268c0,-3.235 2.632,-5.866 5.866,-5.866h43.268c3.234,0 5.866,2.631 5.866,5.866v2.268c-0.001,3.234 -2.632,5.866 -5.867,5.866zM28.095,12.961c-2.132,0 -3.866,1.734 -3.866,3.866v2.268c0,2.132 1.734,3.866 3.866,3.866h43.268c2.132,0 3.866,-1.734 3.866,-3.866v-2.268c0,-2.132 -1.734,-3.866 -3.866,-3.866z" fill="#000000"></path><path d="M72.362,86.961h-44.267c-2.677,0 -4.866,-2.19 -4.866,-4.866v-2.267c0,-2.677 2.19,-4.866 4.866,-4.866h44.267c2.677,0 4.866,2.19 4.866,4.866v2.267c0,2.676 -2.19,4.866 -4.866,4.866z" fill="#806349"></path><path d="M72.362,87.961h-44.267c-3.234,0 -5.866,-2.631 -5.866,-5.866v-2.268c0,-3.235 2.632,-5.866 5.866,-5.866h44.268c3.234,0 5.866,2.631 5.866,5.866v2.268c-0.001,3.234 -2.632,5.866 -5.867,5.866zM28.095,75.961c-2.132,0 -3.866,1.734 -3.866,3.866v2.268c0,2.132 1.734,3.866 3.866,3.866h44.268c2.132,0 3.866,-1.734 3.866,-3.866v-2.268c0,-2.132 -1.734,-3.866 -3.866,-3.866zM71.728,21.961c-0.276,0 -0.5,-0.224 -0.5,-0.5v-3.103c0,-1.322 -1.21,-2.397 -2.697,-2.397h-17.803c-0.276,0 -0.5,-0.224 -0.5,-0.5c0,-0.276 0.224,-0.5 0.5,-0.5h17.803c2.039,0 3.697,1.524 3.697,3.397v3.103c0,0.276 -0.223,0.5 -0.5,0.5z" fill="#000000"></path><path d="M46.728,15.461h2" fill="#ffffff"></path><path d="M48.728,15.961h-2c-0.276,0 -0.5,-0.224 -0.5,-0.5c0,-0.276 0.224,-0.5 0.5,-0.5h2c0.276,0 0.5,0.224 0.5,0.5c0,0.276 -0.223,0.5 -0.5,0.5z" fill="#000000"></path><path d="M40.728,15.461h4" fill="#ffffff"></path><path d="M44.728,15.961h-4c-0.276,0 -0.5,-0.224 -0.5,-0.5c0,-0.276 0.224,-0.5 0.5,-0.5h4c0.276,0 0.5,0.224 0.5,0.5c0,0.276 -0.223,0.5 -0.5,0.5zM71.728,83.961h-31c-0.276,0 -0.5,-0.224 -0.5,-0.5c0,-0.276 0.224,-0.5 0.5,-0.5h31c0.276,0 0.5,0.224 0.5,0.5c0,0.276 -0.223,0.5 -0.5,0.5zM38.728,83.961h-4c-0.276,0 -0.5,-0.224 -0.5,-0.5c0,-0.276 0.224,-0.5 0.5,-0.5h4c0.276,0 0.5,0.224 0.5,0.5c0,0.276 -0.223,0.5 -0.5,0.5zM32.728,83.961h-3c-0.276,0 -0.5,-0.224 -0.5,-0.5c0,-0.276 0.224,-0.5 0.5,-0.5h3c0.276,0 0.5,0.224 0.5,0.5c0,0.276 -0.223,0.5 -0.5,0.5z" fill="#000000"></path></g></g></svg>
            در انتظار پیگیری
          </small>
          <small v-else-if="news.status==='seen'" class="news-status" style="color: green;">
            درحال پیگیری
          </small>
          <small v-else>

          </small>
          <div class="actions">
            <span v-if="user.status==='active'">
              <RouterLink class="c-d" :to="`/show-news/${news.id}`">
                مشاهده
              </RouterLink>
              <RouterLink
                v-if="news.status === 'seen' && news?.reportList?.[0]?.id"
                class="c-b"
                :to="`/show-cartable/${news.reportList[0].id}`">
                پیگیری
              </RouterLink>
              <button
                class="c-s"
                v-if="news.show_status === 'dont'"
                @click="restoreNews(news.id)">
                پخش مجدد
              </button>
              <button
                class="c-r"
                v-if="news.show_status === 'do'"
                @click="deleteNews(news.id)">
                عدم پخش
              </button>
            </span>
            <div v-else class="ban">
              <RouterLink class="c-d" :to="`/show-news/${news.id}`">
                مشاهده
              </RouterLink>
              <RouterLink
                v-if="news.status === 'seen' && news?.reportList?.[0]?.id"
                class="c-b"
                :to="`/show-cartable/${news.reportList[0].id}`">
                پیگیری
              </RouterLink>
              <br>
              اکانت شما
              <span v-if="user.banTime">
                از تاریخ
                {{ moment(user.banTime).format('jYYYY/jMM/jDD') }}
              </span>
              غیر فعال است
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
<script setup>
  import { onMounted, onUnmounted } from 'vue'
  import { useManageNewsStore } from '@/stores/manageNews'
  import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
  import moment from 'moment-jalaali'
  import { useUserStore } from '@/stores/user'
  const store = useManageNewsStore()
  const user = useUserStore()
  const restoreNews = async (id) => {
    const ok = await store.restoreNews(id)
    console.log(ok);
    if (ok) {
      store.toggleShowStatus(id)
    } 
  }
  const deleteNews = async (id) => {
    const ok = await store.deleteNews(id)
    console.log(ok);
    if (ok) {
      store.toggleShowStatus(id)
    }
  }
  const handleVisibilityChange = () => {
    if (document.visibilityState === 'visible') {
      store.startPolling()
    } else {
      store.stopPolling()
    }
  }
  onMounted(async () => {
    await user.fetchUserInfo()
  })
  onMounted(() => {
    document.addEventListener('visibilitychange', handleVisibilityChange)
    if (document.visibilityState === 'visible') {
      store.startPolling()
    }
  })
  onUnmounted(() => {
    document.removeEventListener('visibilitychange', handleVisibilityChange)
    store.stopPolling()
  })
</script>
<style scoped>
  .news-wrapper {
    max-width: 800px;
    margin: 0 auto;
    direction: rtl;
  }
  .title {
    font-size: 24px;
    margin-bottom: 1rem;
  }
  .loading,
  .no-news {
    font-style: italic;
    text-align: center;
    padding: 20px;
    font-weight: bold;
    color: #888;
    border-radius: 10px;
    box-shadow: 0 0 10px grey;
    background: #e0d4e3;
  }
  .news-list {
    list-style: none;
    padding: 0;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-items: center;
  }
  .news-item {
    width: 90%;
    box-sizing: border-box;
    border: none;
    border-radius: 30px;
    padding: 1rem;
    margin-bottom: 10px;
    margin-left: auto;
    margin-right: auto;
    background: wheat;
    box-shadow: 0 0 12px gray;
    text-align: center;
  }
  .news-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .news-title {
    margin: 0;
    word-break: break-all;
    font-size: 18px;
  }
  .news-status {
    font-size: 12px;
    color: #666;
  }
  .category {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-start;
    align-items: center;
    gap: 5px;
    margin-top: 0.5rem;
  }
  .category-item {
    background: #eef;
    padding: 2px 6px;
    margin-left: 4px;
    border-radius: 4px;
  }
  .address {
    font-size: 14px;
    color: #444;
    word-break: break-all;
  }
  .media-list {
    margin: 0.5rem auto;
  }
  .media-item {
    margin-right: 10px;
    margin-top: 10px;
  }
  .media-img {
    max-width: 120px;
    max-height: 80px;
    border-radius: 4px;
    object-fit: cover;
  }
  .media-link {
    display: inline-block;
    padding: 4px 8px;
    background: #ddd;
    border-radius: 4px;
    font-size: 13px;
    text-decoration: none;
    color: #000;
  }
  .ban{
    color: red;
  }
  .actions {
    display: flex;
    justify-content: space-around;
    margin: 1rem 0;
    align-items: center;
  }
  .actions button ,.c-b,.c-d{
    width: 150px;
    display: block;
    text-align: center;
    padding: 6px 12px;
    border: none;
    background: #4e3a85;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    margin-top: 10px;
    box-sizing: border-box;
  }
  .c-d {
    background: #14035e !important;
  }
  .c-r {
    background: #ac3427 !important;
  }
  .c-s {
    background: #29921b !important;
  }
  .actions button:hover {
    opacity: 0.9;
  }
    .tiny-loader {
    width: 20px;
    height: 20px;
    border: 2px solid #ccc;
    border-top-color: #333;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 10px auto;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>
