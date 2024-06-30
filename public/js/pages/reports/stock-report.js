import {
  clearOnChange,
  validation,
  alertBox,
  errorSetter,
  validateDate,
} from '../../utils/helpers.js';
import { getRequest } from '../../utils/ajax.js';
import { HOST_URL } from '../../utils/host.js';
const mandatoryFields = document.querySelectorAll('.mandatory');
const previewBtn = document.querySelector('.preview');
const startDateInput = document.querySelector('#start_date');
const endDateInput = document.querySelector('#end_date');
const table = document.querySelector('#stock-report-table');
const tbody = table?.getElementsByTagName('tbody')[0];
const tableArea = document.querySelector('#table-area');
const baseUrl = `${HOST_URL}/reports`;
import { reportDatatable } from '../../utils/datatable.js';

previewBtn.addEventListener('click', async function () {
  if (validation() > 0) return;
  const startDate = startDateInput.value;
  const endDate = endDateInput.value;

  if (!validateDate(startDateInput, endDateInput)) return;
  const res = await getRequest(
    `${baseUrl}/stock_report_gen?start=${startDate}&end=${endDate}`,
    alertBox
  );
  if (res.success) {
    tableArea.classList.remove('d-none');
    renderTbody(res.data);
    reportDatatable('stock-report-table', [
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '15%', targets: 3 },
      { width: '10%', targets: 4 },
    ]);
  }
});

function renderTbody(data) {
  tbody.innerHTML = '';
  let html = data
    .map(
      dt =>
        `<tr>
            <td class="uppercase">${dt.product_name.toUpperCase()}</td>
            <td>${dt.opening_bal}</td>
            <td>${dt.movement_in}</td>
            <td>${dt.movement_out}</td>
            <td>${dt.balance}</td>
        </tr>`
    )
    .join('');
  tbody.innerHTML = html;
}

clearOnChange(mandatoryFields);
