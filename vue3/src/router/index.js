import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '@/views/home/LoginView.vue';
import RegisterView from '@/views/home/RegisterView.vue';
import MainDashboard from '@/views/dashboard/MainDashboard.vue';
import { sendApi } from '@/utils/api';
import { useMenuStore } from '@/stores/menu'
import { useNotificationStore } from '@/stores/notification'
import { BASE_URL } from '@/config';
const icon=BASE_URL+'/assets/images/fav.png'
const routes = [
  {
    path: '/',
    name: 'login',
    component: LoginView,
    meta:{onlyAuth:true}
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView,
    meta:{checkHasMobileId:true,onlyAuth:true}
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: MainDashboard,
    meta: { requiresAuth: true },
    props: { view: 'dashboard' } 
  },
  {
    path: '/places',
    name: 'places',
    component: MainDashboard,
    meta: { requiresAuth: true },
    props: { view: 'places' } 
  },
  {
    path: '/report-list',
    name: 'report-list',
    component: MainDashboard,
    meta: { requiresAuth: true },
    props: { view: 'report-list' } 
  },
  {
    path: '/manage-news',
    name: 'manage-news',
    component: MainDashboard,
    meta: { requiresAuth: true },
    props: { view: 'manage-news' } 
  },
  {
    path: '/user-setting',
    name: 'user-setting',
    component: MainDashboard,
    meta: { requiresAuth: true },
    props: { view: 'user-setting' } 
  },
  {
    path: '/cartable',
    name: 'cartable',
    component: MainDashboard,
    props: { view: 'cartable' },
    meta: { requiresAuth: true }
  },
  {
    path: '/show-cartable/:id',
    name: 'show-cartable',
    component: MainDashboard,
    props: route => ({ view: 'show-cartable', id: route.params.id }),
    meta: { requiresAuth: true }
  },
  {
    path: '/show-news/:id',
    name: 'show-news',
    component: MainDashboard,
    props: route => ({ view: 'show-news', id: route.params.id }),
    meta: { requiresAuth: true }
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach(async (to, from, next) => {
  if (to.name) {
    document.title = to.name;
  }
  const link = document.querySelector("link[rel~='icon']") || document.createElement('link');
  link.href = icon;
  try {
    const meta = to.meta;
    if (meta.requiresAuth) {
      const res = await sendApi({ 
        action: 'check_auth' ,
        control:'security'
       });
      if (res.status !== 'success') return next('/');
    }
    if (meta.checkHasMobileId) {
      const res = await sendApi({ 
        action: 'check_has_mobile',
        control:'security'
      });
      if (res.status !== 'success') return next('/');
    }
    if (meta.onlyAuth) {
      const res = await sendApi({ 
        action: 'check_auth',
        control:'security'
      });
      if (res.status === 'success') return next('/dashboard');
    }
    next();
  } catch (e) {
    console.error('Router Guard Error:', e);
    next('/');
  }
});
router.afterEach(() => {
  const menuStore = useMenuStore()
  menuStore.close()
  const notificationStore=useNotificationStore()
  notificationStore.close()
})
export default router
