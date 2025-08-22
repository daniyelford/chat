import { ref, onMounted, onUnmounted } from "vue"
import { io } from "socket.io-client"
import axios from "axios"
export function useRealtime({ baseUrl = "http://localhost:3000", route, eventName }) {
  const items = ref([])
  let socket
  const loadData = async () => {
    try {
      const res = await axios.get(`${baseUrl}/${route}`)
      items.value = res.data
    } catch (err) {
      console.error("Error fetching data:", err)
    }
  }
  onMounted(async () => {
    socket = io(baseUrl)
    await loadData()
    socket.on(eventName, (newItem) => {
      console.log(`Realtime [${eventName}]`, newItem)
      items.value.push(newItem)
    })
  })
  onUnmounted(() => {
    if (socket) socket.disconnect()
  })
  return {
    items,
    reload: loadData
  }
}