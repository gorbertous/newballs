/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./monitoring.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./Js/Monitoring/Components/Accidents.js":
/*!***********************************************!*\
  !*** ./Js/Monitoring/Components/Accidents.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Constants = __webpack_require__(/*! ../Constants */ "./Js/Monitoring/Constants.js");

var _Treeview = __webpack_require__(/*! ./Treeview */ "./Js/Monitoring/Components/Treeview.js");

var _Treeview2 = _interopRequireDefault(_Treeview);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Accidents = function () {
    function Accidents() {
        _classCallCheck(this, Accidents);

        // create/update page
        if ($('#form-accidents').length === 1) {
            this.bindElements();
            new _Treeview2.default();
        }

        // select page
        if ($('.accidents-select').length === 1) {
            this.setCode();

            var $this = this;

            $(document).on('click', '.thumbnail', function (e) {
                var letter = $(this).data('letter');
                var toDisable = typeof $(this).data('to-disable') !== 'undefined' ? $(this).data('to-disable') : [];
                var toDeselect = typeof $(this).data('to-deselect') !== 'undefined' ? $(this).data('to-deselect') : [];
                var connectedTo = typeof $(this).data('is-connected-to') !== 'undefined' ? $(this).data('is-connected-to') : [];
                $this.accordionSelect(letter, connectedTo, toDisable, toDeselect);
            });

            $(document).on('closing', '#modal', function (e) {
                $(document).off('click', '.thumbnail');
            });
        }
    }

    _createClass(Accidents, [{
        key: "bindElements",
        value: function bindElements() {
            this.ajaxGetNotificationList();
            this.ajaxGetPeopleDetails();
            this.copyFieldToField();
            this.radioClickHideShowDiv();
        }
    }, {
        key: "ajaxGetPeopleDetails",
        value: function ajaxGetPeopleDetails() {
            var $this = this;

            $(document).on('change', '.change-people-details', function () {
                var accordionIndex = $(this).data('index');

                $.ajax({
                    url: _Constants.ajaxPath + '/accidents/contact',
                    method: "POST",
                    dataType: "json",

                    data: {
                        id: $(this).val()
                    }
                }).done(function (data) {
                    if (data) {
                        if (data.status === 200) {
                            $.each(data, function (index, value) {
                                $('#accidentcontacts-' + accordionIndex + '-' + index).val(value);
                            });

                            if (data.birthday) {
                                $('#accidentcontacts-' + accordionIndex + '-co_code').attr('value', data.co_code).trigger("change");
                            }

                            if (data.birthday) {
                                var birthday_split = data.birthday.split('-');

                                var nd = new Date(birthday_split[0], birthday_split[1], birthday_split[2]);
                                var ndsave = nd.getFullYear() + "-" + nd.getMonth() + "-" + nd.getDate();
                                var nddisp = nd.getDate() + "/" + nd.getMonth() + "/" + nd.getFullYear();

                                if (birthday_split.length === 3) {
                                    $("#accidentcontacts-" + accordionIndex + "-birthday").val(ndsave);
                                    $("#accidentcontacts-" + accordionIndex + "-birthday-disp").kvDatepicker("update", nddisp);
                                }
                            }

                            // only for the first accordion
                            if (accordionIndex === 0) {
                                $('#accidents-aq203_prof').val(data.function);
                                $('#accidents-bq203_prof').val(data.function);

                                $('input:radio[name="Accidents[AQ205_contracttype]"]').filter('[value="' + data.contract + '"]').prop('checked', true);
                                $('input:radio[name="Accidents[BQ205_contracttype]"]').filter('[value="' + data.contract + '"]').prop('checked', true);
                                $('input:radio[name="Accidents[AQ206_worktime]"]').filter('[value="' + data.worktime + '"]').prop('checked', true);
                                $('input:radio[name="Accidents[BQ206_worktime]"]').filter('[value="' + data.worktime + '"]').prop('checked', true);
                            }
                        }

                        // clean all the data if the status return a 404
                        if (data.status === 404) {
                            $("#collapse" + accordionIndex + " input[type='text']").val('');

                            // finding tab and AAA tab
                            $('#accidents-aq203_prof').val('');
                            $('#accidents-bq203_prof').val('');
                        }

                        if ($("#accidentcontacts-" + accordionIndex + "-cw_type").val() === 'V') {
                            $this.ajaxGetNotificationList(data.unite_travail);
                        }
                    }
                }).fail(function (jqXHR, textStatus) {
                    if (_Constants.environment === 'dev') {
                        console.log("ajax request failed! " + textStatus);
                    }
                });
            });
        }
    }, {
        key: "ajaxGetNotificationList",
        value: function ajaxGetNotificationList(workunit) {
            if (workunit === undefined) {
                workunit = $('#accidentcontacts-0-unite_travail').val();
            }

            $.ajax({
                url: _Constants.ajaxPath + '/accidents/notiflist',
                method: "POST",
                dataType: "json",

                data: {
                    text: workunit,
                    json: true
                }
            }).done(function (data) {
                if (data) {
                    var persontobenotif = $("#personsToBeNotified");
                    var persontobenotifInfo = $("#personsToBeNotifiedInfo");

                    if (data.status === 200) {
                        persontobenotif.show();
                        persontobenotifInfo.hide();

                        persontobenotif.html(data.list);
                    } else {
                        persontobenotifInfo.show();
                        persontobenotif.hide();
                    }
                }
            }).fail(function (jqXHR, textStatus) {
                if (_Constants.environment === 'dev') {
                    console.log("ajax request failed! " + textStatus);
                }
            });
        }
    }, {
        key: "copyFieldToField",
        value: function copyFieldToField() {
            $(document).on('change', '.syncFieldA', function () {
                var idA = this.id;
                var idB = idA.replace('-aq', '-bq');
                var jA = $(this);
                var jB = $('#' + idB);

                var radioA = $(this).find('input[type="radio"]:checked').attr('value');

                var checkboxA = $(this).attr('id');
                var isChecked = $('#' + checkboxA).prev().find('.glyphicon-ok').length ? 0 : 1;

                // input text + date picker
                if (jA.data('inputTextVal') === jB.data('inputTextVal')) {
                    // copy for text input
                    jB.val(jA.val());

                    // copy for the date picker
                    $('#' + idB + '-disp').val(jA.val().slice(0, -3));
                }

                // radio buttons
                if (jB.data('radioVal') === undefined) {
                    jB.find('input[value="' + radioA + '"]').prop("checked", true);
                }

                // checkboxes
                if (jB.data('checkboxVal') === undefined && jB.data('krajee-checkboxx')) {
                    jB.attr('value', isChecked);
                    jB.checkboxX('refresh');
                }
            });

            $(document).on('change', '.syncFieldB', function () {
                var idB = this.id;
                var jB = $('#' + idB);

                jB.data('inputTextVal', jB.val());
                jB.data('radioVal', jB);

                jB.data('checkboxVal', jB);
            });
        }
    }, {
        key: "radioClickHideShowDiv",
        value: function radioClickHideShowDiv() {
            var $input = $('[data-input-checked-value]');

            $input.click(function () {
                var input = $(this).find('input[type="radio"]:checked').val();

                var data = $(this).data('input-checked-value');
                var connected = $(this).data('connected-to-div');

                $.each(data, function (i, v) {
                    if (v === input) {
                        $('.hidden-field.' + connected).show();
                    } else {
                        var $field = $('.hidden-field.' + connected);

                        $field.hide();

                        $field.find('input[type="text"], input[type="hidden"]').val('');

                        $field.find('[data-krajee-checkboxx]').attr('value', '0');
                        $field.find('[data-krajee-checkboxx]').checkboxX('refresh');

                        $field.find('.radio input').attr('checked', false);

                        $field.hide();
                    }
                });
            });

            $input.each(function () {
                var input = $(this).find('input[type="radio"]:checked').val();

                var connected = $(this).data('connected-to-div');
                var data = $(this).data('input-checked-value');

                $.each(data, function (i, v) {
                    if (v === input) {
                        $('.hidden-field.' + connected).show();
                    }
                });
            });
        }
    }, {
        key: "accordionSelect",
        value: function accordionSelect(masterElement, connectedTo, elementsToDisable, elementsToDeselect) {
            var $thumbLetter = void 0;
            var $collapseQ = $('#collapse-q');
            var $btnTHumbnail = $('a.btn.thumbnail');

            // remove class disabled from every element
            $btnTHumbnail.removeClass('disabled');

            $btnTHumbnail.css('background-color', 'rgba(255,255,255,1)');

            $collapseQ.collapse('hide');

            // this checks if the element we clicked
            // has a "master" element
            if (connectedTo.length > 0 && $('img#' + masterElement + '.img-w-check').is(':hidden')) {
                $.each(connectedTo, function (index, letter) {
                    $thumbLetter = $('.thumbnail-' + letter);

                    $thumbLetter.children('.img-no-check').hide();
                    $thumbLetter.children('.img-w-check').show();

                    $('#collapse-' + letter).collapse('show');
                });
            }

            $('#collapse-' + masterElement).collapse('toggle');

            // toggle current element image
            $('img#' + masterElement).toggle();

            // loop elements to disable
            $.each(elementsToDisable, function (index, letter) {
                $thumbLetter = $('.thumbnail-' + letter);

                $thumbLetter.addClass('disabled');
                $thumbLetter.css('background-color', 'rgba(0,0,0,0.1)');

                $thumbLetter.children('.img-no-check').show();
                $thumbLetter.children('.img-w-check').hide();

                $('#collapse-' + letter).collapse('hide');
            });

            // loop elements to deselect
            $.each(elementsToDeselect, function (index, letter) {
                if ($('img#' + letter + '.img-w-check').is(':visible') && $('img#' + masterElement + '.img-w-check').is(':hidden')) {
                    $thumbLetter = $('.thumbnail-' + letter);

                    $thumbLetter.children('.img-no-check').show();
                    $thumbLetter.children('.img-w-check').hide();

                    $('#collapse-' + letter).collapse('hide');
                }
            });

            if ($('.thumbnail-' + masterElement).children('.img-no-check').is(':visible') && connectedTo.length === 0) {
                $btnTHumbnail.removeClass('disabled');
                $btnTHumbnail.css('background-color', 'rgba(255,255,255,1)');
                $collapseQ.collapse('show');
            }

            this.getCode();
        }
    }, {
        key: "getCode",
        value: function getCode() {
            var tmp = '';
            var header = [];

            if ($(".thumbnail-h").children(".img-w-check:hidden").length === 0) {
                tmp += 'H';
                header.push('$hospitalFW');
            }
            if ($(".thumbnail-p").children(".img-w-check:hidden").length === 0) {
                tmp += 'P';
                header.push('$aidFW');
            }
            if ($(".thumbnail-c").children(".img-w-check:hidden").length === 0) {
                tmp += 'C';
                header.push('$vehicleFW');
            }
            if ($(".thumbnail-m").children(".img-w-check:hidden").length === 0) {
                tmp += 'M';
                header.push('$materialFW');
            }
            if ($(".thumbnail-a").children(".img-w-check:hidden").length === 0) {
                tmp += 'A';
                header.push('$accessoryFW');
            }
            if (tmp === '') {
                tmp = 'Q';
                header.push('$nearmissFW');
            }

            $('#accidents-accidenttype').val(tmp);
            $('#emodalHeader').find('h2').find('span').html(header.join(' / '));
        }
    }, {
        key: "setCode",
        value: function setCode() {
            var tmp = $('#accidents-accidenttype').val();
            var $this = this;

            $.each($('a.thumbnail'), function () {
                var l = $(this).data('letter').toUpperCase();

                if (tmp.indexOf(l) > -1) {
                    var toDisable = typeof $(this).data('to-disable') !== 'undefined' ? $(this).data('to-disable') : [];
                    var toDeselect = typeof $(this).data('to-deselect') !== 'undefined' ? $(this).data('to-deselect') : [];
                    var connectedTo = typeof $(this).data('is-connected-to') !== 'undefined' ? $(this).data('is-connected-to') : [];

                    $this.accordionSelect(l.toLowerCase(), connectedTo, toDisable, toDeselect);
                }
            });
        }
    }]);

    return Accidents;
}();

