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
    const richTextEditorSelector = 'textarea.rich-editor'
    $(richTextEditorSelector).each((index, el) => {
        let options = {}

        const uploadRoute = $(el).data('upload-route')

        if (uploadRoute) {
            options = {
                ...options,
                images_upload_url: uploadRoute,
                automatic_uploads: true,
            }
        }

        tinymce.init({
            target: el,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste imagetools wordcount'
            ],
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            file_picker_types: 'image',
            images_upload_handler: function (blobInfo, success, failure) {
                let xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = true;
                xhr.open('POST', uploadRoute);
                xhr.setRequestHeader("X-CSRF-Token", $('meta[name="_token"]').attr('content'));
                xhr.onload = function () {
                    let json;
                    if (xhr.status !== 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
            setup(editor) {
                editor.on("keydown", function (e) {
                    /*
                    Dont enable feature! Causing deleting image before saving.
                    TODO Potential fix: store files to remove to array and delete on saving/updating action.

                    if ((e.keyCode == 8 || e.keyCode == 46) && tinymce.activeEditor.selection) {
                        let selectedNode = tinymce.activeEditor.selection.getNode();
                        if (selectedNode && selectedNode.nodeName == 'IMG') {
                            let imageSrc = selectedNode.src;


                            axios.delete('/admin-panel/file-remove', {
                                params: {
                                    file_path: imageSrc,
                                }
                            })
                        }

                    }
                     */
                });
            },
            ...options,
        });
    })

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

    $('[data-action="page-preview"]').click((event) => {
        const el = event.currentTarget
        const route = $(el).data('route')

        axios.get(route).then(r => {
            const pageData = r.data.data.page

            Swal.fire({
                html: pageData.content,
                width: '90%'
            })
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
