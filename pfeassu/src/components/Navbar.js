import { Link, useNavigate } from 'react-router-dom';
import { useSelector, useDispatch } from 'react-redux';
import { logout } from '../userSlice';

export default function Navbar() {
  const user = useSelector(state => state.user.user);
  const dispatch = useDispatch();
  const navigate = useNavigate();

  const handleLogout = () => {
    dispatch(logout());
    navigate('/');
  };

  return (
    <nav className="navbar">
      <Link to="/">Accueil</Link>
      {!user && <Link to="/login">Connexion</Link>}
      {!user && <Link to="/register">Inscription</Link>}
      {user && <button onClick={handleLogout} style={{marginLeft:10}}>Déconnexion</button>}
    </nav>
  );
}
