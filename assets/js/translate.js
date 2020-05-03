$.Translate = {
  context: {},
  columns: [],
  tableParams: null,
  defaultOptions: $.extend(
    { page: 1, count: 20, filter: {}, sort: { _id: "asc" } },
    new URLSearchParams(window.location.search)
  ),
  init: function() {
    this.context.locales = translationCfg.locales;
    this.context.editType = translationCfg.inputType;
    this.context.autoCacheClean = translationCfg.autoCacheClean;
    this.context.labels = translationCfg.label;
    this.context.hideColumnsSelector = false;
    this.context.areAllColumnsSelected = true;
    this.context.profilerTokens = translationCfg.profilerTokens;
    this.context.sharedMsg = null;

    this.context.table = $("#translationTable");
    this.context.noTranslationsMessage = $("#translationNoTranslationsMessage");

    this.draw();
    this.button();
    this.filter();
  },
  draw: function() {
    let data = { _search: true, page: 1, rows: 20 };

    window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(
      str,
      key,
      value
    ) {
      data[key] = value;
    });

    $.ajax({
      url: translationCfg.url.list,
      method: "GET",
      data: data,
      success: function(result) {
        $.Translate.reloadTableData(result.total, result.translations);
      }
    });
  },

  button: function() {
    this.context.table.on("click", 'tbody button[name="cancel"]', function() {
      $.Translate.setRowButtons($(this).closest("tr"), false);
    });

    this.context.table.on("click", 'tbody button[name="edit"]', function() {
      $.Translate.setRowButtons($(this).closest("tr"), true);
    });
  },

  filter: function() {
    this.context.table
      .find('[data-toggle="translation_table_filter"]')
      .on("keyup", function() {
        const params = new URLSearchParams(window.location.search);
        params.set($(this).attr("name"), $(this).val());
        window.history.replaceState(
          {},
          "",
          `${window.location.pathname}?${params}`
        );

        $.Translate.draw();
      });
  },

  reloadTableData: function(count, rows) {
    let tbody = this.context.table.find("tbody");
    tbody.html("");

    if (count <= 0) {
      this.context.noTranslationsMessage.removeClass("hidden");
    } else {
      this.context.noTranslationsMessage.addClass("hidden");

      $.each(rows, function(index, row) {
        let tr = '<tr id="tableRow' + index + '">';

        $.each(row, function(key, value) {
          tr += '<td name="' + key + '">' + value + "</td>";
        });

        tr += '<td class="buttons"></td>';

        tr += "</tr>";

        tbody.append(tr);

        $.Translate.setRowButtons(tbody.find("tr#tableRow" + index), false);
      });
    }
  },

  setRowButtons: function($row, edit) {
    let html;
    if (edit) {
      html =
        '<div class="actions btn-group">\n' +
        '                <button type="button" name="save" class="btn bg-' +
        THEME_NAME +
        'btn-sm">\n' +
        '                    <i class="material-icons">save</i>\n' +
        "                </button>\n" +
        '                <button type="button" name="cancel" class="btn bg-blue-grey btn-sm">\n' +
        '                    <i class="material-icons">cancel</i>\n' +
        "                </button>\n" +
        "            </div>\n";
    } else {
      html =
        '<div class="actions btn-group">\n' +
        '                <button type="button" name="edit" class="btn bg-' +
        THEME_NAME +
        'btn-sm">\n' +
        '                    <i class="material-icons">edit</i>\n' +
        "                </button>\n" +
        '                <button type="button" name="close" class="btn bg-red btn-sm">\n' +
        '                    <i class="material-icons">close</i>\n' +
        "                </button>\n" +
        "            </div>\n";
    }

    $row.find("td.buttons").html(html);
  }
};

$(document).ready(function() {
  $.Translate.init();
});