exports.default = Accidents;

/***/ }),

/***/ "./Js/Monitoring/Components/Actions.js":
/*!*********************************************!*\
  !*** ./Js/Monitoring/Components/Actions.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Constants = __webpack_require__(/*! ../Constants */ "./Js/Monitoring/Constants.js");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Actions = function () {
    function Actions() {
        _classCallCheck(this, Actions);

        if ($('.actions-form').length === 1) {
            this.getContext();

            var $this = this;

            $(document).on('change', '.action-get-context', function () {
                $this.getContext();
            });

            this.actionProgressInsertDate();
        }
    }

    _createClass(Actions, [{
        key: 'actionProgressInsertDate',
        value: function actionProgressInsertDate() {
            $("[data-dynamicform^='dynamicform']").on("afterInsert", function () {
                var field = $(this).find('[data-krajee-kvdatepicker]');

                var date = $('.df-date').data('text');

                $.each(field, function () {
                    if ($(this).val().length === 0) {
                        $(this).val(date);
                        var nextInput = $(this).attr('id').replace('-disp', '');
                        $('#' + nextInput).val(date);
                    }
                });
            });
        }
    }, {
        key: 'getContext',
        value: function getContext() {
            $.ajax({
                url: _Constants.ajaxPath + '/actions/actioncontext',
                method: 'POST',
                dataType: 'json',
                data: {
                    registerinspect_id: $('#actions-id_registerinspect').val(),
                    risk_id: $('#actions-id_risk').val(),
                    accident_id: $('#actions-id_accident').val()
                }
            }).done(function (data) {
                $('.action-context').html(data.action_on);
                $('#data').html(data.content);
            }).fail(function () {
                if (_Constants.environment === 'dev') {
                    console.log('Error Processing Request!');
                }
            });
        }
    }]);

    return Actions;
}();

