<template>
  <div>
    <div class="icon-wrapper" @click="tog">
      🔔
      <span class="badge" v-if="store.unreadCount > 0">{{ store.unreadCount }}</span>
    </div>
    <div class="dropdown" v-if="store.showList">
      <NotificationList
        :notifications="store.notifications"
        @update="store.markAsRead"
      />
      <div v-if="notificationScroll.loadMore" :ref="notificationScroll.el"></div>
    </div>
    <audio ref="notifSound" :src="song" preload="auto" />
  </div>
</template>
<script setup>
  import { ref, onMounted } from 'vue'
  import NotificationList from '@/components/tooles/nav/NotificationList.vue'
  import { BASE_URL } from '@/config'
  import { useNotificationStore } from '@/stores/notification'
  import { polling } from '@/composables/polling'
  import { scroll } from '@/composables/scroll'
  import { subscribeToPush } from '@/utils/pushService';
  const logo = BASE_URL + '/assets/images/fav.png'
  const song = BASE_URL + '/assets/song/notif.mp3'
  const notifSound = ref(null)
  const hasAccess = ref(false)
  const store = useNotificationStore()
  function playSound() {
    notifSound.value?.play().catch(() => {})
  }
  function showNativeNotification(title, body) {
    if (Notification.permission === 'granted') {
      if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistration().then(registration => {
          if (registration) {
            registration.showNotification(title, {
              body,
              icon: logo,
            });
          } else {
            const notification = new Notification(title, {
              body,
              icon: logo,
            });
            notification.onclick = () => {
              window.focus();
              notification.close();
            };
          }
        });
      } else {
        const notification = new Notification(title, {
          body,
          icon: logo,
        });
        notification.onclick = () => {
          window.focus();
          notification.close();
        };
      }
    }
  }
  function askNotificationPermission() {
    Notification.requestPermission().then(permission => {
      if (permission === "granted") {
        hasAccess.value = true
        if ('serviceWorker' in navigator) {
          navigator.serviceWorker.register(`${BASE_URL}/assets/sw.js`)
          .then(() => {
            subscribeToPush();
          })
          .catch(err => {
            console.error('SW registration failed:', err);
          });
        }
      } else {
        const retry = confirm('برای دریافت اعلان‌ها لطفاً دسترسی نوتیفیکیشن را فعال کنید. می‌خواهید دوباره تلاش شود؟');
        if (retry) {
          askNotificationPermission();
        }
      }
    });
  }
  function tog() {
    store.toggle()
    if (!hasAccess.value) {
      askNotificationPermission()
    }
  }
  polling(async () => await store.fetchNotifications({ limit: store.notifications.length>0?store.notifications.length:10, offset: 0 }), {
    intervalMs: 10000,
    isDifferent: (old, fresh) => {
      if (!old || !Array.isArray(old)) return true
      const oldArray = Array.isArray(old) ? old : []
      const freshArray = Array.isArray(fresh) ? fresh : []
      const oldIds = new Set(oldArray.map(n => n.id))
      const freshIds = new Set(freshArray.map(n => n.id))
      return oldArray.length !== freshArray.length || [...freshIds].some(id => !oldIds.has(id))
    },
    onChange: (freshData) => {
      const newOnes = freshData?.items.filter(n => !store.notifications.find(o => o.id === n.id))
      newOnes.forEach(n => {
        playSound()
        showNativeNotification(n.title, n.body)
        store.pushNotification(n)
      })
    }
  })
  const notificationScroll = scroll(
    async ({ offset }) => await store.fetchNotifications({ limit: 10, offset }),
    { immediate: true }
  )
  onMounted(() => {
    if (!('Notification' in window)) {
      alert('مرورگر شما از نوتیفیکیشن پشتیبانی نمی‌کند.');
      return;
    }
  });
</script>
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
    left: 40px;
    width: calc(100% -80px);
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    position: fixed;
  }
</style>