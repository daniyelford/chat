<template>
  <div>
    <!-- حالت بارگذاری اولیه -->
    <div class="loading" v-if="!newsStore.isLoaded">
      در حال بارگذاری...
    </div>

    <!-- نمایش کارت‌ها -->
    <div class="card-inner" v-else-if="visibleNews.length > 0">
      <div v-for="card in visibleNews" :key="card.id" class="card">
        
        <!-- اطلاعات کاربر -->
        <div class="user-info">
          <img v-if="card.user?.image" :src="card.user.image" alt="user" />
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></g></svg>
          <p v-if="card.user?.name || card.user?.family">
            {{ card.user.name }} {{ card.user.family }}
          </p>
        </div>

        <!-- مدیا -->
        <div class="media-inner">
          <MediaSlider v-if="card.medias.length > 0" :medias="card.medias" />
        </div>

        <!-- دسته‌بندی -->
        <div class="card-category" v-if="!newsStore.hasRule && card.category.length">
          <span class="category" v-for="category in card.category" :key="category.id">
            {{ category.title }}
          </span>
        </div>

        <!-- توضیحات -->
        <div class="description" v-if="card.description">
          {{ card.description }}
        </div>

        <!-- موقعیت مکانی -->
        <div class="location" v-if="card.location?.city">
          📍 {{ card.location.city }}
        </div>

        <!-- زمان ایجاد -->
        <div class="time">
          {{ moment(card.created_at).format('jYYYY/jMM/jDD') }}
        </div>

        <!-- دکمه مشاهده -->
        <RouterLink class="c-d" :to="{ path: `/show-news/${card.id}` }">
          مشاهده
        </RouterLink>

        <!-- دکمه ثبت در تقویم -->
        <a
          class="choose"
          v-if="newsStore.hasRule"
          @click="openCalendarModal(card.id)"
        >
          بررسی
        </a>
      </div>
      <div ref="loadMoreTrigger" class="scroll-trigger"></div>
    </div>
    <div v-else class="none-cart-error">
      خبری در محدوده شما وجود ندارد
    </div>
    <div v-if="toastMsg" class="toast">
      {{ toastMsg }}
    </div>
    <AddNewsForm />
    <CalendarModal
      v-if="showModal"
      @close="showModal = false"
      @submit="onCalendarSubmit"
    />
  </div>
</template>
<script setup>
    import { ref, onMounted , computed } from 'vue'
    import { useNewsStore } from '@/stores/news'
    import moment from 'moment-jalaali'
    import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
    import CalendarModal from '@/components/tooles/news/CalendarModal.vue'
    import { usePollingWithCompare } from '@/composables/usePollingWithCompare'
    import { useInfiniteScroll } from '@/composables/useInfiniteScroll'
    import AddNewsForm from '@/components/dashboard/pagesContent/AddNewsForm.vue'
    import router from '@/router'
    const newsStore = useNewsStore()
    const toastMsg = ref('')
    const selectedNewsId = ref(null)
    const showModal = ref(false)
    function showToast(msg) {
        toastMsg.value = msg
        setTimeout(() => (toastMsg.value = ''), 3000)
    }

    // 📌 Composable: fetch با limit و offset
    const fetchNews = async ({ limit, offset }) => {
      const res = await newsStore.fetchNews({ limit, offset, append: true })
      return Array.isArray(res) ? res : []
    }


    // 📌 استفاده از useInfiniteScroll
    const total = computed(() => newsStore.total)
    const {
        items: visibleNews,
        loadMoreTrigger,
        setupObserver,
    } = useInfiniteScroll(fetchNews, { limit: 10,total: total, immediate: true })

    // 📌 استفاده از usePollingWithCompare
    usePollingWithCompare( 
        () => newsStore.fetchLatestNewsRaw(5, 0), {
        intervalMs: 6000,
        isDifferent: (oldData, newData) => {
            const oldIds = (oldData || []).map(i => i.id)
            const newIds = (newData || []).map(i => i.id)
            return JSON.stringify(oldIds) !== JSON.stringify(newIds)
        },
        onChange: (newCards) => {
            const filtered = newCards.filter(item => !newsStore.cards.some(card => card.id === item.id))
            if (filtered.length) {
                newsStore.cards = [...filtered, ...newsStore.cards]
                newsStore.total += filtered.length
                showToast('خبر جدید رسید!')
            }
        }
    })

    // 📌 Modal کنترل
    function openCalendarModal(id) {
        selectedNewsId.value = id
        showModal.value = true
    }
    async function onCalendarSubmit({ date }) {
        const jsDate = date ? moment(date, 'jYYYY/jMM/jDD').toDate() : null
        const success = await newsStore.scheduleNewsRunTime(selectedNewsId.value, jsDate)
        if (success) {
            showModal.value = false
            await newsStore.fetchNews()
            router.push({ path:'/cartable' })
        } else {
            alert('خطا در ثبت تاریخ اجرا')
        }
    }
    onMounted(() => {
      setTimeout(() => {
        setupObserver()
      }, 100)
    })
</script>
<style scoped>
    .scroll-trigger {
        height: 1px;
    }
    .toast {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: #2ecc71;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }
    .loading,.none-cart-error{
        text-align: center;
        padding: 20px;
        color: white;
        font-size: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px grey;
        font-weight: bold;
    }
    .loading{
        background: rgb(16, 16, 143);
    }
    .none-cart-error {
        background: rgb(128, 12, 12);
    }
    .media-inner {
        background: white;
        padding: 5px;
    }
    .location {
        margin: 5px;
        gap: 5px;
        display: flex;
        font-size: 11px;
        align-items: center;
    }
    .location svg{
        height: 20px;
        width: 20px;
    }
    .user-info svg,.user-info img{
        width: 50px;
        height: 50px;
        border-radius: 50px;
        display: inline-block;
    }
    .user-info p{
        display: inline-block;
    }
    .user-info{
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
    }
    .card-category {
        text-align: center;
        padding: 10px;
        background: floralwhite;
    }
    .card-inner {
        width: 100%;
        direction: rtl;
        height: 100%;
        display: flex;
        flex-direction: row-reverse;
        flex-wrap: wrap;
        align-content: flex-start;
        justify-content: center;
        align-items: stretch;
    }
    .card {
        background: #f5f5f5;
        width: 49%;
        min-height: 300px;
        margin: 0 0.5% 10px 0.5%;
        border-radius: 10px;
        box-shadow: 0 0 5px grey;
    }
    .media {
        height: 150px;
        width: auto;
        margin: auto;
    }
    .media img,
    .media video {
        width: 100%;
        height: 100%;
    }
    .card-header {
        display: flex;
        flex-direction: row-reverse;
        flex-wrap: nowrap;
        justify-content: space-between;
        align-items: stretch;
        padding: 6px;
    }
    .description {
        min-height: 50px;
        padding: 10px;
        width: 95%;
        text-align: center;
        word-wrap: break-word;
    }
    .time {
        font-size: 10px;
        padding-left: 10px;
        text-align: end;
        margin: 5px;
    }
    .choose ,.c-d {
        width: 95%;
        /* height: 30px; */
        background: rgb(7, 71, 11);
        margin: 5px auto;
        text-align: center;
        border-radius: 10px;
        color: white;
        display: block;
        padding: 10px;
        box-sizing: border-box;
    }
    .c-d {
        background: #071847;
        text-decoration: none;
    }
    @media screen and (max-width: 600px) {
        .card {
            width: 99%;
        }
    }
</style>
