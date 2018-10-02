
export default class Documents {

    constructor() {
        this.onDateChange();
    }

    onDateChange() {
        if ($('.documents-form').length === 1) {
            let $reminder = $('#reminder');

            $reminder.hide();

            if ($('#Enddate-disp').val().length > 0) {
                $reminder.show();
            }

            $(document).on('change', '#Enddate-disp', function () {
                if ($(this).val().length > 0) {
                    $reminder.show();
                } else {
                    $reminder.hide();
                }
            });
        }
    }

}
