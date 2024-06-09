import { basicDatatable } from '../../utils/datatable.js';
function activateMultiSelect() {
  $(function () {
    $('#stores').multiselect({
      includeSelectAllOption: true,
      buttonWidth: '100%',
      maxHeight: 200,
    });
  });
}
activateMultiSelect();
basicDatatable('productsDatatable', [{ width: '10%', targets: 5 }]);
