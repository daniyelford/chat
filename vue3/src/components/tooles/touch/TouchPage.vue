<template>
    <div ref="swipeArea" class="swipe-area">
        <slot />
    </div>
</template>

<script setup>
    import { ref, onMounted, onBeforeUnmount, defineProps } from "vue"
    import Hammer from "hammerjs"
    import { useRouter } from "vue-router"
    const props = defineProps({
        gestures: {
            type: Object,
            default: () => ({
                left: null,
                right: null,
                up: null,
                down: null,
                tap: null,
                doubletap: null
            })
        }
    })
    const router = useRouter()
    const swipeArea = ref(null)
    let hammerInstance = null
    onMounted(() => {
    if (swipeArea.value) {
        hammerInstance = new Hammer(swipeArea.value)
        hammerInstance.get("swipe").set({ direction: Hammer.DIRECTION_ALL })
        hammerInstance.get("tap").set({ taps: 1 })
        hammerInstance.add(new Hammer.Tap({ event: "doubletap", taps: 2 }))
        hammerInstance.on("swipeleft swiperight swipeup swipedown tap doubletap", (ev) => {
            switch (ev.type) {
                case "swipeleft":
                    if (props.gestures.left) router.push({ name:props.gestures.left })
                    break
                case "swiperight":
                    if (props.gestures.right) router.push({ name:props.gestures.right })
                    break
                case "swipeup":
                    if (props.gestures.up) router.push({ name:props.gestures.up })
                    break
                case "swipedown":
                    if (props.gestures.down) router.push({ name:props.gestures.down })
                    break
                case "tap":
                    if (props.gestures.tap) router.push({ name:props.gestures.tap })
                    break
                case "doubletap":
                    if (props.gestures.doubletap) router.push({ name:props.gestures.doubletap })
                    break
            }
        })
    }
    })

    onBeforeUnmount(() => {
        if (hammerInstance) {
            hammerInstance.destroy()
        }
    })
</script>

<style>
    .swipe-area {
        width: 100%;
        height: 100%;
    }
</style>
