document.addEventListener('DOMContentLoaded', () => {
  const propertyForm = document.getElementById('propertyForm');
  const userSearch = document.getElementById('userSearch');
  const interestFilter = document.getElementById('interestFilter');

  if (propertyForm) {
    propertyForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      const formData = new FormData(propertyForm);
      const action = formData.get('action');
      try {
        const response = await fetch(`${window.ADMIN_BASE_URL}/api/properties.php`, {
          method: 'POST',
          body: formData,
        });
        const result = await response.json();
        if (result.success) {
          Swal.fire('Success', result.message, 'success').then(() => {
            if (action === 'add') {
              window.location.href = `${window.ADMIN_BASE_URL}/properties.php`;
            }
          });
        } else {
          Swal.fire('Error', result.message, 'error');
        }
      } catch (error) {
        Swal.fire('Error', 'Could not save property.', 'error');
      }
    });
  }

  document.querySelectorAll('.delete-action').forEach((button) => {
    button.addEventListener('click', async () => {
      const url = button.dataset.url;
      const confirmText = button.dataset.confirm || 'Are you sure?';
      const action = button.dataset.action || '';
      const id = button.dataset.id || '';
      const userId = button.dataset.user || '';
      const propertyId = button.dataset.property || '';
      if (!url || !action) return;
      const confirmed = await Swal.fire({
        title: 'Confirm',
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, continue',
      });
      if (!confirmed.isConfirmed) return;
      const formData = new FormData();
      formData.append('action', action);
      if (id) formData.append('id', id);
      if (userId) formData.append('user_id', userId);
      if (propertyId) formData.append('property_id', propertyId);
      try {
        const response = await fetch(url, { method: 'POST', body: formData });
        const data = await response.json();
        if (data.success) {
          Swal.fire('Done', data.message, 'success').then(() => window.location.reload());
        } else {
          Swal.fire('Error', data.message, 'error');
        }
      } catch (error) {
        Swal.fire('Error', 'Request failed.', 'error');
      }
    });
  });

  if (userSearch) {
    userSearch.addEventListener('input', () => {
      const query = userSearch.value.toLowerCase();
      document.querySelectorAll('.user-row').forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  }

  if (interestFilter) {
    interestFilter.addEventListener('change', () => {
      window.location.search = `filter=${encodeURIComponent(interestFilter.value)}`;
    });
  }
});