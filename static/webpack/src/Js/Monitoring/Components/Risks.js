import {$modalContainer} from "../Constants";

export default class Risks {

    constructor() {
        let $this = this;

        $(document).on('change', '.row-kinney select', function() {
            let kinney = $(this).closest('.row-kinney');
            let aorb   = $(this).attr('id').substr(-1);

            $this.setValue(kinney, aorb);
        });

        $(document).on('closing', $modalContainer, function () {
            window.location.reload();
        });
    }

    setValue(kinney, aorb) {
        let allScores = $('#risk-all-scores').val();
        let allColors = $('#risk-all-colors').val();

        let input_probability = $(kinney).find('[id$=probability' + aorb + ']').val();
        let input_frequence   = $(kinney).find('[id$=frequence' + aorb + ']').val();
        let input_effect      = $(kinney).find('[id$=effect' + aorb + ']').val();

        let input_score = $(kinney).find('[id$=score' + aorb + ']');
        let input_kinneyriskscore = $(kinney).find('[id$=kinneyriskscore' + aorb + ']');

        let score = ((input_probability * input_frequence * input_effect) / 1000);

        // change color of the two inputs
        $(input_score).css({ 'background-color': '' + this.getResult(allColors, score) + '', 'color': '#fff'});
        $(input_kinneyriskscore).css({ 'background-color': '' + this.getResult(allColors, score) + '', 'color': '#fff'});

        // set scores on the two inputs
        $(input_score).val(score);
        $(input_kinneyriskscore).val(this.getResult(allScores, score));
    }

    getResult(input, result) {
        let res = "";

        $.each($.parseJSON(input), function(index, value) {
            if (index < result) {
                res = ''+ value +'';
            }
        });

        return res;
    }

}