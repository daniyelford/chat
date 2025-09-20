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
            <svg v-else version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0,0,256,256"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(2.56,2.56)"><path d="M73,80h-46c-6.075,0 -11,-4.925 -11,-11v-38c0,-6.075 4.925,-11 11,-11h46c6.075,0 11,4.925 11,11v38c0,6.075 -4.925,11 -11,11z" fill="#b2b1c2"></path><path d="M73,76h-46c-3.866,0 -7,-3.134 -7,-7v-38c0,-3.866 3.134,-7 7,-7h46c3.866,0 7,3.134 7,7v38c0,3.866 -3.134,7 -7,7z" fill="#94effe"></path><path d="M73,81h-46c-6.617,0 -12,-5.383 -12,-12v-38c0,-6.617 5.383,-12 12,-12h46c6.617,0 12,5.383 12,12v38c0,6.617 -5.383,12 -12,12zM27,21c-5.514,0 -10,4.486 -10,10v38c0,5.514 4.486,10 10,10h46c5.514,0 10,-4.486 10,-10v-38c0,-5.514 -4.486,-10 -10,-10z" fill="#1f212b"></path><path d="M79.5,69v-4.5l-13.294,-13.294c-0.471,-0.471 -1.089,-0.706 -1.706,-0.706c-0.617,0 -1.235,0.235 -1.706,0.706l-24.294,24.294h34.5c3.59,0 6.5,-2.91 6.5,-6.5z" fill="#52bea0"></path><path d="M73,76h-34.5c-0.202,0 -0.385,-0.122 -0.462,-0.309c-0.077,-0.187 -0.034,-0.402 0.108,-0.545l24.295,-24.294c1.098,-1.099 3.02,-1.099 4.117,0l13.295,13.294c0.094,0.094 0.147,0.221 0.147,0.354v4.5c0,3.86 -3.141,7 -7,7zM39.707,75h33.293c3.309,0 6,-2.691 6,-6v-4.293l-13.148,-13.148c-0.721,-0.721 -1.982,-0.721 -2.703,0z" fill="#1f212b"></path><path d="M27,75.5h40.5l-31.294,-31.294c-0.471,-0.471 -1.089,-0.706 -1.706,-0.706c-0.617,0 -1.235,0.235 -1.706,0.706l-12.294,12.294v12.5c0,3.59 2.91,6.5 6.5,6.5z" fill="#00a6a6"></path><path d="M67.5,76h-40.5c-3.859,0 -7,-3.14 -7,-7v-12.5c0,-0.133 0.053,-0.26 0.146,-0.354l12.295,-12.294c1.098,-1.099 3.02,-1.099 4.117,0l31.295,31.294c0.143,0.143 0.186,0.358 0.108,0.545c-0.078,0.187 -0.259,0.309 -0.461,0.309zM21,56.707v12.293c0,3.309 2.691,6 6,6h39.293l-30.441,-30.441c-0.721,-0.721 -1.982,-0.721 -2.703,0z" fill="#1f212b"></path><path d="M73,76h-46c-3.859,0 -7,-3.14 -7,-7v-38c0,-3.86 3.141,-7 7,-7h41.5c0.276,0 0.5,0.224 0.5,0.5c0,0.276 -0.224,0.5 -0.5,0.5h-41.5c-3.309,0 -6,2.691 -6,6v38c0,3.309 2.691,6 6,6h46c3.309,0 6,-2.691 6,-6v-15.5c0,-0.276 0.224,-0.5 0.5,-0.5c0.276,0 0.5,0.224 0.5,0.5v15.5c0,3.86 -3.141,7 -7,7z" fill="#1f212b"></path><path d="M79.5,44c-0.276,0 -0.5,-0.224 -0.5,-0.5v-2c0,-0.276 0.224,-0.5 0.5,-0.5c0.276,0 0.5,0.224 0.5,0.5v2c0,0.276 -0.224,0.5 -0.5,0.5z" fill="#1f212b"></path><path d="M79.5,51c-0.276,0 -0.5,-0.224 -0.5,-0.5v-4c0,-0.276 0.224,-0.5 0.5,-0.5c0.276,0 0.5,0.224 0.5,0.5v4c0,0.276 -0.224,0.5 -0.5,0.5z" fill="#1f212b"></path><circle cx="66.5" cy="37.5" r="5" fill="#ffe31c"></circle><path d="M66.5,43c-3.032,0 -5.5,-2.467 -5.5,-5.5c0,-3.033 2.468,-5.5 5.5,-5.5c3.032,0 5.5,2.467 5.5,5.5c0,3.033 -2.468,5.5 -5.5,5.5zM66.5,33c-2.481,0 -4.5,2.019 -4.5,4.5c0,2.481 2.019,4.5 4.5,4.5c2.481,0 4.5,-2.019 4.5,-4.5c0,-2.481 -2.019,-4.5 -4.5,-4.5z" fill="#1f212b"></path><path d="M94,95c-0.256,0 -0.512,-0.098 -0.707,-0.293l-88,-88c-0.391,-0.391 -0.391,-1.023 0,-1.414c0.391,-0.391 1.023,-0.391 1.414,0l88,88c0.391,0.391 0.391,1.023 0,1.414c-0.195,0.195 -0.451,0.293 -0.707,0.293z" fill="#1f212b"></path></g></g></svg>
        </div>
        <span class="category" v-for="cat in news.category" :key="cat.id">
            {{ cat.title }}
        </span> 
        <h3>{{ news.description }}</h3>
        <span v-if="news.self">
            <span v-if="userStore.status==='active'">
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
            </span>
            <div v-else class="ban">
                اکانت شما
                <span v-if="userStore.banTime">
                    از تاریخ
                    {{ moment(userStore.banTime).format('jYYYY/jMM/jDD') }}
                </span>
                غیر فعال است
            </div>
        </span>
        <div class="address">
            <p v-if="news.location?.address">{{ news.location.address }}</p>
            <span class="category">{{ moment(news.created_at).format('jYYYY/jMM/jDD') }}</span>
        </div>
    </div>
    <div v-else>
        <div class="tiny-loader"></div>
    </div>
