<template>
    <div class="nav-top">
        <TopNav />
    </div>
    <div class="content" :style="contentStyle">
        <NewsCards v-if="props.view === 'dashboard'" />
        <AddNewsForm v-else-if="props.view === 'add-news'"/>
        <ReportList v-else-if="props.view === 'report-list'" />
        <ManageNews v-else-if="props.view === 'manage-news'" />
        <UserSetting v-else-if="props.view === 'user-setting'" />
        <AllCartables v-else-if="props.view === 'cartable'" />
        <ShowCartable v-else-if="props.view === 'show-cartable'" :id="props.id" />
        <ShowNews v-else-if="props.view === 'show-news'" :id="props.id" />
        <p v-else>نمایش مشخصی یافت نشد</p>
    </div>
    <div v-if="menu.isOpen" class="menu-modal" @click.self="menu.close">
        <div class="menu-content">
            <button class="close-btn" @click="menu.close">×</button>
            <ExteraMenu v-if="props.view === 'dashboard'" :cartable="true" :wallet="true" :list="true" :add="true" :manage="true" :setting="true"/>
            <ExteraMenu v-else-if="props.view === 'add-news'" :cartable="true" :wallet="true" :list="true" :add="false" :manage="true" :setting="true"/>
            <ExteraMenu v-else-if="props.view === 'report-list'" :cartable="true" :wallet="true" :list="false" :add="true" :manage="true" :setting="true"/>
            <ExteraMenu v-else-if="props.view === 'manage-news'" :cartable="true" :wallet="true" :list="true" :add="true" :manage="false" :setting="true"/>
            <ExteraMenu v-else-if="props.view === 'show-news'" :cartable="true" :wallet="true" :list="true" :add="true" :manage="false" :setting="true"/>
            <ExteraMenu v-else-if="props.view === 'user-setting'" :cartable="true" :wallet="true" :list="true" :add="true" :manage="true" :setting="false"/>
            <ExteraMenu v-else-if="props.view === 'cartable'" :cartable="false" :wallet="true" :list="true" :add="true" :manage="true" :setting="true"/>
            <ExteraMenu v-else-if="props.view === 'show-cartable'" :cartable="false" :wallet="true" :list="true" :add="true" :manage="true" :setting="true"/>
            <p v-else>نمایش مشخصی یافت نشد</p>
        </div>
    </div>
</template>
<script setup>
    import TopNav from '@/components/dashboard/menus/TopNav.vue';
    import ExteraMenu from '@/components/dashboard/menus/ExteraMenu.vue';
    import AddNewsForm from '@/components/dashboard/pagesContent/AddNewsForm.vue';
    import AllCartables from '@/components/dashboard/pagesContent/AllCartables.vue';
    import NewsCards from '@/components/dashboard/pagesContent/NewsCards.vue';
    import ManageNews from '@/components/dashboard/pagesContent/ManageNews.vue';
    import ReportList from '@/components/dashboard/pagesContent/ReportList.vue';
    import ShowCartable from '@/components/dashboard/pagesContent/ShowCartable.vue';
    import ShowNews from '@/components/dashboard/pagesContent/ShowNews.vue';
    import UserSetting from '@/components/dashboard/pagesContent/UserSetting.vue';
    import { defineProps , ref , computed , onMounted } from 'vue'
    import { useMenuStore } from '@/stores/menu'
    import { subscribeToPush } from '@/utils/pushService';
    const menu = useMenuStore()
    import { BASE_URL } from '@/config';
    const backgroundImage=ref(BASE_URL+'/assets/images/content.jpg')
    const props = defineProps({
        view: String,
        id: [String, Number]
    })
    const contentStyle = computed(() => ({
        backgroundImage: `url(${backgroundImage.value})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
    }))

    onMounted(() => {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register(`${BASE_URL}/assets/sw.js`)
        .then(() => {
            subscribeToPush()
        })
        .catch(err => {
            console.error('SW registration failed:', err);
        });
    }
    });
</script>
<style scoped>
    .menu-modal {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: flex;
        justify-content: flex-end;
    }
    .menu-content {
        width: 290px;
        background: white;
        height: 100%;
    }
    .close-btn {
        font-size: 20px;
        border: none;
        background: transparent;
        cursor: pointer;
        float: left;
    }
    .nav-top,.content{
        position: fixed;
        box-shadow: 0 0 6px grey;
        left: 0;
        right: 0;
        box-sizing: border-box;
        border: 1px solid #9b7ca9c4;
    }
    .nav-top{
        height: 60px;
        z-index: 9;
        top: 0;
    }
    .content{
        background-origin: border-box;
        height: auto;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        top: 60px;
        bottom: 0;
        padding: 10px;
        height: auto;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>