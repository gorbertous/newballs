
export default class Empcontracts {

    constructor() {
        if ($('.empcontracts-form').length === 1) {
            $("#empcontracts-id_contact").change(function () {
                $.ajax({
                    url: settings.ajax_path + '/empcontracts/workerdata',
                    method: 'POST',
                    data: {id: $(this).val()},
                    dataType: 'json',
                    success: function (data) {
                        $("#empcontracts-position").val(data.Position);
                        $("#empcontracts-worktime").val(data.Worktime);
                        $("#empcontracts-id_workunit").val(data.ID_Workunit).trigger("change");
                        $("#empcontracts-id_workplace").val(data.ID_Workplace).trigger("change");
                        $("#empcontracts-id_employer").val(data.ID_Employer).trigger("change");

                        var sd = data.ContractStart.split('-');
                        var nddisp = '';
                        var ndsave = '';

                        if (sd.length == 3) {
                            var nd = new Date(sd[0], sd[1], sd[2]);
                            nddisp = nd.getDate() + "/" + nd.getMonth() + "/" + nd.getFullYear();
                            ndsave = nd.getFullYear() + "-" + nd.getMonth() + "-" + nd.getDate();
                        }

                        $("#empcontracts-start").val(ndsave);
                        $("#empcontracts-start-disp-kvdate").kvDatepicker("update", nddisp);
                    },
                    error: function () {
                        console.log("An error occured!");
                    }
                });
            });
        }
    }

}