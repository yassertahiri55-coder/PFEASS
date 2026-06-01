import React, { useState, useEffect } from 'react';
import { getDocuments, getSinistres, uploadDocument, deleteDocument } from '../api-axios';
import axios from 'axios';
// Téléchargement sécurisé d'un document
async function downloadDocument(id, nom) {
  const token = localStorage.getItem('token');
  const response = await axios.get(`http://localhost:8000/api/documents/${id}/download`, {
    responseType: 'blob',
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json'
    }
  });
  const url = window.URL.createObjectURL(new Blob([response.data]));
  const link = document.createElement('a');
  link.href = url;
  link.setAttribute('download', nom);
  document.body.appendChild(link);
  link.click();
  link.remove();
}

export default function GererDocuments() {
  const [documents, setDocuments] = useState([]);
  const [sinistres, setSinistres] = useState([]);
  const [file, setFile] = useState(null);
  const [sinistreId, setSinistreId] = useState('');
  const [dossierId, setDossierId] = useState('');
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(false);

  // Charger la liste des sinistres (pour la dropdown)
  useEffect(() => {
    getSinistres()
      .then(setSinistres)
      .catch(() => setSinistres([]));
  }, []);

  // Charger la liste des documents
  const fetchDocuments = () => {
    setLoading(true);
    getDocuments()
      .then(setDocuments)
      .catch(() => setDocuments([]))
      .finally(() => setLoading(false));
  };
  useEffect(fetchDocuments, []);

  // Upload d'un document
  const handleUpload = async (e) => {
    e.preventDefault();
    setMessage('');
    if (!file || !sinistreId) {
      setMessage('Sélectionnez un fichier et un sinistre.');
      return;
    }
    const formData = new FormData();
    formData.append('nom', file.name);
    formData.append('type', file.type);
    formData.append('sinistre_id', sinistreId);
    formData.append('fichier', file);
    if (dossierId) {
      formData.append('dossier_id', dossierId);
    }
    setLoading(true);
    try {
      await uploadDocument(formData);
      setMessage('Document ajouté !');
      setFile(null);
      setSinistreId('');
      setDossierId('');
      fetchDocuments();
    } catch (err) {
      setMessage('Erreur lors de l\'upload');
    } finally {
      setLoading(false);
    }
  };

  // Suppression d'un document
  const handleDelete = async (id) => {
    setLoading(true);
    try {
      await deleteDocument(id);
      setDocuments(docs => docs.filter(doc => doc.id !== id));
    } catch {
      setMessage('Erreur lors de la suppression');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{maxWidth:600, margin:'0 auto'}}>
      <h3>Gérer vos documents</h3>
      <form onSubmit={handleUpload} style={{marginBottom:20, display:'flex', gap:8, alignItems:'center'}}>
        <input type="file" onChange={e => setFile(e.target.files[0])} />
        <select value={sinistreId} onChange={e => setSinistreId(e.target.value)}>
          <option value="">Sélectionnez un sinistre</option>
          {sinistres.map(s => (
            <option key={s.id} value={s.id}>{s.titre || s.id}</option>
          ))}
        </select>
        <input
          type="text"
          placeholder="ID dossier (optionnel)"
          value={dossierId}
          onChange={e => setDossierId(e.target.value)}
          style={{width:120}}
        />
        <button type="submit" disabled={loading}>Uploader</button>
      </form>
      {message && <p>{message}</p>}
      {loading ? <p>Chargement...</p> : (
        <table style={{width:'100%', borderCollapse:'collapse'}}>
          <thead>
            <tr>
              <th>Nom</th>
              <th>Type</th>
              <th>Sinistre</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {documents.length === 0 ? (
              <tr><td colSpan={4} style={{textAlign:'center'}}>Aucun document</td></tr>
            ) : (
              documents.map(doc => (
                <tr key={doc.id}>
                  <td>{doc.nom}</td>
                  <td>{doc.type}</td>
                  <td>{doc.sinistre_id}</td>
                  <td>
                    <button
                      onClick={() => downloadDocument(doc.id, doc.nom)}
                      style={{marginRight:8}}
                    >
                      Télécharger
                    </button>
                    <button onClick={() => handleDelete(doc.id)} style={{color:'red'}}>Supprimer</button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      )}
    </div>
  );
}
