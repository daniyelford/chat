import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '@/views/home/LoginView.vue';
import RegisterView from '@/views/home/RegisterView.vue';
import MainDashboard from '@/views/dashboard/MainDashboard.vue';
import NotFoundPage from '@/components/NotFoundPage.vue';
import { useMenuStore } from '@/stores/menu'
import { useNotificationStore } from '@/stores/notification'
import { useSecurityStore } from '@/stores/security';
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
  { 
    path: '/:pathMatch(.*)*', 
    name: 'NotFound', 
    component: NotFoundPage 
  }
]
const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})
router.beforeEach(async (to, from, next) => {
  const security = useSecurityStore()
  if (to.name) {
    document.title = to.name;
  }
  const link = document.querySelector("link[rel~='icon']") || document.createElement('link');
  link.href = icon;
  if (to.meta.requiresAuth) {
    const ok = await security.checkAuth()
    if (!ok) return next('/')
  }
  if (to.meta.checkHasMobileId) {
    const ok = await security.checkHasMobile()
    if (!ok) return next('/')
  }
  if (to.meta.onlyAuth) {
    const ok = await security.checkOnlyAuth()
    if (ok) return next('/dashboard')
  }
  next()
});
router.afterEach(() => {
  const menuStore = useMenuStore()
  menuStore.close()
  const notificationStore=useNotificationStore()
  notificationStore.close()
})
export default router
