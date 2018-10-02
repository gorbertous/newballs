import {ajaxPath, environment} from "../Constants";

let object = {};

export default class Imonths {

    constructor(url, params, handledepdrop) {
        let $this = this;

        $this.survey('#Startdate', function () {
            $this.recalCnext();
        });

        $(document).on("change", "#iMonths", function () {
            $this.recalCnext();
        });

        $this.onChangeImonths(url, params, handledepdrop);
    }

    recalCnext() {
        let $sd = $("#Startdate").val().split('-');

        if ($sd.length == 3) {
            let $im = $("#iMonths").val();
            let $nextdate = $("#Nextdate");

            if ($im.length == 0) {
                $nextdate.val('');
                $("#Nextdate-disp-kvdate").kvDatepicker("update", '');
            } else {
                let $nd = new Date($sd[0], $sd[1]-(1-$im), $sd[2]);
                let $nddisp = $nd.getDate() + "/" + ($nd.getMonth()+1) + "/" + $nd.getFullYear();
                let $ndsave = $nd.getFullYear() + "-" + ($nd.getMonth()+1) + "-" + $nd.getDate();

                if ($nextdate.val() != $ndsave) {
                    if (confirm($('.warn-message').data('text')) === true) {
                        $nextdate.val($ndsave);
                        $("#Nextdate-disp-kvdate").kvDatepicker("update", $nddisp);
                    }
                }
            }
        }
    }

    survey(selector, callback) {
        let $input = $(selector);
        let $oldvalue = $input.val();

        setInterval(function(){
            if ($input.val() != $oldvalue){
                $oldvalue = $input.val();
                callback();
            }
        }, 100);
    }

    onChangeImonths(url, params, handledepdrop) {
        let $this = this;

        if (handledepdrop) {
            $(document).on('depdrop:afterChange', '.input-change-imonths', function(e) {
                e.preventDefault();
                $this.trigger(url, params);
            });
        }

        $(document).on('change', '.input-change-imonths', function(e) {
            e.preventDefault();
            $this.trigger(url, params);
        });
    }

    trigger(url, params) {
        let $this = this;
        object = {};

        $.each(params, function(k, v) {
            object[k] = $(v).val();
        });

        $.ajax({
            url      : ajaxPath + url,
            dataType : 'html',
            method   : 'POST',
            data     : object
        })
        .done(function(data) {
            $("#stdMonths").text(" (Std = " + data + ")");
            
            let $iMonths = $('#iMonths');
            console.log($iMonths.val());
            if ($iMonths.val().length != 0) {
                $this.recalCnext();
            }
        })
        .fail(function() {
            if (environment === 'dev') {
                console.log('Error processing Imonths'); 
            } 
        });
    }

}