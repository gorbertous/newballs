
export default class Workers {

    constructor() {
        //EU + EEA countries + Switzerland
        let no_permit_list = ["LU","AT","BE","BG","CY","CZ","DE","DK","EE","ES","FI",
         "FR","GB","GR","HR","HU","IE","IT","LT","LV","MT","NL",
         "PL","PT","RO","SE","SI","SK","NO","IS","LI","CH"];

        let $contactNat = $("#contacts-nationality");

        if ($.inArray($contactNat.val(), no_permit_list) === -1 && $contactNat.val() !== '') {
            $("#permitDetails").show();
        }

        $contactNat.on("change",function(){
            if( $.inArray($(this).val(), no_permit_list) === -1) {
                $("#permitDetails").show();
            } else{
                $("#contacts-non_eu").val("0");
                $("#permitDetails").hide();
            }
        });
    }

}