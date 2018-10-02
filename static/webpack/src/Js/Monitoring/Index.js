import Pages from "./Pages";
import {$modalContainer, translate} from "./Constants";

let $url = null;
let $title = null;

export default class Index {

  init() {
    this.changeTheme();

    new Pages();

    this.initModal();
    this.formValidation();
    this.bootstrapTooltip();
    this.mainSidebarToggle();
    this.selectRefreshPjax();

    // when we have a pjax request init pages
    // for example Accidents -> create new
    $(document).on('pjax:end', function () {
      new Pages();
    });
  }

  modalUserHasTyped() {
    let $input = $('.iziModal-content').find('input[type="text"], input[type="password"], textarea');
    let $this = this;

    $($input).on('change', function() {
      $('.iziModal-button-close').removeAttr('data-izimodal-close');
      $('button[type="button"].btn.btn-danger').removeAttr('data-dismiss');

      window.onbeforeunload = function () {
        // some browser don't have a message by default
        // so just in case we provide one
        return translate.close_window_warning;
      };

      $('.iziModal-button-close, button[type="button"].btn.btn-danger').unbind().click(function () {
        $this.onCloseAlert();
      });
    });

    $(document).on('click', 'button[type="submit"].btn-success', function() {
      window.onbeforeunload = null;
    });
  }

  onCloseAlert() {
    if (!confirm(translate.close_window_warning)) {
      return false;
    } else {
      // set the onbeforeunload event to null
      window.onbeforeunload = null;
      $modalContainer.iziModal('close');
    }
  }

  initModal() {
    let $this = this;

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
      $modalContainer.iziModal({
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

        onOpening: function (modal) {
          modal.startLoading();
        },

        // get the content via ajax and display it inside the modal
        onOpened: function (modal) {
          // start the ajax request
          $.ajax({
            url: $url,
            method: 'POST',
            dataType: 'html',
            timeout: 30000,
            async: false,
          })
          .done(function (data) {
            $('.iziModal-content').html(data).promise().done(function () {
              new Pages($url.split('/'));
              $this.modalUserHasTyped();
              modal.stopLoading();
            });
          })
          .fail(function (error) {
            // something went wrong? display the error message on the modal
            // Note: In production automatically users won't see the PHP errors
            $('.iziModal-content').html(error.responseText);
            modal.stopLoading();
          });
        },

        // destroy the modal on closed
        onClosed: function (modal) {
          modal.destroy();
        }
      });

      // everything setup correctly just open the modal
      $modalContainer.iziModal('open');
    });

    $(document).on('click', '.form-group.pull-right [data-dismiss="modal"]', function () {
      $modalContainer.iziModal('close');
    });

    $(document).on('closing', $modalContainer, function () {
      if (typeof tinymce !== 'undefined') {
        tinymce.remove();
      }

      $('.iziModal-content').html('');

      // $('.iziModal-button-close').attr('data-izimodal-close', 'true');

      $(document).off('change', '.change-people-details');
      $(document).off('change', '.input-change-imonths');
      $(document).off('depdrop:afterChange', '.input-change-imonths');

      $('#kvFileinputModal').remove();

      let $form = $('#w1-form');
      if ($form.length > 1) {
        $form.remove();
      }
    });
  }

  formValidation() {
    $(document).on('afterValidate', '#modal .iziModal-content form', function () {
      let $modalBody = $('.iziModal-content');
      let $modalHeader = $('.iziModal-header');

      let $btnsuccess = $modalBody.find('.btn-success');
      let $btnwarning = $modalBody.find('.btn-danger');
      let $arrowCancel = $modalHeader.find('.iziModal-button-close');

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

          let tab = $(this).closest('.tab-pane').attr('id');
          $modalBody.find('.nav-pills li a[href^="#' + tab + '"]').css('color', 'red');
        }
      });
    });
  }

  bootstrapTooltip() {
    $(document).tooltip({selector: '[data-toggle="tooltip"]'});
  }

  mainSidebarToggle() {
    try {
      this.isMainSidebarToggled();

      $(document).on('click', 'a.sidebar-toggle', function () {
        let sidebarIsClosed = $('body').hasClass('sidebar-collapse');

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

  isMainSidebarToggled() {
    let isSidebarOpenOrClosed = localStorage.getItem('keepSidebarClosed');

    if (isSidebarOpenOrClosed === 'true') {
      $('.sidebar-mini').addClass('sidebar-collapse');
    }
  }

  changeTheme() {
    this.setThemeOnLocalStorage();
    $(document).on('click', '[data-change-theme-color]', function (e) {
      e.preventDefault();
      let color = $(this).data('change-theme-color');
      localStorage.setItem('themeColor', color.toString());
      $('body').attr('class', function (index, currentValue) {
        let skin = currentValue.split(' ');
        return currentValue.replace(skin[0], color);
      });
    });
  }

  setThemeOnLocalStorage() {
    let themeColor = localStorage.getItem('themeColor');

    if (themeColor !== null) {
      $('body').attr('class', function (index, currentValue) {
        let skin = currentValue.split(' ');
        return currentValue.replace(skin[0], themeColor);
      });
    }
  }

  selectRefreshPjax() {
    $(document).on('click', '#pjax-refresh-selects .btn-success', function () {
      $(document).on('pjax:send', '#pjax-refresh-selects', function () {
        $modalContainer.hide();
      });

      $(document).on('pjax:end', '#pjax-refresh-selects', function () {
        let $btn = $('.kv-panel-before').find('.disabled');

        if ($btn.length === 0) {
          $btn = $('.panel-before').find('.disabled');
        }

        window.location.href = $($btn).attr('href');
      });
    });
  }

}