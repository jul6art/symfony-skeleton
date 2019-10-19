if (typeof jQuery === "undefined") {
  throw new Error("jQuery plugins need to be before this file");
}

import tinymce from "tinymce/tinymce";
import "tinymce/themes/silver";
import "tinymce/plugins/paste";
import "tinymce/plugins/link";

$.App = {
  init: function() {
    this.card();
    this.colorize();
    this.console();
    this.cookie();
    this.dialog();
    this.dropdown();
    this.editInPlace();
    this.settings();
    this.tooltip();
  },
  blockUI: function(elem) {
    if (typeof elem === "undefined") {
      $.blockUI({
        message: $("#loader")
      });
    } else {
      $(elem).block({
        message: $("#loader")
      });
    }
  },
  card: function() {
    $('[data-toggle="grid-collapse"]').on("click", function() {
      $(this)
        .closest(".card")
        .toggleClass("collapsed");
    });

    $('[data-toggle="grid-expand"]').on("click", function() {
      $(this)
        .closest(".card")
        .removeClass("collapsed");
      $(this)
        .closest(".card")
        .toggleClass("expanded");
    });

    $('[data-toggle="grid-close"]').on("click", function() {
      $(this)
        .closest(".card")
        .remove();
    });
  },
  colorize: function() {
    $(".pagination li.active a").addClass("bg-" + THEME_NAME);
  },
  console: function() {
    let vsweb_console_message = function() {
      if (window.console) {
        let message =
          "" +
          " __    __    ___            __    __    __   ____\n" +
          " \\ \\  / /   / __|           \\ \\  /  \\  / /  |  __\\   __\n" +
          "  \\ \\/ / _  \\__ \\  _         \\ \\/ /\\ \\/ /   |  __|  |  _\\\n" +
          "   \\__/ (_) |___/ (_)         \\__/  \\__/    |  __/  |___/\n" +
          "                https://vsweb.be\n" +
          "\n" +
          "\n" +
          "VsWeb, all about web" +
          "\n" +
          "\n" +
          "This is the story of the guy who loved code so much that he wanted to know all the languages ​​and all the frameworks.\n" +
          "This love pushed him to constantly exceed his limits to build his experience\n" +
          "\n" +
          "Follow him on https://vsweb.be\n" +
          "NB : To stop seeing this message, vsweb_console_stop();";

        console.log(message);
      }
    };

    let localStorageSupported = function() {
      let test = "test";
      try {
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return true;
      } catch (e) {
        return false;
      }
    };

    window.vsweb_console_start = function() {
      if (localStorageSupported()) {
        localStorage["vsweb-console-message"] = 1;
      }
      return true;
    };

    window.vsweb_console_stop = function() {
      if (localStorageSupported()) {
        localStorage["vsweb-console-message"] = 0;
      }
      return true;
    };

    if (
      localStorageSupported() &&
      localStorage["vsweb-console-message"] !== "0"
    ) {
      vsweb_console_message();
    }
  },
  cookie: function() {
    $.cookieBubble({
      messageText: COOKIE_TRANSLATIONS.message,
      iconColor: $.App.getThemeColor(THEME_NAME),
      buttonColor: $.App.getThemeColor(THEME_NAME),
      buttonText: COOKIE_TRANSLATIONS.confirm_button,
      cookiePolicyButtonText: COOKIE_TRANSLATIONS.cookie_policy_button,
      cookiePolicyButtonUrl: COOKIE_TRANSLATIONS.cookie_policy_url
    });
  },
  dialog: function() {
    if (typeof ACTIVATED_FUNCTIONS.confirm_delete !== "undefined") {
      require("sweetalert");
      $("body").on("click", '[data-confirm="confirm"]', function(e) {
        e.preventDefault();
        let link = $(this);

        swal({
          title: DIALOG_TRANSLATIONS.confirm_title,
          text: link.data("dialog-confirm"),
          icon: "warning",
          buttons: [
            DIALOG_TRANSLATIONS.cancel_button,
            DIALOG_TRANSLATIONS.confirm_button
          ],
          dangerMode: true
        })
          .then(willDelete => {
            if (willDelete) {
              $.ajax({
                url: link.attr("href"),
                method: "get",
                success: function(response) {
                  if (response.success) {
                    swal(link.data("dialog-success"), {
                      icon: "success"
                    }).then(() => {
                      if (link.data("redirect")) {
                        window.location = link.data("redirect");
                      } else {
                        $("body").trigger("datatable.refresh.force");
                      }
                    });
                  }
                }
              }).catch(err => {
                if (err) {
                  console.log(err);
                  swal(
                    DIALOG_TRANSLATIONS.ajax_error_title,
                    DIALOG_TRANSLATIONS.ajax_error_text,
                    "error"
                  );
                } else {
                  swal.stopLoading();
                  swal.close();
                }
              });
            } else {
              swal(
                DIALOG_TRANSLATIONS.cancel_title,
                link.data("dialog-cancel"),
                "error"
              );
            }
          })
          .catch(err => {
            if (err) {
              console.log(err);
              swal(
                DIALOG_TRANSLATIONS.ajax_error_title,
                DIALOG_TRANSLATIONS.ajax_error_text,
                "error"
              );
            } else {
              swal.stopLoading();
              swal.close();
            }
          });
      });
    }
  },
  dropdown: function() {
    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        $('[data-toggle="dropdown"]')
          .parent()
          .removeClass("open");
      }
    });
  },
  editInPlace: function() {
    if (typeof ACTIVATED_FUNCTIONS.edit_in_place !== "undefined") {
      tinymce.init({
        selector: '[data-provide="wysiwyg"][data-inline][data-edit]',
        language: WYSIWYG_LOCALE,
        plugins: [
          "advlist autolink lists link image charmap print preview hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars code fullscreen",
          "insertdatetime media nonbreaking save",
          "emoticons template paste textpattern imagetools"
        ],
        inline: true,
        powerpaste_word_import: "clean",
        powerpaste_html_import: "clean"
      });

      tinymce.init({
        selector: '[data-provide="wysiwyg"][data-inline][data-translate]',
        language: WYSIWYG_LOCALE,
        plugins: [
          "advlist autolink lists link image charmap print preview hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars code fullscreen",
          "insertdatetime media nonbreaking save",
          "emoticons template paste textpattern imagetools"
        ],
        inline: true,
        toolbar: "undo redo"
      });
    }
  },
  getThemeColor: function(color) {
    switch (color) {
      case "white":
      default:
        return "#FFFFFF";
      case "black":
        return "#000000";
      case "red":
        return "#F44336";
      case "pink":
        return "#E91E63";
      case "purple":
        return "#9C27B0";
      case "deep-purple":
        return "#673AB7";
      case "indigo":
        return "#3F51B5";
      case "blue":
        return "#2196F3";
      case "light-blue":
        return "#03A9F4";
      case "cyan":
        return "#00BCD4";
      case "teal":
        return "#009688";
      case "green":
        return "#4CAF50";
      case "light-green":
        return "#8BC34A";
      case "lime":
        return "#CDDC39";
      case "yellow":
        return "#ffe821";
      case "amber":
        return "#FFC107";
      case "orange":
        return "#FF9800";
      case "deep-orange":
        return "#FF5722";
      case "brown":
        return "#795548";
      case "grey":
        return "#9E9E9E";
      case "blue-grey":
        return "#607D8B";
    }
  },
  notify: function(
    colorName,
    text,
    placementFrom,
    placementAlign,
    animateEnter,
    animateExit
  ) {
    if (colorName === null || colorName === "") {
      colorName = "bg-black";
    }
    if (text === null || text === "") {
      text = "Turning standard Bootstrap alerts";
    }
    if (animateEnter === null || animateEnter === "") {
      animateEnter = "animated fadeInDown";
    }
    if (animateExit === null || animateExit === "") {
      animateExit = "animated fadeOutUp";
    }
    let allowDismiss = true;

    $.notify(
      {
        message: text
      },
      {
        type: colorName,
        allow_dismiss: allowDismiss,
        newest_on_top: true,
        timer: 1000,
        progressBar: true,
        placement: {
          from: placementFrom,
          align: placementAlign
        },
        animate: {
          enter: animateEnter,
          exit: animateExit
        },
        template:
          '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' +
          (allowDismiss ? "p-r-35" : "") +
          '" role="alert">' +
          '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
          '<span data-notify="icon"></span> ' +
          '<span data-notify="title">{1}</span> ' +
          '<span data-notify="message">{2}</span>' +
          '<div class="progress" data-notify="progressbar">' +
          '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
          "</div>" +
          '<a href="{3}" target="{4}" data-notify="url"></a>' +
          "</div>"
      }
    );
  },
  settings: function() {
    $("body")
      .on("change", '#settings input[type="checkbox"]', function(e) {
        let input = $(this);

        if (typeof ACTIVATED_FUNCTIONS.confirm_delete !== "undefined") {
          $.ajax({
            url: Routing.generate("admin_functionality_switch", {
              functionality: input.data("id"),
              state: input.prop("checked") ? 1 : 0
            }),
            method: "GET",
            success: function(result) {
              if (result.success) {
                swal(DIALOG_TRANSLATIONS.refresh_title, {
                  icon: "success"
                }).then(() => {
                  window.location.reload();
                });
              }
            }
          }).catch(err => {
            if (err) {
              console.log(err);
              swal(
                DIALOG_TRANSLATIONS.ajax_error_title,
                DIALOG_TRANSLATIONS.ajax_error_text,
                "error"
              );
            } else {
              swal.stopLoading();
              swal.close();
            }
          });
        } else {
          window.location = Routing.generate("admin_functionality_switch", {
            functionality: input.data("id"),
            state: input.prop("checked") ? 1 : 0
          });
        }
      })
      .on("change", '#settings input[type="text"], #settings select', function(
        e
      ) {
        let input = $(this);

        if (input.val() != -1) {
          if (typeof ACTIVATED_FUNCTIONS.confirm_delete !== "undefined") {
            $.ajax({
              url: Routing.generate("admin_setting_set", {
                setting: input.data("id"),
                value: input.val().toString()
              }),
              method: "GET",
              success: function(result) {
                if (result.success) {
                  swal(DIALOG_TRANSLATIONS.refresh_title, {
                    icon: "success"
                  }).then(() => {
                    window.location.reload();
                  });
                }
              }
            }).catch(err => {
              if (err) {
                console.log(err);
                swal(
                  DIALOG_TRANSLATIONS.ajax_error_title,
                  DIALOG_TRANSLATIONS.ajax_error_text,
                  "error"
                );
              } else {
                swal.stopLoading();
                swal.close();
              }
            });
          } else {
            window.location = Routing.generate("admin_setting_set", {
              setting: input.data("id"),
              value: input.val()
            });
          }
        }
      });
  },
  tooltip: function() {
    $('[data-toggle="tooltip"]').tooltip();
  },
  unblockUI: function(elem) {
    if (elem) {
      $.unblockUI(elem);
    } else {
      $.unblockUI();
    }
  }
};

