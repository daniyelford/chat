<template>
  <div class="notification-list">
    <h3 style="padding-right: 5px; margin-top: 5px;">نوتیفیکیشن‌ها</h3>
    <ul v-if="props.notifications.length">
      <li
        v-for="notif in props.notifications"
        :key="notif.id"
        :class="{ unread: notif.is_read === 'dont' }"
        @click="$emit('update', notif.id)"
      >
        <RouterLink v-if="notif.url" :to="{ path:notif.url }">
          <strong>{{ notif.title }}</strong>
          <p>{{ notif.body }}</p>
          <small>{{ formatDate(notif.created_at) }}</small>
        </RouterLink>
        <span v-else>
          <strong>{{ notif.title }}</strong>
          <span>{{ notif.body }}</span>
          <small style="direction: ltr; font-size: 10.3px;">{{ formatDate(notif.created_at) }}</small>
        </span>
      </li>
    </ul>
    <div v-else>
      نوتیفیکیشنی وجود ندارد.
    </div>
    <!-- 🔻 المنت trigger برای infinite scroll -->
    <div ref="loadMoreDiv" style="height: 1px;"></div>
  </div>
</template>
<script setup>
import { ref, onMounted, defineProps, defineEmits } from 'vue'
import moment from 'moment-jalaali'

const props = defineProps({
  notifications: { type: Array, required: true },
  canLoadMore: { type: Boolean, default: false }
})

const emit = defineEmits(['update', 'loadMoreTriggerReady'])

const loadMoreDiv = ref(null)

onMounted(() => {
  if (loadMoreDiv.value) {
    emit('loadMoreTriggerReady', loadMoreDiv.value)
  }
})

function formatDate(date) {
  return moment(date).format('jYYYY/jMM/jDD HH:mm')
}
</script>


<style scoped>
    .notification-list {
        padding: 1rem;
    }
    .notification-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 350px;
        overflow-y: auto;
    }
    .notification-list li {
        border-radius: 10px;
        margin-bottom: 5px;
        background-color: #edfcfa;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
        transition: background 0.3s;
    }
    .notification-list li.unread {
        background-color: #c4f511;
        font-weight: bold;
    }
    .notification-list li:hover {
        background-color: #eef;
    }
    a{
        text-decoration: none;
        color: #2f285a;
        padding: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-wrap: nowrap;
        text-align: center;
        gap: 15px;
    }
</style>