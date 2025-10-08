<template>
  <div class="intro-container">
    <Swiper
      :modules="[Pagination]"
      direction="vertical"
      :mousewheel="true"
      :pagination="{ clickable: true }"
      class="intro-swiper">
            <SwiperSlide>
        <div class="intro-card">
          <img :src="`${BASE_URL}/assets/images/me.jpg`" alt="Me" class="intro-img" />
          <h2>سلام! من دانیال فرد 👋</h2>
          <p>
            برنامه نویس تحت وب و فعال اجتماعی با هدف ساخت سامانه‌هایی که کار رو برای مردم ساده‌تر کنه 
            در خدمت شمام
            ❤️
            <hr>
            اگر بخوایین میتونید با من به روش های پایین در ارتباط باشید و حتی کار هامو دنبال کنید
            <ol style="text-align: start;direction: rtl;">
                <li><a href="tel:+989336160295">شماره تماس</a></li>
                <li><a href="mailto:29danialfrd69@gmail.com">gmail</a></li>
                <li><a href="https://github.com/daniyelford">github</a></li>
                <li><a href="https://www.npmjs.com/~danialfrd">npm</a></li>
            </ol>
        </p>
        </div>
      </SwiperSlide>
      <SwiperSlide>
        <div class="intro-card">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="120px" height="120px" viewBox="0,0,256,256"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(2.13333,2.13333)"><rect x="19.995" y="43.133" width="80" height="16.849" fill="#000000"></rect><path d="M94.995,60.133h-2v-28h-66v28h-1.99c-4.971,0 -9,4.029 -9,9c0,4.971 4.029,9 9,9h1.99v1c0,18.225 14.775,33 33,33v0c18.225,0 33,-14.775 33,-33v-1h2c4.971,0 9,-4.029 9,-9c0,-4.971 -4.03,-9 -9,-9z" fill="#000000" opacity="0.35"></path><path d="M59.995,108.133v0c-18.225,0 -33,-14.775 -33,-33v-47h66v47c0,18.226 -14.775,33 -33,33z" fill="#ffdddc"></path><path d="M19.995,40.97c0,-28.076 34.084,-21.057 34.084,-21.057c-17.042,0 -2.303,22.461 -34.084,30.884c0,-2.808 0,-9.827 0,-9.827z" fill="#000000"></path><path d="M74.995,16.133c0,0 0.144,33 25,33v-5c0,0 2,-28 -25,-28z" fill="#000000"></path><path d="M25.005,74.133h2.077h0.831l0.474,-18h-3.382c-4.971,0 -9,4.029 -9,9v0c0,4.971 4.03,9 9,9z" fill="#ffdddc"></path><path d="M94.995,74.133h-2.077h-0.831l-0.474,-18h3.382c4.971,0 9,4.029 9,9v0c0,4.971 -4.03,9 -9,9z" fill="#ffdddc"></path><path d="M19.995,21.133c0,0 16.52,21 37,21c16.384,0 29,-12 29,-12c0,0 -4.155,-23.397 -31.461,-23.397c-19.115,0 -34.539,14.397 -34.539,14.397z" fill="#000000" opacity="0.35"></path><path d="M19.995,16.133c0,0 16.52,22 37,22c10.922,0 25,-10 25,-10c0,0 -2.886,-22 -30.192,-22c-19.114,0 -31.808,10 -31.808,10z" fill="#000000"></path><g fill="#000000" opacity="0.35"><path d="M87.3,100h-26.8v-5h26.8c5.349,0 9.7,-4.352 9.7,-9.7v-9.8h5v9.8c0,8.106 -6.595,14.7 -14.7,14.7z"></path></g><g fill="#0075ff"><path d="M87.3,97h-26.8v-5h26.8c5.349,0 9.7,-4.352 9.7,-9.7v-9.8h5v9.8c0,8.106 -6.595,14.7 -14.7,14.7z"></path></g><circle cx="60" cy="97" r="8" fill="#000000" opacity="0.35"></circle><circle cx="60" cy="94" r="8" fill="#0037ff"></circle><rect x="93" y="51" width="13" height="30" fill="#000000" opacity="0.35"></rect><rect x="93" y="48" width="13" height="30" fill="#0037ff"></rect></g></g></svg>
          <h2>پشتیبانی و ارتباط</h2>
          <p>
            هر موقع سوال، مشکل یا پیشنهادی داشتی، از طریق پیام به ادمین با ما در ارتباط باش.
            ما همیشه پاسخ‌ گو هستیم.
          </p>
          <RouterLink :to="{ path: '/login' }" class="login-btn">
            شروع
          </RouterLink>
          
        </div>
      </SwiperSlide>
    </Swiper>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/pagination'
import { Pagination } from 'swiper/modules'
import { BASE_URL } from './config'

const router = useRouter()

</script>

<style scoped>
.intro-container {
  width: 100%;
  height: 100vh;
  background: linear-gradient(to bottom, #f8fafc, #e9ecef);
  display: flex;
  justify-content: center;
  align-items: center;
}
.intro-swiper {
  width: 100%;
  height: 100%;
}
.intro-card {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 30px;
  text-align: center;
}
.intro-img {
  width: 200px;
  height: auto;
  margin-bottom: 20px;
}
h2 {
  font-size: 1.8rem;
  margin-bottom: 10px;
  color: #212529;
}
p {
  max-width: 500px;
  color: #495057;
  font-size: 1rem;
}
.login-btn {
  background-color: #0d6efd;
  color: white;
  text-decoration: none;
  padding: 12px 30px;
  border-radius: 10px;
  margin-top: 20px;
  font-weight: bold;
  transition: 0.3s;
}
.login-btn:hover {
  background-color: #0b5ed7;
}
</style>
