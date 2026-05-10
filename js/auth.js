document.addEventListener('DOMContentLoaded', () => {
  const watchForm = (formId) => {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      const formData = new FormData(form);
      const response = await fetch(`${window.APP_BASE_URL}/api/auth.php`, {
        method: 'POST',
        body: formData
      });
      const result = await response.json();
      if (result.success) {
        Swal.fire({ icon: 'success', title: 'Success', text: result.message, timer: 1400, showConfirmButton: false });
        setTimeout(() => {
          window.location.href = result.redirect || '/PGFinder/index.php';
        }, 1400);
      } else {
        Swal.fire('Oops', result.message, 'error');
      }
    });
  };

  watchForm('loginForm');
  watchForm('signupForm');
});
