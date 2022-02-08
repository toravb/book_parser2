const Schema = require('validate')

// const numOrNull = (val) => typeof val === 'number' || val === null
// const messageOrNull = (val) =>
//   (typeof val === 'string' && val.length > 0 && val.length <= 1000) ||
//   val === null
const objectOrNull = (val) => typeof val === 'object'

const incomingDataSchema = new Schema(
  {
    type: {
      type: String,
      required: true
    },
    to: {
      type: Array,
      required: true
    },
    text: {
      type: String,
      required: false
    },
    attachments: {
      use: { objectOrNull }
    },
    updated_at: {
      type: String,
      required: true
    },
    chat_id: {
      type: Number,
      required: true
    }
  },
  {
    strip: false
  }
)

incomingDataSchema.message({
  numOrNull: () => {
    return 'Chat id must be an existing chat id or null.'
  }
})

module.exports = incomingDataSchema
