import { getRequest } from '../../utils/ajax.js';
import { reportDatatable } from '../../utils/datatable.js';
import {
  alertBox,
  clearOnChange,
  validateDate,
  validation,
} from '../../utils/helpers.js';
import { HOST_URL } from '../../utils/host.js';
import { numberWithCommas } from '../../utils/formatters.js';

const mandatoryFields = document.querySelectorAll('.mandatory');
const previewBtn = document.querySelector('.preview');
const startDateInput = document.querySelector('#start_date');
const endDateInput = document.querySelector('#end_date');
const reportTypeSelect = document.querySelector('#report_type');
// const table = document.querySelector('#stock-report-table');
// const tbody = table?.getElementsByTagName('tbody')[0];
const tableArea = document.querySelector('#table-area');
const baseUrl = `${HOST_URL}/reports`;

previewBtn.addEventListener('click', async function () {
  if (validation() > 0) return;
  const startDate = startDateInput.value;
  const endDate = endDateInput.value;

  if (!validateDate(startDateInput, endDateInput)) return;
  const res = await getRequest(
    `${baseUrl}/sales_report?start=${startDate}&end=${endDate}&type=${reportTypeSelect.value}`,
    alertBox
  );
  if (res.success) {
    if (reportTypeSelect.value === 'summary') {
      renderSummaryReport(res.data);
      reportDatatable('sales-report-table', [
        { width: '20%', targets: 1 },
        { width: '20%', targets: 2 },
      ]);
    } else {
      renderDetailedReport(res.data);
      reportDatatable('sales-report-table', [
        { width: '10%', targets: 0 },
        { width: '20%', targets: 2 },
        { width: '10%', targets: 3 },
        { width: '10%', targets: 4 },
        { width: '10%', targets: 5 },
      ]);
    }
  }
});

function renderSummaryReport(data) {
  let html = `
      <table id="sales-report-table" class="table table-sm table-bordered table-striped">
        <thead>
          <tr>
              <th>Product</th>
              <th>Qty</th>
              <th>Value</th>
          </tr>
        </thead>
        <tbody>
        ${data
          .map(
            dt =>
              `<tr>
                  <td>${dt.product_name.toUpperCase()}</td>
                  <td>${dt.qty}</td>
                  <td>${dt.amount}</td>
               </tr>`
          )
          .join('')}
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2">Total</td>
            <td>${numberWithCommas(
              data.reduce((a, b) => a + Number(b.amount), 0)
            )}</td>
          </tr>
        </tfoot>
    </table>
  `;

  tableArea.innerHTML = html;
}

function renderDetailedReport(data) {
  const total = data.reduce((a, b) => a + Number(b.amount), 0);
  let html = `
      <table id="sales-report-table" class="table table-sm table-bordered table-striped">
        <thead>
          <tr>
              <th>Date</th>
              <th>Product</th>
              <th>Customer</th>
              <th>Qty</th>
              <th>Rate</th>
              <th>Amount</th>
          </tr>
        </thead>
        <tbody>
        ${data
          .map(
            dt =>
              `<tr>
                  <td>${dt.sale_date}</td>
                  <td>${dt.product_name.toUpperCase()}</td>
                  <td>${dt.customer_name.toUpperCase()}</td>
                  <td>${dt.qty}</td>
                  <td>${dt.rate}</td>
                  <td>${dt.amount}</td>
               </tr>`
          )
          .join('')}
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5">Total</td>
            <td>${numberWithCommas(total)}</td>
          </tr>
        </tfoot>
    </table>
  `;

  tableArea.innerHTML = html;
}

clearOnChange(mandatoryFields);
