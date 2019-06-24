if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

// css files
import '../css/table.scss';

// modules
require('datatables.net');
require('datatables.net-dt');

// manually imported datatable-bootstrap  script
(function(window, document, undefined){

    let factory = function( $, DataTable ) {
        "use strict";


        /* Set the defaults for DataTables initialisation */
        $.extend( true, DataTable.defaults, {
            dom:
            "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            renderer: 'bootstrap'
        } );


        /* Default class modification */
        $.extend( DataTable.ext.classes, {
            sWrapper:      "dataTables_wrapper form-inline dt-bootstrap",
            sFilterInput:  "form-control input-sm",
            sLengthSelect: "form-control input-sm"
        } );


        /* Bootstrap paging button renderer */
        DataTable.ext.renderer.pageButton.bootstrap = function ( settings, host, idx, buttons, page, pages ) {
            let api     = new DataTable.Api( settings );
            let classes = settings.oClasses;
            let lang    = settings.oLanguage.oPaginate;
            let btnDisplay, btnClass, counter=0;

            let attach = function( container, buttons ) {
                let i, ien, node, button;
                let clickHandler = function ( e ) {
                    e.preventDefault();
                    if ( !$(e.currentTarget).hasClass('disabled') ) {
                        api.page( e.data.action ).draw( 'page' );
                    }
                };

                for ( i=0, ien=buttons.length ; i<ien ; i++ ) {
                    button = buttons[i];

                    if ( $.isArray( button ) ) {
                        attach( container, button );
                    }
                    else {
                        btnDisplay = '';
                        btnClass = '';

                        switch ( button ) {
                            case 'ellipsis':
                                btnDisplay = '&hellip;';
                                btnClass = 'disabled';
                                break;

                            case 'first':
                                btnDisplay = lang.sFirst;
                                btnClass = button + (page > 0 ?
                                    '' : ' disabled');
                                break;

                            case 'previous':
                                btnDisplay = lang.sPrevious;
                                btnClass = button + (page > 0 ?
                                    '' : ' disabled');
                                break;

                            case 'next':
                                btnDisplay = lang.sNext;
                                btnClass = button + (page < pages-1 ?
                                    '' : ' disabled');
                                break;

                            case 'last':
                                btnDisplay = lang.sLast;
                                btnClass = button + (page < pages-1 ?
                                    '' : ' disabled');
                                break;

                            default:
                                btnDisplay = button + 1;
                                btnClass = page === button ?
                                    'active' : '';
                                break;
                        }

                        if ( btnDisplay ) {
                            node = $('<li>', {
                                'class': classes.sPageButton+' '+btnClass,
                                'id': idx === 0 && typeof button === 'string' ?
                                    settings.sTableId +'_'+ button :
                                    null
                            } )
                                .append( $('<a>', {
                                        'href': '#',
                                        'aria-controls': settings.sTableId,
                                        'data-dt-idx': counter,
                                        'tabindex': settings.iTabIndex
                                    } )
                                        .html( btnDisplay )
                                )
                                .appendTo( container );

                            settings.oApi._fnBindAction(
                                node, {action: button}, clickHandler
                            );

                            counter++;
                        }
                    }
                }
            };

            // IE9 throws an 'unknown error' if document.activeElement is used
            // inside an iframe or frame.
            let activeEl;

            try {
                // Because this approach is destroying and recreating the paging
                // elements, focus is lost on the select button which is bad for
                // accessibility. So we want to restore focus once the draw has
                // completed
                activeEl = $(host).find(document.activeElement).data('dt-idx');
            }
            catch (e) {}

            attach(
                $(host).empty().html('<ul class="pagination"/>').children('ul'),
                buttons
            );

            if ( activeEl ) {
                $(host).find( '[data-dt-idx='+activeEl+']' ).focus();
            }
        };


        /*
         * TableTools Bootstrap compatibility
         * Required TableTools 2.1+
         */
        if ( DataTable.TableTools ) {
            // Set the classes that TableTools uses to something suitable for Bootstrap
            $.extend( true, DataTable.TableTools.classes, {
                "container": "DTTT btn-group",
                "buttons": {
                    "normal": "btn btn-default",
                    "disabled": "disabled"
                },
                "collection": {
                    "container": "DTTT_dropdown dropdown-menu",
                    "buttons": {
                        "normal": "",
                        "disabled": "disabled"
                    }
                },
                "print": {
                    "info": "DTTT_print_info"
                },
                "select": {
                    "row": "active"
                }
            } );

            // Have the collection use a bootstrap compatible drop down
            $.extend( true, DataTable.TableTools.DEFAULTS.oTags, {
                "collection": {
                    "container": "ul",
                    "button": "li",
                    "liner": "a"
                }
            } );
        }

    }; // /factory


// Define as an AMD module if possible
factory(jQuery, jQuery.fn.dataTable);


})(window, document);

$(document).ready(function() {
    $('table.dataTable').each(function () {
        let table = $(this);
        let datatableOptions = {
            dom: 'BflrRtip',
            lengthMenu: [10, 25, 50, 100, 500, 1000],
            language: DATATABLE_TRANSLATIONS,
        };

        if (table.data('export')) {
            require('datatables.net-buttons/js/dataTables.buttons.min');
            require('datatables.net-buttons/js/buttons.flash.min');
            require('jszip');
            require('pdfmake');
            require('./vfs_fonts');
            require('datatables.net-buttons/js/buttons.html5.min');
            require('datatables.net-buttons/js/buttons.print.min');
            datatableOptions.buttons = [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ];
        }

        if (table.data('responsive')) {
            require('datatables.net-responsive');
            require('datatables.net-responsive-dt/css/responsive.dataTables.min.css');
            require('datatables.net-responsive-dt');
            datatableOptions.responsive = true;
        }

        if (table.data('colReorder')) {
            require('datatables.net-colreorder/js/dataTables.colReorder.min');
            datatableOptions.colReorder = true;
        }

        let datatable = table.DataTable(datatableOptions);

        datatable.on('draw.dt', function () {
            console.log('ici');
        });
    });
} );