import { basicDatatable, deleteButtonClick } from './utils/datatable.js';

document.addEventListener('DOMContentLoaded', function () {
  const avatar = document.getElementById('avatar');
  const dropdown = document.getElementById('dropdown');
  const switchCheck = document.querySelector('.switch');
  const switchInput = document.querySelector('.switch-input');

  avatar.addEventListener('click', function () {
    dropdown.classList.toggle('hidden');
  });

  document.addEventListener('click', function (event) {
    const isClickInside =
      avatar.contains(event.target) || dropdown.contains(event.target);
    if (!isClickInside) {
      dropdown.classList.add('hidden');
    }
  });

  const dropdownItems = dropdown.querySelectorAll('li');
  dropdownItems.forEach(item => {
    item.addEventListener('click', function () {
      dropdown.classList.add('hidden');
    });
  });

  switchCheck?.addEventListener('click', function (e) {
    switchInput.value = switchInput.checked;
  });
});

basicDatatable('storesDatatable', [{ width: '10%', targets: 2 }]);
deleteButtonClick('storesDatatable', 'deleteModal', 'id');
