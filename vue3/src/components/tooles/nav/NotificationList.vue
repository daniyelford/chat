<template>
  <div class="notification-list">
    <h3 class="title">نوتیفیکیشن‌ها</h3>
    <ul v-show="props.notifications.length">
      <li
        v-for="notif in props.notifications"
        :key="notif.id"
        :class="{ unread: notif.is_read === 'dont' }"
        @click="$emit('update', notif.id)"
      >
        <RouterLink v-if="notif.url" :to="{ path: notif.url }">
          <strong>{{ notif.title }}</strong>
          <p>{{ notif.body }}</p>
          <small>{{ formatDate(notif.created_at) }}</small>
        </RouterLink>
        <span v-else>
          <strong>{{ notif.title }}</strong>
          <p>{{ notif.body }}</p>
          <small class="date">{{ formatDate(notif.created_at) }}</small>
        </span>
      </li>
    </ul>
    <div v-if="props.notifications.length==0">
      نوتیفیکیشنی وجود ندارد.
    </div>
    <div v-if="canLoadMore" ref="loadMoreDiv" class="scroll-trigger">
    </div>
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
    if (!date) return '-'
    return moment(date).format('jYYYY/jMM/jDD HH:mm')
  }
</script>
<style scoped>
  .notification-list {
    padding: 1rem;
  }
  .notification-list ul {
    padding: 0;
    margin: 0;
    max-height: 350px;
    overflow-y: auto;
    list-style: none;
  }
  .notification-list li {
    margin-bottom: 5px;
    background-color: #edfcfa;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
    transition: background 0.3s;
    border-radius: 10px;
  }
  .notification-list li.unread {
    font-weight: bold;
    background-color: #c4f511;
  }
  .notification-list li:hover {
    background-color: #eef;
  }
  a{
    color: #2f285a;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-wrap: nowrap;
    gap: 15px;
    text-align: center;
    text-decoration: none;
  }
</style>