</template>
<script setup>
    import { onMounted, ref , defineProps } from 'vue'
    import { useNewsStore } from '@/stores/news'
    import { useUserStore } from '@/stores/user'
    import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
    import moment from 'moment-jalaali'
    const props=defineProps({
        id:Number
    })
    const store = useNewsStore()
    const userStore = useUserStore()
    const news = ref(null)
    const restoreNews = async (id) => {
        await store.newsRestore(id)
        news.value = await store.fetchNewsById(props.id)
    }
    const deleteNews = async (id) => {
        await store.newsDelete(id)
        news.value = await store.fetchNewsById(props.id)
    }
    onMounted(async () => {
        await userStore.fetchUserInfo()
        news.value = await store.fetchNewsById(props.id)
    })
</script>
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
        margin: 15px 0;
        text-align: center;
    }
    .pub,.pri{
        padding: 2px;
        border-radius: 5px;
    }
    .pub{
        background: #03b403;
        color: #70f870;
    }
    .pri{
        background: #cc0303;
        color: #ec8065;
    }
    .category {
        background: lightgrey;
        border-radius: 5px;
        padding: 4px 10px;
        margin: 5px 1px;
        display: inline-block;
    }
    h3 {
        margin: 15px 0 0 0;
        text-align: center;
        background: yellow;
        padding: 5px;
    }
    .address{
        margin: 0 0 10px 0;
        background: beige;
        padding: 19px;
        border-radius: 0 0 20px 20px;
        text-align: center;
    }
    .address p {
        margin: 0;
    }
    small {
        margin-top: 5px;
        float: left;
    }
    .c-r,.c-s{
        width: 100%;
        display: block;
        text-align: center;
        padding: 10px;
        border: none;
        background: #4e3a85;
        color: white;
        cursor: pointer;
        text-decoration: none;
        box-sizing: border-box;
    }
    .c-r {
        background: #ac3427 !important;
    }
    .c-s {
        background: #29921b !important;
    }
    .ban{
        color: red;
        padding: 5px;
    }
    .tiny-loader {
        width: 20px;
        height: 20px;
        border: 2px solid #ccc;
        border-top-color: #333;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 10px auto;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>