<script setup>
import { ref } from 'vue'
import CategorySettingList from '@/components/tooles/category/CategorySettingList.vue'
import UsersSettingList from '@/components/tooles/users/UsersSettingList.vue'
import BaseModal from '@/components/tooles/modal/BaseModal.vue'
const isToolsOpen = ref(false)
const showCategoryModal = ref(false)
const showUserModal = ref(false)
const toggleToolsMenu = () => {
  isToolsOpen.value = !isToolsOpen.value
}
</script>
<template>
  <div class="tools-menu">
    <button class="tools-toggle" @click="toggleToolsMenu">
      ابزارها
      <svg xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16">
        <path d="M480-360 280-560h400L480-360Z"/>
      </svg>
    </button>
    <transition name="fade">
      <div v-if="isToolsOpen" class="tools-items">
        <button class="tool-btn" @click="showCategoryModal=true">
          📂 دسته‌بندی‌ها
        </button>
        <button class="tool-btn" @click="showUserModal=true">
          👥 کاربران
        </button>
      </div>
    </transition>
  </div>
  <BaseModal :show="showCategoryModal" @close="showCategoryModal = false">
    <CategorySettingList />
  </BaseModal>
  <BaseModal :show="showUserModal" @close="showUserModal = false">
    <UsersSettingList />
  </BaseModal>
</template>

<style scoped>
.tools-menu {
  position: relative;
}

.tools-toggle {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 6px 22px;
  background: #88c6ff;
  border: 1px solid #ddd;
  border-radius: 6px;
  cursor: pointer;
}

.tools-items {
  display: flex;
  flex-direction: column;
  gap: 5px;
  position: absolute;
  top: 110%;
  right: 0;
  background: white;
  border: 1px solid #ddd;
  padding: 8px;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.tool-btn {
  background: none;
  border: none;
  text-align: right;
  cursor: pointer;
  padding: 4px;
}

.tool-btn:hover {
  background: #f0f0f0;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>