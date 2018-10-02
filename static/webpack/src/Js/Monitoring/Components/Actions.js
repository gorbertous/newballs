import {ajaxPath, environment} from "../Constants";

export default class Actions {

    constructor() {
        if ($('.actions-form').length === 1) {
            this.getContext();

            let $this = this;

            $(document).on('change', '.action-get-context', function () {
                $this.getContext();
            });

            this.actionProgressInsertDate();
        }
    }

    actionProgressInsertDate() {
        $("[data-dynamicform^='dynamicform']").on("afterInsert", function() {
            let field = $(this).find('[data-krajee-kvdatepicker]');

            let date = $('.df-date').data('text');

            $.each(field, function() {
                if($(this).val().length === 0) {
                    $(this).val(date);
                    let nextInput = $(this).attr('id').replace('-disp', '');
                    $('#' + nextInput).val(date);
                }
            });
        });
    }

    getContext() {
        $.ajax({
            url      : ajaxPath + '/actions/actioncontext',
            method   : 'POST',
            dataType : 'json',
            data: {
                registerinspect_id : $('#actions-id_registerinspect').val(),
                risk_id            : $('#actions-id_risk').val(),
                accident_id        : $('#actions-id_accident').val()
            }
        }).done(function(data) {
            $('.action-context').html(data.action_on);
            $('#data').html(data.content);
        }).fail(function() {
            if (environment === 'dev') {
                console.log('Error Processing Request!');
            }
        });
    }

}