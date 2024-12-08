$(document).ready(function () {

    $(document).on('click', '.btn-add-cart', function () {
        var obj = $(this);
        $.post(obj.attr('href'), function (data) {
            updateCart(true);
            /*
            if (obj) {
                var cart = $('.btn-cart');
                var imgtodrag = $(obj).closest('.item').find("img").eq(0);
                if (imgtodrag) {
                    var imgclone = imgtodrag.clone()
                            .offset({
                                top: imgtodrag.offset().top,
                                left: imgtodrag.offset().left
                            })
                            .css({
                                'opacity': '0.5',
                                'position': 'absolute',
                                'height': '150px',
                                'width': '150px',
                                'z-index': '100'
                            })
                            .appendTo($('body'))
                            .animate({
                                'top': cart.offset().top + 10,
                                'left': cart.offset().left + 10,
                                'width': 75,
                                'height': 75
                            }, 1000, 'easeInOutExpo');

                    imgclone.animate({
                        'width': 0,
                        'height': 0
                    }, function () {
                        $(imgclone).detach()
                    });
                }
            }*/
        });
        return false;
    });

    $(document).on('click', '.btn-cart-update', function () {
        var obj = $(this);
        $.post(obj.attr('href'), function (data) {
            updateCart(false);
        });
        return false;
    });

    $(document).on('change', '.cart-qty-box', function () {
        var obj = $(this);
        $.post(obj.data('url'), {qty: obj.val()}, function (data) {
            updateCart(false);
        });
        return false;
    });

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
});

function updateCart(isTop, obj) {
    $.pjax.reload({container: "#pjax-cart"});

    $("#pjax-cart").on('pjax:success', function (event) {
        var c = 0;
        $('#pjax-cart .table-cart .cart-book .cart-qty-box').each(function (i, e) {
            c++;
        });
        console.log('set count = ' + c);
        $('.badge-cart-items-count').text(c);

        $('#base-modal').modal('hide');


        if (isTop) {
            event.preventDefault();
            $("html, body").stop().animate({scrollTop: 0}, 600);
            $("#my-cart").collapse('show');
        }

    });
}
