import { basicDatatable } from '../../utils/datatable.js';
import { getRequest } from '../../utils/ajax.js';
import { HOST_URL } from '../../utils/host.js';

basicDatatable('receiptsDatatable', [{ width: '10%', targets: 3 }]);

const storeSelect = document.getElementById('store_from');
const transferNos = document.getElementById('transfer_no');
const alertBox = document.querySelector('.alert');
const table = document.getElementById('items_table');
const tbody = table?.getElementsByTagName('tbody')[0];
const baseUrl = `${HOST_URL}/receipts`;

storeSelect?.addEventListener('change', async e => {
  const store = e.target.value;
  if (!store) return;
  transferNos.innerHTML = '';
  let html = `<option value="" disabled selected>Select store from</option>`;
  const res = await getRequest(
    `${baseUrl}/get_transfers?store=${store}`,
    alertBox
  );
  if (res.success) {
    const data = res.data
      .map(
        transfer =>
          `<option value="${transfer.id}">${transfer.transfer_no}</option>`
      )
      .join('');
    html += data;
    transferNos.innerHTML = html;
  }
});

transferNos?.addEventListener('change', async e => {
  const transferNo = e.target.value;
  if (!transferNo) return;

  tbody.innerHTML = '';
  const res = await getRequest(
    `${baseUrl}/get_items?transferNo=${transferNo}`,
    alertBox
  );
  if (res.success) {
    renderTable(res.data);
  }
});

function renderTable(data) {
  const html = data
    .map(
      dt =>
        `<tr>
            <td class="hidden"><input type="text" name="product_id[]" value="${dt.product_id}"/></td>
            <td><input type="text" name="product[]" class="w-full table-input uppercase" value="${dt.product_name}" readonly/></td>
            <td><input type="number" name="transfered_qty[]" class="table-input" value="${dt.qty}" readonly/></td>
            <td><input type="number" name="received_qty[]" style="background-color: azure;" value="" /></td>
        </tr>        
        `
    )
    .join('');

  tbody.innerHTML = html;
}
