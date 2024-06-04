// JavaScript for dropdown interactivity
document.addEventListener('DOMContentLoaded', function () {
  const avatar = document.getElementById('avatar');
  const dropdown = document.getElementById('dropdown');

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
});
