const express = require('express')
const http = require('http')
const socketIo = require('socket.io')
const cors = require('cors')

const app = express()
const server = http.createServer(app)
const io = socketIo(server, {
  cors: { origin: '*' }
})

app.use(cors())
app.use(express.json())

io.on('connection', (socket) => {
  console.log('Client connected:', socket.id)
})

app.post('/notify', (req, res) => {
  const data = req.body
  console.log('Notification from CI3:', data)
  io.emit('data-updated', data)
  res.json({ status: 'ok' })
})

const PORT = 3000
server.listen(PORT, () => console.log(`Node.js server running on port ${PORT}`))
