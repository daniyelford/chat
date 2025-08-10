<template>
  <div class="p-4">
    <h2 class="text-lg font-bold mb-4">مدیریت کاربران</h2>

    <!-- دکمه افزودن کاربر -->
    <button
      class="bg-green-500 text-white px-4 py-2 rounded mb-4"
      @click="openForm()"
    >
      افزودن کاربر
    </button>

    <!-- خطا -->
    <div v-if="error" class="bg-red-100 text-red-700 p-2 rounded mb-4">
      {{ error }}
    </div>

    <!-- جدول کاربران -->
    <table class="w-full border">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2">#</th>
          <th class="p-2">نام</th>
          <th class="p-2">موبایل</th>
          <th class="p-2">عملیات</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="u in users" :key="u.id" class="border-t">
          <td class="p-2">{{ u.id }}</td>
          <td class="p-2">{{ u.fullName }}</td>
          <td class="p-2">{{ u.mobile }}</td>
          <td class="p-2">
            <button class="bg-blue-500 text-white px-2 py-1 rounded mr-1" @click="openForm(u)">ویرایش</button>
            <button class="bg-red-500 text-white px-2 py-1 rounded" @click="removeUser(u.id)">حذف</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- دکمه لود بیشتر -->
    <div v-if="hasMore && !loading" class="mt-4">
      <button class="bg-gray-500 text-white px-4 py-2 rounded" @click="loadMore">
        بارگذاری بیشتر
      </button>
    </div>

    <!-- لودینگ -->
    <div v-if="loading" class="mt-4">در حال بارگذاری...</div>

    <!-- فرم افزودن/ویرایش -->
    <div v-if="showForm" class="mt-6 border p-4 rounded bg-gray-50">
      <h3 class="font-bold mb-2">{{ editUser ? 'ویرایش کاربر' : 'افزودن کاربر' }}</h3>
      <form @submit.prevent="submitForm">
        <input v-model="form.fullName" placeholder="نام کامل" class="border p-2 w-full mb-2" />
        <input v-model="form.mobile" placeholder="موبایل" class="border p-2 w-full mb-2" />
        <button class="bg-green-600 text-white px-4 py-2 rounded" type="submit">
          ذخیره
        </button>
        <button class="ml-2 px-4 py-2 rounded border" @click="cancelForm" type="button">
          انصراف
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useUserStore } from '@/stores/user'

const store = useUserStore()

const { users, hasMore, loading, error, fetchUsers, submitUser, deleteUser } = store

const limit = ref(10)
const offset = ref(0)
const showForm = ref(false)
const editUser = ref(null)
const form = ref({ fullName: '', mobile: '' })

// بارگذاری اولیه
onMounted(() => {
  fetchUsers({ limite: limit.value, offset: offset.value })
})

// بارگذاری بیشتر
function loadMore() {
  offset.value += limit.value
  fetchUsers({ limite: limit.value, offset: offset.value })
}

// حذف کاربر
async function removeUser(id) {
  if (confirm('آیا از حذف مطمئن هستید؟')) {
    await deleteUser(id)
  }
}

// باز کردن فرم
function openForm(user = null) {
  editUser.value = user
  if (user) {
    form.value.fullName = user.fullName
    form.value.mobile = user.mobile
  } else {
    form.value.fullName = ''
    form.value.mobile = ''
  }
  showForm.value = true
}

// بستن فرم
function cancelForm() {
  showForm.value = false
}

// ارسال فرم
async function submitForm() {
  await submitUser(form.value, editUser.value ? editUser.value.id : null)
  // ری‌لود لیست
  offset.value = 0
  await fetchUsers({ limite: limit.value, offset: offset.value })
  showForm.value = false
}
</script>

<style scoped>
  table th,
  table td {
    text-align: center;
  }
</style>
