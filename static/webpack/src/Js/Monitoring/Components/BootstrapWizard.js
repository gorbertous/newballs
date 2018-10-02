
export default class BootstrapWizard {

    constructor() {
        let $rootwizard = $('#rootwizard');

        if ($rootwizard.length > 0) {
            $rootwizard.bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                'onTabShow': function (tab, navigation, index) {
                    let $total = navigation.find('li').length - 1;
                    let $current = index + 1;
                    if ($current === 1) {
                        // first page
                        $rootwizard.find('input.btn.button-previous').hide();
                        $rootwizard.find('input.btn.button-next').show();
                        $rootwizard.find('button.btn.btn-success').hide();
                        $rootwizard.find('button.btn.btn-primary').hide();
                    } else if ($current >= $total) {
                        // last page
                        $rootwizard.find('input.btn.button-previous').show();
                        $rootwizard.find('input.btn.button-next').hide();
                        $rootwizard.find('button.btn.btn-success').show();
                        $rootwizard.find('button.btn.btn-primary').show();
                    } else {
                        $rootwizard.find('input.btn.button-previous').show();
                        $rootwizard.find('input.btn.button-next').show();
                        $rootwizard.find('button.btn.btn-success').hide();
                        $rootwizard.find('button.btn.btn-primary').hide();
                    }
                }
            });
        }
    }

}