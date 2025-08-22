<template>
  <div class="inner-posts">
    <div class="loading" v-if="!newsStore.isLoaded">
      <div class="tiny-loader"></div>
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
          <MediaSlider v-if="Array.isArray(card.medias) && card.medias.length > 0" :medias="card.medias" />
        </div>
        <div class="card-category" v-if="Array.isArray(card.category) && card.category.length > 0">
          <span class="category" v-for="category in card.category" :key="category.id">
            {{ category.title }}
          </span>
        </div>
        <div class="description" v-if="card.description">
          {{ truncateText(card.description) }}
        </div>
        <a class="choose" v-if="card.location?.lat && card.location?.lon" style="margin-top: 0; float: left;" @click="openMapModal(card)">نمایش روی نقشه</a>
        <div class="location" v-if="card.location?.city">📍 {{ card.location.city }}</div>
        <div style="clear: both;"></div>
        <div class="time">📅 {{ moment(card.created_at).format('jYYYY/jMM/jDD') }}</div>
        <RouterLink class="choose" :to="{ path: `/show-news/${card.id}` }">
          مشاهده
        </RouterLink>
        <a v-if="card.self && userStore.status==='active'" class="choose" @click="handleEdit(card.id,null)">
          ویرایش
        </a>
        <a
          class="choose"
          v-if="userStore.status==='active' && newsStore.hasRule && !card.self"
          @click="handleReply(card.id)"
        >
          پاسخ
        </a>
        <a
          class="choose"
          v-if="newsStore.hasRule && !card.self && userStore.status==='active'"
          @click="openCalendarModal(card.id,0)"
        >
          قرار ملاقات
        </a>
        <a class="choose" v-if="card.reports && card.reports.length" @click="toggleReports(card.id)">
          {{ showReports[card.id] ? 'بستن گزارش‌ها' : 'نمایش گزارش‌ها' }}
        </a>
        <div class="report-block" v-if="card.reports && card.reports.length && showReports[card.id]">
          <div class="single-report" v-for="report in card.reports" :key="report.id">
            <div class="reporter-user">
              <img v-if="report.reporter.image" :src="report.reporter.image" alt="reporter image">
              <svg v-else xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></g></svg>
              <p>{{ report.reporter.name }} {{ report.reporter.family }}</p>
            </div>
            <div class="media-inner" v-if="Array.isArray(report.media) && report.media.length > 0">
              <MediaSlider :medias="report.media" />
            </div>
            <p v-if="report.description">📄 {{ truncateText(report.description) }}</p>
            <a
              class="choose"
              v-if="report.location?.lat && report.location?.lon"
              @click="openMapModal(report, card)"
              style="margin-top: 0; float: left;"
              >
              نمایش روی نقشه
            </a>
            <div class="location" v-if="report.location?.city">📍 {{ report.location.city }}</div>
            <div style="clear: both;"></div>
            <p v-if="report.run_time">
              📅 تاریخ ملاقات {{ moment(report.run_time).format('jYYYY/jMM/jDD') }}
            </p>
            <div class="time">📅 {{ moment(report.created_at).format('jYYYY/jMM/jDD') }}</div>
            <a v-if="userStore.status==='active' && report.reporter.self && report.description" class="choose" @click="handleEdit(card.id,report.id)">
              ویرایش
            </a>
            <a
            class="choose"
            v-if="userStore.status==='active' && report.reporter.self && report.run_time"
            @click="openCalendarModal(card.id,report.id)">
              ویرایش زمان ملاقات
            </a>
            <RouterLink class="choose" :to="{ path: `/show-cartable/${report.id}` }">
              مشاهده
            </RouterLink>
            <a
              class="choose"
              v-if="userStore.status==='active' && report.reporter.self && !report.run_time"
              @click="openCalendarModal(card.id,report.id)"
            >
              قرار ملاقات
            </a>
          </div>
        </div>
      </div>
      <div ref="loadMoreTrigger" class="scroll-trigger"></div>
    </div>
    <div v-else class="none-cart-error">
      <span>
        خبری در محدوده شما وجود ندارد
      </span>
    </div>
    <span v-if="toastMsg" class="toast">
      {{ toastMsg }}
    </span>
    <CalendarModal
    v-if="showModal"
    :initialDate="modalRunTime"
    @close="showModal = false"
    @submit="onCalendarSubmit"
    />
  </div>
  <AddNewsForm v-if="userStore.status==='active'"
  :reply-to-id="replyToId"
  :edit-data="editCard"
  :edit-report="editReport"
  @clearReplyId="replyToId = 0; editCard = null; editReport = null"
  />
  <div v-else-if="userStore.status==='inactive'" class="ban">
    دسترسی شما غیر فعال است
    <span v-if="userStore.banTime">
      تاریخ این اقدام
      {{ moment(userStore.banTime).format('jYYYY/jMM/jDD') }}
    </span>
  </div>
  <div v-else-if="userStore.infoIsLoaded" class="load">
    <div class="tiny-loader"></div>
  </div>
  <BaseModal :show="showMapModal" @close="showMapModal = false">
    <SinglePlaceMap
    v-if="selectedPlace && selectedPlace.lat && selectedPlace.lon && userCoordinate && userCoordinate.lat && userCoordinate.lon"
    :user-center="userCoordinate"
    :place="selectedPlace"
    />
  </BaseModal>