exports.default = Actions;

/***/ }),

/***/ "./Js/Monitoring/Components/BootstrapWizard.js":
/*!*****************************************************!*\
  !*** ./Js/Monitoring/Components/BootstrapWizard.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var BootstrapWizard = function BootstrapWizard() {
    _classCallCheck(this, BootstrapWizard);

    var $rootwizard = $('#rootwizard');

    if ($rootwizard.length > 0) {
        $rootwizard.bootstrapWizard({
            'nextSelector': '.button-next',
            'previousSelector': '.button-previous',
            'onTabShow': function onTabShow(tab, navigation, index) {
                var $total = navigation.find('li').length - 1;
                var $current = index + 1;
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
};

exports.default = BootstrapWizard;

/***/ }),

/***/ "./Js/Monitoring/Components/Chemicals.js":
/*!***********************************************!*\
  !*** ./Js/Monitoring/Components/Chemicals.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var calcArray = [];
var valP2 = null;
var valP3 = null;
var res = null;
var finalRes = null;

var Chemicals = function () {
    function Chemicals() {
        _classCallCheck(this, Chemicals);

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

        var $this = this;

        $(document).on('change', '[name$="[chem_codes_id_freq]"], [name$="[chem_codes_id_qty]"]', function (e) {
            e.preventDefault();
            $this.calculateRisk();
        });

        this.calculateRisk();
        this.keepRegisterplusIds();
    }

    _createClass(Chemicals, [{
        key: 'calculateRisk',
        value: function calculateRisk() {
            finalRes = 0;

            $.each($('#consumption-tabular table tbody tr'), function (i) {
                valP2 = $('#jchemconsumption-' + i + '-chem_codes_id_qty').val();
                valP3 = $('#jchemconsumption-' + i + '-chem_codes_id_freq').val();

                res = calcArray[parseInt(valP2) * 10 + parseInt(valP3)];

                if (res > finalRes) {
                    finalRes = res;
                }
            });

            $('#p2').val(valP2);
            $('#p3').val(valP3);

            $('#exposure').val(finalRes).trigger('depdrop:change');
        }
    }, {
        key: 'keepRegisterplusIds',
        value: function keepRegisterplusIds() {
            $(document).on('change', '#stock_ids', function () {
                var registerplusIds = $('#chemicals-chem_registerplus_ids').val();
                $('#registerplusIds').val(registerplusIds);
            });
        }
    }]);

    return Chemicals;
}();

exports.default = Chemicals;

/***/ }),

/***/ "./Js/Monitoring/Components/Dashboard.js":
/*!***********************************************!*\
  !*** ./Js/Monitoring/Components/Dashboard.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Constants = __webpack_require__(/*! ../Constants */ "./Js/Monitoring/Constants.js");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Dashboard = function () {
    function Dashboard() {
        _classCallCheck(this, Dashboard);

        // do not execute this if we are on the modal 'filter'
        if ($('#form-filter').length === 0) {
            this.meteoLux();
            this.mainTabs();

            var $this = this;
            $.getScript("https://www.gstatic.com/charts/loader.js", function (data, textStatus, jqxhr) {
                if (textStatus === 'success') {
                    google.charts.load("current", { packages: ["corechart"] });
                    google.charts.setOnLoadCallback($this.drawWorkunitsChart);
                    google.charts.setOnLoadCallback($this.drawAbsencesChart);
                } else {
                    if (_Constants.environment === 'dev') {
                        console.log('Error loading JS charts! ', textStatus, jqxhr);
                    }
                }
            });
        }
    }

    _createClass(Dashboard, [{
        key: "mainTabs",
        value: function mainTabs() {
            var $tabs = $('.dashboard-todos-tabs').find('.tab-pane');
            var $menuLi = $('.dashboard-todos-li');

            $.each($tabs, function () {
                var lengthBox = $(this).children('#box').length;
                var idBox = $(this).attr('id');
                var $liMenu = $menuLi.find('li.' + idBox);

                if (lengthBox > 0) {
                    $liMenu.find('.number-items-circle').html(lengthBox);

                    var $boxContainsBgClass = $(this).find('.small-box');

                    $.each($boxContainsBgClass, function () {
                        var $bgBox = $(this).attr('class');

                        if ($bgBox.indexOf('bg-yellow') >= 0) {
                            $liMenu.find('.number-items-circle').css('background-color', '#ffa64d');
                        } else if ($bgBox.indexOf('bg-red') >= 0) {
                            $liMenu.find('.number-items-circle').css('background-color', '#ff6666');

                            // stop the loop execution if we found a red alert
                            return false;
                        }
                    });
                } else {
                    $(this).remove();
                    $liMenu.remove();
                }
            });
        }
    }, {
        key: "drawWorkunitsChart",
        value: function drawWorkunitsChart() {
            var data = new google.visualization.DataTable();

            $.ajax({
                url: _Constants.ajaxPath + '/dashboard/piechart',
                dataType: "json",
                async: false
            }).done(function (jsonData) {
                data.addColumn('string', 'TotalWorkers');
                data.addColumn('number', 'Workunit');

                $.each(jsonData.data, function (i, jsonData) {
                    data.addRows([[jsonData.workunit, parseInt(jsonData.totalWorkers)]]);
                });

                if (jsonData.data.length === 0) {
                    $('.piechart-container').remove();
                } else {
                    var options = {
                        height: 400,
                        sliceVisibilityThreshold: 0.022,
                        is3D: true,
                        datalessRegionColor: '#dedede',
                        defaultColor: '#dedede',
                        legend: {
                            position: 'top',
                            maxLines: 5
                        },
                        chartArea: {
                            top: 120
                        },
                        colorAxis: {
                            colors: ['#54C492', '#cc0000']
                        }
                    };

                    var pieChart = new google.visualization.PieChart(document.getElementById('piechart_content'));

                    pieChart.draw(data, options);
                }
            }).fail(function () {
                $('#piechart_content').html('An error occurred while loading the chart.');
            });
        }
    }, {
        key: "drawAbsencesChart",
        value: function drawAbsencesChart() {
            var data = new google.visualization.DataTable();

            $.ajax({
                url: _Constants.ajaxPath + '/dashboard/areachart',
                dataType: "json",
                async: false
            }).done(function (jsonData) {
                data.addColumn('date', jsonData.text_start);
                data.addColumn('number', jsonData.text_sickness);
                data.addColumn('number', jsonData.text_family);
                data.addColumn('number', jsonData.text_accident);
                data.addColumn('number', jsonData.text_parental);

                data.addColumn({ type: 'string', role: 'tooltip' });

                $.each(jsonData.data, function (i, jsonData) {
                    data.addRows([[new Date(jsonData.start), parseInt(jsonData.sickness), parseInt(jsonData.family), parseInt(jsonData.accident), parseInt(jsonData.parental), jsonData.tooltip]]);
                });

                if (jsonData.data.length === 0) {
                    $('.areachart-container').remove();
                } else {
                    var options = {
                        height: 400,
                        hAxis: {
                            title: jsonData.text_month,
                            titleTextStyle: {
                                color: '#333'
                            }
                        },
                        vAxis: {
                            title: jsonData.text_hours,
                            minValue: 0
                        },
                        legend: {
                            position: 'top',
                            maxLines: 5
                        }
                    };

                    var areaChart = new google.visualization.AreaChart(document.getElementById('areachart_content'));

                    areaChart.draw(data, options);
                }
            }).fail(function () {
                $('#areachart_content').html('An error occurred while loading the chart.');
            });
        }
    }, {
        key: "meteoLux",
        value: function meteoLux() {
            $.getScript("/static/libs/js/meteolux.js", function (data, textStatus, jqxhr) {
                if (textStatus === 'success') {
                    try {
                        new Meteolux({
                            theme: "light",
                            dropShadow: false,
                            maxWidth: "800",
                            displayAlert: true,
                            displayToday: true,
                            displayTodayNext: true,
                            nextDaysNumber: 4,
                            containerID: "meteolux-widget"
                        });
                    } catch (e) {
                        return false;
                    }
                } else {
                    if (_Constants.environment === 'dev') {
                        console.log('Error loading meteolux JS!', textStatus, jqxhr);
                    }
                }
            });
        }
    }]);

    return Dashboard;
}();

