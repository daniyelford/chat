import { getToken } from '@/utils/token'
import { BASE_URL, API_SECRET_KEY } from '@/config'
import router from '@/router'
let isLoggingOut = false
export async function sendApi(data = {}) {
  try {
    const token = await getToken()
    const response = await fetch(`${BASE_URL}/api`, {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
        'X-AUTHORIZATION': `Bearer ${token.token}`,
        'X-API-KEY': API_SECRET_KEY,
        'X-API-KEY-BACK': token.key,
      },
      body: JSON.stringify(data)
    })
    const result = await response.json()
    if (result.code === 401 && result.status === 'error') {
      if (!isLoggingOut) {
        isLoggingOut = true
        localStorage.clear()
        router.push('/')
      }
      window.location.reload()
      throw new Error('Unauthorized')
    }
    return result
  } catch (err) {
    console.error('API Error:', err)
    throw err
  }
}
export function sendApiWithProgress(data = {}, onProgress = null) {
  return new Promise(async (resolve, reject) => {
    try {
      const token = await getToken();
      const xhr = new XMLHttpRequest();
      xhr.open('POST', `${BASE_URL}/api`);
      xhr.withCredentials = true;
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.setRequestHeader('X-AUTHORIZATION', `Bearer ${token.token}`);
      xhr.setRequestHeader('X-API-KEY', API_SECRET_KEY);
      xhr.setRequestHeader('X-API-KEY-BACK', token.key);
      xhr.upload.onprogress = (event) => {
        if (event.lengthComputable && onProgress) {
          onProgress((event.loaded / event.total) * 100);
        }
      };
      xhr.onload = () => {
        if (xhr.status === 200) {
          const result = JSON.parse(xhr.responseText);
          if (result.code === 401 && result.status === 'error') {
            if (!isLoggingOut) {
              isLoggingOut = true;
              localStorage.clear();
              router.push('/');
            }
            window.location.reload();
            reject(new Error('Unauthorized'));
          } else {
            resolve(result);
          }
        } else {
          reject(new Error(`HTTP ${xhr.status}`));
        }
      };
      xhr.onerror = () => reject(new Error('Network error'));
      xhr.send(JSON.stringify(data));
    } catch (err) {
      console.error('API Error:', err);
      reject(err);
    }
  });
}
