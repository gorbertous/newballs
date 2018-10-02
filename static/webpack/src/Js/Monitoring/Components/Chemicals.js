
let calcArray = [];
let valP2    = null;
let valP3    = null;
let res      = null;
let finalRes = null;

export default class Chemicals {

    constructor() {
        $('#usage .select2-selection__choice, #danger .select2-selection__choice').attr('title', '');

        calcArray[11] = 1;
        calcArray[12] = 1;
        calcArray[13] = 1;
        calcArray[14] = 1;
        calcArray[21] = 2;
        calcArray[22] = 2;
        calcArray[23] = 2;
        calcArray[24] = 2;
        calcArray[31] = 3;
        calcArray[32] = 3;
        calcArray[33] = 3;
        calcArray[34] = 4;
        calcArray[41] = 3;
        calcArray[42] = 4;
        calcArray[43] = 4;
        calcArray[44] = 5;
        calcArray[51] = 4;
        calcArray[52] = 5;
        calcArray[53] = 5;
        calcArray[54] = 5;

        let $this = this;

        $(document).on('change', '[name$="[chem_codes_id_freq]"], [name$="[chem_codes_id_qty]"]', function(e) {
            e.preventDefault();
            $this.calculateRisk();
        });

        this.calculateRisk();
        this.keepRegisterplusIds();
    }

    calculateRisk() {
        finalRes = 0;

        $.each($('#consumption-tabular table tbody tr'), function(i) {
            valP2 = $('#jchemconsumption-'+ i +'-chem_codes_id_qty').val();
            valP3 = $('#jchemconsumption-'+ i +'-chem_codes_id_freq').val();

            res = calcArray[parseInt(valP2) * 10 + parseInt(valP3)];

            if (res > finalRes) {
                finalRes = res;
            }
        });

        $('#p2').val(valP2);
        $('#p3').val(valP3);

        $('#exposure').val(finalRes).trigger('depdrop:change');
    }

    keepRegisterplusIds() {
        $(document).on('change', '#stock_ids', function() {
            let registerplusIds = $('#chemicals-chem_registerplus_ids').val();
            $('#registerplusIds').val(registerplusIds);
        });
    }

}