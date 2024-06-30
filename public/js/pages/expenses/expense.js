import { basicDatatable, deleteButtonClick } from '../../utils/datatable.js';

basicDatatable('expensesDatatable', [{ width: '10%', targets: 3 }]);
deleteButtonClick('expensesDatatable', 'deleteModal', 'id');
