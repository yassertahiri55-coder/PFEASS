import React, { useEffect, useState, useCallback } from 'react';
import { useSelector } from 'react-redux';
import { getRendezVous } from '../api-axios';

export default function ListeRendezVous() {
  const user = useSelector(state => state.user.user);
  const [rendezvous, setRendezvous] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchRendezvous = useCallback(() => {
    if (!user?.id) return;
    setLoading(true);
    setError(null);

    getRendezVous()
      .then(data => setRendezvous(data))
      .catch(() => setError('Erreur lors du chargement des rendez-vous'))
      .finally(() => setLoading(false));
  }, [user]);

  useEffect(() => {
    fetchRendezvous();
  }, [fetchRendezvous]);

  if (!user?.id) return <p>Vous devez être connecté pour voir vos rendez-vous.</p>;
  if (loading) return <p>Chargement des rendez-vous...</p>;
  if (error) return <p style={{ color: 'red' }}>{error}</p>;

  return (
    <div>
      <button
        onClick={fetchRendezvous}
        style={{ marginBottom: 16, padding: '6px 18px', borderRadius: 5, border: '1px solid #ccc', background: '#e0e7ff', cursor: 'pointer' }}
      >
        Rafraîchir
      </button>
      <table style={{ margin: '0 auto', borderCollapse: 'collapse', minWidth: 600 }}>
        <thead>
          <tr>
            <th style={{ border: '1px solid #ccc', padding: 8 }}>Date</th>
            <th style={{ border: '1px solid #ccc', padding: 8 }}>Lieu</th>
            <th style={{ border: '1px solid #ccc', padding: 8 }}>Description</th>
            <th style={{ border: '1px solid #ccc', padding: 8 }}>Statut</th>
            <th style={{ border: '1px solid #ccc', padding: 8 }}>Dossier</th>
          </tr>
        </thead>
        <tbody>
          {rendezvous.length === 0 ? (
            <tr>
              <td colSpan={5} style={{ textAlign: 'center', padding: 12 }}>Aucun rendez-vous pour le moment.</td>
            </tr>
          ) : (
            rendezvous.map(r => (
              <tr key={r.id}>
                <td style={{ border: '1px solid #ccc', padding: 8 }}>{r.date ? new Date(r.date).toLocaleString() : '-'}</td>
                <td style={{ border: '1px solid #ccc', padding: 8 }}>{r.lieu}</td>
                <td style={{ border: '1px solid #ccc', padding: 8 }}>{r.description || '-'}</td>
                <td style={{ border: '1px solid #ccc', padding: 8 }}>{r.statut}</td>
                <td style={{ border: '1px solid #ccc', padding: 8 }}>#{r.dossier_id}</td>
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  );
}
