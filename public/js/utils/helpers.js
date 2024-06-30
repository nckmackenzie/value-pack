export const alertBox = document.querySelector('.alert-box');
export function getSelectedText(sel) {
  return sel.options[sel.selectedIndex].text;
}

export function validation() {
  let errorCount = 0;

  const mandatoryField = document.querySelectorAll('.mandatory');
  mandatoryField?.forEach(field => {
    if (!field.value || field.value == '') {
      field.classList.add('is-invalid');
      field.nextSibling.nextSibling.textContent = 'Field is required';
      errorCount++;
    }
  });

  return errorCount;
}

export function clearOnChange(mandatoryField) {
  mandatoryField?.forEach(field => {
    field.addEventListener('change', function () {
      field.classList.remove('is-invalid');
      field.nextSibling.nextSibling.textContent = '';
    });
  });
}

export const errorSetter = (element, message) => {
  const html = `
    <div class="alert custom-destructive alert-dismissible fade show" role="alert">
      <p class="text-sm font-medium text-rose-900">👉 ${message}</p>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  `;
  element.innerHTML = '';
  element.innerHTML = html;
};

export function validateDate(start, end, elm) {
  if (new Date(start.value).getTime() > new Date(end.value).getTime()) {
    errorSetter(elm || alertBox, 'Start date cannot be greather than end date');
    return false;
  }
  return true;
}
