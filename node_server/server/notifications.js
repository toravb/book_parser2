const config = require('./config.js')
const api = require('./api.js')
const incomingDataValidation = require('./validations/incomingDataSchema.js')
const redis = require('./redis.js')

module.exports = function (nsp) {
    nsp.on('connection', (socket) => {
        console.log('socket')
        // console.log(socket)
        socket.join(String(socket.auth.id))
        const roomId = socket.auth.id
        console.log(roomId)
        socket.on('disconnect', () => {

            console.log('Client disconnected from /chat')
        })
    })
    redis.on('message', (channel, message) => {
        console.log('Redis')
        console.log(channel)
        if (channel === config.redisNotificationsChannel) {

            message = JSON.parse(message)
            console.log(message)
            if (message.data.type === 'new_comment_like') {
                const data = {
                    sender: message.data.sender,
                    book: message.data.book,
                    createdAt: message.data.createdAt
                }
                if (message.data && Array.isArray(message.data.to)) {
                    message.data.to.forEach((el) => {
                        data.to = [el]
                        nsp.to(String(el)).emit('sendMessage', data)
                    })
                }
            }
        }
    })
}
