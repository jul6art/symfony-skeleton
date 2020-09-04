$.Translate = {
  context: {},

  init: function() {
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
      let row = $(this).closest("tr");
      $.Translate.setRowButtons(row, false);
      $.Translate.setRowCells(row, false);
    });

    this.context.table.on("click", 'tbody button[name="edit"]', function() {
      let row = $(this).closest("tr");
      $.Translate.setRowButtons(row, true);
      $.Translate.setRowCells(row, true);
    });

    this.context.table.on("click", 'tbody button[name="save"]', function() {
      let row = $(this).closest("tr");
      $.Translate.setRowButtons(row, false);
      $.Translate.setRowCells(row, false);

      $.ajax({
        url: ""
      });
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
        $.Translate.setRowCells(tbody.find("tr#tableRow" + index), false);
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
        '                    <i class="' +
        translationCfg.button.save.icon +
        '"></i>\n' +
        "                </button>\n" +
        '                <button type="button" name="cancel" class="btn bg-blue-grey btn-sm">\n' +
        '                    <i class="' +
        translationCfg.button.cancel.icon +
        '"></i>\n' +
        "                </button>\n" +
        "            </div>\n";
    } else {
      html =
        '<div class="actions btn-group">\n' +
        '                <button type="button" name="edit" class="btn bg-' +
        THEME_NAME +
        'btn-sm">\n' +
        '                    <i class="' +
        translationCfg.button.edit.icon +
        '"></i>\n' +
        "                </button>\n" +
        // '                <button type="button" name="close" class="btn bg-red btn-sm">\n' +
        // '                    <i class="material-icons">close</i>\n' +
        // "                </button>\n" +
        "            </div>\n";
    }

    $row.find("td.buttons").html(html);
  },

  setRowCells: function($row, edit) {
    var cells = $row.find(
      'td:not([name="_id"]):not([name="_domain"]):not([name="_key"]):not(.buttons)'
    );

    $.each(cells, function(index, cell) {
      cell = $(cell);

      if (edit) {
        cell.html(
          "<" +
            translationCfg.inputType +
            ' style="width: 100%">' +
            decodeURI(cell.html()) +
            "</" +
            translationCfg.inputType +
            ">"
        );
      } else {
        cell.html(encodeURI(cell.find(translationCfg.inputType).val()));
      }
    });
  }
};

$(document).ready(function() {
  $.Translate.init();
});
