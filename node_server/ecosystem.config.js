const config = require('./server/config.js')

module.exports = {
  apps: [
    {
      name: 'bloggers-socket-server',
      exec_mode: 'cluster',
      instances: config.instances, // Or a number of instances
      script: './server/index.js',
      args: 'start',
      watch: true
    }
  ]
}
