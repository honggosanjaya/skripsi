window.$ = window.jQuery = require('jquery')
require('datatables.net-dt');
require('datatables.net-datetime');
require('datatables.net-searchpanes-bs5');

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

require('select2');
import Swal from 'sweetalert2';
window.Swal = Swal;

$(document).ready(function () {
  $('.select2').select2({
    allowClear: true,
    theme: "bootstrap-5",
    width: "100%"
  });
});

$(document).ready(function () {
  $('#table, #table2').DataTable({
    fixedHeader: true,
    buttons: [
      'searchPanes'
    ],
    dom: 'Bfrtip',
    "order": [],
  });
});