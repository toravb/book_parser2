const config = require('./config.js')
const express = require('express')()
const http = require('http').createServer(express)
const io = require('socket.io')(http, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST'],
    allowedHeaders: ['Authorization'],
    credentials: true
  }
})
const nsp = require('./nsp.js')
const Chat = require('./chat.js')

nsp(io, ['chat'])

Chat(io.nsps.chat)

express.get('/', (req, res) => {
  res.send('<h1>Hello world</h1>')
})

http.listen(config.port, config.host, () => {
  console.log(`Socket server listening on ${config.host}:${config.port}`)
})
