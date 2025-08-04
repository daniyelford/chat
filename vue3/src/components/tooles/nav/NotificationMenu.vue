<script setup>
  import { ref, watch, onMounted } from 'vue'
  import NotificationList from '@/components/tooles/nav/NotificationList.vue'
  import { BASE_URL } from '@/config'
  import { useNotificationStore } from '@/stores/notification'
  import { usePollingWithCompare } from '@/composables/usePollingWithCompare'
  import { useInfiniteScroll } from '@/composables/useInfiniteScroll'
  const logo = BASE_URL + '/assets/images/logo.png'
  const song = BASE_URL + '/assets/song/notif.mp3'
  const notifSound = ref(null)
  const store = useNotificationStore()
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
  usePollingWithCompare(() => store.fetchNotifications({ limit: 10, offset: 0 }), {
    intervalMs: 10000,
    runOnStart: true,
    isDifferent: (old, fresh) => {
      const oldArray = Array.isArray(old) ? old : []
      const freshArray = Array.isArray(fresh) ? fresh : []
      const oldIds = new Set(oldArray.map(n => n.id))
      const freshIds = new Set(freshArray.map(n => n.id))
      return oldArray.length !== freshArray.length || [...freshIds].some(id => !oldIds.has(id))
    },
    onChange: (freshData) => {
      const newOnes = freshData.filter(n => !store.notifications.find(o => o.id === n.id))
      newOnes.forEach(n => {
        playSound()
        showNativeNotification(n.title, n.body)
        store.pushNotification(n)
      })
    }
  })
  const loadMoreTrigger = ref(null)
  const {
    canLoadMore,
    setupObserver,
  } = useInfiniteScroll(
    ({ offset }) => store.fetchNotifications({ limit: 10, offset }),
    { immediate: false }
  )
  watch(loadMoreTrigger, (el) => {
    if (el) setupObserver()
  })
  function onLoadMoreTriggerReady(el) {
    loadMoreTrigger.value = el
  }
</script>
<template>
  <div>
    <div class="icon-wrapper" @click="store.toggle()">
      🔔
      <span class="badge" v-if="store.unreadCount > 0">{{ store.unreadCount }}</span>
    </div>
    <div class="dropdown" v-if="store.showList">
      <NotificationList
        :notifications="store.notifications"
        :can-load-more="canLoadMore"
        @update="store.markAsRead"
        @loadMoreTriggerReady="onLoadMoreTriggerReady"
      />
    </div>
    <audio ref="notifSound" :src="song" preload="auto" />
  </div>
</template>
<style scoped>
  .icon-wrapper {
    font-size: 24px;
    cursor: pointer;
    position: relative;
  }
  .badge {
    top: -6px;
    right: -10px;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    position: absolute;
  }
  .dropdown {
    top: 60px;
    left: 0;
    width: 300px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    position: fixed;
  }
</style>