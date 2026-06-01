import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useParams, useNavigate } from 'react-router-dom';
import { useSelector } from 'react-redux';

export default function DetailSinistre() {
  const user = useSelector(state => state.user.user);
  const { id } = useParams();
  const navigate = useNavigate();
  const [details, setDetails] = useState(null);
  const [loading, setLoading] = useState(true);

  const fetchDetails = () => {
    const token = localStorage.getItem('token');
    axios.get(`http://localhost:8000/api/sinistres/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
      .then(res => setDetails(res.data))
      .catch(() => setDetails(null))
      .finally(() => setLoading(false));
  };

  useEffect(() => {
    fetchDetails();
  }, [id]);

  const [sending, setSending] = useState(false);
  const [sendMsg, setSendMsg] = useState(null);

  if (loading) return <div className="main-container"><p>Chargement...</p></div>;
  if (!details) return <div className="main-container"><p>Erreur lors du chargement du sinistre.</p></div>;

  const handleEnvoyerDocuments = async () => {
    setSending(true);
    setSendMsg(null);
    try {
      const token = localStorage.getItem('token');
      await axios.post(`http://localhost:8000/api/sinistres/${id}/envoyer-documents`, {}, {
        headers: { Authorization: `Bearer ${token}` }
      });
      setSendMsg('Documents envoyés à l\'expert.');
      setTimeout(() => {
        fetchDetails();
      }, 400); // Délai pour garantir la propagation
    } catch (e) {
      setSendMsg("Erreur lors de l'envoi");
    } finally {
      setSending(false);
    }
  };

  return (
    <div className="main-container">
      {/* DEBUG: Infos utilisateur connecté */}
      <div style={{background:'#f8f8f8',padding:8,marginBottom:8,border:'1px solid #ccc'}}>
        <strong>DEBUG</strong> | user.id: {user?.id} | user.role: {user?.role} | sinistre.user_id: {details?.user_id}
      </div>
      <button onClick={() => navigate(-1)} style={{marginBottom:16}}>Retour</button>
      <h3>Détails du sinistre #{details.id}</h3>
      <p><strong>Titre :</strong> {details.titre}</p>
      <p><strong>Description :</strong> {details.description}</p>
      <p><strong>Type :</strong> {details.type}</p>
      <p><strong>Date de déclaration :</strong> {details.date_declaration}</p>
      <p><strong>Statut :</strong> {details.statut}</p>
      <p><strong>Utilisateur :</strong> {details.user ? `${details.user.prenom} ${details.user.name} (${details.user.email})` : details.user_id}</p>
      <h5>Dossiers liés</h5>
      {details.dossiers && details.dossiers.length > 0 ? (
        <ul>
          {details.dossiers.map(d => (
            <li key={d.id}>
              <strong>Numéro :</strong> {d.numero} | <strong>Statut :</strong> {d.statut} | <strong>Ouverture :</strong> {d.date_ouverture} | <strong>Clôture :</strong> {d.date_cloture || '—'}
            </li>
          ))}
        </ul>
      ) : <p>Aucun dossier lié.</p>}
      <h5>Documents liés</h5>
      {user && user.role === 'agent' && details.statut !== 'transfere_expert' && (
        <div style={{marginBottom:16}}>
          <button onClick={handleEnvoyerDocuments} disabled={sending}>
            {sending ? 'Envoi en cours...' : 'Envoyer le sinistre à l\'expert'}
          </button>
          {sendMsg && <span style={{marginLeft:10, color: sendMsg.startsWith('Erreur') ? 'red' : 'green'}}>{sendMsg}</span>}
        </div>
      )}
      {details.documents && details.documents.length > 0 ? (
        <ul>
          {details.documents.map(doc => (
            <li key={doc.id}>
              <strong>{doc.nom}</strong> ({doc.type})
              {doc.chemin && (
                <a href={`http://localhost:8000/storage/documents/${doc.chemin}`} target="_blank" rel="noopener noreferrer" style={{marginLeft:8}}>Télécharger</a>
              )}
            </li>
          ))}
        </ul>
      ) : <p>Aucun document lié.</p>}
    </div>
  );
}
