import { sendApi } from "@/utils/api";
import { BASE_URL,NOTIFICATION_VAP_ID } from "@/config";
import { urlBase64ToUint8Array } from '@/utils/urlBase64ToUint8Array';
export async function subscribeToPush() {
  if ('serviceWorker' in navigator && 'PushManager' in window) {
    navigator.serviceWorker.register(`${BASE_URL}/assets/sw.js`)
    .then(async registration => {
      const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(NOTIFICATION_VAP_ID),
      });
      const response = await sendApi({ control: 'user', action: 'subscribe', data:subscription })
        if (response.status!=='success') throw new Error('Server failed to save subscription');
    })
    .catch(err => {
      console.error('❌ SW registration failed2:', err);
    });
  } else {
    console.warn('❌ Push not supported in this browser.');
  }
}