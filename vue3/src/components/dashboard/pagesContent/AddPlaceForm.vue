<template>
    <div class="add-place-container">
        <h2>{{ props.editPlace ? 'ویرایش مکان' : 'افزودن مکان جدید' }}</h2>
        <form @submit.prevent="submitPlace">
            <input v-model="form.title" placeholder="عنوان مکان" required />
            <textarea v-model="form.description" placeholder="توضیحات" />
            <Multiselect
                v-model="form.category_id"
                :options="placeStore.categories"
                :searchable="true"
                :loading="placeStore.categoryLoading"
                :internal-search="false"
                :clear-on-select="false"
                @search-change="q => placeStore.fetchCategories(q)"
                :multiple="true"
                track-by="id"
                label="title"
                placeholder="انتخاب دسته‌بندی‌ها"
                selectLabel="برای انتخاب کلیک کنید"
                deselectLabel="برای حذف کلیک کنید"
            />
            <UploaderManyMedia
                :url="'place_media/'"
                :toAction="'addPlace'"
                v-model="form.media_id"
                :initial-medias="initialMedias"
                ref="uploaderRef"
                @done="handleUploadResult"
            />
            <AddressSelector
                :loginCity="address"
                :userCoordinate="userCoordinate"
                :model-value="form.user_address"
                @update="val => form.user_address = val"
                @loading="isAddressLoading = $event"
            />
            <button type="submit" :disabled="isSubmitDisabled">
                {{ props.editPlace ? 'ذخیره تغییرات' : 'ثبت مکان' }}
            </button>
        </form>
    </div>
</template>
<script setup>
    import { ref, computed, onMounted, defineProps, defineEmits, watch } from 'vue'
    import UploaderManyMedia from '@/components/tooles/upload/UploaderManyMedia.vue'
    import AddressSelector from '@/components/tooles/news/AddressSelector .vue'
    import Multiselect from 'vue-multiselect'
    import { usePlaceStore } from '@/stores/place'
    const uploaderRef = ref(null)
    const address = ref('')
    const userCoordinate = ref(null)
    const isAddressLoading = ref(false)
    const placeStore = usePlaceStore()
    const emit = defineEmits(['done'])
    const initialMedias = ref([])
    const props = defineProps({
        editPlace: Object
    })
    const form = ref({
        title: '',
        description: '',
        category_id: [],
        media_id: [],
        user_address: { type: 'location', value: '' }
    })
    const resetForm = () => {
        form.value = {
            title: '',
            description: '',
            media_id: [],
            user_address: { type: 'location', value: '' },
            category_id: []
        }
        initialMedias.value = []
        uploaderRef.value?.reset()
    }
    const handleUploadResult = (uploaded) => {
        form.value.media_id = uploaded.map(i => i.id)
    }
    const isSubmitDisabled = computed(() => {
        const f = form.value
        return !f.title || !f.user_address?.value?.lat || isAddressLoading.value || uploaderRef.value?.uploading
    })
    const submitPlace = async () => {
        const res = await placeStore.submitPlace({
            ...form.value,
            edit: props.editPlace?.id || null
        },
        props.editPlace?.id || null)
        if (res.status === 'success') {
            await placeStore.fetchAllPlaces()
            emit('done')
            resetForm()
        } else {
            alert(res.message || 'خطا در ثبت مکان')
        }
    }
    onMounted( async () => {
        if (props.editPlace) {
            const p = props.editPlace
            form.value.title = p.title || ''
            form.value.description = p.description || ''
            form.value.media_id = p.medias?.map(m => m.id) || []
            initialMedias.value = p.medias || []
            form.value.category_id = p.categories?.map(c => c.id) || []
            form.value.user_address = {
                type: 'location',
                value: {
                    address: p.addresses?.[0]?.address || '',
                    lat: p.addresses?.[0]?.lat,
                    lon: p.addresses?.[0]?.lon,
                    city: p.addresses?.[0]?.city || '',
                    address_id: p.addresses?.[0]?.id || ''
                }
            }
        }
        await placeStore.fetchCategories('')
        address.value = placeStore.userCity || ''
        userCoordinate.value = placeStore.userCoordinate
    })
    watch(() => props.editPlace, (newVal) => {
        if (newVal) {
            const p = newVal
            form.value.title = p.title || ''
            form.value.description = p.description || ''
            form.value.media_id = p.medias?.map(m => m.id) || []
            initialMedias.value = p.medias || []
            form.value.category_id = p.categories?.map(c => c.id) || []
            form.value.user_address = {
            type: 'location',
            value: {
                address: p.addresses?.[0]?.address || '',
                lat: p.addresses?.[0]?.lat,
                lon: p.addresses?.[0]?.lon,
                city: p.addresses?.[0]?.city || '',
                address_id: p.addresses?.[0]?.id || ''
            }
            }
        } else {
            resetForm()
        }
    })
</script>