exports.default = Dashboard;

/***/ }),

/***/ "./Js/Monitoring/Components/Documents.js":
/*!***********************************************!*\
  !*** ./Js/Monitoring/Components/Documents.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Documents = function () {
    function Documents() {
        _classCallCheck(this, Documents);

        this.onDateChange();
    }

    _createClass(Documents, [{
        key: 'onDateChange',
        value: function onDateChange() {
            if ($('.documents-form').length === 1) {
                var $reminder = $('#reminder');

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
    }]);

    return Documents;
}();

exports.default = Documents;

/***/ }),

/***/ "./Js/Monitoring/Components/Dynamicform.js":
/*!*************************************************!*\
  !*** ./Js/Monitoring/Components/Dynamicform.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * @type {Array}
 * @private
 */
var _ids = [];

var Dynamicform = function () {

    /**
     * @param ids {array}
     */
    function Dynamicform(ids) {
        _classCallCheck(this, Dynamicform);

        _ids = ids;
        this.init();
    }

    _createClass(Dynamicform, [{
        key: 'init',
        value: function init() {
            this.dateFixerOnSubmit();
            this.datePickerFixer();

            var $this = this;

            $.each(_ids, function (index, value) {
                $this.afterInsert(value);
                $this.afterDelete(value);

                if ($('.' + value).find('.item-container').length === 0) {
                    $('.' + value + ' .new-dynamicform-item').show();
                }
            });
        }

        /**
         * @param value {string}
         */

    }, {
        key: 'afterInsert',
        value: function afterInsert(value) {
            var $value = $("." + value);

            $value.on("afterInsert", function () {
                if ($value.find('.item-container').length === 1) {
                    $('.' + value + ' .new-dynamicform-item').hide();
                }

                $("." + value + " .panel-title-sn").each(function (index) {
                    $(this).html(index + 1);
                });
            });
        }

        /**
         * @param value {string}
         */

    }, {
        key: 'afterDelete',
        value: function afterDelete(value) {
            var $value = $("." + value);

            $value.on("afterDelete", function () {
                if ($value.find(".item-container").length === 0) {
                    $value.find(".new-dynamicform-item").show();
                }

                $("." + value + " .panel-title-sn").each(function (index) {
                    $(this).html(index + 1);
                });
            });
        }
    }, {
        key: 'datePickerFixer',
        value: function datePickerFixer() {
            $("[data-dynamicform^='dynamicform']").on("afterInsert", function () {
                var datePickers = $(this).find("[data-krajee-kvdatepicker]");

                datePickers.each(function () {
                    $(this).parent().removeData();
                    $(this).parent().kvDatepicker(eval($(this).attr("data-krajee-kvdatepicker")));
                });
            });
        }

        /**
         * javascript to handle kvDatepicker created dynamically
         * just before submitting, we read the DISPLAYED form control
         * reformat from dd/MM/yyyy to phpY-m-d and update the value
         * of the HIDDEN form control (which is sent as POST variable)
         */

    }, {
        key: 'dateFixerOnSubmit',
        value: function dateFixerOnSubmit() {
            $(".form-has-dynamicform").on("submit", function () {
                $(".krajee-datepicker").each(function () {
                    var disp = $(this).attr("id");
                    var hidd = "#" + disp.replace("-disp", "");
                    var dparts = $(this).val().split("/");

                    var hiddd = '';

                    if (dparts.length === 3) {
                        hiddd = dparts[2] + '-' + dparts[1] + '-' + dparts[0];
                    }

                    if ($(hidd).val() !== hiddd) {
                        $(hidd).val(hiddd);
                    }
                });
            });
        }
    }]);

    return Dynamicform;
}();

exports.default = Dynamicform;

/***/ }),

/***/ "./Js/Monitoring/Components/Empcontracts.js":
/*!**************************************************!*\
  !*** ./Js/Monitoring/Components/Empcontracts.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Empcontracts = function Empcontracts() {
    _classCallCheck(this, Empcontracts);

    if ($('.empcontracts-form').length === 1) {
        $("#empcontracts-id_contact").change(function () {
            $.ajax({
                url: settings.ajax_path + '/empcontracts/workerdata',
                method: 'POST',
                data: { id: $(this).val() },
                dataType: 'json',
                success: function success(data) {
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
                error: function error() {
                    console.log("An error occured!");
                }
            });
        });
    }
};

exports.default = Empcontracts;

/***/ }),

/***/ "./Js/Monitoring/Components/Imonths.js":
/*!*********************************************!*\
  !*** ./Js/Monitoring/Components/Imonths.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Constants = __webpack_require__(/*! ../Constants */ "./Js/Monitoring/Constants.js");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var object = {};

