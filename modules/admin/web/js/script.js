$(document).on('click', '[data-modal="1"]', function () {
    var url = $(this).attr('href') || $(this).data('url');
    if ($(this).data('modal-size') === 'lg') {
        $('#base-modal .modal-dialog').addClass('modal-lg');
    } else {
        $('#base-modal .modal-dialog').removeClass('modal-lg');
    }
    $.get(url, function (data) {
        $('#base-modal .modal-content').html(data);
        $('#base-modal').modal('show');
    });
    return false;
});


$(document).on('click', 'a[data-ajax="1"]', function () {
    var obj = $(this);
    var method = $(obj).data('ajax-method') ? $(obj).data('ajax-method') : 'get';
    var xhr = function (method, obj) {
        $.ajax({
            method: method,
            url: $(obj).attr('href'),
            success: function (data) {
                if ($(obj).data('ajax-pjax-reload')) {
                    $.pjax.reload({
                        container: $(obj).data('ajax-pjax-reload'),
                        timeout: 5000
                    });
                }
                $(obj).trigger('afterAjaxSubmit', {data: data});
            }
        });
    }

    if ($(obj).data('ajax-confirm')) {
        $.confirm({
            title: 'กรุณายืนยันคำสั่ง',
            content: $(obj).data('ajax-confirm'),
            buttons: {
                ok: {
                    text: 'ตกลง',
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        xhr(method, obj);
                    }
                },
                cancel: {
                    text: 'ยกเลิก',
                    action: function () {

                    }
                }
            }
        });
    } else {
        xhr(method, obj);
    }
    return false;
});

$(document).on('beforeSubmit', 'form[data-ajax-form="1"]', function (e) {
    var form = $(this);
    var formData = form.serialize();
    $.ajax({
        async: true,
        url: form.attr("action"),
        type: form.attr("method"),
        data: formData,
        success: function (data) {
            $('#base-modal').modal('hide');
            if ($(form).data('ajax-pjax-reload')) {
                $.pjax.reload({
                    container: $(form).data('ajax-pjax-reload'),
                    timeout: $(form).data('ajax-pjax-timeout') ? $(form).data('ajax-pjax-timeout') : 5000
                });
            }
            switch (data.type) {
                case 'ajax-alert':
                    $('#alert-modal .modal-body').html(data.msg);
                    $('#alert-modal').modal('show');
                    break;
            }
        },
        error: function () {

        }
    });
    return false;
});