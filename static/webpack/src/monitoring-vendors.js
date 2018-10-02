/**
 * For a better performance and recommended by webpack
 * any external library should be imported here!
 *
 * Folders imported from node_modules:
 * you don't need to specify 'node_modules' on the path, webpack automatically knows
 */

// import adminlte ( imported from '/node_modules/admin-lte' folder. )
import 'admin-lte/dist/js/adminlte.min.js';
import 'admin-lte/dist/css/AdminLTE.min.css';
import 'admin-lte/dist/css/skins/skin-blue.min.css';
import 'admin-lte/dist/css/skins/skin-blue-light.min.css';

// import iziModal ( imported from '/node_modules/izimodal' folder. )
import 'izimodal/js/iziModal.min.js';
import 'izimodal/css/iziModal.min.css';

// import eSST Monitoring style
import './Css/Monitoring/app.scss';