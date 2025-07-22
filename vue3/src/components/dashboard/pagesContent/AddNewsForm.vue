<template>
  <UploaderManyMedia
  :url="'news_media/'"
  :toAction="'addNews'"
  @done="handleUploadResult" />
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
  import { ref , onMounted , computed , defineProps } from 'vue'
  import UploaderManyMedia from '@/components/tooles/upload/UploaderManyMedia.vue'
  import Multiselect from 'vue-multiselect'
  import 'vue-multiselect/dist/vue-multiselect.min.css'
  import AddressSelector from '@/components/tooles/news/AddressSelector .vue'
  import BaseModal from '@/components/tooles/modal/BaseModal.vue'
  // import router from '@/router'
  import { useNewsStore } from '@/stores/news'
  import { sendApi } from '@/utils/api'
  const newsStore = useNewsStore()
  const props = defineProps({
    replyToId: {
      type: Number,
      default: 0,
    }
  })
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
  onMounted(async () => {
    const data = await sendApi({ control: 'news', action: 'add_data' })
    if (data.status === 'success') {
      address.value = data.address
      rule.value = data.rule
      userCoordinate.value={
        lat: data.coordinate.lat,
        lon: data.coordinate.lon
      }
      if (rule.value) {
        categories.value = data.category ? [data.category] : []
        form.value.category_id = data.category ? [data.category] : []
      } else {
        categories.value = data.category ?? []
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
        // router.push({ path: '/manage-news' })
      } else {
        alert('خطا در ثبت خبر: ' + res.message)
      }
    } catch (err) {
      alert('خطا در ارسال: ' + err.message)
    }
  }
</script>
<style scoped>
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