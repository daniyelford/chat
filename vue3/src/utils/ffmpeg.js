import { createFFmpeg, fetchFile } from '@ffmpeg/ffmpeg';
import { BASE_URL } from '@/config';
const ffmpeg = createFFmpeg({
    log: true,
    mainName: 'main',
    corePath:   `${BASE_URL}/assets/ffmpeg_st/ffmpeg-core.js`,
    wasmPath:   `${BASE_URL}/assets/ffmpeg_st/ffmpeg-core.wasm`,
    workerPath: `${BASE_URL}/assets/ffmpeg_st/ffmpeg-core.worker.js`
});
let ffmpegLoaded = false;
export async function compressVideo(file, onProgress) {
  if (!ffmpegLoaded) {
    await ffmpeg.load();
    ffmpegLoaded = true;
  }
  if (onProgress) {
    ffmpeg.setProgress(({ ratio }) => {
      onProgress(Math.round(ratio * 100));
    });
  }
  ffmpeg.FS('writeFile', 'input.mp4', await fetchFile(file));
  await ffmpeg.run('-i', 'input.mp4', '-vf', 'scale=640:-1', '-b:v', '800k', '-c:a', 'aac', 'output.mp4');
  const data = ffmpeg.FS('readFile', 'output.mp4');
  const compressedBlob = new Blob([data.buffer], { type: 'video/mp4' });
  const compressedFile = new File(
    [compressedBlob],
    file.name.replace(/\.\w+$/, '_compressed.mp4'),
    { type: 'video/mp4' }
  );
  return compressedFile;
}
