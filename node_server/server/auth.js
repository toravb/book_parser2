const api = require('./api.js')

module.exports = async function (token) {
  if (token) {
    return await api.get('/users/user_id', {
      headers: { Authorization: 'Bearer ' + token }
    })
  } else {
    return null
  }
}
