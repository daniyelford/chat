<template>
  <div class="top-send">
    <div v-if="replyCard" class="reply-preview">
      <div class="reply-box">
        <strong>پاسخ به:</strong>
        <div class="user-replay">
          <img v-if="replyCard.user.image" :src="replyCard.user.image" alt="reporter image">
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></g></svg>
          <p v-if="replyCard?.user"> {{ replyCard.user.name }} {{ replyCard.user.family }}</p>
        </div>
        <p v-if="replyCard?.description">{{ replyCard.description }}</p>
        <button class="close-btn" @click="clearReply">×</button>
      </div>
    </div>
    <UploaderManyMedia
    :url="'news_media/'"
    :toAction="'addNews'"
    ref="uploaderRef"
    @done="handleUploadResult" />
  </div>
  <form @submit.prevent="submitForm">
    <textarea v-model="form.description" rows="4" required></textarea>
    <BaseModal :show="showModal" @close="showModal = false">
      <div v-if="!rule" class="selectInner">
        <multiselect
          v-model="form.category_id"
          :options="categories"
          :multiple="true"
          track-by="id"
          label="title"
          placeholder="گیرنده را انتخاب کنید"
          selectLabel="برای انتخاب این مورد کلیک کنید"
          selectedLabel="انتخاب شده"
          deselectLabel="برای حذف این مورد، کلیک کنید"
          noOptions="موردی برای انتخاب وجود ندارد"
          noResult="نتیجه‌ای با این جستجو پیدا نشد"
        />
      </div>
      <AddressSelector
        :loginCity="address"
        :userCoordinate="userCoordinate"
        :model-value="form.user_address"
        @update="val => form.user_address = val"
        @loading="isAddressLoading = $event"
      />
      <button type="submit" :disabled="isSubmitDisabled">ثبت خبر</button>
    </BaseModal>
  </form>
  <button :disabled="isSaveDisabled" @click="showModal = true">افزودن خبر</button>
</template>
<script setup>
  import { ref , onMounted , computed , defineProps , defineEmits , watch } from 'vue'
  import UploaderManyMedia from '@/components/tooles/upload/UploaderManyMedia.vue'
  import Multiselect from 'vue-multiselect'
  import 'vue-multiselect/dist/vue-multiselect.min.css'
  import AddressSelector from '@/components/tooles/news/AddressSelector .vue'
  import BaseModal from '@/components/tooles/modal/BaseModal.vue'
  import { useNewsStore } from '@/stores/news'
  const replyCard = ref(null)
  const newsStore = useNewsStore()
  const props = defineProps({
    replyToId: {
      type: Number,
      default: 0,
    }
  })  
  const emit = defineEmits(["clearReplyId"])
  const uploaderRef = ref(null)
  const categories = ref([])
  const address = ref('')
  const rule = ref(false)
  const userCoordinate=ref(null)
  const isAddressLoading = ref(false)
  const showModal = ref(false)
  const form = ref({
    user_address: { type: 'location', value: '' },
    media_id: [],
    description: '',
    category_id: [],
  })
  const handleUploadResult = (uploadedData) => {
    form.value.media_id = uploadedData.map(item => item.id)
  }
  function clearReply() {
    replyCard.value = null
    emit('clearReplyId')
  }
  onMounted(async () => {
    const data = await newsStore.fetchAddNewsData()
    if (data) {
      address.value = data.address
      rule.value = data.rule
      userCoordinate.value = data.coordinate
      if (rule.value) {
        categories.value = data.category ? [data.category] : []
        form.value.category_id = data.category ? [data.category] : []
      } else {
        categories.value = data.category??[]
      }
    } 
  })
  const isSubmitDisabled = computed(() => {
    const isDescriptionEmpty = !form.value.description.trim()
    const isCategoryInvalid = !rule.value && (!form.value.category_id || form.value.category_id.length === 0)
    const isLocationSelected = form.value.user_address?.type === 'location'
    const isAddressInvalid = isLocationSelected && (!form.value.user_address?.value || !form.value.user_address.value.address?.trim())
    const isStillLoading = isLocationSelected && isAddressLoading.value
    return isDescriptionEmpty || isCategoryInvalid || isAddressInvalid || isStillLoading
  })
  const isSaveDisabled = computed(()=>{
    return !form.value.description.trim()
  })
  const submitForm = async () => {
    const finalData = {
      ...form.value,
      reply_to_id: props.replyToId,
    }
    try {
      const res = await newsStore.addNews(finalData)
      if (res.status === 'success') {
        showModal.value = false
        form.value = {
          user_address: { type: 'location', value: '' },
          media_id: [],
          description: '',
          category_id: rule.value ? form.value.category_id : []
        }
        clearReply()
        uploaderRef.value?.reset()
      } else {
        alert('خطا در ثبت خبر: ' + res.message)
      }
    } catch (err) {
      alert('خطا در ارسال: ' + err.message)
    }
  }
  watch(() => props.replyToId, (newId) => {
    if (newId > 0) {
      replyCard.value = newsStore.cards.find(c => c.id === newId) || null
    } else {
      replyCard.value = null
    }
  })
</script>
<style scoped>
  .top-send {
    bottom: 45px;
    left: 0;
    right: 0;
    height: auto;
    position: fixed;
  }
  .reply-box {
    background-color: #fff3cd;
    padding: 10px 5px 3px;
    direction: rtl;
    border-right: 4px solid #ffc107;
    border-top-left-radius: 10px;
  }
  .user-replay{
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .user-replay img, .user-replay svg {
    width: 35px;
    height: 35px;
    border-radius: 50px;
  }
  .reply-box p {
    margin: 0;
    padding: 5px;
  }
  .reply-box .close-btn {
    position: absolute;
    border-radius: 15px;
    top: 5px;
    left: 5px;
    width: 30px;
    background: #ff0000;
    border: none;
    cursor: pointer;
    height: 30px;
    padding: 5px;
    color: white;
    font-weight: bold;
    font-size: larger;
  }
  .selectInner{
    margin-top: 30px;
    margin-bottom: 15px;
  }
  form {
    direction: rtl;
  }
  textarea {
    border-radius: 5px;
    width: auto;
    padding: .5rem;
    box-sizing: border-box;
    height: 45px;
    position: fixed;
    bottom: 0;
    z-index: 9999;
    left: 45px;
    right: 45px;
  }
  label{
    margin-bottom: 0.5rem;
    display: block;
  }
  button {
    padding: .5rem;
    background-color: #10b981;
    color: white;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    position: fixed;
    transition: background-color .2s ease, opacity .2s ease;
    bottom: 0;
    right: 0;
    width: 45px;
    height: 45px;
    transition: background-color 0.2s ease, opacity 0.2s ease;
  }
  button:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
    opacity: 0.6;
  }
  form button{
    width: 100%;
    position: unset;
    height: unset;
    padding: 10px;
    margin-top: 10px;
  }
</style>
<style>
  .multiselect__option {
    text-align: start;
  }
  .multiselect__placeholder {
    float: right;
  }
  .multiselect__option:after {
    left: 0;
    right: unset !important;
  }
</style>