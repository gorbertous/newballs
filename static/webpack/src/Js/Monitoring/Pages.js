// import components
import Accidents       from "./Components/Accidents";
import Actions         from "./Components/Actions";
import Dynamicform     from "./Components/Dynamicform";
import Dashboard       from "./Components/Dashboard";
import Imonths         from "./Components/Imonths";
import Empcontracts    from "./Components/Empcontracts";
import Documents       from "./Components/Documents";
import Infrastructure  from "./Components/Infrastructure";
import Message         from "./Components/Message";
import Risks           from "./Components/Risks";
import Workers         from "./Components/Workers";
import BootstrapWizard from "./Components/BootstrapWizard";
import Chemicals       from "./Components/Chemicals";
import Stock           from "./Components/Stock";

// import variables
import {languages, environment} from "./Constants";

/**
 * Class Pages
 * This class is an helper to execute functions only in certain pages
 */
export default class Pages
{
    /**
     * the default language of the application is 'fr' and it is hidden from the URL
     * so if we find another language on the URL like 'en' or 'de' we need to access the
     * second param from pathName or the URL.
     *
     * @param url string|null
     */
    constructor(url) {
        let path;
        let pathName;

        // the pathname must be updated everytime we load via ajax
        pathName = window.location.pathname.split('/');

        if (typeof url !== 'undefined') {
            path = url[2];
        } else {
            path = pathName[2];
        }

        if ($.inArray(path, languages) !== -1) {
            path = pathName[3];

            if (typeof url !== 'undefined') {
                path = url[3];
            }
        }

        this.Pages(path);
    }

    /**
     * Execute functions only in some pages
     *
     * @param path {string}
     * @constructor
     */
    Pages(path) {

        switch(path) {
            case 'dashboard':
                new Dashboard();
                break;

            case 'actions':
                new Actions();
                break;

            case 'accidents':
                new Accidents();
                break;

            case 'registerinspect':
                if ($('.registerinspect-form').length === 1) {
                    new Imonths('/registerinspect/registeritemimonths', {
                        registerplus_id : "#registerplus_id",
                        inspecttype_id  : "#inspecttype_id"
                    }, false);
                }
                break;

            case 'medvisits':
                if ($('.medvisits-form').length === 1) {
                    new Imonths('/medvisits/empcontractworkunitsimonths', {
                        empcontract_id: '#empcontract'
                    }, true);

                    $("select#medvisits-examtype").change();
                }
                break;

            case 'empcontracts':
                new Empcontracts();
                break;

            case 'raptri':
                if ($('.raptri-form').length === 1) {
                    new Imonths(null, null);
                }
                break;

            case 'invposris':
                if ($('.invposris-form').length === 1) {
                    new Imonths(null, null);
                }
                break;

            case 'documents':
                new Documents();
                break;

            case 'infrastructure':
                new Infrastructure();
                break;

            case 'diagnostics':
                new BootstrapWizard();
                break;

            case 'message':
                new Message();
                break;

            case 'risks':
                new Risks();
                break;
            
            case 'stock':
                new Stock();
                break;

            case 'trainingshistory':
                if ($('.trainingshistory-form').length === 1) {
                    new Imonths('/trainingshistory/imonths', {
                        id: '#trainingshistory-id_training'
                    }, false);
                }
                break;

            case 'workers':
                new Workers();
                break;

            case 'chemicals':
                if ($('.chemicals-form').length === 1) {
                    new Chemicals();
                }
                break;

            default:
                if (environment === 'dev') {
                    console.log('No path defined!');
                }
                break;
        }

        // execute the dynamicform patches on the pages we use it
        if ($('.dynamicform_actions_status').length === 1) {
            new Dynamicform(['dynamicform_actions_status']);
        }
        else if ($('.dynamicform_wrapper_risks').length === 1) {
            new Dynamicform(['dynamicform_wrapper_risks', 'dynamicform_wrapper']);
        }
        else if ($('.dynamicform_wrapper').length === 1) {
            new Dynamicform(['dynamicform_wrapper']);
        }
    }
}