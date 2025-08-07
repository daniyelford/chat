<script setup>
    import { onMounted, ref , defineProps } from 'vue'
    import { useNewsStore } from '@/stores/news'
    import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
    import moment from 'moment-jalaali'
    const props=defineProps({
        id:Number
    })
    const store = useNewsStore()
    const news = ref(null)
    const restoreNews = async (id) => {
        await sendApi({ control: 'news', action: 'restore_news', data: { id: id } })
        news.value = await store.fetchNewsById(props.id)
    }
    const deleteNews = async (id) => {
        await sendApi({ control: 'news', action: 'delete_news', data: { id: id } })
        news.value = await store.fetchNewsById(props.id)
    }
    onMounted(async () => {
        news.value = await store.fetchNewsById(props.id)
    })
</script>

<template>
    <div v-if="news" class="news">
        <small class="pub" v-if="news.privacy==='public'">
            عمومی
        </small>
        <small class="pri" v-else>
            محرمانه
        </small>
        <div class="user-info">
            <img class="user-img" v-if="news.user.image" :src="news.user.image" alt="newser image">
            <svg class="user-img" v-else xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></g></svg>
            <span>
                {{ news.user.name }} {{ news.user.family }}
            </span>
        </div>
        <div class="media">
            <MediaSlider v-if="news.medias.length" :medias="news.medias" />
        </div>
        <span class="category" v-for="cat in news.category" :key="cat.id">
            {{ cat.title }}
        </span> 
        <h3>{{ news.description }}</h3>
        <p v-if="news.location?.address" style="margin: 10px 0;">{{ news.location.address }}</p>
        <small>{{ moment(news.created_at).format('jYYYY/jMM/jDD') }}</small>
        
        <button
        class="c-s"
        v-if="news.show_status === 'dont'"
        @click="restoreNews(news.id)">
            پخش مجدد
        </button>
        <button
        class="c-r"
        v-if="news.show_status === 'do'"
        @click="deleteNews(news.id)">
            عدم پخش
        </button>
    </div>
    <div v-else>
        <p>در حال بارگذاری...</p>
    </div>
</template>
<style scoped>
    .news {
        background: white;
        padding: 15px;
        border-radius: 10px;
        direction: rtl;
    }
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .user-img {
        width: 50px;
        height: 50px;
        border-radius: 50px;
    }
    .media {
        margin-top: 15px;
        text-align: center;
    }
    .pub,.pri{
        padding: 2px;
        border-radius: 5px;
        float: left;
    }
    .pub{
        background: #03b403;
        color: #70f870;
    }
    .pri{
        background: #cc0303;
        color: #ec8065;
    }
</style>