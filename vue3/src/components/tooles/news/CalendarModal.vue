<template>
    <BaseModal :show="true" @close="close">
        <h3>انتخاب زمان ملاقات</h3>
        <div class="calendar-container">
            <date-picker
            v-model="selectedDate"
            format="jYYYY/jMM/jDD"
            display-format="jYYYY/jMM/jDD"
            auto-submit
            />
        </div>
        <div class="actions">
            <button @click="submit">ثبت</button>
            <button @click="close">انصراف</button>
        </div>
    </BaseModal>
    <div class="modal-mask" @click.self="close">
        <div class="modal-container">
        </div>
    </div>
</template>
<script setup>
    import { ref,defineEmits,defineProps,watchEffect } from 'vue'
    import moment from 'moment-jalaali'
    import BaseModal from '@/components/tooles/modal/BaseModal.vue'
    import DatePicker from 'vue3-persian-datetime-picker'
    const emit = defineEmits(['close', 'submit'])
    const selectedDate = ref(null)
    const props = defineProps({
        initialDate: String
    })
    function close() {
        emit('close')
    }
    function submit() {
        if (!selectedDate.value) {
            alert('لطفاً تاریخ را انتخاب کنید')
            return
        }
        emit('submit', {
            date: selectedDate.value
        })
        close()
    }
    watchEffect(() => {
        console.log('watchEffect -> initialDate', props.initialDate)
        if (props.initialDate) {
            selectedDate.value = moment(props.initialDate).format('jYYYY/jMM/jDD')
        } else {
            selectedDate.value = null
        }
    })
</script>
<style scoped>
    h3{
        margin-top: 0;
        text-align: center;
    }
    .actions button{
        width: 48%;
        margin: 1%;
        padding: 10px 0;
        border: none;
        color: white;
        font-size: 15px;
        font-weight: bold;
        border-radius: 10px;
    }
    .actions button:last-child{
        background: red;
    }
    .actions button:first-child{
        background: green;
    }
    .radio-group {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin: 10px 0;
    }
    .calendar-container {
        margin-top: 15px;
    }
    .actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }
</style>