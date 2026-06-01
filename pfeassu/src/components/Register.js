
import { useState } from 'react';
import axios from 'axios';


export default function Register() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirm, setPasswordConfirm] = useState('');
  const [name, setName] = useState('');
  const [prenom, setPrenom] = useState('');
  const [telephone, setTelephone] = useState('');
  const [pays, setPays] = useState('');
  const [dateNaissance, setDateNaissance] = useState('');
  const [role, setRole] = useState('client'); // 'client', 'agent', ...
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setSuccess(null);
    if (password !== passwordConfirm) {
      setError('Les mots de passe ne correspondent pas');
      return;
    }
    try {
      await axios.post(
        'http://localhost:8000/api/register',
        {
          name,
          prenom,
          email,
          password,
          password_confirmation: passwordConfirm,
          telephone,
          pays,
          date_naissance: dateNaissance,
          role: role,
        }
      );
      setSuccess('Inscription réussie !');
    } catch (err) {
      setError(err.response?.data?.message || 'Erreur lors de l\'inscription');
    }
  };

  return (
    <div className="auth-container">
      <h2>Inscription</h2>
      <form onSubmit={handleSubmit}>
        <input type="text" placeholder="Nom" value={name} onChange={e => setName(e.target.value)} required />
        <input type="text" placeholder="Prénom" value={prenom} onChange={e => setPrenom(e.target.value)} required />
        <input type="email" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} required />
        <input type="password" placeholder="Mot de passe" value={password} onChange={e => setPassword(e.target.value)} required />
        <input type="password" placeholder="Confirmer le mot de passe" value={passwordConfirm} onChange={e => setPasswordConfirm(e.target.value)} required />
        <input type="text" placeholder="Téléphone" value={telephone} onChange={e => setTelephone(e.target.value)} />
        <input type="text" placeholder="Pays" value={pays} onChange={e => setPays(e.target.value)} />
        <input type="date" placeholder="Date de naissance" value={dateNaissance} onChange={e => setDateNaissance(e.target.value)} />
        <select value={role} onChange={e => setRole(e.target.value)} required>
          <option value="client">Client</option>
          <option value="agent">Agent</option>
        </select>
        <button type="submit">S'inscrire</button>
      </form>
      {error && <p style={{color:'red'}}>{error}</p>}
      {success && <p style={{color:'green'}}>{success}</p>}
    </div>
  );
}
