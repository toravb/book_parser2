const config = require('./config.js')
const redis = require('./redis.js')

module.exports = function (nsp) {
  nsp.on('connection', (socket) => {
    socket.join(String(socket.auth.id))
    const roomId = socket.auth.id
    console.log(roomId)
    socket.on('disconnect', () => {
      console.log('Client disconnected from /chat')
    })
  })
  redis.on('message', (channel, message) => {
    if (channel === config.redisNotificationsChannel) {
      message = JSON.parse(message)
      const data = {
        sender: message.data.sender,
        book: message.data.book,
        createdAt: message.data.createdAt
      }
      if (message.data.type === 'new_answer_on_comment' || message.data.type === 'new_answer_in_branch') {
        data.text = message.data.text
      }
      if (message.data && Array.isArray(message.data.to)) {
        message.data.to.forEach((el) => {
          nsp.to(String(el)).emit(message.data.type, data)
        })
      }
    }
  })
}
