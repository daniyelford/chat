<template>
    <div v-if="show" class="modal-overlay">
        <div class="modal-content">
            <button @click="$emit('close')" class="close-button">X</button>
            <slot />
        </div>
    </div>
</template>
<script setup>
    import { defineProps, defineEmits } from 'vue'
    defineProps(['show'])
    defineEmits(['close'])
</script>
<style scoped>
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        margin-top: 55px;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 10px;
        width: 100%;
        max-width: 640px;
        position: relative;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .close-button {
        position: absolute;
        top: 10px;
        left: 5px;
        color: #ff3b3b;
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
    }
</style>