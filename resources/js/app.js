require('./bootstrap');
require('alpinejs');
// require('datatables');
import { io } from 'socket.io-client'
const socketsHost = process.env.MIX_SOCKET_HOST
const socketsPort = process.env.MIX_SOCKET_PORT
const socketServerBaseUrl = `${socketsHost}:${socketsPort}`

const options = {
    autoConnect: false,
    auth: {
        token: '',
    },
}
const mainNamespace = io(socketServerBaseUrl, options)
const chatNamespace = io(socketServerBaseUrl + '/chat', options)
const notificationsNamespace = io(
    socketServerBaseUrl + '/notifications',
    options
)


window.$ = window.jQuery = require('jquery');

$(document).ready(function () {

    $('button.disabled').click(function (e) {
        e.preventDefault()
    })

})
