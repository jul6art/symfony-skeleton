if (typeof jQuery === "undefined") {
  throw new Error("jQuery plugins need to be before this file");
}

// css files
import "../css/table.scss";

// modules
import "datatables.net";
import "datatables.net-dt";
import "datatables.net-bs";

$(document).ready(function() {
  $("table.dataTable").each(function() {
    let table = $(this);
    let datatableOptions = {
      dom: "BflrRtip",
      lengthMenu: [10, 25, 50, 100, 500, 1000],
      language: {
        lengthMenu: Translator.trans("javascript.table.default.lengthMenu"),
        zeroRecords: Translator.trans("javascript.table.default.zeroRecords"),
        info: Translator.trans("javascript.table.default.info"),
        infoEmpty: Translator.trans("javascript.table.default.infoEmpty"),
        infoFiltered: Translator.trans("javascript.table.default.infoFiltered"),
        search: Translator.trans("javascript.table.default.search"),
        paginate: {
          previous: Translator.trans("javascript.table.default.previous"),
          next: Translator.trans("javascript.table.default.next")
        }
      }
    };

    if (table.data("export")) {
      require("datatables.net-buttons/js/dataTables.buttons.min");
      require("datatables.net-buttons/js/buttons.flash.min");
      require("jszip");
      require("pdfmake");
      require("./vfs_fonts");
      require("datatables.net-buttons/js/buttons.html5.min");
      require("datatables.net-buttons/js/buttons.print.min");
      datatableOptions.buttons = ["copy", "csv", "excel", "pdf", "print"];
    }

    if (table.data("responsive")) {
      require("datatables.net-responsive");
      require("datatables.net-responsive-dt/css/responsive.dataTables.min.css");
      require("datatables.net-responsive-dt");
      datatableOptions.responsive = true;
    }

    if (table.data("colReorder")) {
      require("datatables.net-colreorder/js/dataTables.colReorder.min");
      datatableOptions.colReorder = true;
    }

    let datatable = table.DataTable(datatableOptions);

    datatable.on("init.dt.dth", function() {
      if (window.console) {
        console.log("init");
      }
    });

    datatable.on("draw.dt", function() {
      if (window.console) {
        console.log("draw");
      }
    });

    $("body").on("datatable.refresh.force", function() {
      if (window.console) {
        datatable.draw();
      }
    });

    datatable.on("processing.dt", function(e, settings, processing) {
      if (window.console) {
        console.log("processing");
      }
      if (processing) {
        $.App.blockUI(table);
      } else {
        $.App.unblockUI(table);
      }
    });
  });
});
