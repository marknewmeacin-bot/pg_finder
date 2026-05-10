import React, { useEffect, useState } from 'react';

export default function PropertyStats() {
  const [stats, setStats] = useState({ users: 0, properties: 0, interests: 0, cities: 0 });
  const [loading, setLoading] = useState(true);
  
  useEffect(() => {
    async function fetchStats() {
      try {
        const response = await fetch('/PGFinder/admin/api/dashboard-stats.php');
        const data = await response.json();
        if (data.success) {
          setStats(data.stats);
        }
      } catch (error) {
        console.error('Failed to load stats', error);
      } finally {
        setLoading(false);
      }
    }
    fetchStats();
  }, []);

  if (loading) {
    return <div>Loading statistics...</div>;
  }

  return (
    <div className="row g-3">
      {['users', 'properties', 'interests', 'cities'].map((key) => (
        <div className="col-6 col-xl-3" key={key}>
          <div className="card p-3 shadow-sm">
            <div className="card-body">
              <h6 className="text-muted text-uppercase">{key.replace(/^(.)/, (m) => m.toUpperCase())}</h6>
              <h2>{stats[key]}</h2>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}
