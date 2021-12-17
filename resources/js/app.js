require('./bootstrap');
require('alpinejs');
// require('datatables');
window.$ = window.jQuery = require('jquery');

$(document).ready(function () {

    $('button.disabled').click(function (e) {
        e.preventDefault()
    })

})
