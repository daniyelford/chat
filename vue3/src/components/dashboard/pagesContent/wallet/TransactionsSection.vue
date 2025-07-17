<template>
  <div class="transaction-list" ref="listRef" @scroll.passive="handleScroll">
    <h3>لیست تراکنش‌ها</h3>

    <div v-if="store.transactions.length === 0">تراکنشی وجود ندارد.</div>

    <ul>
      <li v-for="tx in paginatedTransactions" :key="tx.id" class="transaction-item">
        <p><strong>مبلغ:</strong> {{ formatAmount(tx.amount) }}</p>
        <p><strong>پرداخت‌کننده:</strong> {{ tx.pay_user.name }} {{ tx.pay_user.family }}</p>
        <p><strong>گیرنده:</strong> {{ tx.give_user.name }} {{ tx.give_user.family }}</p>
        <p><strong>تاریخ:</strong> {{ formatDate(tx.created_at) }}</p>
      </li>
    </ul>

    <div v-if="loadingMore" class="loading">در حال دریافت بیشتر...</div>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue'
  import { useWalletStore } from '@/stores/wallet'
  const store = useWalletStore()
  const listRef = ref(null)
  const currentPage = ref(1)
  const perPage = 10
  const loadingMore = ref(false)
  const endReached = ref(false)
  const paginatedTransactions = computed(() =>
    store.transactions.slice(0, currentPage.value * perPage)
  )
  const fetchMore = async () => {
    if (loadingMore.value || endReached.value) return
    loadingMore.value = true
    const previousLength = store.transactions.length
    await store.fetchTransactions({ limit: (currentPage.value + 1) * perPage })
    if (store.transactions.length === previousLength) {
      endReached.value = true
    } else {
      currentPage.value++
    }

    loadingMore.value = false
  }
  const handleScroll = () => {
    const el = listRef.value
    if (!el) return
    const scrollBottom = el.scrollTop + el.clientHeight
    const threshold = el.scrollHeight - 100
    if (scrollBottom >= threshold) {
      fetchMore()
    }
  }
</script>

<style scoped>
.transaction-list {
  padding: 1rem;
}
.transaction-item {
  border: 1px solid #ddd;
  padding: 0.75rem;
  margin-bottom: 0.5rem;
}
</style>
