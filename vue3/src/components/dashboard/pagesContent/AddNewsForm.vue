<template>
  <div class="top-send">
    <div v-if="replyCard" class="reply-preview">
      <div class="reply-box">
        <strong>{{ props.editReport ? 'ویرایش پاسخ' : 'پاسخ به:' }}</strong>
        <div class="user-replay">
          <img v-if="replyCard.user.image" :src="replyCard.user.image" alt="reporter image">
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></g></svg>
          <p v-if="replyCard?.user"> {{ replyCard.user.name }} {{ replyCard.user.family }}</p>
        </div>
        <p v-if="replyCard?.description">{{ replyCard.description }}</p>
        <button class="close-btn" @click="clearReply">×</button>
      </div>
    </div>
    <div class="edit" v-if="props.editData || props.editReport">
      <button class="close-btn" @click="clearReply">×</button>
      ویرایش
    </div>
    <UploaderManyMedia
    :url="'news_media/'"
    :toAction="'addNews'"
    :initial-medias="medias"
    :edit-mode="!!props.editData || !!props.editReport"
    v-model="form.media_id"
    ref="uploaderRef"
    @done="handleUploadResult" 
    @delete="id => pendingDeletes.value.push(id)"/>
  </div>
  <form @submit.prevent="submitForm">
    <textarea v-model="form.description" rows="4" required placeholder="متن خبر"></textarea>
    <BaseModal :show="showModal" @close="showModal = false">
      <button v-if="!showAllCategory && rule" @click="toggleShowAllCategory">
        برون سازمانی
      </button>
      <button v-if="showAllCategory && rule" @click="toggleShowAllCategory">
        درون سازمانی
      </button>
      <div v-if="showAllCategory" class="selectInner">
        <multiselect
          v-model="form.category_id"
          :options="categories"
          :multiple="true"
          track-by="id"
          :value-prop="'id'"
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
      <button type="submit" :disabled="isSubmitDisabled">
        {{ props.editData ? 'ذخیره تغییرات' : 'ثبت خبر' }}
      </button>
    </BaseModal>
  </form>
  <button :disabled="isSaveDisabled" @click="showModal = true" style="border-top-right-radius: 15px;">
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256"><g fill="#031c66" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(2,2)"><path d="M24,20c-7.2,0 -13,5.8 -13,13v62c0,7.2 5.8,13 13,13h72.80078l-7.80078,7.80078c-1.1,1.1 -1.30078,2.99922 -0.30078,4.19922c0.6,0.7 1.40078,1 2.30078,1c0.8,0 1.49961,-0.30039 2.09961,-0.90039l13,-13c1.2,-1.2 1.2,-3.09922 0,-4.19922l-12.90039,-12.90039c-1.1,-1.1 -2.99922,-1.29922 -4.19922,-0.19922c-1.3,1.2 -1.29961,3.09844 -0.09961,4.39844l7.90039,7.90039h-72.80078c-3.9,0 -7,-3.1 -7,-7v-62.09961c0,-3.9 3.1,-7 7,-7h80c3.9,0 7,3.1 7,7v19c0,1.7 1.3,3 3,3c1.7,0 3,-1.3 3,-3v-19c0,-7.2 -5.8,-13 -13,-13zM27.63672,44.83789c-0.7625,-0.0625 -1.53672,0.16211 -2.13672,0.66211c-1.2,1 -1.40039,2.99922 -0.40039,4.19922c0.9,1.1 21.50039,25.30078 38.90039,25.30078c17.4,0 38.00039,-24.19922 38.90039,-25.19922c1.1,-1.3 0.89961,-3.20117 -0.40039,-4.20117c-1.2,-1.1 -3.09922,-0.99922 -4.19922,0.30078c0,0.1 -5.10039,5.99883 -11.90039,11.79883c-8.8,7.4 -16.50039,11.30078 -22.40039,11.30078c-5.9,0 -13.60039,-3.90078 -22.40039,-11.30078c-6.9,-5.8 -11.90039,-11.69883 -11.90039,-11.79883c-0.55,-0.65 -1.3,-1 -2.0625,-1.0625zM114,70c-1.7,0 -3,1.3 -3,3v10c0,1.7 1.3,3 3,3c1.7,0 3,-1.3 3,-3v-10c0,-1.7 -1.3,-3 -3,-3z"></path></g></g></svg>
  </button>
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
  const pendingDeletes = ref([])
  const newsStore = useNewsStore()
  const props = defineProps({
    replyToId: {
      type: Number,
      default: 0,
    },
    editData: { 
      type: Object, 
      default: null 
    },
    editReport: { 
      type: Object, 
      default: null 
    }
  })  
  const emit = defineEmits(["clearReplyId"])
  const uploaderRef = ref(null)
  const categories = ref([])
  const address = ref('')
  const rule = ref(false)
  const high = ref(false)
  const userCoordinate=ref(null)
  const isAddressLoading = ref(false)
  const showModal = ref(false)
  const showAllCategory = ref(true)
  const medias = ref([])
  const myCategory = ref([])
  const form = ref({
    user_address: { type: 'location', value: '' },
    media_id: [],
    description: '',
    category_id: [],
  })
  const handleUploadResult = (uploadedData) => {
    form.value.media_id = uploadedData.map(item => item.id)
  }
  function toggleShowAllCategory(){
    showAllCategory.value=!showAllCategory.value
    if(showAllCategory.value){
      form.value.category_id=[]
    }else{
      if(rule.value){
        form.value.category_id=myCategory.value
      }
    }
  }
  function clearReply() {
    replyCard.value = null
    form.value = {
      user_address: { type: 'location', value: '' },
      media_id: [],
      description: '',
      category_id: rule.value ? form.value.category_id : []
    }
    medias.value = []
    emit('clearReplyId')
  }
  onMounted(async () => {
    const data = await newsStore.fetchAddNewsData()
    if (data) {      
      address.value = data.address
      rule.value = data.rule
      high.value = data.highRule
      userCoordinate.value = data.coordinate
      if (rule.value) {
        form.value.category_id = data.myCategory??[]
        myCategory.value = data.myCategory??[]
        showAllCategory.value=false
      }
      categories.value = data.category??[]
    } 
  })
  const isSubmitDisabled = computed(() => {
    const isDescriptionEmpty = !form.value.description.trim()
    const isCategoryInvalid = (!rule.value || high.value) && (!form.value.category_id || form.value.category_id.length === 0)
    const isLocationSelected = form.value.user_address?.type === 'location'
    const isAddressInvalid = isLocationSelected && (!form.value.user_address?.value || !form.value.user_address.value.address?.trim())
    const isStillLoading = isLocationSelected && isAddressLoading.value
    const isUploading = uploaderRef.value?.uploading === true
    return isDescriptionEmpty || isCategoryInvalid || isAddressInvalid || isStillLoading || isUploading
  })
  const isSaveDisabled = computed(()=>{
    return !form.value.description.trim()
  })
  const submitForm = async () => {
    const finalData = {
      ...form.value,
      reply_to_id: props.replyToId,
      edit: props.editData?.id || null,
      edit_report: props.editReport?.id || null,
      delete_media: pendingDeletes.value
    }
    try {
      const res = await newsStore.addNews(finalData)
      if (res.status === 'success') {
        showModal.value = false
        pendingDeletes.value = []
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
  watch(() => props.editData, (newVal) => {
    if (newVal) {
      form.value.description = newVal.description || ''
      form.value.category_id = newVal.category
      form.value.media_id = newVal.medias?.map(m => m.id) || []
      medias.value = newVal.medias || []
      form.value.user_address = {
        type: 'location',
        value: {
          address: newVal.location?.address || '',
          address_id: newVal.location?.address_id || '',
          lat: newVal.location?.lat,
          lon: newVal.location?.lon,
          city: newVal.location?.city,
        }
      }
    }
  })
  watch(() => props.editReport, (report) => {
    if (report) {
      form.value.description = report.description || ''
      form.value.media_id = report.media?.map(m => m.id) || []
      medias.value = report.media || []
      form.value.user_address = {
        type: 'location',
        value: {
          address: report.location?.address || '',
          address_id: report.location?.address_id || '',
          lat: report.location?.lat,
          lon: report.location?.lon,
          city: report.location?.city
        }
      }
    }
  })

</script>
<style scoped>
  .edit{
    background: beige;
    direction: rtl;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .edit button{
    position: static;
    padding: 5px;
    width: 30px;
    height: 30px;
    background: red;
    border-radius: 30px;
  }
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
    min-width: 50%;
    width: calc(100% - 90px);
    max-width: calc(100% - 90px);
    min-height: 45px;
    height: 45px;
    max-height: 200px;
    outline: none;
    border: unset;
    padding: .5rem;
    box-sizing: border-box;
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