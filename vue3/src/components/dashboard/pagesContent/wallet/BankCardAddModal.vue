<template>
  <div class="modal-overlay" @click.self="close">
    <div class="modal-box">
      <h3>افزودن کارت جدید</h3>
      <input v-model="form.shomare_cart" placeholder="شماره کارت" />
      <input v-model="form.shomare_shaba" placeholder="شماره شبا" />
      <input v-model="form.shomare_hesab" placeholder="شماره حساب" />
      <div class="modal-actions">
        <button @click="submit" style="background: green;color: white;">ثبت</button>
        <button @click="close" style="background: red;color: white;">انصراف</button>
      </div>
    </div>
  </div>
</template>

<script setup>
    import { ref,defineEmits } from 'vue'
    const emits = defineEmits(['close', 'submit'])
    const form = ref({
        shomare_cart: '',
        shomare_shaba: '',
        shomare_hesab: ''
    })
    function close() {
        emits('close')
        reset()
    }
    function submit() {
        if (!(form.value.shomare_cart || form.value.shomare_shaba || form.value.shomare_hesab)) {
            alert('لطفاً حداقل یکی از فیلدها را پر کنید.')
            return
        }
        emits('submit', { ...form.value })
        reset()
    }
    function reset() {
        form.value = {
            shomare_cart: '',
            shomare_shaba: '',
            shomare_hesab: ''
        }
    }
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-box {
  background: lightgray;
  padding: 20px;
  border-radius: 10px;
  width: 300px;
}
.modal-box input {
    display: block;
    box-sizing: border-box;
    padding: 10px;
    outline: none;
    border: none;
    margin: 10px 0;
    width: 100%;
    border-radius: 5px;
}
.modal-actions {
  display: flex;
  justify-content: space-between;
}
.modal-actions button {
    width: 49%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    outline: none;
}
</style>
