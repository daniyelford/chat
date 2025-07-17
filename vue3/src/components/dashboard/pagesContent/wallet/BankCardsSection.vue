<script setup>
  import { ref, onMounted, onBeforeUnmount } from 'vue'
  import { useWalletStore } from '@/stores/wallet'
  import BankCardWithdrawalsModal from '@/components/dashboard/pagesContent/wallet/BankCardWithdrawalsModal.vue'
  import BankCardAddModal from '@/components/dashboard/pagesContent/wallet/BankCardAddModal.vue'
  const store = useWalletStore()
  let pollingInterval = null
  const showAddModal = ref(false)
  const startPolling = () => {
    if (pollingInterval) return
    pollingInterval = setInterval(() => {
      store.fetchCards()
    }, 5000)
  }
  const stopPolling = () => {
    if (pollingInterval) {
      clearInterval(pollingInterval)
      pollingInterval = null
    }
  }
  const addCard = async (card) => {
    const res = await store.addCard(card)
    if (res.status !== 'success') {
      alert(res.message || 'خطا در افزودن کارت')
    }
  }
  const removeCard = async (id) => {
    if (!confirm('آیا از حذف این کارت مطمئن هستید؟')) return
    const res = await store.deleteCard(id)
    if (res.status !== 'success') {
      alert(res.message || 'خطا در حذف کارت')
    }
  }
  function formatCardNumber(number) {
    if (!number) return ''
    return number.toString().replace(/(.{4})/g, '$1 ').trim()
  }
  const showWithdrawModal = ref(false)
  const selectedCardId = ref(null)
  const openWithdrawModal = (cardId) => {
    selectedCardId.value = cardId
    showWithdrawModal.value = true
  }
  const handleWithdrawSubmit = async (amount) => {
    const res = await store.requestWithdrawal({
      amount,
      card_id: selectedCardId.value
    })
    if (res.status === 'success') {
      showWithdrawModal.value = false
    } else {
      alert(res.message || 'خطا در واریز')
    }
  }
  onMounted(() => {
    store.fetchCards()
    startPolling()
  })
  onBeforeUnmount(() => {
    stopPolling()
  })
</script>

<template>
  <div class="bank-cards-section">
    <div class="add-card">
      <button @click="showAddModal = true">افزودن کارت جدید</button>
    </div>
    <h2 class="section-title">کارت‌های بانکی</h2>
    <div v-if="store.loading" class="loading">در حال بارگذاری...</div>
    <div v-else>
      <div v-if="store.cards.length === 0" class="no-cards">کارت بانکی ثبت نشده است.</div>
      <ul class="card-list">
        <li v-for="card in store.cards" :key="card.id" class="card-item">
          <button class="withdrawal-btn" @click="openWithdrawModal(card.id)">واریز</button>
          <div class="card-info">
            <p v-if="card.shomare_shaba">
              <small style="font-size: 10px;">
                {{ card.shomare_shaba }}
              </small>
            </p>
            <p v-if="card.shomare_hesab" style="font-size: 11px;">
              <strong>شماره حساب:</strong> {{ card.shomare_hesab }}
            </p>
            <p v-if="card.shomare_cart">
              <strong style="font-size: 18px;">
                {{ formatCardNumber(card.shomare_cart) }}
              </strong>
            </p>
          </div>
          <button class="delete-btn" @click="removeCard(card.id)">حذف</button>
        </li>
      </ul>
    </div>
    <BankCardWithdrawalsModal
      v-if="showWithdrawModal"
      @close="showWithdrawModal = false"
      @submit="handleWithdrawSubmit"
    />
    <BankCardAddModal
      v-if="showAddModal"
      @close="showAddModal = false"
      @submit="(card) => { addCard(card); showAddModal = false }"
    />
  </div>
</template>

<style scoped>
  .bank-cards-section {
    padding: 1rem;
  }
  .section-title {
    font-size: 1.2rem;
    font-weight: bold;
  }
  .card-list {
    margin-top: 1rem;
    padding: 0;
    max-height: 300px;
    overflow-y: auto;
  }
  .card-item {
    border-radius: 10px;
    border: none;
    overflow: hidden;
    box-shadow: 0 0 5px green;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: space-between;
  }
  .card-info {
    padding: 10px;
    text-align: center;
  }
  .add-card {
    float: left;
    margin-top: 1rem;
  }
  .add-card button {
    outline: none;
    border: none;
    padding: 7px 20px;
    background: #2a422a;
    border-radius: 10px;
    color: gold;
  }
  .delete-btn {
    background-color: crimson;
  }
  .withdrawal-btn {
    background-color: rgb(4, 4, 121);
  }
  .delete-btn ,.withdrawal-btn {
    color: white;
    font-weight: bold;
    border: none;
    padding: 10px;
    outline: none;
    border: none;
    cursor: pointer;
  }
</style>
