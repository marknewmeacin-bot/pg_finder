const { useState, useEffect } = React;
const BASE_URL = window.APP_BASE_URL || '';

function PropertyListing() {
  const [properties, setProperties] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filters, setFilters] = useState({ city: '', gender: '', maxBudget: '' });
  const [error, setError] = useState('');

  useEffect(() => {
    loadProperties();
  }, [filters]);

  const loadProperties = async () => {
    setLoading(true);
    setError('');
    const params = new URLSearchParams({ action: 'list' });

    if (filters.city) params.append('city', filters.city);
    if (filters.gender) params.append('gender', filters.gender);
    if (filters.maxBudget) params.append('maxBudget', filters.maxBudget);

    try {
      const response = await fetch(`${BASE_URL}/api/properties.php?${params.toString()}`, { credentials: 'include' });
      if (!response.ok) {
        const text = await response.text();
        throw new Error(`API request failed with status ${response.status}: ${text}`);
      }

      const data = await response.json();
      if (!data || data.success !== true) {
        throw new Error(data?.message || 'Unable to load properties from the API.');
      }

      setProperties(Array.isArray(data.data) ? data.data : []);
    } catch (fetchError) {
      console.error('Property load failed:', fetchError);
      setError('Unable to load properties right now. Please refresh or try again later.');
      setProperties([]);
    } finally {
      setLoading(false);
    }
  };

  const toggleInterest = async (propertyId) => {
    try {
      const response = await fetch(`${BASE_URL}/api/interests.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=toggle&property_id=${encodeURIComponent(propertyId)}`
      });

      if (!response.ok) {
        const text = await response.text();
        throw new Error(`Interest API failed with status ${response.status}: ${text}`);
      }

      const result = await response.json();
      if (!result || !result.success) {
        throw new Error(result?.message || 'Unable to toggle interest.');
      }

      alert(result.message || 'Interest status updated.');
      loadProperties();
    } catch (fetchError) {
      console.error('Interest toggle failed:', fetchError);
      setError('Unable to update interest. Please login or try again.');
    }
  };

  const updateFilter = (field, value) => {
    setFilters((prevFilters) => ({ ...prevFilters, [field]: value }));
  };

  const resetFilters = () => {
    setFilters({ city: '', gender: '', maxBudget: '' });
  };

  return (
    <div className="p-4 bg-white rounded-4 shadow-sm mt-5">
      <div className="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-end mb-4">
        <div className="w-100 w-lg-auto">
          <label className="form-label">City</label>
          <select className="form-select" value={filters.city} onChange={(e) => updateFilter('city', e.target.value)}>
            <option value="">All Cities</option>
            <option value="Delhi">Delhi</option>
            <option value="Bangalore">Bangalore</option>
            <option value="Mumbai">Mumbai</option>
            <option value="Chennai">Chennai</option>
            <option value="Pune">Pune</option>
          </select>
        </div>

        <div className="w-100 w-lg-auto">
          <label className="form-label">Gender</label>
          <select className="form-select" value={filters.gender} onChange={(e) => updateFilter('gender', e.target.value)}>
            <option value="">Any</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Coed">Coed</option>
          </select>
        </div>

        <div className="w-100 w-lg-auto">
          <label className="form-label">Max Budget (₹)</label>
          <input
            type="number"
            className="form-control"
            value={filters.maxBudget}
            onChange={(e) => updateFilter('maxBudget', e.target.value)}
            placeholder="10000"
          />
        </div>

        <button className="btn btn-outline-secondary h-50" onClick={resetFilters}>
          Reset
        </button>
      </div>

      {error && <div className="alert alert-danger">{error}</div>}

      {loading ? (
        <div className="text-center py-5">
          <div className="spinner-border text-primary" role="status"></div>
        </div>
      ) : properties.length ? (
        <div className="row g-4">
          {properties.map((pg) => (
            <div className="col-md-6 col-xl-4" key={pg.id}>
              <div className="card h-100 shadow-sm">
                <img
                  src={pg.image || `${BASE_URL}/images/pg1.jpg`}
                  className="card-img-top"
                  alt={pg.name || 'Property image'}
                  onError={(e) => { e.target.src = `${BASE_URL}/images/pg1.jpg`; }}
                />
                <div className="card-body d-flex flex-column">
                  <h5 className="card-title">{pg.name || 'Unnamed PG'}</h5>
                  <p className="text-muted mb-2">
                    <i className="fas fa-map-marker-alt me-2"></i>
                    {pg.city || 'Unknown city'}
                  </p>
                  <p className="flex-grow-1">{(pg.description || '').slice(0, 80)}...</p>
                  <div className="d-flex flex-wrap gap-2 mb-3">
                    <span className="badge bg-info text-dark">{pg.gender || 'Any'}</span>
                    <span className="badge bg-success">
                      <i className="fas fa-star"></i> {pg.rating ?? 'N/A'}
                    </span>
                  </div>
                  <div className="d-flex justify-content-between align-items-center gap-2">
                    <a href={`${BASE_URL}/property-detail.php?id=${pg.id}`} className="btn btn-outline-primary btn-sm">
                      View Details
                    </a>
                    <button type="button" className="btn btn-danger btn-sm" onClick={() => toggleInterest(pg.id)}>
                      Interested
                    </button>
                    <span className="fw-semibold">&#8377; {pg.price ?? 'N/A'}</span>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="alert alert-info">No PGs matched your filters.</div>
      )}
    </div>
  );
}

const rootContainer = document.getElementById('reactPropertyApp');
if (rootContainer) {
  ReactDOM.createRoot(rootContainer).render(<PropertyListing />);
} else {
  console.error('React mount element #reactPropertyApp not found.');
}
