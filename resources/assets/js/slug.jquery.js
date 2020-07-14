/**
 * Created by Nguyen Tuan Linh on 2016-12-05.
 */
$.fn.extend({
    registerSlugTo: function ($tos) {
        return this.on('keyup', function () {
            var slugTo = slug($(this).val(), {lower: true});
            $tos.each(function () {
                var $to = $(this);
                if ($to.is('span')) {
                    $to.text(slugTo);
                }
                else {
                    $to.val(slugTo);
                }
            });
        });
    },
    registerSlug: function ($clones) {
        var $this = this;
        if (typeof $clones !== 'undefined') {
            $this.on('keyup', function () {
                $clones.each(function () {
                    var $clone = $(this);
                    if ($clone.is('span')) {
                        $clone.text($this.val());
                    }
                    else {
                        $clone.val($this.val());
                    }
                });
            });
        }
        return $this.on('keydown', function (e) {
            if (e.shiftKey || e.ctrlKey || e.altKey || e.metaKey) {
                return false;
            }

            var code = e.keyCode;
            // 65-90,48-57,189,8,9,13,35-40,46,96-105
            return (code >= 65 && code <= 90) ||
                (code >= 48 && code <= 57) ||
                (code >= 35 && code <= 40) ||
                (code >= 96 && code <= 105) ||
                ([189, 8, 9, 13, 46].indexOf(code) != -1);
        });
    }
});