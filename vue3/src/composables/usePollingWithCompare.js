import { ref, onMounted, onBeforeUnmount } from 'vue'
export function usePollingWithCompare(fetchFn, {
    intervalMs = 6000,
    runOnStart = true,
    isDifferent = (oldData, newData) => JSON.stringify(oldData) !== JSON.stringify(newData),
    onChange = () => {},
} = {}) {
    const intervalId = ref(null)
    const lastData = ref(null)
    const poll = async () => {
        try {
            const result = await fetchFn()
            if (isDifferent(lastData.value, result)) {
                lastData.value = result
                onChange(result)
            }
        } catch (err) {
            console.error('Polling error:', err)
        }
    }
    const startPolling = () => {
        stopPolling()
        intervalId.value = setInterval(() => {
            poll()
        }, intervalMs)
    }
    const stopPolling = () => {
        if (intervalId.value) {
            clearInterval(intervalId.value)
            intervalId.value = null
        }
    }
    const handleVisibilityChange = () => {
        if (document.visibilityState === 'visible') {
            if (runOnStart) poll()
            startPolling()
        } else {
            stopPolling()
        }
    }
    onMounted(() => {
        if (runOnStart) poll()
        startPolling()
        document.addEventListener('visibilitychange', handleVisibilityChange)
    })
    onBeforeUnmount(() => {
        stopPolling()
        document.removeEventListener('visibilitychange', handleVisibilityChange)
    })
    return {
        startPolling,
        stopPolling,
        lastData,
    }
}