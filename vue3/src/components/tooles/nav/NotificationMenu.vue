<script setup>
import { ref, onMounted, watch ,computed } from 'vue'
import NotificationList from '@/components/tooles/nav/NotificationList.vue'
import { BASE_URL } from '@/config'
import { useNotificationStore } from '@/stores/notification'
import { usePollingWithCompare } from '@/composables/usePollingWithCompare'
import { useInfiniteScroll } from '@/composables/useInfiniteScroll'

const logo = BASE_URL + '/assets/images/logo.png'
const song = BASE_URL + '/assets/song/notif.mp3'

const showList = ref(false)
const notifSound = ref(null)
const store = useNotificationStore()

function toggleList() {
  showList.value = !showList.value
}

function playSound() {
  notifSound.value?.play().catch(() => {})
}

function showNativeNotification(title, body) {
  if (Notification.permission === 'granted') {
    const notification = new Notification(title, {
      body,
      icon: logo,
    })
    notification.onclick = () => {
      window.focus()
      notification.close()
    }
  }
}

onMounted(() => {
  if (Notification.permission === 'default') {
    Notification.requestPermission()
  }
})

// 🔁 Polling برای نوتیف‌های جدید
usePollingWithCompare(() => store.fetchNotifications({ limit: 10, offset: 0 }), {
  intervalMs: 10000,
  runOnStart: true,
  isDifferent: (oldNotifs, newNotifs) => {
    const oldIds = new Set((oldNotifs || []).map(n => n.id))
    const newIds = new Set((newNotifs || []).map(n => n.id))
    return oldNotifs?.length !== newNotifs?.length || [...newIds].some(id => !oldIds.has(id))
  },
  onChange: (newData) => {
    const newOnes = newData.filter(n => !store.notifications.find(o => o.id === n.id))
    newOnes.forEach(n => {
      playSound()
      showNativeNotification(n.title, n.body)
      store.pushNotification(n)
    })
  }
})

// 📥 Infinite Scroll
const loadMoreTrigger = ref(null)

const {
  items,
  setupObserver,
  loadMore,
  canLoadMore
} = useInfiniteScroll(
  async ({ limit, offset }) => {
    return await store.fetchNotifications({ limit, offset })
  },
  {
    limit: 10,
    total: computed(() => store.totalCount),
    immediate: false,
  }
)

// وقتی loadMoreTrigger آماده شد، observer رو فعال کن
watch(loadMoreTrigger, (el) => {
  if (el) setupObserver()
})

// وقتی لیست باز شد و داده‌ای وجود نداشت، یکبار loadMore کن
watch(showList, (val) => {
  if (val && items.value.length === 0) {
    loadMore()
  }
})

// فقط برای تست
watch(items, (val) => {
  console.log('[🔁 items updated]', val)
})


function onLoadMoreTriggerReady(el) {
  loadMoreTrigger.value = el
}
</script>

<template>
  <div>
    <div class="icon-wrapper" @click="toggleList">
      🔔
      <span class="badge" v-if="store.unreadCount > 0">{{ store.unreadCount }}</span>
    </div>

    <div class="dropdown" v-if="showList">
      <NotificationList
        :notifications="items"
        :can-load-more="canLoadMore"
        @update="store.markAsRead"
        @loadMoreTriggerReady="onLoadMoreTriggerReady"
      />
    </div>

    <audio ref="notifSound" :src="song" preload="auto"></audio>
  </div>
</template>

<style scoped>
    .icon-wrapper {
        font-size: 24px;
        cursor: pointer;
        position: relative;
    }
    .badge {
        position: absolute;
        top: -6px;
        right: -10px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 12px;
    }
    .dropdown {
        position: fixed;
        top: 60px;
        left: 0;
        width: 300px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }
</style>