var Imonths = function () {
    function Imonths(url, params, handledepdrop) {
        _classCallCheck(this, Imonths);

        var $this = this;

        $this.survey('#Startdate', function () {
            $this.recalCnext();
        });

        $(document).on("change", "#iMonths", function () {
            $this.recalCnext();
        });

        $this.onChangeImonths(url, params, handledepdrop);
    }

    _createClass(Imonths, [{
        key: "recalCnext",
        value: function recalCnext() {
            var $sd = $("#Startdate").val().split('-');

            if ($sd.length == 3) {
                var $im = $("#iMonths").val();
                var $nextdate = $("#Nextdate");

                if ($im.length == 0) {
                    $nextdate.val('');
                    $("#Nextdate-disp-kvdate").kvDatepicker("update", '');
                } else {
                    var $nd = new Date($sd[0], $sd[1] - (1 - $im), $sd[2]);
                    var $nddisp = $nd.getDate() + "/" + ($nd.getMonth() + 1) + "/" + $nd.getFullYear();
                    var $ndsave = $nd.getFullYear() + "-" + ($nd.getMonth() + 1) + "-" + $nd.getDate();

                    if ($nextdate.val() != $ndsave) {
                        if (confirm($('.warn-message').data('text')) === true) {
                            $nextdate.val($ndsave);
                            $("#Nextdate-disp-kvdate").kvDatepicker("update", $nddisp);
                        }
                    }
                }
            }
        }
    }, {
        key: "survey",
        value: function survey(selector, callback) {
            var $input = $(selector);
            var $oldvalue = $input.val();

            setInterval(function () {
                if ($input.val() != $oldvalue) {
                    $oldvalue = $input.val();
                    callback();
                }
            }, 100);
        }
    }, {
        key: "onChangeImonths",
        value: function onChangeImonths(url, params, handledepdrop) {
            var $this = this;

            if (handledepdrop) {
                $(document).on('depdrop:afterChange', '.input-change-imonths', function (e) {
                    e.preventDefault();
                    $this.trigger(url, params);
                });
            }

            $(document).on('change', '.input-change-imonths', function (e) {
                e.preventDefault();
                $this.trigger(url, params);
            });
        }
    }, {
        key: "trigger",
        value: function trigger(url, params) {
            var $this = this;
            object = {};

            $.each(params, function (k, v) {
                object[k] = $(v).val();
            });

            $.ajax({
                url: _Constants.ajaxPath + url,
                dataType: 'html',
                method: 'POST',
                data: object
            }).done(function (data) {
                $("#stdMonths").text(" (Std = " + data + ")");

                var $iMonths = $('#iMonths');
                console.log($iMonths.val());
                if ($iMonths.val().length != 0) {
                    $this.recalCnext();
                }
            }).fail(function () {
                if (_Constants.environment === 'dev') {
                    console.log('Error processing Imonths');
                }
            });
        }
    }]);

    return Imonths;
}();

exports.default = Imonths;

/***/ }),

/***/ "./Js/Monitoring/Components/Infrastructure.js":
/*!****************************************************!*\
  !*** ./Js/Monitoring/Components/Infrastructure.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Infrastructure = function () {
    function Infrastructure() {
        _classCallCheck(this, Infrastructure);

        this.onChangeInput();
    }

    _createClass(Infrastructure, [{
        key: 'onChangeInput',
        value: function onChangeInput() {
            $("#infrastructure-fire_level, #infrastructure-area").change(function () {
                var level = parseInt($('#infrastructure-fire_level').val());
                var area = parseInt($('#infrastructure-area').val());

                var $compartmentUE = $("#infrastructure-ue_number");
                var diff = null;
                var ue_no = null;

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
                            ue_no = Math.ceil(diff) * 3 + 18;
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
                            ue_no = Math.ceil(diff) * 6 + 36;
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
                            ue_no = Math.ceil(diff) * 12 + 72;
                            $compartmentUE.val(ue_no);
                        }
                        break;
                }
            });
        }
    }]);

    return Infrastructure;
}();

exports.default = Infrastructure;

/***/ }),

/***/ "./Js/Monitoring/Components/Message.js":
/*!*********************************************!*\
  !*** ./Js/Monitoring/Components/Message.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Constants = __webpack_require__(/*! ../Constants */ "./Js/Monitoring/Constants.js");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Message = function () {
    function Message() {
        _classCallCheck(this, Message);

        this.syncMessage();
    }

    _createClass(Message, [{
        key: 'syncMessage',
        value: function syncMessage() {
            $('#syncmessage').on('click', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: _Constants.ajaxPath + '/message/sync',

                    success: function success(response) {
                        if (response.startsWith('SUCCESS')) {
                            $('#syncmessagespan').text('');
                        }
                        alert(response);
                    }
                });
            });
        }
    }]);

    return Message;
}();

exports.default = Message;

/***/ }),

/***/ "./Js/Monitoring/Components/Risks.js":
/*!*******************************************!*\
  !*** ./Js/Monitoring/Components/Risks.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Constants = __webpack_require__(/*! ../Constants */ "./Js/Monitoring/Constants.js");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Risks = function () {
    function Risks() {
        _classCallCheck(this, Risks);

        var $this = this;

        $(document).on('change', '.row-kinney select', function () {
            var kinney = $(this).closest('.row-kinney');
            var aorb = $(this).attr('id').substr(-1);

            $this.setValue(kinney, aorb);
        });

        $(document).on('closing', _Constants.$modalContainer, function () {
            window.location.reload();
        });
    }

    _createClass(Risks, [{
        key: 'setValue',
        value: function setValue(kinney, aorb) {
            var allScores = $('#risk-all-scores').val();
            var allColors = $('#risk-all-colors').val();

            var input_probability = $(kinney).find('[id$=probability' + aorb + ']').val();
            var input_frequence = $(kinney).find('[id$=frequence' + aorb + ']').val();
            var input_effect = $(kinney).find('[id$=effect' + aorb + ']').val();

            var input_score = $(kinney).find('[id$=score' + aorb + ']');
            var input_kinneyriskscore = $(kinney).find('[id$=kinneyriskscore' + aorb + ']');

            var score = input_probability * input_frequence * input_effect / 1000;

            // change color of the two inputs
            $(input_score).css({ 'background-color': '' + this.getResult(allColors, score) + '', 'color': '#fff' });
            $(input_kinneyriskscore).css({ 'background-color': '' + this.getResult(allColors, score) + '', 'color': '#fff' });

            // set scores on the two inputs
            $(input_score).val(score);
            $(input_kinneyriskscore).val(this.getResult(allScores, score));
        }
    }, {
        key: 'getResult',
        value: function getResult(input, result) {
            var res = "";

            $.each($.parseJSON(input), function (index, value) {
                if (index < result) {
                    res = '' + value + '';
                }
            });

            return res;
        }
    }]);

    return Risks;
}();

exports.default = Risks;

/***/ }),

/***/ "./Js/Monitoring/Components/Stock.js":
/*!*******************************************!*\
  !*** ./Js/Monitoring/Components/Stock.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Constants = __webpack_require__(/*! ../Constants */ "./Js/Monitoring/Constants.js");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Stock = function () {
    function Stock() {
        _classCallCheck(this, Stock);

        if ($('.stock-form').length === 1) {
            this.getLegislations();

            var $this = this;

            $(document).on('change', '.stock-get-type', function () {
                $this.getLegislations();
            });
        }
    }

    _createClass(Stock, [{
        key: 'getLegislations',
        value: function getLegislations() {
            $.ajax({
                url: _Constants.ajaxPath + '/stock/legislationtext',
                method: 'POST',
                dataType: 'json',
                data: {
                    stocktype_id: $('#stock-id_stocktype').val()
                }
            }).done(function (data) {
                $('#data').html(data);
            }).fail(function () {
                if (_Constants.environment === 'dev') {
                    console.log('Error Processing Request!');
                }
            });
        }
    }]);

    return Stock;
}();

exports.default = Stock;

/***/ }),

