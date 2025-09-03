<template>
    <svg viewBox="0 0 36 36">
        <path
        class="circle-bg"
        d="M18 2.0845
            a 15.9155 15.9155 0 0 1 0 31.831
            a 15.9155 15.9155 0 0 1 0 -31.831"
        fill="none"
        stroke="#eee"
        stroke-width="2"
        />
        <path
        class="circle"
        :stroke-dasharray="props.readProgress + ', 100'"
        d="M18 2.0845
            a 15.9155 15.9155 0 0 1 0 31.831
            a 15.9155 15.9155 0 0 1 0 -31.831"
        fill="none"
        stroke="#4caf50"
        stroke-width="2"
        stroke-linecap="round"
        />
        <text x="18" y="20.35" class="percentage" text-anchor="middle">
        {{ props.readProgress.toFixed(0) }}%
        </text>
    </svg>
</template>
<script setup>
    import { defineProps } from 'vue'
    const props=defineProps({
        readProgress: {
            type: Number,
            default: 0
        }
    })
</script>
<style scoped>
    circle {
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
    }
    .percentage {
        font-size: 0.5em;
        fill: #333;
    }
</style>