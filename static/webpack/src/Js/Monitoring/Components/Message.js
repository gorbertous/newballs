import {ajaxPath} from "../Constants";

export default class Message {

    constructor() {
        this.syncMessage();
    }

    syncMessage() {
        $('#syncmessage').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                cache: false,
                url: ajaxPath + '/message/sync',

                success: function (response) {
                    if (response.startsWith('SUCCESS')) {
                        $('#syncmessagespan').text('');
                    }
                    alert(response);
                }
            });
        });
    }

}