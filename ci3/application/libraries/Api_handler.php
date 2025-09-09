<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Api_handler{
    private $handlers=[];
    public function __construct(
        Upload_handler $upload,
        Security_handler $security,
        Login_handler $login,
        User_handler $user,
        News_handler $news,
        Wallet_handler $wallet,
        Place_handler $place,
        Category_handler $category
    ) {
        $this->handlers = [
            'upload'     => $upload,
            'security'   => $security,
            'login'      => $login,
            'user'       => $user,
            'news'       => $news,
            'place'      => $place,
            'category'   => $category,
        ];
    }
    public function handler($data){
        header('Content-Type: application/json');
        if (!empty($data) && !empty($data['control']) && !empty($data['action'])) {
            $control = strtolower(trim($data['control']));
            $action = trim($data['action']);
            if (!array_key_exists($control,$this->handlers) || !isset($this->handlers[$control])) exit(json_encode(['status' => 'error', 'message' => 'ماژول یافت نشد']));
            $handler = $this->handlers[$control];
            if (!method_exists($handler, $action)) exit(json_encode(['status' => 'error', 'message' => "متد «{$action}» در ماژول «{$control}» وجود ندارد"]));
            $method = new ReflectionMethod($handler, $action);
            $numParams = $method->getNumberOfRequiredParameters();
            try {
                exit(json_encode($numParams > 0? $handler->{$action}($data['data']??null): $handler->{$action}()));
            } catch (Exception $e) {
                exit(json_encode(['status' => 'error', 'message' => $e->getMessage()]));
            }
        }
        exit(json_encode(['status' => 'error', 'message' => 'کنترل یا اکشن خالی است']));
    }
    public function upload_video($files, $data) {
        $info = json_decode($data['data'] ?? '', true);
        if (!$info) {
            exit(json_encode(['status' => 'error', 'message' => 'Invalid meta data']));
        }
        $uploadDir = FCPATH . 'storage/chunk/' ;
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $tempFile = $uploadDir . $info['id'] . '.part';
        if (!empty($files)) {
            $file = reset($files);
            $chunkFile = $tempFile . "_chunk_" . $info['chunkIndex'];
            if (!move_uploaded_file($file['tmp_name'], $chunkFile)) {
                exit(json_encode(['status' => 'error', 'message' => 'Failed to save chunk']));
            }
        }
        if (!empty($info['isLastChunk']) && $info['isLastChunk'] === true) {
            $finalFilePath = $uploadDir . $info['fileName'];
            $out = fopen($finalFilePath, 'wb');
            for ($i = 0; $i < $info['total']; $i++) {
                $chunkFile = $tempFile . "_chunk_" . $i;
                if (!file_exists($chunkFile)) {
                    fclose($out);
                    exit(json_encode(['status' => 'error', 'message' => 'Missing chunk ' . $i]));
                }
                $in = fopen($chunkFile, 'rb');
                stream_copy_to_stream($in, $out);
                fclose($in);
                unlink($chunkFile);
            }
            fclose($out);
            $mimeType = mime_content_type($finalFilePath);
            $base64File = 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($finalFilePath));
            $result = $this->handlers['upload']->upload_many_videos([
                'data' => [$base64File],
                'url' => $info['url'],
                'toAction' => $info['toAction']
            ]);
            unlink($finalFilePath);
            exit(json_encode(['status' => 'success', 'message' => 'File uploaded', 'result' => $result]));
        } else {
            exit(json_encode(['status' => 'progress']));
        }
    }
}