</template>
<script setup>
  import { ref, onMounted } from 'vue'
  import moment from 'moment-jalaali'
  import BaseModal from '@/components/tooles/modal/BaseModal.vue'
  import SinglePlaceMap from '@/components/tooles/places/SinglePlaceMap.vue'
  import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
  import CalendarModal from '@/components/tooles/news/CalendarModal.vue'
  import { usePollingWithCompare } from '@/composables/usePollingWithCompare'
  import { useInfiniteScroll } from '@/composables/useInfiniteScroll'
  import AddNewsForm from '@/components/dashboard/pagesContent/AddNewsForm.vue'
  import { useNewsStore } from '@/stores/news'
  import { useUserStore } from '@/stores/user'
  const toastMsg = ref('')
  const selectedNewsId = ref(null)
  const selectedReportId = ref(null)
  const showModal = ref(false)
  const modalRunTime = ref(null)
  const showReports = ref({})
  const replyToId = ref(0)
  const editCard = ref(null)
  const editReport = ref(null)
  const userCoordinate = ref(null)
  const showMapModal = ref(false)
  const selectedPlace = ref(null)
  const newsStore = useNewsStore()
  const userStore = useUserStore()
  const truncateText = (text, max = 50) => {
    if (!text) return ''
    return text.length > max ? text.slice(0, max) + '...' : text
  }
  function showToast(msg) {
    toastMsg.value = msg
    setTimeout(() => (toastMsg.value = ''), 3000)
  }
  function toggleReports(id) {
    showReports.value[id] = !showReports.value[id]
  }
  function handleEdit(newsId, reportId) {
    const card = newsStore.cards.find(c => c.id === newsId)
    editCard.value = card
    replyToId.value = 0
    if (reportId) {
      editReport.value = card.reports.find(r => r.id === reportId) || null
    } else {
      editReport.value = null
    }
  }
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
  usePollingWithCompare(() => newsStore.fetchLatestNewsRaw(10, 0), {
    intervalMs: 6000,
    isDifferent: (oldData, newData) => {
      if (!Array.isArray(oldData) || !Array.isArray(newData)) return true
      if (oldData.length !== newData.length) return true

      for (let i = 0; i < newData.length; i++) {
        const oldItem = oldData[i]
        const newItem = newData[i]

        if (oldItem.id !== newItem.id) return true

        const oldReports = oldItem.reports || []
        const newReports = newItem.reports || []

        if (oldReports.length !== newReports.length) return true

        for (let j = 0; j < newReports.length; j++) {
          const oldReport = oldReports[j]
          const newReport = newReports[j]

          if (
            oldReport.id !== newReport.id ||
            oldReport.updated_at !== newReport.updated_at ||
            oldReport.status !== newReport.status ||
            oldReport.run_time !== newReport.run_time
          ) {
            return true
          }
        }
      }

      return false
    },
    onChange: async (newCards) => {
      for (const newItem of newCards) {
        const index = newsStore.cards.findIndex(c => c.id === newItem.id)
        if (index !== -1) {
          const card = newsStore.cards[index]
          const updatedCard = { ...card, reports: newItem.reports }
          newsStore.cards.splice(index, 1, updatedCard)
        } else {
          newsStore.cards.push(newItem)
        }
      }
      if (newCards.length > 0) {
        showToast('منتظر بمانید')
      }
    }
  })
  function openCalendarModal(id,reportId) {
      selectedNewsId.value = id
      selectedReportId.value=reportId
      if (reportId) {
        const card = newsStore.cards.find(c => c.id === id)
        if (card) {
          const report = card.reports.find(r => r.id === reportId)
          modalRunTime.value = report?.run_time ? new Date(report.run_time) : null
        } else {
          modalRunTime.value = null
        }
      } else {
        modalRunTime.value = null
      }

      showModal.value = true
  }
  async function onCalendarSubmit({ date }) {
      const jsDate = date ? moment(date, 'jYYYY/jMM/jDD').hour(12).minute(0).second(0).toDate() : null
      const success = await newsStore.scheduleNewsRunTime(selectedNewsId.value,selectedReportId.value,jsDate)
      if (success) {
          showModal.value = false
          const updated = await newsStore.fetchNewsById(selectedNewsId.value)
          if (updated) {
            const index = newsStore.cards.findIndex(c => c.id === selectedNewsId.value)
            if (index !== -1) {
              newsStore.cards[index] = updated
            }
          }
      } else {
          alert('خطا در ثبت تاریخ اجرا')
      }
  }
  function openMapModal(item, parentCard = null) {
    const lat = item?.location?.lat
    const lon = item?.location?.lon
    if (!lat || !lon) return
    if(parentCard){
      selectedPlace.value = {
        ...item.location,
        title: (item.run_time?'📅 تاریخ ملاقات '+ moment(item.run_time).format('jYYYY/jMM/jDD') : ''),
        description: (item.description ? item.description : 'بدون عنوان'),
        categories: parentCard?.category || [],
        medias: item.media || [],
        address: item.location.address
      }
    }else{
      selectedPlace.value = {
        ...item.location,
        title:'موقعیت',
        description: item.description || 'بدون عنوان',
        categories: item?.category || [],
        medias: item.medias || [],
        address: item.location.address
      }
    }
    showMapModal.value = true
  }
  onMounted(async () => {
    const res = await newsStore.fetchAddNewsData()
    setTimeout(() => {
      setupObserver()
    }, 100)
    await userStore.fetchUserInfo()
    if (res?.coordinate?.lat && res?.coordinate?.lon) {
      userCoordinate.value = res.coordinate
    }
  })