$(document).ready(function() {
  $.App.init();

  ["success", "notice", "error", "warning", "info"].forEach(function(level) {
    if (FLASH_MESSAGES[level]) {
      let colorClass;
      if (level === "notice") {
        colorClass = "bg-blue-grey";
      } else if (level === "warning") {
        colorClass = "bg-orange";
      } else if (level === "error") {
        colorClass = "bg-red";
      } else if (level === "info") {
        colorClass = "bg-light-blue";
      } else if (level === "success") {
        colorClass = "bg-" + THEME_NAME;
      }

      FLASH_MESSAGES[level].forEach(function(message) {
        $.App.notify(
          colorClass,
          message,
          TOASTR_POSITION.vertical,
          TOASTR_POSITION.horizontal
        );
      });
    }
  });

  $(".flash-message").on("click", function() {
    let placementFrom = $(this).data("placement-from")
      ? $(this).data("placement-from")
      : TOASTR_POSITION.vertical;
    let placementAlign = $(this).data("placement-align")
      ? $(this).data("placement-align")
      : TOASTR_POSITION.horizontal;
    let animateEnter = $(this).data("animate-enter");
    let animateExit = $(this).data("animate-exit");
    let colorName = $(this).data("color-name");
    let text = $(this).data("original-text");

    $.App.notify(
      colorName,
      text,
      placementFrom,
      placementAlign,
      animateEnter,
      animateExit
    );
  });
});

export const getThemeColor = $.App.getThemeColor;
