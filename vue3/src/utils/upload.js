import { sendApi,sendApiWithProgressVideos } from '@/utils/api'
export const uploadVideoInChunks = async (file , url , toAction , chunkSizeMB = 5, onProgress = null) => {
  const chunkSize = chunkSizeMB * 1024 * 1024;
  const totalChunks = Math.ceil(file.size / chunkSize);
  const fileId = `${Date.now()}_${file.name}`;
  let uploadedChunks = 0;
  for (let i = 0; i < totalChunks; i++) {
    const start = i * chunkSize;
    const end = Math.min(start + chunkSize, file.size);
    const chunk = file.slice(start, end);
    const data = {
      id:fileId,
      url:url,
      toAction:toAction,
      chunkIndex: i,
      total: totalChunks,
      fileName: file.name,
      fileSize: file.size,
      isLastChunk: i === totalChunks - 1
    };
    const result=await sendApiWithProgressVideos([chunk], data, (percent) => {
      const overall = Math.round(((uploadedChunks + percent / 100) / totalChunks) * 100);
      if (onProgress) onProgress(overall);
    });
    if(result.status==='success'&&result.result.status==='success') return result.result.videos;
    uploadedChunks++;
  }
}
export const uploadImages = async (base64 , url , toAction) => {
    try {
      const response = await sendApi({
        control: 'upload',
        action: 'upload_many_images',
        data: {
          url: url,
          data: base64,
          toAction: toAction,
        },
      })
      if (response.status === 'success') {
        return response.images
      } else {
        alert('آپلود با خطا مواجه شد: ' + response.message)
      }
    } catch (err) {
      alert('خطا در ارسال: ' + err.message)
    }
  }