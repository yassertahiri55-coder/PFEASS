
import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';


export default function ListeSinistres() {
  const [sinistres, setSinistres] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const token = localStorage.getItem('token');
    const headers = token ? { Authorization: `Bearer ${token}` } : {};
    axios.get('http://localhost:8000/api/sinistres', { headers })
      .then(res => setSinistres(res.data))
      .catch(() => setSinistres([]))
      .finally(() => setLoading(false));
  }, []);

  const handleVoirDetails = (sinistre) => {
    navigate(`/sinistres/${sinistre.id}`);
  };

  return (
    <div className="main-container">
      <h3>Liste de tous les sinistres</h3>
      {loading ? <p>Chargement...</p> : (
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Titre</th>
              <th>Description</th>
              <th>Date</th>
              <th>Utilisateur</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {sinistres.length === 0 ? (
              <tr><td colSpan={6} style={{textAlign:'center'}}>Aucun sinistre</td></tr>
            ) : (
              sinistres.map(s => (
                <tr key={s.id}>
                  <td>{s.id}</td>
                  <td>{s.titre}</td>
                  <td>{s.description}</td>
                  <td>{s.created_at ? new Date(s.created_at).toLocaleString() : ''}</td>
                  <td>{s.user_id}</td>
                  <td>
                    <button onClick={() => handleVoirDetails(s)}>Voir détails</button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      )}
      {/* Les détails sont maintenant sur une autre page */}
    </div>
  );
}
