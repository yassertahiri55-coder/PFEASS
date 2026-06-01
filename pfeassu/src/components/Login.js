
import { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import { useDispatch } from 'react-redux';
import { setUser } from '../userSlice';

export default function Login() {


  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState(null);
  const navigate = useNavigate();
  const dispatch = useDispatch();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    try {
      const response = await axios.post('http://localhost:8000/api/login', {
        email,
        password
      });
      const user = response.data.user;
      const token = response.data.token;
      if (token) {
        localStorage.setItem('token', token);
      }
      dispatch(setUser(user));
      if (user.role === 'client') {
        navigate('/dashboard-client');
      } else if (user.role === 'agent') {
        navigate('/dashboard-agent');
      } else {
        alert('Rôle inconnu, accès refusé. Seuls les rôles agent et client sont autorisés.');
        // Déconnexion immédiate si le rôle n'est pas autorisé
        localStorage.removeItem('token');
        dispatch(setUser(null));
      }
    } catch (err) {
      setError(err.response?.data?.error || 'Erreur lors de la connexion');
    }
  };

  return (
    <div className="auth-container">
      <h2>Connexion</h2>
      <form onSubmit={handleSubmit}>
        <input type="email" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} required />
        <input type="password" placeholder="Mot de passe" value={password} onChange={e => setPassword(e.target.value)} required />
        <button type="submit">Se connecter</button>
      </form>
      {error && <p style={{color:'red'}}>{error}</p>}
    </div>
  );
}

