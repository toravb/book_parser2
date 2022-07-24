const config = require('./config.js')
// const api = require('./api.js')
// const incomingDataValidation = require('./validations/incomingDataSchema.js')
const redis = require('./redis.js')

module.exports = function (nsp) {
  nsp.on('connection', (socket) => {
    // console.log(socket)
    socket.join(String(socket.auth.id))
    const roomId = socket.auth.id
    console.log(roomId)
    // api.get(`/chats/rooms/${roomId}`).then(
    //   (resp) => {
    //     let chatsList = []
    //     if (resp.data.data.length) {
    //       chatsList = resp.data.data
    //       chatsList.forEach((el) => {
    //         if (socket.adapter.rooms.get(String(el))) {
    //           socket.to(String(el)).emit('statusChanged', [
    //             {
    //               id: roomId,
    //               status: 'online'
    //             }
    //           ])
    //         }
    //       })
    //     }
    //   },
    //   (e) => {
    //     console.log(e.response.data)
    //   }
    // )
    // socket.on('checkStatus', (incomingData, acknowledgement) => {
    //   if (Array.isArray(incomingData) && incomingData.length) {
    //     const resp = []
    //     incomingData.forEach((el) => {
    //       if (socket.adapter.rooms.get(String(el))) {
    //         resp.push({
    //           id: el,
    //           status: 'online'
    //         })
    //       } else {
    //         resp.push({
    //           id: el,
    //           status: 'offline'
    //         })
    //       }
    //       socket.adapter.rooms.get(String(el))
    //     })
    //     acknowledgement({
    //       status: 'success',
    //       data: resp
    //     })
    //   } else {
    //     acknowledgement({
    //       status: 'success',
    //       data: 'Invalid incoming data'
    //     })
    //   }
    // })
    // socket.on('getMessage', (incomingData, acknowledgement) => {
    //   const validationErrors = incomingDataValidation.validate(incomingData)
    //   if (validationErrors.length) {
    //     const errors = []
    //     validationErrors.forEach((el) => {
    //       errors.push({
    //         path: el.path,
    //         message: el.message
    //       })
    //     })
    //     acknowledgement({
    //       status: 'error',
    //       message: 'The given data was invalid',
    //       errors
    //     })
    //   } else {
    //     const data = {
    //       from: socket.auth.id,
    //       text: incomingData.text,
    //       attachments: incomingData.attachments,
    //       updated_at: incomingData.updated_at,
    //       type: incomingData.type
    //     }
    //     const dbData = {
    //       to: incomingData.to,
    //       chat_id: incomingData.chat_id
    //     }
    //     if (incomingData.type === 'text' || incomingData.type === 'system') {
    //       dbData.text = incomingData.text
    //     }
    //     if (incomingData.type === 'attachment') {
    //       dbData.attachments = incomingData.attachments
    //     }
    //
    //     api
    //       .post('/messages', dbData, {
    //         headers: { Authorization: 'Bearer ' + socket.handshake.auth.token }
    //       })
    //       .then(
    //         (resp) => {
    //           data.chat_id = incomingData.chat_id
    //           data.id = resp.data.data.message_id
    //           data.to = incomingData.to
    //           incomingData.to.forEach((el) => {
    //             socket.to(String(el)).emit('sendMessage', data)
    //             acknowledgement({
    //               status: 'success',
    //               messageId: resp.data.data.message_id
    //             })
    //           })
    //         },
    //         (e) => {
    //           acknowledgement({
    //             status: 'error',
    //             message: e.response.data.message,
    //             errors: e.response.data
    //           })
    //         }
    //       )
    //   }
    // })
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
