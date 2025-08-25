import { ref, onMounted, onUnmounted } from "vue";
export function useSocket(url = "ws://localhost:9090") {
  const socket = ref(null);
  const isConnected = ref(false);
  const messages = ref([]);
  const connect = () => {
    socket.value = new WebSocket(url);
    socket.value.onopen = () => {
      isConnected.value = true;
      console.log("🔌 Connected to WebSocket");
    };
    socket.value.onmessage = (event) => {
      try {
        const data = JSON.parse(event.data);
        messages.value.push(data);
        console.log("📩 New message:", data);
      } catch {
        messages.value.push(event.data);
        console.log("📩 Raw message:", event.data);
      }
    };
    socket.value.onclose = () => {
      isConnected.value = false;
      console.log("❌ Disconnected from WebSocket");
    };
    socket.value.onerror = (err) => {
      console.error("⚠️ WebSocket error:", err);
    };
  };
  const send = (msg) => {
    if (isConnected.value && socket.value) {
      const payload = typeof msg === "object" ? JSON.stringify(msg) : msg;
      socket.value.send(payload);
      console.log("📤 Sent:", payload);
    }
  };
  onMounted(connect);
  onUnmounted(() => {
    if (socket.value) {
      socket.value.close();
    }
  });
  return {
    isConnected,
    messages,
    send,
  };
}
