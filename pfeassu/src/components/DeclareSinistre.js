
import React, { useState } from 'react';
import { createSinistre } from '../api-axios';
import { useSelector } from 'react-redux';

export default function DeclareSinistre() {

  const user = useSelector(state => state.user.user);
  const [titre, setTitre] = useState('');
  const [description, setDescription] = useState('');
  const [dateDeclaration, setDateDeclaration] = useState('');
  const [type, setType] = useState('');
  const [success, setSuccess] = useState(null);
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setSuccess(null);
    try {
      await createSinistre({
        user_id: user?.id,
        titre,
        description,
        date_declaration: dateDeclaration,
        type
      });
      setSuccess('Sinistre déclaré avec succès !');
      setTitre('');
      setDescription('');
      setDateDeclaration('');
      setType('');
    } catch (err) {
      if (err.response?.data?.errors) {
        // Laravel renvoie un objet errors { champ: [messages] }
        const messages = Object.values(err.response.data.errors).flat().join(' ');
        setError(messages);
      } else {
        setError(err.response?.data?.error || 'Erreur lors de la déclaration');
      }
    }
  };

  if (!user?.id) {
    return (
      <div className="auth-container">
        <h2>Déclarer un sinistre</h2>
        <p style={{color:'red'}}>Vous devez être connecté pour déclarer un sinistre.</p>
      </div>
    );
  }

  return (
    <div className="auth-container">
      <h2>Déclarer un sinistre</h2>
      <form onSubmit={handleSubmit}>
        <input type="text" placeholder="Titre du sinistre" value={titre} onChange={e => setTitre(e.target.value)} required />
        <input type="text" placeholder="Type de sinistre" value={type} onChange={e => setType(e.target.value)} required />
        <input type="date" value={dateDeclaration} onChange={e => setDateDeclaration(e.target.value)} required />
        <textarea placeholder="Description" value={description} onChange={e => setDescription(e.target.value)} required />
        <button type="submit">Déclarer</button>
      </form>
      {success && <p style={{color:'green'}}>{success}</p>}
      {error && <p style={{color:'red'}}>{error}</p>}
    </div>
  );
}

