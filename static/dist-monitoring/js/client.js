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
/******/ 	return __webpack_require__(__webpack_require__.s = "./client.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../../../node_modules/normalize.css/normalize.css":
/*!*******************************************************************!*\
  !*** /var/www/esst.test/node_modules/normalize.css/normalize.css ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "../../../node_modules/password-strength-meter/dist/password.min.css":
/*!*************************************************************************************!*\
  !*** /var/www/esst.test/node_modules/password-strength-meter/dist/password.min.css ***!
  \*************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "../../../node_modules/password-strength-meter/dist/password.min.js":
/*!************************************************************************************!*\
  !*** /var/www/esst.test/node_modules/password-strength-meter/dist/password.min.js ***!
  \************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * @author Òscar Casajuana a.k.a. elboletaire <elboletaire at underave dot net>
 * @link https://github.com/elboletaire/password-strength-meter
 */
!function(a){"use strict";var e=function(e,s){function t(a){return-1===a?s.shortPass:-2===a?s.containsUsername:(a=a<0?0:a,a<34?s.badPass:a<68?s.goodPass:s.strongPass)}function n(a,e){var t=0;if(a.length<s.minimumLength)return-1;if(s.username){if(a.toLowerCase()===e.toLowerCase())return-2;if(s.usernamePartialMatch&&e.length){var n=new RegExp(e.toLowerCase());if(a.toLowerCase().match(n))return-2}}t+=4*a.length,t+=r(1,a).length-a.length,t+=r(2,a).length-a.length,t+=r(3,a).length-a.length,t+=r(4,a).length-a.length,a.match(/(.*[0-9].*[0-9].*[0-9])/)&&(t+=5);var o=".*[!,@,#,$,%,^,&,*,?,_,~]";return o=new RegExp("("+o+o+")"),a.match(o)&&(t+=5),a.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)&&(t+=10),a.match(/([a-zA-Z])/)&&a.match(/([0-9])/)&&(t+=15),a.match(/([!,@,#,$,%,^,&,*,?,_,~])/)&&a.match(/([0-9])/)&&(t+=15),a.match(/([!,@,#,$,%,^,&,*,?,_,~])/)&&a.match(/([a-zA-Z])/)&&(t+=15),(a.match(/^\w+$/)||a.match(/^\d+$/))&&(t-=10),t>100&&(t=100),t<0&&(t=0),t}function r(a,e){for(var s="",t=!1,n=0;n<e.length;n++){t=!0;for(var r=0;r<a&&r+n+a<e.length;r++)t=t&&e.charAt(r+n)===e.charAt(r+n+a);r<a&&(t=!1),t?(n+=a-1,t=!1):s+=e.charAt(n)}return s}function o(){var r=!0,o=s.showText,h=s.showPercent,i=a("<div>").addClass("pass-graybar"),c=a("<div>").addClass("pass-colorbar"),l=a("<div>").addClass("pass-wrapper").append(i.append(c));return e.parent().addClass("pass-strength-visible"),s.animate&&(l.css("display","none"),r=!1,e.parent().removeClass("pass-strength-visible")),s.showPercent&&(h=a("<span>").addClass("pass-percent").text("0%"),l.append(h)),s.showText&&(o=a("<span>").addClass("pass-text").html(s.enterPass),l.append(o)),e.after(l),e.keyup(function(){var r=s.username||"";r&&(r=a(r).val());var i=n(e.val(),r);e.trigger("password.score",[i]);var l=i<0?0:i;if(c.css({backgroundPosition:"0px -"+l+"px",width:l+"%"}),s.showPercent&&h.html(l+"%"),s.showText){var p=t(i);!e.val().length&&i<=0&&(p=s.enterPass),o.html()!==a("<div>").html(p).html()&&(o.html(p),e.trigger("password.text",[p,i]))}}),s.animate&&(e.focus(function(){r||l.slideDown(s.animateSpeed,function(){r=!0,e.parent().addClass("pass-strength-visible")})}),e.blur(function(){!e.val().length&&r&&l.slideUp(s.animateSpeed,function(){r=!1,e.parent().removeClass("pass-strength-visible")})})),this}var h={shortPass:"The password is too short",badPass:"Weak; try combining letters & numbers",goodPass:"Medium; try using special charecters",strongPass:"Strong password",containsUsername:"The password contains the username",enterPass:"Type your password",showPercent:!1,showText:!0,animate:!0,animateSpeed:"fast",username:!1,usernamePartialMatch:!0,minimumLength:4};return s=a.extend({},h,s),o.call(this)};a.fn.password=function(s){return this.each(function(){new e(a(this),s)})}}(jQuery);

/***/ }),

/***/ "../../../node_modules/reset.css/reset.css":
/*!***********************************************************!*\
  !*** /var/www/esst.test/node_modules/reset.css/reset.css ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./Css/Monitoring/Clientarea/style.scss":
/*!**********************************************!*\
  !*** ./Css/Monitoring/Clientarea/style.scss ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./client.js":
/*!*******************!*\
  !*** ./client.js ***!
  \*******************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(/*! reset.css */ "../../../node_modules/reset.css/reset.css");

__webpack_require__(/*! normalize.css */ "../../../node_modules/normalize.css/normalize.css");

__webpack_require__(/*! password-strength-meter */ "../../../node_modules/password-strength-meter/dist/password.min.js");

__webpack_require__(/*! password-strength-meter/dist/password.min.css */ "../../../node_modules/password-strength-meter/dist/password.min.css");

__webpack_require__(/*! ./Css/Monitoring/Clientarea/style.scss */ "./Css/Monitoring/Clientarea/style.scss");

$('.field-needs-pw-validation').password({
  showPercent: false,
  showText: false,
  animate: true,
  animateSpeed: 'fast',
  minimumLength: 8
});

$("#filter").keyup(function () {
  // Retrieve the input field text and reset the count to zero
  var filter = $.trim($(this).val()),
      count = 0;

  // Loop through the comment list
  $("nav ul li").each(function () {
    // If the list item does not contain the text phrase fade it out
    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
      $(this).fadeOut();

      // Show the list item if the phrase matches and increase the count by 1
    } else {
      $(this).show();
      count++;
    }
  });

  $(".filter-count").text("Found " + count + " result(s) " + (filter.length > 0 ? "for '" + filter + "'" : ''));
});

$(document).on('click', '.toggle-mandants', function (e) {
  e.preventDefault();
  $('.mandants').slideToggle();
});

/***/ })

/******/ });
//# sourceMappingURL=client.js.map