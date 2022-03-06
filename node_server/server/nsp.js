const auth = require('./auth.js')

module.exports = function (socket, nspList) {
    // console.log(socket)
    console.log(nspList)
    if (nspList && nspList.length) {
        socket.nsps = {}
        nspList.forEach((el) => {
            const nsp = socket.of(`/${el}`)

            nsp.use((socket, next) => {

                auth(socket.handshake.auth.token).then(
                    (resp) => {

                        socket.auth = {
                            id: resp.data.data
                        }
                        return next()
                    },
                    (e) => {
                        socket.disconnect();
                        return next(new Error("unauthorized"))
                    }
                )
            })

            console.log('No connection')
            nsp.on('connection', (nsp) => {

                console.log(`Client connected to /${el}`)
                nsp.on('disconnect', () => {
                  console.log(nsp.adapter.rooms)
                  console.log(`Client disconnected from /${el}`)
                })
            })
            socket.nsps[el] = nsp
        })
    }
}
