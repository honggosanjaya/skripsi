window.$ = window.jQuery = require('jquery')
require('datatables.net-dt');
require('datatables.net-datetime');
require('datatables.net-searchpanes-bs5');

require('select2');
import Swal from 'sweetalert2';
window.Swal = Swal;

$(document).ready(function () {
  $('.select-two').select2();
});