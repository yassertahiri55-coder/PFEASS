import React, { useEffect, useState, useCallback } from 'react';
import { useSelector } from 'react-redux';
import { getDossiers } from '../api-axios';

export default function ListeDossiers() {
  const user = useSelector(state => state.user.user);
  const [dossiers, setDossiers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Fonction pour charger les dossiers
  const fetchDossiers = useCallback(() => {
    if (!user?.id) return;
    setLoading(true);
    getDossiers()
      .then(data => {
        // Pour l'agent : afficher tous les dossiers, tous sinistres
        setDossiers(data);
        setLoading(false);
      })
      .catch(() => {
        setError('Erreur lors du chargement des dossiers');
        setLoading(false);
      });
  }, [user]);

  useEffect(() => {
    fetchDossiers();
  }, [fetchDossiers]);

  if (!user?.id) return <p>Vous devez être connecté.</p>;
  if (loading) return <p>Chargement...</p>;
  if (error) return <p style={{color:'red'}}>{error}</p>;
  // Toujours afficher le tableau, même si aucun dossier
  // Affiche un message dans le tableau si aucun dossier

  return (
    <div>
      <button onClick={fetchDossiers} style={{marginBottom:16, padding:'6px 18px', borderRadius:5, border:'1px solid #ccc', background:'#e0e7ff', cursor:'pointer'}}>Rafraîchir</button>
      <table style={{margin:'0 auto', borderCollapse:'collapse', minWidth:500}}>
      <thead>
        <tr>
          <th style={{border:'1px solid #ccc', padding:8}}>Numéro</th>
          <th style={{border:'1px solid #ccc', padding:8}}>Statut</th>
          <th style={{border:'1px solid #ccc', padding:8}}>Date ouverture</th>
          <th style={{border:'1px solid #ccc', padding:8}}>Date clôture</th>
        </tr>
      </thead>
      <tbody>
        {dossiers.length === 0 ? (
          <tr>
            <td colSpan={4} style={{textAlign:'center', padding:12}}>Aucun dossier en attente.</td>
          </tr>
        ) : (
          dossiers.map(d => (
            <tr key={d.id}>
              <td style={{border:'1px solid #ccc', padding:8}}>{d.numero}</td>
              <td style={{border:'1px solid #ccc', padding:8}}>{d.statut}</td>
              <td style={{border:'1px solid #ccc', padding:8}}>{d.date_ouverture}</td>
              <td style={{border:'1px solid #ccc', padding:8}}>{d.date_cloture || '-'}</td>
            </tr>
          ))
        )}
      </tbody>
      </table>
    </div>
  );
}
