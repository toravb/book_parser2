const Redis = require('ioredis')
const config = require('./config.js')

const redis = new Redis({
  port: config.redisPort,
  host: config.redisHost,
  password: config.redisPassword,
  db: config.redisDb
})
redis.subscribe(config.redisNotificationsChannel)
redis.subscribe(config.redisAttechmentsionsChannel)

module.exports = redis
