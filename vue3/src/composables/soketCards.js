import { ref, onMounted, onBeforeUnmount } from 'vue'
import { io } from 'socket.io-client'

const socket = io('http://localhost:3000')
const realtimeData = ref(null)

socket.on('connect', () => {
  console.log('Connected to WebSocket server')
})

socket.on('data-updated', (data) => {
  console.log('Realtime update received:', data)
  realtimeData.value = data
})

onBeforeUnmount(() => {
  socket.disconnect()
})