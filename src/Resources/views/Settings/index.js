$(document).ready(function () {
    "use strict";
    var list = $('.settings-form'),
        setting_list = $('#setting_list');

    list.filter('.sets').find('.action-edit').click(function (e) {
        e.preventDefault();
        var obj = $(this).closest('li');
        var str = obj.find('input').val();
        var wallets = $('.page-admin-settings-wallets');
        while (true) {
            str = prompt('Введите новое значение для "' + obj.find('.title').text() + '"', str);
            if (str === null) {
                return;
            }
            if (wallets.size()) {
                if (!confirm('Сохранить значение "' + str + '" для "' + obj.find('.title').text() + '"? Это может нарушить работу сайта')) {
                    str = obj.find('input').val();
                } else {
                    break;
                }
            } else {
                break;
            }
        }
        $fn.query(Routing.generate('admin_savesetting'), function (data) {
            obj.find('input').val(data.newval);
        }, {
            'newval': str,
            'path':   obj.find('input').attr('id').replace(/^settings_\.?(.+)$/g, '$1'),
            'stype':  wallets.size() ? 'wallets' : ($('.page-admin-settings-rates').size() ? 'commissions' : 'req')
        });
    });

    var addToggle = function (obj, target) {
        if (!obj.size()) {
            return null;
        }
        var btn = $('<a href="#toggle" class="action-toggle"></a>');
        btn.click(function (e) {
            e.preventDefault();
            if ($(this).hasClass('closed')) {
                target.show();
                $(obj).addClass('shown').removeClass('hidden');
                $(this).text('скрыть');
                $(this).removeClass('closed');
            } else {
                target.hide();
                $(obj).addClass('hidden').removeClass('shown');
                $(this).text('показать');
                $(this).addClass('closed');
            }
        }).click();
        obj.append(btn);
        return btn;
    };

    list.filter('.collapsible').each(function () {
        var title = $(this).children('h3').eq(0);
        var target = $(this).children('.collapse-cont').eq(0);
        var btn = addToggle(title, target);
        $(this).data('collapsibleButton', btn);
    });
    list.find('ul.toggleable').each(function () {
        var title = $(this).parent().children('h3');
        addToggle(title, $(this));
    });

    setting_list.find('.action-edit').click(function (e) {
        e.preventDefault();
        var textar = $(this).siblings('textarea'),
            sid = textar.data('setting-sid');
        textar.data('value', textar.val());
        if (textar) {
            $fn.editbox(textar[0].id, null, {width: '99%', height: 350});
        }
        textar.parent().append($('<a/>').append(
            $('<img/>', {'src': '/bundles/werkintsettings/images/ok.png'}).click(function () {
                var val = textar.val(),
                    vall = textar.data('value');
                if (vall == val) {
                    return;
                }
                $fn.query(Routing.generate('werkint_settings_savesetting'), function (data) {
                    textar.val(data.value);
                    textar.data('value', data.value);
                }, {
                    sid:   sid,
                    value: val
                });
            })
        ).css('margin', '0.5em'));
        $(this).remove();
    });
    var input = function () {
        var inp = $(this),
            el = inp.closest('label').find('a.action-apply');
        if (inp.val() != inp.data('value')) {
            el.addClass('active');
        } else {
            el.removeClass('active');
        }
    };
    setting_list.find('label input.editable').each(function () {
        var inp = $(this),
            el = inp.siblings('a.action-apply');
        inp.data('value', inp.prop('value'));
        inp.bind('input', input);
        el.click(function () {
            var val = inp.prop('value'),
                sid = inp.data('setting-sid');
            if (val == inp.data('value')) {
                return;
            }
            $fn.query(Routing.generate('werkint_settings_savesetting'), function (data) {
                inp.data('value', data.value);
                inp.val(data.value);
                input.call(inp);
            }, {
                sid:   sid,
                value: val
            });

        });
        input.call(this);
    });

    setting_list.find('.action-toggle').click(function (e) {
        var ths = $(this);
        if (ths.data('toggle-name') != 'boolean-type') {
            return;
        }
        e.preventDefault();
        var sid = ths.siblings('input.sid').prop('value'),
            val = !ths.hasClass('active');
        if (!sid) {
            return;
        }
        $fn.query(Routing.generate('werkint_settings_savesetting'), function (data) {
            if (data['value'] == '0') {
                ths.removeClass('active');
            }
            if (data['value'] == '1') {
                ths.addClass('active');
            }
        }, {
            sid:   sid,
            value: Number(val)
        });
    });

    setting_list.find('.actions.actions-remove button').click(function (e) {
        var ths = $(this),
            sid = ths.data('setting-sid');
        if (!confirm('Вы точно хотите удалить?')) {
            return;
        }
        $fn.query(Routing.generate('werkint_settings_array_node_remove'), function (data) {
            if (data['sid'] == sid) {
                ths.closest('li').remove();
            }
        }, {
            sid: sid
        });
    });
    $(window).bind('hashchange',function () {
        if (!window.location.hash) {
            return;
        }
        if (window.location.hash.substr(1, 8) != 'setting-') {
            return;
        }
        var num = Number(window.location.hash.substr(9)),
            anchor = $('a.setting-' + num);
        var el = anchor.closest('.settings-form.collapsible');
        while (el.data('collapsibleButton')) {
            el.data('collapsibleButton').addClass('hidden').click();
            el = el.parent().closest('.settings-form.collapsible');
        }
        if (anchor.size()) {
            anchor.get(0).scrollIntoView();
        }
    }).trigger('hashchange');

    $('button.action-add-arraynode').click(function () {
        var sid = Number($(this).data('parentid'));
        $fn.query(Routing.generate('werkint_settings_addarraynode'), function () {
            //$('a.setting-' + sid).get(0).scrollIntoView();
            window.location.hash = '#setting-' + sid;
            window.location.reload(true);
        }, {
            'sid': sid
        });
    });

    $('#action_update_settings').click(function () {
        $(this).closest('.buttons').addClass('loading');
        $fn.query(Routing.generate('werkint_settings_update'), function () {
            document.location.reload();
        });
    });
});