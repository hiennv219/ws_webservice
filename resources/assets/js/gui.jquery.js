/**
 * Created by Nguyen Tuan Linh on 2016-12-05.
 */
function openWindow(url, name, specs, replace) {
    var specsArr = [];
    for (var key in specs) {
        specsArr.push(key + '=' + specs[key]);
    }
    return window.open(url, name, specsArr.join(','), replace);
}

function quickForm(action, data, method) {
    if (typeof method === 'undefined') method = 'post';
    var $quickForm = $('#quick-form');
    if ($quickForm.length > 0) {
        $quickForm.remove();
    }
    $form = $('<form id="quick-form" action="' + action + '" method="' + method + '" class="hide"></form>');
    for (var name in data) {
        $form.append('<input type="hidden" name="' + name + '" value="' + data[name] + '">');
    }
    $form.appendTo('body');
    return $form;
}

$(function () {
    // fix stacked modals
    // var _zIndexModal = 1050;
    // $(document).on('shown.bs.modal', '.modal', function (e) {
    //     $(this).css('z-index', ++_zIndexModal);
    // });

    $(document).on('click', '.go-url', function (e) {
        e.preventDefault();
        var url = $(this).attr('data-url');
        if (url) {
            window.location.href = url;
        }
    }).on('click', '.open-window', function (e) {
        e.preventDefault();

        var $this = $(this);
        var data = $.extend($this.data());
        var url = '';
        var name = '';
        var replace = true;
        var callback = null;
        if (data.url) {
            url = data.url;
            delete data.url;
        }
        else {
            if ($this.is('a')) {
                url = $this.attr('href');
            }
            else if ($this.is('img')) {
                url = $this.attr('src');
            }
        }
        if (data.name) {
            name = data.name;
            delete data.name;
        }
        if (data.replace) {
            replace = data.replace;
            delete data.replace;
        }
        if (data.callback) {
            callback = data.callback;
            delete data.callback;
        }
        var w = openWindow(url, name, data, replace);
        if (callback && typeof callback === 'string') {
            window[callback](this, w);
        }
        return false;
    });

    $('.unsigned-integer-input').on('keydown', function (e) {
        return (e.which >= 48 && e.which <= 57)
            || (e.which >= 96 && e.which <= 105)
            || [8, 9, 13, 35, 36, 37, 39, 46, 144].indexOf(e.which) != -1; // back space, tab, enter, end, home, left arrow, right arrow, delete, num lock
    });

    $('.pop-hover').popover({trigger: 'manual', html: true, animation: false})
        .on('mouseenter', function () {
            var _this = this;
            $(this).popover('show');
            $('.popover').on('mouseleave', function () {
                $(_this).popover('hide');
            });
        }).on('mouseleave', function () {
        var _this = this;
        setTimeout(function () {
            if (!$('.popover:hover').length) {
                $(_this).popover('hide');
            }
        }, 300);
    });
});
