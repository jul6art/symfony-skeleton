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

    this.build();
  },
  build: function() {
    let data = {};

    let urlParameters = new URLSearchParams(window.location.search);
    let domain = urlParameters.get("filter[_domain]");

    if (domain !== null && domain.length) {
      data["_domain"] = domain;
      data["_search"] = "true";
    }

    $.ajax({
      url: translationCfg.url.list,
      method: "GET",
      data: data,
      success: function(result) {
        console.log(result);
        $.Translate.reloadTableData(result.total, result.translations);
      }
    });
  },

  reloadTableData: function(count, rows) {
    let tbody = this.context.table.find("tbody");

    if (count <= 0) {
      this.context.noTranslationsMessage.removeClass("hidden");
      tbody.html("");
    } else {
      this.context.noTranslationsMessage.addClass("hidden");

      $.each(rows, function(index, row) {
        let tr = "<tr>";

        $.each(row, function(key, value) {
          tr += "<td>" + value + "</td>";
        });

        tr += "</tr>";

        tbody.append(tr);
      });
    }
  },

  getColumnsDefinition: function() {
    return this.columns;
  },

  getTableParams: function() {
    return this.tableParams;
  }
};

$(document).ready(function() {
  $.Translate.init();
});
