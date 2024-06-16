import { basicDatatable } from '../../utils/datatable.js';
const table = document.querySelector('.table-cm');
const tbody = table?.getElementsByTagName('tbody')[0];

table?.addEventListener('click', function (event) {
  const checkboxes = tbody.querySelectorAll('input[type="checkbox"]');
  if (event.target.matches('input[type="checkbox"]')) {
    event.target.previousSibling.previousSibling.value =
      event.target.checked.toString();
  }
});

basicDatatable('rolesDatatable', [{ width: '10%', targets: 1 }]);
