<template>
  <div class="slider-container">
    <Swiper
    :modules="[Navigation, Pagination, Autoplay]"
    navigation
    :autoplay="{ delay: 10000, stopOnLastSlide: true }"
    :pagination="{ clickable: true }"
    :loop="false"
    class="slider-box"
    >
      <SwiperSlide v-for="(card, index) in cards" :key="index">
        <div class="card">
          <img :src="BASE_URL+card.image" alt="" class="card-img" />
          <h2 class="card-title">{{ card.title }}</h2>
          <p class="card-text">{{ card.long }}</p>
          <div v-if="index === cards.length - 1" class="login-btn-box">
            <router-link :to="{path:'/login'}" class="login-btn">
              ورود
            </router-link>
          </div>
        </div>
      </SwiperSlide>
    </Swiper>
  </div>
</template>
<script setup>
import { Swiper, SwiperSlide } from "swiper/vue"
import { BASE_URL } from "./config"
import { Navigation, Pagination, Autoplay } from "swiper/modules"
import "swiper/css"
import "swiper/css/navigation"
const cards = [
  {
      title: "خوش آمدید",
      long:"شما در این برنامه می توانید درخواست های خود را مکتوب و به دولت ارجاع دهید",
      image: "/assets/images/fav.png",
  },
  {
    title: "ورود و ثبت‌ نام",
    long: "ورود با شماره موبایل و تایید کد برای اولین بار و سپس با فعال سازی اثر انگشت بدون دردسر و سریع امن و آماده‌ی استفاده. حساب کاربری بساز یا سریع وارد شو و از اخبار روز آگاه شو و از خدمات استفاده کن.",
    image: "/assets/images/login.png",
  },
  {
    title: "عملکرد",
    long: "اخبار روز رو به صورت عمومی دنبال کن و مشکلات و سوالاتت رو به‌صورت چت محرمانه ارسال کن؛ پشتیبانی پاسخ می‌ده، راهنمایی می‌شه و در صورت نیاز پیگیری رسمی انجام می‌شود و شما پاسخ را از ارگان مورد نظر دریافت می کنید.",
    image: "/assets/images/chat.png",
  },
  {
    title: "درخواست ملاقات",
    long: "در صورتی که مشکل یا سوال شما نیاز به نشست حضوری داشته باشد ارگان مربوطه برای شما تایخی را برای این کار در نظر خواهد گرفت",
    image: "/assets/images/meeting.png",
  },
  {
    title: "ضبط صدا و تبدیل به متن",
    long: "کافیست صحبت کنی؛ صدای شما ضبط و به متن تبدیل می‌شود تا سریع ارسال یا آرشیو شود — مناسب برای گزارش سریع و ثبت درخواست‌ ها.",
    image: "/assets/images/voice.png",
  },
  {
    title: "ارسال عکس و مدارک",
    long: "تصاویر مربوط به مشکل، مدرک یا محل را بارگذاری کن — همراه با درخواستت ارسال و برای بررسی ذخیره خواهد شد.",
    image: "/assets/images/upload.png",
  },
  {
    title: "ثبت موقعیت",
    long: "اگر مکان دقیق را می‌دانی، آن را دستی وارد کن یا از نقشه انتخاب کن؛ اطلاعات موقعیت همراه درخواست ارسال و برای پیگیری استفاده می‌شود.",
    image: "/assets/images/location.png",
  },
  {
    title: "ارجاع به نهاد مربوطه",
    long: "پس از ثبت، می‌توانی درخواست را به استان یا سازمان مرتبط ارسال کنی تا پیگیری رسمی انجام شود و وضعیت پاسخ را مشاهده کنی.",
    image: "/assets/images/org.png",
  },
   {
    title: "اعلان‌ها",
    long: "با اعلان‌ها در لحظه از رویدادها و تغییرات مهم آگاه شوید، پیام‌ها و وضعیت درخواست‌ها به شما اطلاع داده می‌شود.",
    image: "/assets/images/notification.png",
  },
  {
    title: "مدیریت اخبار",
    long: "تمامی اخبار سیستم را در بخش مربوطه مدیریت کنید، ارسال یا عدم ارسال اخبار و اطلاع‌رسانی گزارشات و ملاقات ها.",
    image: "/assets/images/news.png",
  },
    {
    title: "ویرایش خبر",
    long: "هر خبر را باز کنید، متن و تصویر آن را تغییر دهید و ذخیره کنید تا کاربران نسخه‌ی به‌روز شده را مشاهده کنند.",
    image: "/assets/images/edit-news.png",
  },
  {
    title: "تنظیمات کاربر",
    long: "اطلاعات حساب خود مانند اسم و فامیل و اثر انگشت را مدیریت و به‌روزرسانی کنید.",
    image: "/assets/images/user-settings.png",
  },
  {
    title: "مکان‌های اطراف",
    long: "مکان‌ها را بر اساس دسته‌بندی (خدمات، فروش، ادارات و...) و انتخاب شهر یا موقعیت خود فیلتر کن و نزدیک‌ترین‌ها را مشاهده و تماس بگیر یا مراجعه کن.",
    image: "/assets/images/places.png",
  },
]

</script>
<style>
.swiper-pagination {
    position: relative;
    bottom: 25px;
    text-align: center;
    height: 20px !important;
    display: flex;
    gap: 5px;
    align-items: center;
    justify-content: center;
    flex-wrap: nowrap;
    flex-direction: row;
}

.swiper-pagination-bullet {
    width: 15px;
    height: 15px;
    border: 1px solid grey;
    border-radius: 50px;
}

.swiper-pagination-bullet-active {
  background: #2563eb;
  transform: scale(1.2);
}

.swiper-button-next, .swiper-button-prev {
    top: 60% !important;
}
</style>

<style scoped>
.slider-container {
  max-width: 900px;
  margin: 40px auto;
}

.slider-box {
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
  background: #fff;
}

/* کارت‌ها */
.card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 40px 20px;
  min-height: 400px;
}

.card-img {
  width: 100%;
  height: 250px;
  object-fit: contain;
  margin-bottom: 25px;
}

.card-title {
  font-size: 22px;
  font-weight: bold;
  color: #222;
  margin-bottom: 15px;
}

.card-text {
  font-size: 15px;
  color: #666;
  line-height: 1.6;
  max-width: 600px;
}
.login-btn-box {
  margin-top: 30px;
}
.login-btn {
  background: linear-gradient(90deg, #2563eb, #4f46e5);
  border: none;
  color: #fff;
  font-size: 16px;
  font-weight: bold;
  padding: 12px 28px;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
}

.login-btn:hover {
  transform: scale(1.05);
  background: linear-gradient(90deg, #1d4ed8, #4338ca);
}
</style>
