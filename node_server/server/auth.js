const api = require('./api.js')

module.exports = async function (token) {
    console.log('token')
    console.log(token)
  if (token) {
      console.log('Before response')

         return await api.get('/users/user_id', {
              headers: { Authorization: 'Bearer ' + token }

          })



  } else {
    return null
  }
}
