require('./bootstrap')
window.$ = window.jQuery = require('jquery')
require('admin-lte/dist/js/adminlte')
require('select2/dist/js/select2.min');
import Swal from 'sweetalert2'


const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    showCloseButton: true,
})

const select2Options = {
    theme: 'bootstrap4',
}


$(document).ready(function () {
    $(':required').each((index, el) => {
        $(el).closest('label').addClass('required')
    })

    $('[data-action="select2"]').each((index, el) => {
        let options = {}

        if ($(el).data('ajax')) {
            const key = $(el).data('key')
            const textField = $(el).data('text-field')
            const pagination = $(el).data('pagination')

            options.ajax = {
                url: $(el).data('ajax'),
                data: (params) => {
                    return {
                        search: params.term ?? '',
                        page: params.page || 1
                    }
                },
                processResults: (data) => {
                    let dataArray = [],
                        results = [],
                        morePages = false

                    if (!pagination) {
                        dataArray = data.data
                    } else {
                        dataArray = data.data.data
                        morePages = data.data.current_page !== data.data.last_page
                    }

                    results = dataArray.map(element => {
                        return {
                            id: element[key] ?? '',
                            text: element[textField] ?? ''
                        }
                    })

                    return {
                        results: results,
                        pagination: {
                            more: morePages,
                        }
                    };
                },
            }
        }

        $(el).select2({
            ...select2Options,
            ...options,
        })
    })

    $('[data-action="toast-notification"]').each((index, el) => {
        Toast.fire(swalDataFromElement(el))
    })

    $('[data-action="delete-confirm"]').click((event) => {
        const el = event.currentTarget
        const route = $(el).data('route')

        Swal.fire({
            ...swalDataFromElement(el),
            iconColor: 'var(--danger)',
            showCancelButton: true,
            focusCancel: true,
            cancelButtonText: 'Нет',
            confirmButtonText: 'Да, удалить',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-outline-danger mr-2',
                cancelButton: 'btn btn-outline-success',
            },
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => {
                return axios.delete(route)
                    .then(r => {
                        return r
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            error
                        )
                    })
            },
        }).then(async (result) => {
            if (result.value?.data?.status === 'success') {
                await Swal.fire({
                    icon: 'success',
                    title: 'Удалено!',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1000,
                    allowOutsideClick: false,
                })

                responseActionProceed(result.value)
            }
        })
    })
})

const swalDataFromElement = (el) => {
    return {
        icon: $(el).data('icon'),
        title: $(el).data('title'),
        text: $(el).data('text'),
        timer: $(el).data('timer'),
    }
}

const responseActionProceed = (response) => {
    console.log(response)
    if (response.status === 200) {
        switch (response.data.data.action) {
            case 'redirect':
                location.href = response.data.data.route
        }
    }
}
