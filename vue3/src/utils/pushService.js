import { sendApi } from "@/utils/api";
import { BASE_URL } from "@/config";
import { urlBase64ToUint8Array } from '@/utils/urlBase64ToUint8Array';
const publicVapidKey = 'BCqO9cq0iSx7R9EDVULhIo7MmNVvTjFnX4KxouSlzNvU9aY56i6hWXt6_DHeJw9YSQjQw_c-_B9_1I6ImTMGavI';
export async function subscribeToPush() {
  if ('serviceWorker' in navigator && 'PushManager' in window) {
    navigator.serviceWorker.register(`${BASE_URL}/assets/sw.js`)
    .then(async registration => {
      const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(publicVapidKey),
      });
      const response = await sendApi({ control: 'user', action: 'subscribe', data:subscription })
        if (response.status!=='success') throw new Error('Server failed to save subscription');
    })
    .catch(err => {
      console.error('❌ SW registration failed:', err);
    });
  } else {
    console.warn('❌ Push not supported in this browser.');
  }
}