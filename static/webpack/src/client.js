import 'reset.css';
import 'normalize.css';

import 'password-strength-meter';
import 'password-strength-meter/dist/password.min.css';

import './Css/Monitoring/Clientarea/style.scss';

$('.field-needs-pw-validation').password({
  showPercent: false,
  showText: false,
  animate: true,
  animateSpeed: 'fast',
  minimumLength: 8
});

$("#filter").keyup(function () {
  // Retrieve the input field text and reset the count to zero
  let filter = $.trim($(this).val()),
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

  $(".filter-count").text("Found " + count + " result(s) " + ((filter.length > 0) ? "for '" + filter + "'" : ''));
});

$(document).on('click', '.toggle-mandants', function (e) {
  e.preventDefault();
  $('.mandants').slideToggle();
});