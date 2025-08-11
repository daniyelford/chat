<template>
  <div class="category-settings">
    <form @submit.prevent="handleSubmit" class="category-form">
      <input v-model="form.title" placeholder="نام دسته‌بندی" />
      <select v-model="form.status">
        <option value="active">فعال</option>
        <option value="inactive">غیرفعال</option>
      </select>
      <select v-model="form.for_place">
        <option value="yes">برای مکان</option>
        <option value="no">بدون مکان</option>
      </select>
      <select v-model="form.is_force">
        <option value="1">اجباری</option>
        <option value="0">غیراجباری</option>
      </select>
      <button type="submit">
        {{ editId ? 'ویرایش دسته‌بندی' : 'افزودن دسته‌بندی' }}
      </button>
      <button v-if="editId" type="button" @click="resetForm">انصراف</button>
    </form>
    <div v-if="categoryStore.categoryListLoading">
      <div class="tiny-loader"></div>
    </div>
    <div v-else-if="categoryStore.allCategories.length>0" class="categories">
      <table>
        <thead>
          <tr>
            <th>عنوان</th>
            <th>مخصوص جایگاه مکانی</th>
            <th>دارای اهمیت بالا</th>
            <th>عملیات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cat in categoryStore.allCategories" :key="cat.id">
            <td>
              {{ cat.title }} 
            </td>
            <td>
              {{ cat.for_place }}
            </td>
            <td>
              {{ cat.is_force }}
            </td>
            <td>
              <button @click="startEdit(cat)">ویرایش</button>
              <button @click="deleteCategory(cat.id)">حذف</button>
            </td>
          </tr>
          <tr ref="CategoryLoadTrigger" style="height: 1px;"></tr>
        </tbody>
      </table>
    </div>
    <div v-else class="error">
      دسته بندی ساخته نشده
    </div>
  </div>
</template>

<script setup>
  import { useCategoryStore } from '@/stores/category'
  import { onMounted, ref } from 'vue'
  import { useInfiniteScroll } from '@/composables/useInfiniteScroll'
  const categoryStore = useCategoryStore()
  const editId = ref(null)
  const form = ref({
    title: '',
    status: 'active',
    for_place: 'no',
    is_force: '0'
  })
  const handleSubmit = async () => {
    if (!form.value.title.trim()) return
    const payload = {
      title: form.value.title.trim(),
      status: form.value.status,
      for_place: form.value.for_place,
      is_force: form.value.is_force
    }
    await categoryStore.submitCategory(payload, editId.value)
    resetForm()
  }
  const startEdit = (cat) => {
    editId.value = cat.id
    form.value.title = cat.title
    form.value.status = cat.status
    form.value.for_place = cat.for_place
    form.value.is_force = String(cat.is_force)
  }
  const resetForm = () => {
    editId.value = null
    form.value = {
      title: '',
      status: 'active',
      for_place: 'no',
      is_force: '0'
    }
  }
  const deleteCategory = async (id) => {
    await categoryStore.deleteCategory(id)
  }
  const {
    loadMoreTrigger: CategoryLoadTrigger,
    setupObserver:setupCategoryObserver,
  } = useInfiniteScroll(async ({offset}) => {
    await categoryStore.fetchCategoriesPaginated(offset,10)
    return {
      items: categoryStore.allCategories,
      has_more: categoryStore.hasMoreCategories,
    }
  })
  onMounted(() => {
    setupCategoryObserver()
  })
</script>

<style scoped>
  .category-settings {
    max-width: 600px;
    margin: auto;
    padding: 20px;
  }
  .category-form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
  }
  .category-form input,
  .category-form select {
    flex: 1 1 150px;
  }
  table{
    text-align: center;
  }
  .categories {
    max-height: 350px;
    overflow: auto;
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