/***/ "./Js/Monitoring/Components/Treeview.js":
/*!**********************************************!*\
  !*** ./Js/Monitoring/Components/Treeview.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var newid = 0;
var tree = [];

var Treeview = function () {
    function Treeview() {
        _classCallCheck(this, Treeview);

        if ($('.tree-box').length > 0) {
            this.jsonToTree();

            this.addNode();
            this.removeNode();
            this.modifyNode();

            $(document).on('closing', '#modal', function () {
                $(document).off('click', '.add-has-child');
                $(document).off('click', '.remove-child');
                $(document).off('click', '.edit-child');
            });

            $(document).on('mouseover', 'ul.trees ul li', function () {
                $(this).find('.btns').eq(0).css('visibility', 'visible');
            });

            $(document).on('mouseout', 'ul.trees ul li', function () {
                $(this).find('.btns').eq(0).css('visibility', 'hidden');
            });
        }
    }

    /**
     * @returns {string}
     */


    _createClass(Treeview, [{
        key: 'nodeHtml',
        value: function nodeHtml(label) {
            return '<li class="has-child">' + '<input type="checkbox" checked><span class="tree-control"></span>' + '<label>' + label + '</label>' + '<span class="btns">' + '<span class="glyphicon glyphicon-pencil edit-child btnm"></span>' + '<span class="glyphicon glyphicon-trash remove-child btnm"></span>' + '<span class="glyphicon glyphicon-plus add-has-child btnm"></span>' + '</span>' + '<ul></ul>' + '</li>';
        }
    }, {
        key: 'addNode',
        value: function addNode() {
            var $this = this;
            $(document).on('click', '.add-has-child', function (e) {
                e.preventDefault();

                var newNode = $(this).closest('li').find('ul').eq(0);

                $(newNode).append($this.nodeHtml($('.label-why').data('text')));

                $this.treeToJson();
            });
        }
    }, {
        key: 'removeNode',
        value: function removeNode() {
            var $this = this;

            $(document).on('click', '.remove-child', function (e) {
                e.preventDefault();

                var removeNode = $(this).closest('li').eq(0);

                krajeeDialog.confirm($('.label-confirm').data('text'), function (result) {
                    if (result) {
                        $(removeNode).remove();
                        $this.treeToJson();
                    }
                });
            });
        }
    }, {
        key: 'modifyNode',
        value: function modifyNode() {
            var $this = this;

            $(document).on('click', '.edit-child', function (e) {
                e.preventDefault();

                var node = $(this).closest('li').eq(0).find('label').eq(0);

                var value = $(node).text();

                // add a class to the label we clicked
                $(node).addClass('edit');

                if (value !== undefined) {
                    krajeeDialog.prompt({
                        label: $('.label-rename').data('text'),
                        placeholder: value,
                        value: value
                    }, function (result) {
                        if (result) {
                            $('.edit').text(result);
                            $this.treeToJson();
                        }
                        // remove every .edit class from the label
                        $('label').removeClass('edit');
                    });
                }
            });
        }
    }, {
        key: 'treeToJson',
        value: function treeToJson() {
            newid = 0;
            tree = [];

            var rootul = $('.trees');

            this.recurseToJson(rootul, newid);

            $('#accidents-fivewhy').val(JSON.stringify(tree));
        }
    }, {
        key: 'recurseToJson',
        value: function recurseToJson(startul, parentid) {
            var childlis = $(startul).children("li");
            var $this = this;

            $.each($(childlis), function (key, childli) {
                var id = newid++;
                var label = $(childli).find('label').eq(0).text();

                if (id !== 0) {
                    tree.push({
                        'id': id,
                        'label': label,
                        'parentid': parentid
                    });
                }
                var childul = $(childli).children("ul");
                $this.recurseToJson(childul, id);
            });
        }
    }, {
        key: 'jsonToTree',
        value: function jsonToTree() {
            var $accfw = $('#accidents-fivewhy');

            if ($accfw.length > 0) {
                var fivewhyValue = $accfw.val();

                if (fivewhyValue.length > 0) {
                    tree = $.parseJSON(fivewhyValue);
                    var rootul = $('.trees ul');
                    this.recurseToTree(rootul, 0);
                }
            }
        }
    }, {
        key: 'recurseToTree',
        value: function recurseToTree(startul, parentid) {
            var $this = this;
            $.each(tree, function (k, v) {
                if (v.parentid === parentid) {
                    var childli = $($this.nodeHtml(v.label));

                    $(startul).append(childli);

                    var childul = $(childli).children("ul");

                    $this.recurseToTree(childul, v.id);
                }
            });
        }
    }]);

    return Treeview;
}();

exports.default = Treeview;

/***/ }),

/***/ "./Js/Monitoring/Components/Workers.js":
/*!*********************************************!*\
  !*** ./Js/Monitoring/Components/Workers.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Workers = function Workers() {
    _classCallCheck(this, Workers);

    //EU + EEA countries + Switzerland
    var no_permit_list = ["LU", "AT", "BE", "BG", "CY", "CZ", "DE", "DK", "EE", "ES", "FI", "FR", "GB", "GR", "HR", "HU", "IE", "IT", "LT", "LV", "MT", "NL", "PL", "PT", "RO", "SE", "SI", "SK", "NO", "IS", "LI", "CH"];

    var $contactNat = $("#contacts-nationality");

    if ($.inArray($contactNat.val(), no_permit_list) === -1 && $contactNat.val() !== '') {
        $("#permitDetails").show();
    }

    $contactNat.on("change", function () {
        if ($.inArray($(this).val(), no_permit_list) === -1) {
            $("#permitDetails").show();
        } else {
            $("#contacts-non_eu").val("0");
            $("#permitDetails").hide();
        }
    });
};

exports.default = Workers;

/***/ }),

/***/ "./Js/Monitoring/Constants.js":
/*!************************************!*\
  !*** ./Js/Monitoring/Constants.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
/**
 * @type {jQuery|HTMLElement}
 */
var $modalContainer = exports.$modalContainer = $('#modal');
/**
 * @type {array[]}
 */
var languages = exports.languages = window.settings.languages;
/**
 * @type {string}
 */
var environment = exports.environment = window.settings.environment;
/**
 * @type {string}
 */
var ajaxPath = exports.ajaxPath = window.settings.ajax_path;
/**
 * @type {array[]}
 */
var translate = exports.translate = window.messages;

/***/ }),

/***/ "./Js/Monitoring/Index.js":
/*!********************************!*\
  !*** ./Js/Monitoring/Index.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Pages = __webpack_require__(/*! ./Pages */ "./Js/Monitoring/Pages.js");

var _Pages2 = _interopRequireDefault(_Pages);

var _Constants = __webpack_require__(/*! ./Constants */ "./Js/Monitoring/Constants.js");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var $url = null;
var $title = null;

