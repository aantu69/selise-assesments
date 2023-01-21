window._ = require('lodash');

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
    require('admin-lte');
    require('admin-lte/plugins/datatables/jquery.dataTables.min');
    require('admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min');
    require('admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars');
    require('admin-lte/plugins/select2/js/select2');
    // // require("jquery-ui/ui/");
    require("jquery-ui/ui/widgets/datepicker");
    // require("admin-lte/plugins/inputmask/inputmask");

} catch (e) {}