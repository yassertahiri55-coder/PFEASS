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
      <div className="navbar-container">
        {/* Brand Logo */}
        <Link to="/" className="navbar-brand">
          <div className="brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="2.5" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
          </div>
          <span>
            PFEASS <span className="brand-accent">Assur</span>
          </span>
        </Link>

        {/* Navigation Links */}
        <div className="navbar-links">
          {!user ? (
            <Link to="/login" className="nav-link-connexion">Connexion</Link>
          ) : (
            <>
              {user.role === 'client' && (
                <Link to="/dashboard-client">Tableau de bord</Link>
              )}
              {user.role === 'agent' && (
                <Link to="/dashboard-agent">Tableau de bord</Link>
              )}
            </>
          )}
        </div>

        {/* Authentication Actions */}
        <div className="navbar-actions">
          {!user ? (
            <>
              <Link to="/login" className="btn-login">Connexion</Link>
              <Link to="/register" className="btn-register">S'inscrire</Link>
            </>
          ) : (
            <div className="user-profile">
              <div className="user-badge">
                {user.prenom} {user.name} ({user.role === 'client' ? 'Client' : 'Agent'})
              </div>
              <button onClick={handleLogout} className="btn-logout">
                Déconnexion
              </button>
            </div>
          )}
        </div>
      </div>
    </nav>
  );
}
