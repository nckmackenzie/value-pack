import { basicDatatable, deleteButtonClick } from '../../utils/datatable.js';
import { getRequest } from '../../utils/ajax.js';
import { HOST_URL } from '../../utils/host.js';
import { numberWithCommas } from '../../utils/formatters.js';

const table = document.querySelector('.table-cm');
const customer = document.querySelector('#customer');
const tbody = table?.getElementsByTagName('tbody')[0];
const alertBox = document.querySelector('.alert');
const totalAmountInput = document.querySelector('#total');

table?.addEventListener('click', function (event) {
  if (event.target.matches('input[type="checkbox"]')) {
    event.target.previousSibling.previousSibling.value =
      event.target.checked.toString();
  }
});

customer?.addEventListener('change', async function () {
  if (!customer.value || customer.value.toString().trim().length === 0) {
    return;
  }
  const url = `${HOST_URL}/payments/get_pending?customer=${customer.value}`;
  const res = await getRequest(url, alertBox);
  if (res.success) {
    renderData(res.data);
  }
});

function calculateTotal() {
  let total = 0;
  const paymentInputs = tbody.querySelectorAll('input[name="payments[]"]');
  paymentInputs.forEach(input => {
    const value = parseFloat(input.value) || 0;
    total += value;
  });
  totalAmountInput.value = numberWithCommas(total.toFixed(2));
}

function addBlurEventListeners() {
  const paymentInputs = tbody.querySelectorAll('input[name="payments[]"]');
  paymentInputs.forEach(input => {
    input.addEventListener('blur', calculateTotal);
  });
}

function renderData(data) {
  tbody.innerHTML = '';
  const itemsMarkup = data
    .map(
      item =>
        ` <tr>
            <td class="hidden">
                <input type="text" name="invoice_ids[]" class="w-full" value="${
                  item.id
                }" />
            </td>
            <td>
              <input type="text" name="invoice_nos[]" class="w-full table-input" value="${
                item.invoice_no
              }" readonly/>
            </td>
            <td>
              <input type="text" name="invoice_amounts[]" class="w-full table-input" value="${numberWithCommas(
                item.invoice_amount
              )}" readonly/></td>
            <td><input type="text" name="amount_dues[]" class="w-full table-input" value="${numberWithCommas(
              item.amount_due
            )}" readonly/></td>
            <td><input type="number" name="payments[]" style="background-color: azure;" value="" /></td>
        </tr>`
    )
    .join('');
  tbody.innerHTML = itemsMarkup;
  addBlurEventListeners();
}

basicDatatable('paymentsDatatable', [{ width: '10%', targets: 5 }]);
deleteButtonClick('paymentsDatatable', 'deleteModal', 'id');
