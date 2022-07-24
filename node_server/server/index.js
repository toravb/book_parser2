const config = require('./config.js')
// const express = require('express')()
// const http = require('http').createServer(express)
const ebookConverter = require('node-ebook-converter')
const { createServer } = require('http')
const api = require('./api.js')

const express = require('express')
const app = express()
const httpServer = createServer(app)

const io = require('socket.io')(httpServer, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST'],
    allowedHeaders: ['Authorization'],
    credentials: true
  }
})
const nsp = require('./nsp.js')
const Notifications = require('./notifications.js')

nsp(io, ['chat'])
nsp(io, ['notifications'])

Notifications(io.nsps.notifications)
// Chat(io.nsps.chat)
app.use(express.json())
app.use(express.urlencoded({ extended: true }))

app.get('/', (req, res) => {
  res.send('<h1>Hello world</h1>')
})

app.post('/convert', (req, res) => {
// Adds the convertion to the Execution Queue

  ebookConverter.convert({
    input: req.body.pathForConversion,
    output: req.body.pathAfterConversion,
    authors: 'Loveread Webnauts'
  }).then(response => {
    console.log('Converted')
    api.post('/parse_pdf', {
      id: req.body.bookId,
      path: req.body.storagePath,
      pathForConversion: req.body.pathForConversion
    }).then(
      console.log('sended')
    ).catch(error => console.error(error))
  })
    .catch(error => console.error(error))
  res.send('success')
})

app.listen(config.port, config.host, () => {
  console.log(`Socket server listening on ${config.host}:${config.port}`)
})
