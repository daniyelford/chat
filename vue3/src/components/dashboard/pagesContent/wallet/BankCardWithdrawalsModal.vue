<template>
  <div class="modal">
    <div class="modal-content">
      <h3>مبلغ درخواستی</h3>
      <p v-if="userStore.wallet !== null">موجودی شما: {{ Number(userStore.wallet).toLocaleString('fa-IR') }} تومان</p>
      <input v-model="amount" type="number" placeholder="مبلغ (تومان)" />
      <div class="modal-actions">
        <button @click="submit" :disabled="!isValidAmount">واریز</button>
        <button @click="$emit('close')">بستن</button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref,defineEmits,computed } from 'vue'
  import { useUserStore } from '@/stores/user'
  const amount = ref('')
  const emit = defineEmits(['close', 'submit'])
  const userStore = useUserStore()

  const isValidAmount = computed(() => {
    const value = parseInt(amount.value)
    return (
      !isNaN(value) &&
      value > 0 &&
      value <= userStore.wallet
    )
  })
  const submit = () => {
    if (!amount.value || parseInt(amount.value) <= 0) {
      alert('لطفاً مبلغ معتبر وارد کنید')
      return
    }
    emit('submit', parseInt(amount.value))
  }
</script>

<style scoped>
.modal {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 10px;
}
.modal-actions {
  display: flex;
  justify-content: space-around;
  margin-top: 10px;
  align-items: center;
}
.modal-actions button:last-child{
  background: red;
}
.modal-actions button:first-child{
  background: green;
}
.modal-actions button:first-child:disabled{
  background: gray;
  cursor: not-allowed;
}
.modal-actions button {
  width: 49%;
  color: white;
  padding: 10px;
  border-radius: 10px;
  border: none;
  outline: none;
  cursor: pointer;
}
input{
  box-sizing: border-box;
  border: none;
  outline: none;
  box-shadow: 0 0 5px grey;
  border-radius: 10px;
  padding: 10px;
  outline: none;
}

</style>