</script>
<style scoped>
  .ban,.load{
    position: fixed;
    border-radius: 45px 45px 0 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 55px;
    text-align: center;
    color: white;
    font-size: 16.5px;
    padding-top: 10px;
    font-weight: bolder;
    box-sizing: border-box;
  }
  .ban{
    background: red;
  }
  .load{
    background: rgb(122, 6, 122);
  }
  .inner-posts {
    position: fixed;
    top: 60px;
    bottom: 45px;
    overflow: hidden;
    left: 0;
    right: 0;
  }
  .scroll-trigger{
    position: relative;
    top: 10px;
  }
  .card-inner {
    padding: 10px;
    width: 100%;
    box-sizing: border-box;
    display: flex;
    flex-direction: column-reverse;
    max-height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    gap: 3px;
    justify-content: flex-start;
  }
  .card {
    box-shadow: 0 5px 5px grey;
    /* box-shadow: 0px 0px 125px #153c23; */
    border-left: 5px solid #e17cfd;
    box-sizing: border-box;
    width: 100%;
    padding: 0.5rem 2rem 2rem;
    border-radius: 0 50px 50px 50px;
    /* border-radius: 0 50px 50px 0px; */
    background-color: #fff6c1;
    align-self: flex-start;
    position: sticky;
    bottom: 10px;
    direction: rtl;
    transition: all 0.3s ease;
  }
  .card .user-info.my-news {
    text-align: right;
  }
  .card.my-news {
    border-right: 5px solid #0a7283;
    border-left: unset;
    align-self: flex-end;
    border-radius: 50px 0 50px 50px;
    /* border-radius: 50px 0px 0px 50px; */
    background-color: #b3ffd0;
  }
  /* .card.my-news::before {
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
  } */
  .user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
  }
  .user-info img ,.user-info svg {
    width: 45px;
    height: 45px;
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
    float: left;
    margin-top: 10px;
  }
  .choose {
    display: inline-block;
    margin-right: 0.2rem;
    margin-top: 0.5rem;
    background: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    cursor: pointer;
    text-decoration: none;
  }
  .choose:hover {
    background: #0056b3;
  }
  .report-block {
    margin-top: 1rem;
    padding-top: 0.5rem;
    border-top: 1px solid #ccc;
    display: flex;
    gap: 10px;
    justify-content: flex-start;
    flex-direction: row;
    overflow: auto;
  }
  .single-report {
    margin-top: 0.5rem;
    position: sticky;
    right: 7px;
    width: 100%;
    font-size: 0.9rem;
    background: #fafafa;
    padding: 15px;
    border-radius: 0.5rem;
  }
  .reporter-user {
    display: flex;
    gap: 6px;
    align-items: center;
  }
  .reporter-user img, .reporter-user svg {
    width: 35px;
    height: 35px;
    border-radius: 50px;
  }
  .none-cart-error{
    text-align: center;
    background-color: #dc6a6a;
    position: fixed;
    bottom: calc(50% - 150px);
    top: calc(50% - 150px);
    left: calc(50% - 150px);
    right: calc(50% - 150px);
    height: 300px;
    overflow: hidden;
    width: 300px;
    font-size: x-large;
    border-radius: 50%;
    font-weight: bolder;
    box-sizing: border-box;
    box-shadow: 0 0 20px #1e1212;
  }
  .none-cart-error span{
    display: block;
    background: #fff;
    top: 40%;
    height: 20%;
    box-sizing: border-box;
    color: #000;
    position: relative;
    padding-top: 5%;
  }
  .toast{
    position: fixed;
    z-index: 99999999999999;
    padding: 10px;
    background-color: greenyellow;
    border-radius: 10px;
    bottom: 85px;
    left: 15px;
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