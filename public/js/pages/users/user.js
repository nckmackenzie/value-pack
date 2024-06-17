import { basicDatatable } from '../../utils/datatable.js';
basicDatatable('usersDatatable', [
  { width: '10%', targets: 3 },
  { width: '15%', targets: 4 },
]);

function activateMultiSelect() {
  $(function () {
    $('#store').multiselect({
      includeSelectAllOption: true,
      buttonWidth: '100%',
      maxHeight: 200,
    });
  });
}

activateMultiSelect();
