const config = require('./config.js')
const axios = require('axios')

module.exports = axios.create({
  baseURL: config.apiBaseUrl
})
