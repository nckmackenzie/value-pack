import { basicDatatable, deleteButtonClick } from '../../utils/datatable.js';

basicDatatable('expenseaccountsDatatable', [{ width: '10%', targets: 2 }]);
deleteButtonClick('expenseaccountsDatatable', 'deleteModal', 'id');
