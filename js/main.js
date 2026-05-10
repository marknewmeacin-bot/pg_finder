document.addEventListener('DOMContentLoaded', () => {
  const applyFiltersBtn = document.getElementById('applyFilters');
  const resetFiltersBtn = document.getElementById('resetFilters');
  const loadingIndicator = document.getElementById('loadingIndicator');

  function showLoading(show) {
    if (!loadingIndicator) return;
    loadingIndicator.classList.toggle('d-none', !show);
  }

  async function fetchProperties() {
    if (!document.getElementById('propertyList')) return;
    showLoading(true);
    const city = document.getElementById('cityFilter').value;
    const gender = document.getElementById('genderFilter').value;
    const maxBudget = document.getElementById('budgetFilter').value;
    const params = new URLSearchParams();
    if (city) params.append('city', city);
    if (gender) params.append('gender', gender);
    if (maxBudget) params.append('maxBudget', maxBudget);
    params.append('action', 'list');

    try {
      const response = await fetch(`${window.APP_BASE_URL}/api/properties.php?${params.toString()}`);
      const result = await response.json();
      if (result.success) {
        renderPropertyCards(result.data);
      }
    } catch (error) {
      console.error('Failed to fetch properties', error);
    } finally {
      showLoading(false);
    }
  }

  function renderPropertyCards(properties) {
    const list = document.getElementById('propertyList');
    if (!list) return;
    if (!properties.length) {
      list.innerHTML = '<div class="col-12"><div class="alert alert-info">No properties match your filter. Try another city or budget.</div></div>';
      return;
    }

    list.innerHTML = properties.map((property) => `
      <div class="col-md-6 col-xl-4">
        <div class="card property-card h-100 shadow-sm">
          <img src="${property.image}" class="card-img-top" alt="${property.name}" onerror="this.src='${window.APP_BASE_URL}/images/pg1.jpg'">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">${property.name}</h5>
            <p class="text-muted mb-1"><i class="fas fa-map-marker-alt me-2"></i>${property.city}</p>
            <p class="mb-2">${property.description.slice(0, 90)}...</p>
            <div class="mb-3 d-flex flex-wrap gap-2">
              <span class="badge bg-info text-dark">${property.gender}</span>
              <span class="badge bg-success"><i class="fas fa-star"></i> ${property.rating}</span>
            </div>
            <div class="mt-auto d-flex justify-content-between align-items-center">
              <a href="property-detail.php?id=${property.id}" class="btn btn-outline-primary btn-sm">View Details</a>
              <button class="btn btn-danger btn-sm interest-toggle-btn" data-property-id="${property.id}">Interested</button>
            </div>
          </div>
        </div>
      </div>
    `).join('');
    attachInterestButtons();
  }

  function attachInterestButtons() {
    document.querySelectorAll('.interest-toggle-btn').forEach((button) => {
      button.addEventListener('click', async () => {
        const propertyId = button.dataset.propertyId;
        if (!propertyId) return;
        try {
          const response = await fetch(`${window.APP_BASE_URL}/api/interests.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=toggle&property_id=${encodeURIComponent(propertyId)}`
          });
          const result = await response.json();
          if (result.success) {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: result.message,
              showConfirmButton: false,
              timer: 1300
            });
          } else {
            Swal.fire('Oops', result.message, 'warning');
          }
        } catch (error) {
          Swal.fire('Error', 'Unable to update interest. Please login first.', 'error');
        }
      });
    });
  }

  if (applyFiltersBtn) {
    applyFiltersBtn.addEventListener('click', fetchProperties);
  }
  if (resetFiltersBtn) {
    resetFiltersBtn.addEventListener('click', () => {
      document.getElementById('cityFilter').value = '';
      document.getElementById('genderFilter').value = '';
      document.getElementById('budgetFilter').value = '';
      fetchProperties();
    });
  }

  fetchProperties();
});
