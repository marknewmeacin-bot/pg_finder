document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('#detailInterestBtn, .interest-toggle-btn').forEach((button) => {
    button.addEventListener('click', async () => {
      const propertyId = button.dataset.propertyId;
      if (!propertyId) return;
      try {
        const response = await fetch(`${window.APP_BASE_URL}/api/interests.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `action=toggle&property_id=${encodeURIComponent(propertyId)}`
        });
        const data = await response.json();
        if (data.success) {
          const actionLabel = data.status === 'added' ? 'Added' : 'Removed';
          Swal.fire({ icon: 'success', title: `${actionLabel} successfully`, text: data.message, timer: 1200, showConfirmButton: false });
          if (button.id === 'detailInterestBtn') {
            button.classList.toggle('btn-danger');
            button.classList.toggle('btn-outline-danger');
            button.textContent = data.status === 'added' ? 'Remove from Interested' : 'Mark as Interested';
          } else {
            button.closest('.col-md-6')?.remove();
          }
        } else {
          Swal.fire('Failed', data.message, 'warning');
        }
      } catch (error) {
        Swal.fire('Error', 'Please login to manage interested properties.', 'error');
      }
    });
  });
});
