
export default class Infrastructure {

    constructor() {
        this.onChangeInput();
    }

    onChangeInput() {
        $("#infrastructure-fire_level, #infrastructure-area").change(function() {
            let level = parseInt($('#infrastructure-fire_level').val());
            let area  = parseInt($('#infrastructure-area').val());

            let $compartmentUE = $("#infrastructure-ue_number");
            let diff  = null;
            let ue_no = null;

            switch (level) {
                case 0:
                    if (area <= 50) {
                        ue_no = 3;
                        $compartmentUE.val(ue_no);
                    } else if (area > 50 && area <= 100) {
                        ue_no = 5;
                        $compartmentUE.val(ue_no);
                    } else if (area > 100 && area <= 200) {
                        ue_no = 6;
                        $compartmentUE.val(ue_no);
                    } else if (area > 200 && area <= 300) {
                        ue_no = 8;
                        $compartmentUE.val(ue_no);
                    } else if (area > 300 && area <= 400) {
                        ue_no = 9;
                        $compartmentUE.val(ue_no);
                    } else if (area > 400 && area <= 500) {
                        ue_no = 11;
                        $compartmentUE.val(ue_no);
                    } else if (area > 500 && area <= 600) {
                        ue_no = 12;
                        $compartmentUE.val(ue_no);
                    } else if (area > 600 && area <= 700) {
                        ue_no = 14;
                        $compartmentUE.val(ue_no);
                    } else if (area > 700 && area <= 800) {
                        ue_no = 15;
                        $compartmentUE.val(ue_no);
                    } else if (area > 800 && area <= 900) {
                        ue_no = 17;
                        $compartmentUE.val(ue_no);
                    } else if (area > 900 && area <= 1000) {
                        ue_no = 18;
                        $compartmentUE.val(ue_no);
                    } else if (area > 1000) {
                        diff = (area - 1000) / 250;
                        ue_no = Math.ceil(diff)*3 + 18;
                        $compartmentUE.val(ue_no);
                    }
                    break;
                case 1:
                    if (area <= 50) {
                        ue_no = 6;
                        $compartmentUE.val(ue_no);
                    } else if (area > 50 && area <= 100) {
                        ue_no = 9;
                        $compartmentUE.val(ue_no);
                    } else if (area > 100 && area <= 200) {
                        ue_no = 12;
                        $compartmentUE.val(ue_no);
                    } else if (area > 200 && area <= 300) {
                        ue_no = 15;
                        $compartmentUE.val(ue_no);
                    } else if (area > 300 && area <= 400) {
                        ue_no = 18;
                        $compartmentUE.val(ue_no);
                    } else if (area > 400 && area <= 500) {
                        ue_no = 21;
                        $compartmentUE.val(ue_no);
                    } else if (area > 500 && area <= 600) {
                        ue_no = 24;
                        $compartmentUE.val(ue_no);
                    } else if (area > 600 && area <= 700) {
                        ue_no = 27;
                        $compartmentUE.val(ue_no);
                    } else if (area > 700 && area <= 800) {
                        ue_no = 30;
                        $compartmentUE.val(ue_no);
                    } else if (area > 800 && area <= 900) {
                        ue_no = 33;
                        $compartmentUE.val(ue_no);
                    } else if (area > 900 && area <= 1000) {
                        ue_no = 36;
                        $compartmentUE.val(ue_no);
                    } else if (area > 1000) {
                        diff = (area - 1000) / 250;
                        ue_no = Math.ceil(diff)*6 + 36;
                        $compartmentUE.val(ue_no);
                    }
                    break;
                case 2:
                    if (area <= 50) {
                        ue_no = 12;
                        $compartmentUE.val(ue_no);
                    } else if (area > 50 && area <= 100) {
                        ue_no = 18;
                        $compartmentUE.val(ue_no);
                    } else if (area > 100 && area <= 200) {
                        ue_no = 24;
                        $compartmentUE.val(ue_no);
                    } else if (area > 200 && area <= 300) {
                        ue_no = 30;
                        $compartmentUE.val(ue_no);
                    } else if (area > 300 && area <= 400) {
                        ue_no = 36;
                        $compartmentUE.val(ue_no);
                    } else if (area > 400 && area <= 500) {
                        ue_no = 42;
                        $compartmentUE.val(ue_no);
                    } else if (area > 500 && area <= 600) {
                        ue_no = 48;
                        $compartmentUE.val(ue_no);
                    } else if (area > 600 && area <= 700) {
                        ue_no = 54;
                        $compartmentUE.val(ue_no);
                    } else if (area > 700 && area <= 800) {
                        ue_no = 60;
                        $compartmentUE.val(ue_no);
                    } else if (area > 800 && area <= 900) {
                        ue_no = 66;
                        $compartmentUE.val(ue_no);
                    } else if (area > 900 && area <= 1000) {
                        ue_no = 72;
                        $compartmentUE.val(ue_no);
                    } else if (area > 1000) {
                        diff = (area - 1000) / 250;
                        ue_no = Math.ceil(diff)*12 + 72;
                        $compartmentUE.val(ue_no);
                    }
                    break;
            }
        });
    }

}