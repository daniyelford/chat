<script setup>
  import { ref , defineAsyncComponent, onMounted } from 'vue'
  import { useNewsStore } from '@/stores/news'
  import ReportListModal from '@/components/tooles/news/ReportListModal.vue'
  const store = useNewsStore()
  const showModal = ref(false)
  const selectedEvent = ref(null)
  const jalaliCalendar = defineAsyncComponent(() =>
    import('vue3-jalali-calendar').then(mod => mod.jalaliCalendar)
  )
  const showEventModal = (event) => {
    selectedEvent.value = event.raw
    showModal.value = true
  }
  const closeModal = () => {
    showModal.value = false
    selectedEvent.value = null
  }
  onMounted(() => {
    store.loadEvents()
  })
</script>
<template>
  <jalaliCalendar
    :eventsList="store.events"
    disablePastDays
    @on-event-click="showEventModal"
  />
  <ReportListModal :show="showModal" :event="selectedEvent" @close="closeModal" />
</template>
<style>
  #persian-calendar #vpc_calendar .vpc_week .vpc_day.vpc_week-period-day , #persian-calendar #vpc_calendar .vpc_week .vpc_day{
    min-height: 250px !important;        
  }
  @media screen and (max-width: 600px) {
    #persian-calendar #vpc_header #vpc_date-control .vpc_now-date {
      margin: auto 5px !important;
      width: 140px !important;
    }
    #persian-calendar #vpc_header {
      padding: 20px 5px !important;
      justify-content: center;
    }
    #persian-calendar #vpc_header #vpc_date-control .vpc_today-btn {
      margin: 0 5px !important;
    }
  }
</style>