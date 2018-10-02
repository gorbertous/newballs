import {ajaxPath, environment} from "../Constants";
import Treeview from "./Treeview";

export default class Accidents {

    constructor() {
        // create/update page
        if ($('#form-accidents').length === 1) {
            this.bindElements();
            new Treeview();
        }

        // select page
        if ($('.accidents-select').length === 1) {
            this.setCode();

            let $this = this;

            $(document).on('click', '.thumbnail', function (e) {
                let letter      = $(this).data('letter');
                let toDisable   = (typeof $(this).data('to-disable') !== 'undefined') ? $(this).data('to-disable') : [];
                let toDeselect  = (typeof $(this).data('to-deselect') !== 'undefined') ? $(this).data('to-deselect') : [];
                let connectedTo = (typeof $(this).data('is-connected-to') !== 'undefined') ? $(this).data('is-connected-to') : [];
                $this.accordionSelect(letter, connectedTo, toDisable, toDeselect);
            });

            $(document).on('closing', '#modal', function (e) {
                $(document).off('click', '.thumbnail');
            });
        }
    }

    bindElements() {
        this.ajaxGetNotificationList();
        this.ajaxGetPeopleDetails();
        this.copyFieldToField();
        this.radioClickHideShowDiv();
    }

    ajaxGetPeopleDetails() {
        let $this = this;

        $(document).on('change', '.change-people-details', function () {
            let accordionIndex = $(this).data('index');

            $.ajax({
               url      : ajaxPath + '/accidents/contact',
               method   : "POST",
               dataType : "json",

               data: {
                   id : $(this).val()
               }
            }).done(function (data) {
                if (data) {
                    if (data.status === 200) {
                        $.each(data, function (index, value) {
                            $('#accidentcontacts-' + accordionIndex + '-' + index).val(value);
                        });

                        if (data.birthday) {
                            $('#accidentcontacts-' + accordionIndex + '-co_code').attr('value', data.co_code).trigger("change");
                        }

                        if (data.birthday) {
                            const birthday_split = data.birthday.split('-');

                            const nd = new Date(birthday_split[0], birthday_split[1], birthday_split[2]);
                            const ndsave = nd.getFullYear() + "-" + nd.getMonth() + "-" + nd.getDate();
                            const nddisp = nd.getDate() + "/" + nd.getMonth() + "/" + nd.getFullYear();

                            if (birthday_split.length === 3) {
                                $("#accidentcontacts-" + accordionIndex + "-birthday").val(ndsave);
                                $("#accidentcontacts-" + accordionIndex + "-birthday-disp").kvDatepicker("update", nddisp);
                            }
                        }

                        // only for the first accordion
                        if (accordionIndex === 0) {
                            $('#accidents-aq203_prof').val(data.function);
                            $('#accidents-bq203_prof').val(data.function);

                            $('input:radio[name="Accidents[AQ205_contracttype]"]').filter('[value="' + data.contract + '"]').prop('checked', true);
                            $('input:radio[name="Accidents[BQ205_contracttype]"]').filter('[value="' + data.contract + '"]').prop('checked', true);
                            $('input:radio[name="Accidents[AQ206_worktime]"]').filter('[value="' + data.worktime + '"]').prop('checked', true);
                            $('input:radio[name="Accidents[BQ206_worktime]"]').filter('[value="' + data.worktime + '"]').prop('checked', true);
                        }
                    }

                    // clean all the data if the status return a 404
                    if (data.status === 404) {
                        $("#collapse" + accordionIndex + " input[type='text']").val('');

                        // finding tab and AAA tab
                        $('#accidents-aq203_prof').val('');
                        $('#accidents-bq203_prof').val('');
                    }

                    if ($("#accidentcontacts-" + accordionIndex + "-cw_type").val() === 'V') {
                        $this.ajaxGetNotificationList(data.unite_travail);
                    }
                }
            }).fail(function (jqXHR, textStatus) {
                if (environment === 'dev') {
                    console.log("ajax request failed! " + textStatus);
                }
            });
        });
    }

    ajaxGetNotificationList(workunit) {
        if (workunit === undefined) {
            workunit = $('#accidentcontacts-0-unite_travail').val();
        }

        $.ajax({
            url      : ajaxPath + '/accidents/notiflist',
            method   : "POST",
            dataType : "json",

            data: {
                text : workunit,
                json : true
            }
        }).done(function (data) {
            if (data) {
                const persontobenotif     = $("#personsToBeNotified");
                const persontobenotifInfo = $("#personsToBeNotifiedInfo");

                if (data.status === 200) {
                    persontobenotif.show();
                    persontobenotifInfo.hide();

                    persontobenotif.html(data.list);
                } else {
                    persontobenotifInfo.show();
                    persontobenotif.hide();
                }
            }
        }).fail(function (jqXHR, textStatus) {
            if (environment === 'dev') {
                console.log("ajax request failed! " + textStatus);
            }
        });
    }

    copyFieldToField() {
        $(document).on('change', '.syncFieldA', function() {
            let idA = this.id;
            let idB = idA.replace('-aq', '-bq');
            let jA  = $(this);
            let jB  = $('#' + idB);

            let radioA = $(this).find('input[type="radio"]:checked').attr('value');

            let checkboxA = $(this).attr('id');
            let isChecked = ($('#'+checkboxA).prev().find('.glyphicon-ok').length) ? 0 : 1;

            // input text + date picker
            if (jA.data('inputTextVal') === jB.data('inputTextVal')) {
                // copy for text input
                jB.val(jA.val());

                // copy for the date picker
                $('#' + idB + '-disp').val(jA.val().slice(0, -3));
            }

            // radio buttons
            if ( jB.data('radioVal') === undefined ) {
                jB.find('input[value="' + radioA + '"]').prop("checked", true);
            }

            // checkboxes
            if ( jB.data('checkboxVal') === undefined && jB.data('krajee-checkboxx') ) {
                jB.attr('value', isChecked);
                jB.checkboxX('refresh');
            }
        });

        $(document).on('change', '.syncFieldB', function() {
            let idB = this.id;
            let jB  = $('#' + idB);

            jB.data('inputTextVal', jB.val());
            jB.data('radioVal', jB);

            jB.data('checkboxVal', jB);
        });
    }

