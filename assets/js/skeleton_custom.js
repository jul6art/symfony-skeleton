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
    this.detectIp();
    this.dialog();
    this.dropdown();
    this.editInPlace();
    this.esc();
    this.impersonate();
    this.progressiveWebApp();
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
    let collapseButtonToggle = function(button) {
      button.closest(".card").toggleClass("collapsed");

      setTimeout(function() {
        button
          .find(".label-toggle")
          .text(
            button.closest(".card").hasClass("collapsed")
              ? button.data("state-off-label")
              : button.data("state-on-label")
          );
      }, 300);
    };

    $('[data-toggle="grid-collapse"]').on("click", function() {
      collapseButtonToggle($(this));
    });

    $('[data-toggle="grid-expand"]').on("click", function() {
      var button = $(this);

      if (button.closest(".card").hasClass("collapsed")) {
        collapseButtonToggle(
          button.closest(".card").find('[data-toggle="grid-collapse"]')
        );
      }

      button.closest(".card").toggleClass("expanded");

      setTimeout(function() {
        button
          .find(".label-toggle")
          .text(
            button.closest(".card").hasClass("expanded")
              ? button.data("state-off-label")
              : button.data("state-on-label")
          );
      }, 300);
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
      messageText: Translator.trans("javascript.cookie.message"),
      iconColor: $.App.getThemeColor(THEME_NAME),
      buttonColor: $.App.getThemeColor(THEME_NAME),
      buttonText: Translator.trans("javascript.cookie.buttons.confirm"),
      cookiePolicyButtonText: Translator.trans(
        "javascript.cookie.buttons.cookie_policy"
      ),
      cookiePolicyButtonUrl: COOKIE_POLICY_URL
    });
  },
  detectIp: function() {
    $("body")
      .on("click", '[data-toggle="ip-detect"]', function(e) {
        e.preventDefault();
        let ip_list_input = $('[name="edit_maintenance[exceptionIpList]"]'),
          ip_list = ip_list_input
            .val()
            .split(", ")
            .filter(function(ip) {
              return ip.length > 0;
            }),
          ip = $(this).data("ip");

        if ($.inArray(ip, ip_list) === -1) {
          ip_list.push(ip);
          ip_list_input.val(ip_list.join(", ")).trigger("change");
        }
      })
      .on(
        "form.event.post_submit.success",
        'form[name="edit_maintenance"]',
        function() {
          $(this)
            .find('[name="edit_maintenance[exceptionIpList][]"]')
            .selectpicker();
        }
      );
  },
  dialog: function() {
    if (typeof FUNCTIONALITIES.confirm_delete !== "undefined") {
      require("sweetalert");
      $("body").on("click", '[data-confirm="confirm"]', function(e) {
        e.preventDefault();
        let link = $(this);

        let wrapper = document.createElement("div");
        wrapper.innerHTML = Translator.trans(
          link.data("dialog-confirm"),
          link.data("dialog-confirm-parameters")
        );

        swal({
          title: Translator.trans("javascript.dialog.ajax.confirm.title"),
          content: wrapper,
          icon: "warning",
          buttons: [
            Translator.trans("javascript.dialog.ajax.buttons.cancel"),
            Translator.trans("javascript.dialog.ajax.buttons.success")
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
                    wrapper.innerHTML = Translator.trans(
                      link.data("dialog-success"),
                      link.data("dialog-confirm-parameters")
                    );
                    swal({
                      title: Translator.trans(
                        "javascript.dialog.ajax.success.title"
                      ),
                      content: wrapper,
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
                    Translator.trans("javascript.dialog.ajax.error.title"),
                    Translator.trans("javascript.dialog.ajax.error.text"),
                    "error"
                  );
                } else {
                  swal.stopLoading();
                  swal.close();
                }
              });
            } else {
              wrapper.innerHTML = wrapper.innerHTML = Translator.trans(
                link.data("dialog-cancel"),
                link.data("dialog-confirm-parameters")
              );
              swal({
                title: Translator.trans("javascript.dialog.ajax.cancel.title"),
                content: wrapper,
                icon: "error"
              });
            }
          })
          .catch(err => {
            if (err) {
              console.log(err);
              swal(
                Translator.trans("javascript.dialog.ajax.error.title"),
                Translator.trans("javascript.dialog.ajax.error.text"),
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
    if (typeof FUNCTIONALITIES.edit_in_place !== "undefined") {
      let toggleParameters = function(string, parameters, returnValue = true) {
        $.each(parameters, function(key, value) {
          value = value.replace(/&nbsp;/g, " ");
          if (returnValue) {
            string = string.replace(new RegExp(value, "g"), key);
          } else {
            string = string.replace(new RegExp(key, "g"), value);
          }
        });

        return string;
      };

      tinymce.init({
        selector: '[data-provide="wysiwyg"][data-inline][data-edit]',
        inline: true,
        auto_focus: false,
        entity_encoding: "raw",
        skin: "oxide-dark",
        language: WYSIWYG_LOCALE,
        powerpaste_word_import: "clean",
        powerpaste_html_import: "clean",
        // plugins: [
        //   "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        //   "searchreplace wordcount visualblocks visualchars code fullscreen",
        //   "insertdatetime media nonbreaking save",
        //   "emoticons template paste textpattern imagetools"
        // ],
        plugins: [
          "advlist autolink lists link charmap print preview hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars code fullscreen",
          "insertdatetime nonbreaking save",
          "emoticons template paste textpattern"
        ]
      });

      tinymce.init({
        selector: '[data-provide="wysiwyg"][data-inline][data-translate]',
        inline: true,
        auto_focus: false,
        entity_encoding: "raw",
        skin: "oxide-dark",
        language: WYSIWYG_LOCALE,
        powerpaste_word_import: "clean",
        powerpaste_html_import: "clean",
        toolbar: "undo redo translateInPlaceSave translateInPlaceCancel",
        // plugins: [
        //   "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        //   "searchreplace wordcount visualblocks visualchars code fullscreen",
        //   "insertdatetime media nonbreaking save",
        //   "emoticons template paste textpattern imagetools"
        // ],
        plugins: [
          "advlist autolink lists link charmap print preview hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars code fullscreen",
          "insertdatetime nonbreaking save",
          "emoticons template paste textpattern"
        ],
        setup: editor => {
          let baseElement = $(editor.getElement());
          let parameters = baseElement.data("parameters");
          baseElement.on("click", function() {
            editor.show();
            baseElement.trigger("focus");
          });

          editor.on("focus", function() {
            editor.setContent(
              toggleParameters(editor.getContent(), parameters)
            );
          });

          $("body").on("keyboard.keyup.esc", function(e) {
            editor.fire("blur");
            editor.hide();
          });

          editor.on("blur", function() {
            editor.setContent(
              toggleParameters(editor.getContent(), parameters, false)
            );
          });

          editor.ui.registry.addButton("translateInPlaceSave", {
            text: "Enregistrer",
            onAction: () => {
              this.blockUI();

              $.ajax({
                url: Routing.generate("admin_translation_edit", {
                  domain: baseElement.data("domain"),
                  key: baseElement.data("key")
                }),
                method: "POST",
                data: {
                  value: editor.getContent()
                },
                success: function(result) {
                  editor.setContent(
                    toggleParameters(editor.getContent(), parameters, false)
                  );
                  editor.hide();
                  $.App.unblockUI();
                  $.App.notify(
                    "bg-" + THEME_NAME,
                    Translator.trans(
                      "javascript.notification.translation.edited"
                    ),
                    TOASTR_POSITION.vertical,
                    TOASTR_POSITION.horizontal
                  );
                },
                error: function(error) {
                  $.App.unblockUI();
                  $.App.notify(
                    "bg-red",
                    Translator.trans("javascript.dialog.ajax.error.text"),
                    TOASTR_POSITION.vertical,
                    TOASTR_POSITION.horizontal
                  );
                }
              });
            }
          });

          editor.ui.registry.addButton("translateInPlaceCancel", {
            text: "Fermer",
            onAction: () => {
              editor.setContent(
                toggleParameters(editor.getContent(), parameters, false)
              );
              editor.hide();
            }
          });
        },
        init_instance_callback: editor => {
          editor.fire("blur");
          editor.hide();
        }
      });
    }
  },
  esc: function() {
    $(document).keyup(function(e) {
      // escape key maps to keycode `27`
      if (e.keyCode == 27) {
        $("body").trigger("keyboard.keyup.esc");
      }
    });
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
  impersonate: function() {
    $(".info-impersonate").on("click", function() {
      window.location = $(this).data("url");
    });
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
  progressiveWebApp: function() {
    if (typeof FUNCTIONALITIES.progressive_web_app !== "undefined") {
      let manifestLink = $("#progressiveWebAppManifest");

      if (manifestLink.length) {
        let manifest = {
          short_name: manifestLink.data("name"),
          name: manifestLink.data("name"),
          icons: manifestLink.data("icons"),
          start_url: window.location.origin + "/",
          scope: window.location.origin + "/",
          background_color: getThemeColor(manifestLink.data("color")),
          display: "standalone",
          theme_color: getThemeColor(manifestLink.data("color"))
        };

        let stringManifest = JSON.stringify(manifest),
          blob = new Blob([stringManifest], { type: "application/json" }),
          url = URL.createObjectURL(blob);

        manifestLink.attr("href", url);
      }
    }
  },
  settings: function() {
    $("body")
      .on("change", '#settings input[type="checkbox"]', function(e) {
        let input = $(this);

        if (typeof FUNCTIONALITIES.confirm_delete !== "undefined") {
          $.ajax({
            url: Routing.generate("admin_functionality_switch", {
              functionality: input.data("id"),
              state: input.prop("checked") ? 1 : 0
            }),
            method: "GET",
            success: function(result) {
              if (result.success) {
                swal(Translator.trans("javascript.dialog.ajax.refresh.title"), {
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
                Translator.trans("javascript.dialog.ajax.error.title"),
                Translator.trans("javascript.dialog.ajax.error.text"),
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
          if (typeof FUNCTIONALITIES.confirm_delete !== "undefined") {
            $.ajax({
              url: Routing.generate("admin_setting_set", {
                setting: input.data("id"),
                value: input.val().toString()
              }),
              method: "GET",
              success: function(result) {
                if (result.success) {
                  swal(
                    Translator.trans("javascript.dialog.ajax.refresh.title"),
                    {
                      icon: "success"
                    }
                  ).then(() => {
                    window.location.reload();
                  });
                }
              }
            }).catch(err => {
              if (err) {
                console.log(err);
                swal(
                  Translator.trans("javascript.dialog.ajax.error.title"),
                  Translator.trans("javascript.dialog.ajax.error.text"),
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
    if (typeof elem === "undefined") {
      $.unblockUI();
    } else {
      $(elem).unblock();
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