var Index = function () {
  function Index() {
    _classCallCheck(this, Index);
  }

  _createClass(Index, [{
    key: "init",
    value: function init() {
      this.changeTheme();

      new _Pages2.default();

      this.initModal();
      this.formValidation();
      this.bootstrapTooltip();
      this.mainSidebarToggle();
      this.selectRefreshPjax();

      // when we have a pjax request init pages
      // for example Accidents -> create new
      $(document).on('pjax:end', function () {
        new _Pages2.default();
      });
    }
  }, {
    key: "modalUserHasTyped",
    value: function modalUserHasTyped() {
      var $input = $('.iziModal-content').find('input[type="text"], input[type="password"], textarea');
      var $this = this;

      $($input).on('change', function () {
        $('.iziModal-button-close').removeAttr('data-izimodal-close');
        $('button[type="button"].btn.btn-danger').removeAttr('data-dismiss');

        window.onbeforeunload = function () {
          // some browser don't have a message by default
          // so just in case we provide one
          return _Constants.translate.close_window_warning;
        };

        $('.iziModal-button-close, button[type="button"].btn.btn-danger').unbind().click(function () {
          $this.onCloseAlert();
        });
      });

      $(document).on('click', 'button[type="submit"].btn-success', function () {
        window.onbeforeunload = null;
      });
    }
  }, {
    key: "onCloseAlert",
    value: function onCloseAlert() {
      if (!confirm(_Constants.translate.close_window_warning)) {
        return false;
      } else {
        // set the onbeforeunload event to null
        window.onbeforeunload = null;
        _Constants.$modalContainer.iziModal('close');
      }
    }
  }, {
    key: "initModal",
    value: function initModal() {
      var $this = this;

      $(document).on('click', '.showModalButton', function (e) {
        e.preventDefault();

        // extract the title from the button
        if ($(this).is('[transtitle]')) {
          $title = $(this).attr('transtitle');
        } else {
          $title = $(this).attr('title');
        }

        if ($title.length === 0) {
          $title = $(this).attr('data-original-title');
        }

        // extract the url from the button with all the settings
        $url = $(this).attr('value');

        // initialize the iziModal Plugin
        _Constants.$modalContainer.iziModal({
          title: $title,
          headerColor: '#3c8dbc',
          padding: 23,
          width: 930,
          top: 30,
          bottom: 30,
          overlayClose: false,
          fullscreen: true,
          openFullscreen: false,
          icon: 'fa fa-ellipsis-v',
          transitionIn: false,
          transitionOut: false,
          transitionOutOverlay: false,
          transitionInOverlay: false,
          bodyOverflow: true,

          onOpening: function onOpening(modal) {
            modal.startLoading();
          },

          // get the content via ajax and display it inside the modal
          onOpened: function onOpened(modal) {
            // start the ajax request
            $.ajax({
              url: $url,
              method: 'POST',
              dataType: 'html',
              timeout: 30000,
              async: false
            }).done(function (data) {
              $('.iziModal-content').html(data).promise().done(function () {
                new _Pages2.default($url.split('/'));
                $this.modalUserHasTyped();
                modal.stopLoading();
              });
            }).fail(function (error) {
              // something went wrong? display the error message on the modal
              // Note: In production automatically users won't see the PHP errors
              $('.iziModal-content').html(error.responseText);
              modal.stopLoading();
            });
          },

          // destroy the modal on closed
          onClosed: function onClosed(modal) {
            modal.destroy();
          }
        });

        // everything setup correctly just open the modal
        _Constants.$modalContainer.iziModal('open');
      });

      $(document).on('click', '.form-group.pull-right [data-dismiss="modal"]', function () {
        _Constants.$modalContainer.iziModal('close');
      });

      $(document).on('closing', _Constants.$modalContainer, function () {
        if (typeof tinymce !== 'undefined') {
          tinymce.remove();
        }

        $('.iziModal-content').html('');

        // $('.iziModal-button-close').attr('data-izimodal-close', 'true');

        $(document).off('change', '.change-people-details');
        $(document).off('change', '.input-change-imonths');
        $(document).off('depdrop:afterChange', '.input-change-imonths');

        $('#kvFileinputModal').remove();

        var $form = $('#w1-form');
        if ($form.length > 1) {
          $form.remove();
        }
      });
    }
  }, {
    key: "formValidation",
    value: function formValidation() {
      $(document).on('afterValidate', '#modal .iziModal-content form', function () {
        var $modalBody = $('.iziModal-content');
        var $modalHeader = $('.iziModal-header');

        var $btnsuccess = $modalBody.find('.btn-success');
        var $btnwarning = $modalBody.find('.btn-danger');
        var $arrowCancel = $modalHeader.find('.iziModal-button-close');

        $modalBody.find('.nav-pills li a').attr('style', '');

        // disable/hide buttons
        $btnsuccess.prop('disabled', true);
        $btnwarning.prop('disabled', true);
        $arrowCancel.hide();

        $(".help-block").each(function () {
          if ($(this).text().length > 0) {
            // enable/show buttons
            $btnsuccess.prop('disabled', false);
            $btnwarning.prop('disabled', false);
            $arrowCancel.show();

            var tab = $(this).closest('.tab-pane').attr('id');
            $modalBody.find('.nav-pills li a[href^="#' + tab + '"]').css('color', 'red');
          }
        });
      });
    }
  }, {
    key: "bootstrapTooltip",
    value: function bootstrapTooltip() {
      $(document).tooltip({ selector: '[data-toggle="tooltip"]' });
    }
  }, {
    key: "mainSidebarToggle",
    value: function mainSidebarToggle() {
      try {
        this.isMainSidebarToggled();

        $(document).on('click', 'a.sidebar-toggle', function () {
          var sidebarIsClosed = $('body').hasClass('sidebar-collapse');

          if (sidebarIsClosed) {
            localStorage.setItem('keepSidebarClosed', true);
          } else {
            localStorage.setItem('keepSidebarClosed', false);
          }
        });
      } catch (e) {
        return false;
      }
    }
  }, {
    key: "isMainSidebarToggled",
    value: function isMainSidebarToggled() {
      var isSidebarOpenOrClosed = localStorage.getItem('keepSidebarClosed');

      if (isSidebarOpenOrClosed === 'true') {
        $('.sidebar-mini').addClass('sidebar-collapse');
      }
    }
  }, {
    key: "changeTheme",
    value: function changeTheme() {
      this.setThemeOnLocalStorage();
      $(document).on('click', '[data-change-theme-color]', function (e) {
        e.preventDefault();
        var color = $(this).data('change-theme-color');
        localStorage.setItem('themeColor', color.toString());
        $('body').attr('class', function (index, currentValue) {
          var skin = currentValue.split(' ');
          return currentValue.replace(skin[0], color);
        });
      });
    }
  }, {
    key: "setThemeOnLocalStorage",
    value: function setThemeOnLocalStorage() {
      var themeColor = localStorage.getItem('themeColor');

      if (themeColor !== null) {
        $('body').attr('class', function (index, currentValue) {
          var skin = currentValue.split(' ');
          return currentValue.replace(skin[0], themeColor);
        });
      }
    }
  }, {
    key: "selectRefreshPjax",
    value: function selectRefreshPjax() {
      $(document).on('click', '#pjax-refresh-selects .btn-success', function () {
        $(document).on('pjax:send', '#pjax-refresh-selects', function () {
          _Constants.$modalContainer.hide();
        });

        $(document).on('pjax:end', '#pjax-refresh-selects', function () {
          var $btn = $('.kv-panel-before').find('.disabled');

          if ($btn.length === 0) {
            $btn = $('.panel-before').find('.disabled');
          }

          window.location.href = $($btn).attr('href');
        });
      });
    }
  }]);

  return Index;
}();

