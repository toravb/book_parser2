require('bootstrap')

window.$ = window.jQuery = require('jquery')
require('admin-lte/dist/js/adminlte')


$(document).ready(function () {
    $(':required').each((index, el) => {
        $(el).closest('label').addClass('required')
    })
})
