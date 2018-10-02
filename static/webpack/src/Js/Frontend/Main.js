import 'aos/dist/aos.css';
import AOS from 'aos/dist/aos.js';

export default class Main {

  constructor() {
    // init AOS plugin
    AOS.init();

    $(function() {
      onResizeNavHeader();
      openMainNav();
      closeMainNav();
      tabModuleItems();

      smoothScroll();

      $(window).scroll(stickyHeader);
      stickyHeader();
      countUp();
    });
  }

}

const stickyHeader = () => {
  let $mainHeader = $('header#main-header');

  if ($(window).scrollTop() >= 100) {
    $mainHeader.addClass('fixed');
  } else {
    $mainHeader.removeClass('fixed');
  }
};

const countUp = () => {
  // animated number counter from zero to value
  $('span.number span.cup').each(function () {
    $(this).prop('Counter', 0).animate({
      Counter: $(this).text()
    }, {
      duration: 3000,
      easing: 'swing',
      step: function (now) {
        $(this).text(Math.ceil(now));
      }
    });
  });
};

const smoothScroll = () => {
  $("a.scroll").on('click', function (event) {
    event.preventDefault();

    let page = $(this).attr('href');

    $('html, body').animate({
      scrollTop: $(page).offset().top - 90
    }, 700);
  });
};

const onResizeNavHeader = () => {
  $(window).resize(function() {
    let $nav = $("nav.nav-header");

    if ($(window).outerWidth() > 850) {
      $nav.css({
        'display': 'block'
      });
    } else {
      $nav.css({
        'display': 'none'
      });
    }
  });
};

const openMainNav = () => {
  $('nav.nav-items li.burger-menu a').click(function(e) {
    e.preventDefault();
    $('nav.nav-header').slideToggle('fast');
  });
};

const closeMainNav = () => {
  $('[data-close-nav="header"]').click(function(e) {
    e.preventDefault();
    $('nav.nav-header').slideToggle('fast');
  });
};

const tabModuleItems = () => {
  $('nav.module-items ul li a').click(function(e) {
    e.preventDefault();

    $('nav.module-items ul li a').removeClass('active');
    $(this).addClass('active');

    let $openDiv = $(this).data('open');
    $('section#module .box').removeClass('active');
    $('#'+$openDiv).addClass('active');
  });
};



//Contact form

$( '.js-input' ).keyup(function() {
    if( $(this).val() ) {
        $(this).addClass('not-empty');
    } else {
        $(this).removeClass('not-empty');
    }
});