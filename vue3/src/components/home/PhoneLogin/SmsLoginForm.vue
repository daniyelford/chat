<template>
    <form @submit.prevent="submitCode">
        <p class="msg" v-if="message">{{ message }}</p>
        <OtpInput v-model="code" length="6" :reset="resetOtpInput"/>
        <button type="submit" :disabled="submited" class="submitBtn">تأیید کد</button>
        <button type="button" @click="editPhone" class="editPhone">
            ویرایش شماره
        </button>
        <button
            type="button"
            @click="resendCode"
            :disabled="resendDisabled"
            class="resendBtn">
            ارسال مجدد کد
            <span v-if="resendDisabled">({{ countdown }} ثانیه)</span>
        </button>
    </form>
</template>
<script setup>
    import { ref,onMounted,onUnmounted,defineProps,defineEmits } from 'vue'
    import { sendApi } from '@/utils/api'
    import OtpInput from 'vue-otp-autofill'
    import router from '@/router'
    const emit = defineEmits(['back'])
    const resetOtpInput=ref(0)
    const message=ref('')
    const code=ref('') 
    const resendDisabled=ref(false)
    const countdown=ref(0)
    const submited = ref(false)
    const props = defineProps({
        phone: String
    })
    const editPhone = () => {
        sessionStorage.setItem('login_step', '1')
        code.value=''
        resetOtpInput.value++
        emit('back',true)
    }
    const submitCode=async()=>{
        submited.value=true
        try {
            const response = await sendApi({
                action: 'verify_sms_code',
                data: { phone: props.phone, code: code.value },
                control:'login'
            })
            if (response.status === 'success') {
                sessionStorage.setItem('login_step', '2')
                sessionStorage.removeItem('submit_phone_timer')
                sessionStorage.removeItem('phone')
                if (response.url === 'dashboard') {
                    sessionStorage.setItem('isLogin', true);
                    window.dispatchEvent(new Event("storage"));
                    router.push({ name: 'dashboard' })
                } else if (response.url === 'register') {
                    router.push({ name: 'register' })
                } else {
                    message.value = 'مشخصات ناقص است. لطفاً دوباره تلاش کنید.'
                }
            } else {
                message.value = 'کد وارد شده اشتباه است.'
            }
        } catch (error) {
            message.value = 'خطا در تأیید کد.'
            console.error(error)
        }finally{
            submited.value=false
        }
    }
    const resendCode = async () => {
        if (resendDisabled.value) return
        try {
            const response = await sendApi({ 
                action: 'send_phone_login',
                data: props.phone,
                control:'login'
            })
            if (response.status === 'success') {
                message.value = 'کد مجدداً ارسال شد.'
                startCountdown()
            } else {
                message.value = response.message || 'ارسال مجدد موفق نبود'
            }
        } catch (error) {
            message.value = 'خطا در ارسال مجدد کد.'
            console.error(error)
        }
    }
    let interval = null;
    const startCountdown = () => {
        const now = Date.now()
        const expireTime = now + 60000 
        sessionStorage.setItem('resend_code_timer', expireTime)
        updatecountdown()
        interval = setInterval(updatecountdown, 1000)
    }
    const updatecountdown = () => {
        const expireTime = parseInt(sessionStorage.getItem('resend_code_timer') || 0)
        const now = Date.now()
        const diff = Math.ceil((expireTime - now) / 1000)
        countdown.value = diff > 0 ? diff : 0
        if (countdown.value <= 0 && interval) {
            resendDisabled.value=false
            clearInterval(interval)
            interval = null
        }else{
            resendDisabled.value=true
        }
    }
    onUnmounted(() => {
        if (interval) clearInterval(interval)
    })
    onMounted(() => {
        updatecountdown()
        interval = setInterval(updatecountdown, 1000)
    })
</script>
<style scoped>
    form {
        width: 100%;
    }
    .msg{
        border-radius: 10px;
        background-color: #5e6295;
        color: #fefff8;
        padding: 10px;
        text-align: center;
        margin: 5px auto;
    }
    button{
        color: white;
        padding: 9px;
        display: inline-block;
        border-radius: 10px;
        border: none;
        width: 49%;
        cursor: pointer;
        margin-top: 10px;
    }
    button[disabled] {
        opacity: 0.6;
        background-color: rgb(143, 141, 141);
        cursor: not-allowed;
    }
    .submitBtn{
        background-color: green;
        margin-left: 1%;
    }
    .editPhone{
        background-color: orangered;
        margin-right: 1%;
    }
    .resendBtn{
        width: 100%;
        display: block;
        background-color: goldenrod;
    }
</style>