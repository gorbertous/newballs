import {ajaxPath, environment} from "../Constants";

export default class Stock {

    constructor() {
        if ($('.stock-form').length === 1) {
            this.getLegislations();

            let $this = this;

            $(document).on('change', '.stock-get-type', function () {
                $this.getLegislations();
            });
        }
    }

    getLegislations() {
        $.ajax({
            url      : ajaxPath + '/stock/legislationtext',
            method   : 'POST',
            dataType : 'json',
            data: {
                stocktype_id : $('#stock-id_stocktype').val()
            }
        }).done(function(data) {
            $('#data').html(data);
        }).fail(function() {
            if (environment === 'dev') {
                console.log('Error Processing Request!');
            }
        });
    }

}