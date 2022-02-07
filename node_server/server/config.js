require('dotenv').config()

module.exports = {
  host: process.env.HOST,
  port: process.env.PORT,
  redisHost: process.env.REDIS_HOST,
  redisPort: process.env.REDIS_PORT,
  redisDb: process.env.REDIS_DB,
  redisPassword: process.env.REDIS_PASSWORD,
  redisNotificationsChannel: process.env.REDIS_NOTIFICATIONS_CHANNEL,
  redisAttechmentsionsChannel: process.env.REDIS_ATTACHMENTS_CHANNEL,
  apiBaseUrl: process.env.API_BASE_URL,
  clientOrigin: process.env.CLIENT_ORIGIN,
  instances: process.env.INSTANCES
}
