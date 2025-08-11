npm init -y
npm install express socket.io cors
node server.js

in vue js
npm install socket.io-client


// application/controllers/Api.php
class Api extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('curl'); // برای ارسال درخواست HTTP به Node.js
        $this->load->database();
    }

    public function add_data() {
        // فرضاً داده از POST میاد
        $data = $this->input->post();

        // ذخیره داده در دیتابیس (مثال ساده)
        $this->db->insert('my_table', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            // بعد از ذخیره، به Node.js خبر میدیم
            $this->notifyNodeJs($insert_id, $data);

            echo json_encode(['status' => 'success', 'id' => $insert_id]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    private function notifyNodeJs($id, $data) {
        $nodeUrl = "http://localhost:3000/notify";  // فرض آدرس سرور Node.js

        $payload = [
            'id' => $id,
            'data' => $data,
        ];

        // ارسال POST به Node.js
        $this->curl->simple_post($nodeUrl, $payload);
    }
}
 


<script setup>
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
</script>

<template>
  <div>
    <h3>Realtime Data Update</h3>
    <pre>{{ realtimeData }}</pre>
  </div>
</template>