exports.default = Index;

/***/ }),

/***/ "./Js/Monitoring/Pages.js":
/*!********************************!*\
  !*** ./Js/Monitoring/Pages.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); // import components


// import variables


var _Accidents = __webpack_require__(/*! ./Components/Accidents */ "./Js/Monitoring/Components/Accidents.js");

var _Accidents2 = _interopRequireDefault(_Accidents);

var _Actions = __webpack_require__(/*! ./Components/Actions */ "./Js/Monitoring/Components/Actions.js");

var _Actions2 = _interopRequireDefault(_Actions);

var _Dynamicform = __webpack_require__(/*! ./Components/Dynamicform */ "./Js/Monitoring/Components/Dynamicform.js");

var _Dynamicform2 = _interopRequireDefault(_Dynamicform);

var _Dashboard = __webpack_require__(/*! ./Components/Dashboard */ "./Js/Monitoring/Components/Dashboard.js");

var _Dashboard2 = _interopRequireDefault(_Dashboard);

var _Imonths = __webpack_require__(/*! ./Components/Imonths */ "./Js/Monitoring/Components/Imonths.js");

var _Imonths2 = _interopRequireDefault(_Imonths);

var _Empcontracts = __webpack_require__(/*! ./Components/Empcontracts */ "./Js/Monitoring/Components/Empcontracts.js");

var _Empcontracts2 = _interopRequireDefault(_Empcontracts);

var _Documents = __webpack_require__(/*! ./Components/Documents */ "./Js/Monitoring/Components/Documents.js");

var _Documents2 = _interopRequireDefault(_Documents);

var _Infrastructure = __webpack_require__(/*! ./Components/Infrastructure */ "./Js/Monitoring/Components/Infrastructure.js");

var _Infrastructure2 = _interopRequireDefault(_Infrastructure);

var _Message = __webpack_require__(/*! ./Components/Message */ "./Js/Monitoring/Components/Message.js");

var _Message2 = _interopRequireDefault(_Message);

var _Risks = __webpack_require__(/*! ./Components/Risks */ "./Js/Monitoring/Components/Risks.js");

var _Risks2 = _interopRequireDefault(_Risks);

var _Workers = __webpack_require__(/*! ./Components/Workers */ "./Js/Monitoring/Components/Workers.js");

var _Workers2 = _interopRequireDefault(_Workers);

var _BootstrapWizard = __webpack_require__(/*! ./Components/BootstrapWizard */ "./Js/Monitoring/Components/BootstrapWizard.js");

var _BootstrapWizard2 = _interopRequireDefault(_BootstrapWizard);

var _Chemicals = __webpack_require__(/*! ./Components/Chemicals */ "./Js/Monitoring/Components/Chemicals.js");

var _Chemicals2 = _interopRequireDefault(_Chemicals);

var _Stock = __webpack_require__(/*! ./Components/Stock */ "./Js/Monitoring/Components/Stock.js");

var _Stock2 = _interopRequireDefault(_Stock);

var _Constants = __webpack_require__(/*! ./Constants */ "./Js/Monitoring/Constants.js");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Class Pages
 * This class is an helper to execute functions only in certain pages
 */
var Pages = function () {
    /**
     * the default language of the application is 'fr' and it is hidden from the URL
     * so if we find another language on the URL like 'en' or 'de' we need to access the
     * second param from pathName or the URL.
     *
     * @param url string|null
     */
    function Pages(url) {
        _classCallCheck(this, Pages);

        var path = void 0;
        var pathName = void 0;

        // the pathname must be updated everytime we load via ajax
        pathName = window.location.pathname.split('/');

        if (typeof url !== 'undefined') {
            path = url[2];
        } else {
            path = pathName[2];
        }

        if ($.inArray(path, _Constants.languages) !== -1) {
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


    _createClass(Pages, [{
        key: "Pages",
        value: function Pages(path) {

            switch (path) {
                case 'dashboard':
                    new _Dashboard2.default();
                    break;

                case 'actions':
                    new _Actions2.default();
                    break;

                case 'accidents':
                    new _Accidents2.default();
                    break;

                case 'registerinspect':
                    if ($('.registerinspect-form').length === 1) {
                        new _Imonths2.default('/registerinspect/registeritemimonths', {
                            registerplus_id: "#registerplus_id",
                            inspecttype_id: "#inspecttype_id"
                        }, false);
                    }
                    break;

                case 'medvisits':
                    if ($('.medvisits-form').length === 1) {
                        new _Imonths2.default('/medvisits/empcontractworkunitsimonths', {
                            empcontract_id: '#empcontract'
                        }, true);

                        $("select#medvisits-examtype").change();
                    }
                    break;

                case 'empcontracts':
                    new _Empcontracts2.default();
                    break;

                case 'raptri':
                    if ($('.raptri-form').length === 1) {
                        new _Imonths2.default(null, null);
                    }
                    break;

                case 'invposris':
                    if ($('.invposris-form').length === 1) {
                        new _Imonths2.default(null, null);
                    }
                    break;

                case 'documents':
                    new _Documents2.default();
                    break;

                case 'infrastructure':
                    new _Infrastructure2.default();
                    break;

                case 'diagnostics':
                    new _BootstrapWizard2.default();
                    break;

                case 'message':
                    new _Message2.default();
                    break;

                case 'risks':
                    new _Risks2.default();
                    break;

                case 'stock':
                    new _Stock2.default();
                    break;

                case 'trainingshistory':
                    if ($('.trainingshistory-form').length === 1) {
                        new _Imonths2.default('/trainingshistory/imonths', {
                            id: '#trainingshistory-id_training'
                        }, false);
                    }
                    break;

                case 'workers':
                    new _Workers2.default();
                    break;

                case 'chemicals':
                    if ($('.chemicals-form').length === 1) {
                        new _Chemicals2.default();
                    }
                    break;

                default:
                    if (_Constants.environment === 'dev') {
                        console.log('No path defined!');
                    }
                    break;
            }

            // execute the dynamicform patches on the pages we use it
            if ($('.dynamicform_actions_status').length === 1) {
                new _Dynamicform2.default(['dynamicform_actions_status']);
            } else if ($('.dynamicform_wrapper_risks').length === 1) {
                new _Dynamicform2.default(['dynamicform_wrapper_risks', 'dynamicform_wrapper']);
            } else if ($('.dynamicform_wrapper').length === 1) {
                new _Dynamicform2.default(['dynamicform_wrapper']);
            }
        }
    }]);

    return Pages;
}();

exports.default = Pages;

/***/ }),

/***/ "./monitoring.js":
/*!***********************!*\
  !*** ./monitoring.js ***!
  \***********************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _Index = __webpack_require__(/*! ./Js/Monitoring/Index */ "./Js/Monitoring/Index.js");

var _Index2 = _interopRequireDefault(_Index);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

new _Index2.default().init();

/***/ })

/******/ });
//# sourceMappingURL=app.js.map