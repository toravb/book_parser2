const config = require('./config.js')
const redis = require('./redis.js')

module.exports = function (nsp) {
  this.notifications = nsp
  this.notifications.on('connection', (socket) => {
    socket.join(String(socket.auth.id))
  })

  // redis.on('message', (channel, message) => {
  //   if (channel === config.redisNotificationsChannel) {
  //     message = JSON.parse(message)
  //     this.notifications.in(String(message.data.to)).emit('newMessage', message)
  //   }
  // })
}
