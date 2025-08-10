<template>
  <div>
    <button style="float: left;" @click="openForm()">افزودن کاربر</button>
    <h2>مدیریت کاربران</h2>
    <div v-if="loading">
      <div class="tiny-loader"></div>
    </div>
    <table v-else-if="users.length > 0">
      <tbody>
        <tr v-for="u in users" :key="u.id">
          <td class="p-2">
            <img v-if="u.image" :src="u.image" alt="avatar"/>
            <svg v-else data-v-0c463e07="" xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g data-v-0c463e07=""><rect data-v-0c463e07="" fill="none" height="24" width="24"></rect></g><g data-v-0c463e07=""><path data-v-0c463e07="" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"></path></g></svg>
          </td>
          <td class="p-2">{{ u.name }}</td>
          <td class="p-2">{{ u.family }}</td>
          <td class="p-2">{{ u.mobile }}</td>
          <td class="p-2">
            <div v-if="u.rules?.length">
              <div v-for="(rule,index) in u.rules" :key="index">
                <span>{{ rule.category_name }}</span> - {{ rule.name }}
              </div>
            </div>
            <span v-else>بدون نقش</span>
          </td>
          <td class="p-2">
            <button @click="openForm(u)">ویرایش</button>
            <button v-if="u.user_status === 'active'" @click="onDisableUser(u.user_id)">غیر فعال</button>
            <button v-else @click="onEnableUser(u.user_id)">فعال</button>
          </td>
        </tr>
        <tr ref="UsersLoadTrigger" style="height: 1px;"></tr>
      </tbody>
    </table>
    <div v-else class="error">{{ error }}</div>

    <div v-if="showForm" class="mt-6 border p-4 rounded bg-gray-50">
      <h3 class="font-bold mb-2">{{ editUser ? 'ویرایش کاربر' : 'افزودن کاربر' }}</h3>
      <form @submit.prevent="submitForm">
        <input v-model="form.name" placeholder="نام" class="border p-2 w-full mb-2" />
        <input v-model="form.family" placeholder="نام خانوادگی" class="border p-2 w-full mb-2" />
        <input v-model="form.mobile" placeholder="موبایل" class="border p-2 w-full mb-2" />
        <label class="block font-bold mt-2">نقش‌ها</label>
        <select v-model="form.rule_id" class="border p-2 w-full mb-2">
          <option value="">انتخاب کنید</option>
          <option v-for="r in allRules" :key="r.id" :value="r.id">
            {{ r.name }}
          </option>
        </select>
        <label class="block font-bold mt-2">دسته‌بندی</label>
        <select v-model="form.category_id" class="border p-2 w-full mb-4">
          <option value="">انتخاب کنید</option>
          <option v-for="c in allCategories" :key="c.id" :value="c.id">
            {{ c.title }}
          </option>
        </select>
        <button class="bg-green-600 text-white px-4 py-2 rounded" type="submit">
          ذخیره
        </button>
        <button class="ml-2 px-4 py-2 rounded border" @click="showForm = false" type="button">
          انصراف
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import { useUserStore } from '@/stores/user'
  import { useInfiniteScroll } from '@/composables/useInfiniteScroll'
  import { storeToRefs } from 'pinia'
  const store = useUserStore()
  const { users, hasMore, loading, error, allCategories, allRules } = storeToRefs(store)
  const { fetchUsers, submitUser, disableUser, enableUser } = store
  const showForm = ref(false)
  const editUser = ref(null)
  const form = ref({
    name: '',
    family: '',
    mobile: '',
    rule_id: '',
    category_id: ''
  })
  async function onDisableUser(id) {
    const ok = await disableUser(id)
    if(ok){
      const user = users.value.find(u => u.user_id === id)
      if (user) {
        user.user_status = 'inactive'
      }
    }
  }
  async function onEnableUser(id) {
    const ok = await enableUser(id)
    if(ok){
      const user = users.value.find(u => u.user_id === id)
      if (user) {
        user.user_status = 'active'
      }
    }
  }
  function openForm(user = null) {
    editUser.value = user
    if (user) {
      form.value = {
        name: user.name,
        family: user.family,
        mobile: user.mobile,
        rule_id: user.rules?.[0]?.rule_id || '',
        category_id: user.rules?.[0]?.category_id || ''
      }
    } else {
      form.value = {
        name: '',
        family: '',
        mobile: '',
        rule_id: '',
        category_id: ''
      }
    }
    showForm.value = true
  }
  async function submitForm() {
    await submitUser(form.value, editUser.value ?? null)
    showForm.value = false
  }
  const {
    loadMoreTrigger: UsersLoadTrigger,
    setupObserver:setupUsersObserver,
  } = useInfiniteScroll(async ({offset}) => {
    await fetchUsers(10,offset)
    return {
      items: users,
      has_more: hasMore,
    }
  })
  onMounted(async () => {
    setupUsersObserver()
  })
</script>

<style scoped>

  table {
    max-height: 300px;
    overflow: auto;
  }
  table td {
    text-align: center;
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
