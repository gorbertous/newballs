/**
 * @type {Array}
 * @private
 */
let _ids = [];

export default class Dynamicform {

    /**
     * @param ids {array}
     */
    constructor(ids) {
        _ids = ids;
        this.init();
    }

    init() {
        this.dateFixerOnSubmit();
        this.datePickerFixer();

        let $this = this;

        $.each(_ids, function(index, value) {
            $this.afterInsert(value);
            $this.afterDelete(value);

            if ($('.' + value).find('.item-container').length === 0) {
                $('.' + value + ' .new-dynamicform-item').show();
            }
        });
    }

    /**
     * @param value {string}
     */
    afterInsert(value) {
        const $value = $("." + value);

        $value.on("afterInsert", function() {
            if ($value.find('.item-container').length === 1 ) {
                $('.' + value + ' .new-dynamicform-item').hide();
            }

            $("." + value + " .panel-title-sn").each(function(index) {
                $(this).html(index + 1);
            });
        });
    }

    /**
     * @param value {string}
     */
    afterDelete(value) {
        const $value = $("." + value);

        $value.on("afterDelete", function() {
            if ($value.find(".item-container").length === 0) {
                $value.find(".new-dynamicform-item").show();
            }

            $("." + value + " .panel-title-sn").each(function(index) {
                $(this).html(index + 1);
            });
        });
    }

    datePickerFixer() {
        $("[data-dynamicform^='dynamicform']").on("afterInsert", function() {
            const datePickers = $(this).find("[data-krajee-kvdatepicker]");

            datePickers.each(function () {
                $(this).parent().removeData();
                $(this).parent().kvDatepicker(eval($(this).attr("data-krajee-kvdatepicker")));
            });
        });
    }

    /**
     * javascript to handle kvDatepicker created dynamically
     * just before submitting, we read the DISPLAYED form control
     * reformat from dd/MM/yyyy to phpY-m-d and update the value
     * of the HIDDEN form control (which is sent as POST variable)
     */
    dateFixerOnSubmit() {
        $(".form-has-dynamicform").on("submit", function() {
            $(".krajee-datepicker").each(function() {
                const disp   = $(this).attr("id");
                const hidd   = "#" + disp.replace("-disp", "");
                const dparts = $(this).val().split("/");

                let hiddd = '';

                if (dparts.length === 3) {
                    hiddd = dparts[2] + '-' + dparts[1] + '-' + dparts[0];
                }

                if ($(hidd).val() !== hiddd) {
                    $(hidd).val(hiddd);
                }
            });
        });
    }

}