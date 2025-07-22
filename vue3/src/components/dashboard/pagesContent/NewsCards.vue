<template>
  <div class="inner-posts">
    <div class="loading" v-if="!newsStore.isLoaded">
      در حال بارگذاری...
    </div>
    <div class="card-inner" v-else-if="newsStore.cards.length > 0">
      <div v-for="card in newsStore.cards" :key="card.id" class="card" :class="{ 'my-news': card.self }">
        <div class="user-info">
          <img v-if="card.user?.image" :src="card.user.image" alt="user" />
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></g></svg>
          <p v-if="card.user?.name || card.user?.family">
            {{ card.user.name }} {{ card.user.family }}
          </p>
        </div>
        <div class="media-inner">
          <MediaSlider v-if="card.medias.length > 0" :medias="card.medias" />
        </div>
        <div class="card-category" v-if="!newsStore.hasRule && card.category.length">
          <span class="category" v-for="category in card.category" :key="category.id">
            {{ category.title }}
          </span>
        </div>
        <div class="description" v-if="card.description">
          {{ card.description }}
        </div>
        <div class="location" v-if="card.location?.city">
          📍 {{ card.location.city }}
        </div>
        <div class="time">
          {{ moment(card.created_at).format('jYYYY/jMM/jDD') }}
        </div>
        <RouterLink class="c-d" :to="{ path: `/show-news/${card.id}` }">
          مشاهده
        </RouterLink>
        <a
          class="choose"
          v-if="newsStore.hasRule"
          @click="handleReply(card.id)"
        >
          پاسخ
        </a>
        <a
          class="choose"
          v-if="newsStore.hasRule"
          @click="openCalendarModal(card.id)"
        >
          قرار ملاقات
        </a>
        <div class="report-block" v-if="card.reports && card.reports.length">
          <h4>گزارش‌ها:</h4>
          <div class="single-report" v-for="report in card.reports" :key="report.id">
            <p>📄 {{ report.description }}</p>
            <p>📅 {{ moment(report.created_at).format('jYYYY/jMM/jDD HH:mm') }}</p>
            <p>🧑‍💼 گزارش‌دهنده: {{ report.reporter.name }} {{ report.reporter.family }}</p>
            <div class="media-inner" v-if="report.media.length">
              <MediaSlider :medias="report.media" />
            </div>
          </div>
        </div>

      </div>
      <div ref="loadMoreTrigger" class="scroll-trigger"></div>
    </div>
    <div v-else class="none-cart-error">
      خبری در محدوده شما وجود ندارد
    </div>
    <div v-if="toastMsg" class="toast">
      {{ toastMsg }}
    </div>
    <CalendarModal
    v-if="showModal"
    @close="showModal = false"
    @submit="onCalendarSubmit"
    />
  </div>
  <AddNewsForm :reply-to-id="replyToId" />
</template>
<script setup>
    import { ref, onMounted } from 'vue'
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
    const replyToId = ref(0)
    function handleReply(newsId) {
      replyToId.value = newsId
    }
    const fetchNews = async () => {
      const ok = await newsStore.fetchNews({
        limit: 10,
        offset: newsStore.cards.length,
        append: true,
      })
      if (ok) {
        return {
          items: newsStore.cards,
          has_more: newsStore.more,
        }
      }
      return {
        items: newsStore.cards,
        has_more: false,
      }
    }
    const {
        loadMoreTrigger,
        setupObserver,
    } = useInfiniteScroll(fetchNews, { limit: 10, immediate: true })
    usePollingWithCompare( 
      () => newsStore.fetchLatestNewsRaw(5, 0), {
      intervalMs: 6000,
      isDifferent: (oldData, newData) => {
        const oldIds = Array.isArray(oldData) ? oldData.map(i => i.id) : []
        const newIds = Array.isArray(newData) ? newData.map(i => i.id) : []
        return JSON.stringify(oldIds) !== JSON.stringify(newIds)
      },
      onChange: async (newCards) => {
        const filtered = newCards.filter(item => !newsStore.cards.some(card => card.id === item.id))
        if (filtered.length) {
          if (filtered.length > 10) {
            const scrollTop = window.scrollY
            await newsStore.fetchNews({ limit: 10, offset: 0, append: false })
            showToast('خبر جدید رسید!')
            window.scrollTo(0, scrollTop)
          } else {
            newsStore.cards = [...filtered, ...newsStore.cards]
            showToast('خبر جدید رسید!')
          }
        }
      }

    })
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
  .inner-posts {
    padding: 10px;
    position: fixed;
    top: 60px;
    bottom: 45px;
    overflow: auto;
    left: 0;
    right: 0;
  }
  .card-inner {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  .card {
    max-width: 75%;
    padding: 1rem;
    border-radius: 1.2rem;
    background-color: #f1f1f1;
    align-self: flex-start;
    position: relative;
    direction: rtl;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }
  .card .user-info.my-news {
    text-align: right;
  }
  .card.my-news {
    align-self: flex-end;
    background-color: #d0f0ff;
  }
  .card.my-news::before {
    content: "";
    position: absolute;
    top: 12px;
    right: -10px;
    border-width: 10px;
    border-style: solid;
    border-color: transparent transparent transparent #d0f0ff;
  }
  .card:not(.my-news)::before {
    content: "";
    position: absolute;
    top: 12px;
    left: -10px;
    border-width: 10px;
    border-style: solid;
    border-color: transparent #f1f1f1 transparent transparent;
  }
  .user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
  }
  .user-info img ,.user-info svg {
    width: 32px;
    height: 32px;
    object-fit: cover;
    border-radius: 50%;
  }
  .card-category {
    margin-top: 0.5rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
  }
  .card-category .category {
    background: #eee;
    padding: 2px 6px;
    border-radius: 6px;
    font-size: 0.75rem;
  }
  .description {
    margin-top: 0.5rem;
    line-height: 1.6;
  }
  .time {
    font-size: 0.75rem;
    color: #777;
    margin-top: 0.5rem;
  }
  .c-d {
    display: inline-block;
    margin-top: 0.5rem;
    color: #007bff;
    font-weight: bold;
  }
  .choose {
    display: inline-block;
    margin-right: 0.5rem;
    margin-top: 0.5rem;
    background: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    cursor: pointer;
  }
  .choose:hover {
    background: #0056b3;
  }
  .report-block {
    margin-top: 1rem;
    padding-top: 0.5rem;
    border-top: 1px solid #ccc;
  }
  .single-report {
    margin-top: 0.5rem;
    font-size: 0.9rem;
    background: #fafafa;
    padding: 0.5rem;
    border-radius: 0.5rem;
  }
</style>