    radioClickHideShowDiv() {
        const $input = $('[data-input-checked-value]');

        $input.click(function() {
            const input = $(this).find('input[type="radio"]:checked').val();

            const data      = $(this).data('input-checked-value');
            const connected = $(this).data('connected-to-div');

            $.each(data, function(i, v) {
                if (v === input) {
                    $('.hidden-field.' + connected).show();
                } else {
                    const $field = $('.hidden-field.' + connected);

                    $field.hide();

                    $field.find('input[type="text"], input[type="hidden"]').val('');

                    $field.find('[data-krajee-checkboxx]').attr('value', '0');
                    $field.find('[data-krajee-checkboxx]').checkboxX('refresh');

                    $field.find('.radio input').attr('checked', false);

                    $field.hide();
                }
            });
        });

        $input.each(function() {
            const input = $(this).find('input[type="radio"]:checked').val();

            const connected = $(this).data('connected-to-div');
            const data      = $(this).data('input-checked-value');

            $.each(data, function(i, v) {
                if (v === input) {
                    $('.hidden-field.' + connected).show();
                }
            });
        });
    }

    accordionSelect(masterElement, connectedTo, elementsToDisable, elementsToDeselect) {
        let $thumbLetter;
        let $collapseQ    = $('#collapse-q');
        let $btnTHumbnail = $('a.btn.thumbnail');

        // remove class disabled from every element
        $btnTHumbnail.removeClass('disabled');

        $btnTHumbnail.css('background-color', 'rgba(255,255,255,1)');

        $collapseQ.collapse('hide');

        // this checks if the element we clicked
        // has a "master" element
        if (connectedTo.length > 0 && $('img#' + masterElement + '.img-w-check').is(':hidden')) {
            $.each(connectedTo, function(index, letter) {
                $thumbLetter = $('.thumbnail-' + letter);

                $thumbLetter.children('.img-no-check').hide();
                $thumbLetter.children('.img-w-check').show();

                $('#collapse-' + letter).collapse('show');
            });
        }

        $('#collapse-' + masterElement).collapse('toggle');

        // toggle current element image
        $('img#' + masterElement).toggle();

        // loop elements to disable
        $.each(elementsToDisable, function(index, letter) {
            $thumbLetter = $('.thumbnail-' + letter);

            $thumbLetter.addClass('disabled');
            $thumbLetter.css('background-color', 'rgba(0,0,0,0.1)');

            $thumbLetter.children('.img-no-check').show();
            $thumbLetter.children('.img-w-check').hide();

            $('#collapse-' + letter).collapse('hide');
        });

        // loop elements to deselect
        $.each(elementsToDeselect, function(index, letter) {
            if ($('img#' + letter + '.img-w-check').is(':visible') && $('img#' + masterElement + '.img-w-check').is(':hidden')) {
                $thumbLetter = $('.thumbnail-' + letter);

                $thumbLetter.children('.img-no-check').show();
                $thumbLetter.children('.img-w-check').hide();

                $('#collapse-' + letter).collapse('hide');
            }
        });

        if ($('.thumbnail-' + masterElement).children('.img-no-check').is(':visible') && connectedTo.length === 0) {
            $btnTHumbnail.removeClass('disabled');
            $btnTHumbnail.css('background-color', 'rgba(255,255,255,1)');
            $collapseQ.collapse('show');
        }

        this.getCode();
    }

    getCode() {
        let tmp = '';
        let header = [];

        if ($(".thumbnail-h").children(".img-w-check:hidden").length === 0) {
            tmp += 'H';
            header.push('$hospitalFW');
        }
        if ($(".thumbnail-p").children(".img-w-check:hidden").length === 0) {
            tmp += 'P';
            header.push('$aidFW');
        }
        if ($(".thumbnail-c").children(".img-w-check:hidden").length === 0) {
            tmp += 'C';
            header.push('$vehicleFW');
        }
        if ($(".thumbnail-m").children(".img-w-check:hidden").length === 0) {
            tmp += 'M';
            header.push('$materialFW');
        }
        if ($(".thumbnail-a").children(".img-w-check:hidden").length === 0) {
            tmp += 'A';
            header.push('$accessoryFW');
        }
        if (tmp === ''){
            tmp = 'Q';
            header.push('$nearmissFW');
        }

        $('#accidents-accidenttype').val(tmp);
        $('#emodalHeader').find('h2').find('span').html(header.join(' / '));
    }

    setCode() {
        let tmp = $('#accidents-accidenttype').val();
        let $this = this;

        $.each($('a.thumbnail'), function() {
            let l = $(this).data('letter').toUpperCase();

            if (tmp.indexOf(l) > -1) {
                let toDisable   = (typeof $(this).data('to-disable') !== 'undefined') ? $(this).data('to-disable') : [];
                let toDeselect  = (typeof $(this).data('to-deselect') !== 'undefined') ? $(this).data('to-deselect') : [];
                let connectedTo = (typeof $(this).data('is-connected-to') !== 'undefined') ? $(this).data('is-connected-to') : [];

                $this.accordionSelect(l.toLowerCase(), connectedTo, toDisable, toDeselect);
            }
        });
    }

}