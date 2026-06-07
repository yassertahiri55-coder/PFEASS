import React, { useState, useEffect } from 'react';
import { useSelector } from 'react-redux';
import axios from 'axios';
import ListeSinistres from './ListeSinistres';
import ListeNotifications from './ListeNotifications';

export default function DashboardAgent() {
  const user = useSelector(state => state.user.user);
  const [activeView, setActiveView] = useState('dossiers');
  const [agentSinistres, setAgentSinistres] = useState([]);
  const [loadingSinistres, setLoadingSinistres] = useState(true);
  const [recipientRole, setRecipientRole] = useState('client');
  const [selectedDossierId, setSelectedDossierId] = useState('');
  const [notificationMessage, setNotificationMessage] = useState('');
  const [sendingNotif, setSendingNotif] = useState(false);
  const [sendResult, setSendResult] = useState(null);
  const [stats, setStats] = useState(null);
  const [loadingStats, setLoadingStats] = useState(false);
  const [statsError, setStatsError] = useState(null);

  useEffect(() => {
    const token = localStorage.getItem('token');
    const headers = token ? { Authorization: `Bearer ${token}` } : {};
    axios.get('http://localhost:8000/api/sinistres', { headers })
      .then(res => {
        setAgentSinistres(res.data);
        if (res.data.length > 0) {
          setSelectedDossierId(res.data[0].id);
        }
      })
      .catch(() => setAgentSinistres([]))
      .finally(() => setLoadingSinistres(false));
  }, []);

  useEffect(() => {
    if (activeView !== 'stats' || stats || loadingStats) {
      return;
    }

    const token = localStorage.getItem('token');
    const headers = token ? { Authorization: `Bearer ${token}` } : {};
    setLoadingStats(true);
    setStatsError(null);

    axios.get('http://localhost:8000/api/sinistres/agent-statistiques', { headers })
      .then((res) => setStats(res.data))
      .catch((error) => setStatsError(error.response?.data?.error || error.message))
      .finally(() => setLoadingStats(false));
  }, [activeView, stats, loadingStats]);

  const handleSendNotification = async (event) => {
    event.preventDefault();
    setSendingNotif(true);
    setSendResult(null);
    const token = localStorage.getItem('token');
    const headers = token ? { Authorization: `Bearer ${token}` } : {};
    try {
      const res = await axios.post('http://localhost:8000/api/notifications', {
        type: recipientRole,
        message: notificationMessage,
        dossier_id: selectedDossierId,
        recipient_role: recipientRole,
      }, {
        headers
      });
      setSendResult({ success: true, message: res.data.message || 'Notification envoyée.' });
      setNotificationMessage('');
    } catch (error) {
      setSendResult({ success: false, message: error.response?.data?.error || error.message });
    } finally {
      setSendingNotif(false);
    }
  };

  return (
    <div className="dashboard-container">
      {/* Premium Dark Glassmorphic Nav */}
      <nav style={{
        display: 'flex', 
        justifyContent: 'center', 
        gap: '12px', 
        marginBottom: '40px', 
        background: 'rgba(17, 24, 39, 0.75)', 
        padding: '10px', 
        borderRadius: '16px', 
        flexWrap: 'wrap',
        border: '1px solid rgba(255,255,255,0.08)'
      }}>
        <button
          style={{
            padding: '12px 24px', 
            borderRadius: '12px', 
            border: 'none', 
            background: activeView === 'dossiers' ? 'linear-gradient(135deg, #6366f1, #3b82f6)' : 'transparent', 
            color: activeView === 'dossiers' ? '#ffffff' : '#94a3b8', 
            cursor: 'pointer',
            fontWeight: '600',
            transition: 'all 0.25s ease',
            boxShadow: activeView === 'dossiers' ? '0 4px 15px rgba(99, 102, 241, 0.35)' : 'none'
          }}
          onClick={() => setActiveView('dossiers')}
        >
          📂 Gérer les dossiers
        </button>
        <button
          style={{
            padding: '12px 24px', 
            borderRadius: '12px', 
            border: 'none', 
            background: activeView === 'notifications' ? 'linear-gradient(135deg, #6366f1, #3b82f6)' : 'transparent', 
            color: activeView === 'notifications' ? '#ffffff' : '#94a3b8', 
            cursor: 'pointer',
            fontWeight: '600',
            transition: 'all 0.25s ease',
            boxShadow: activeView === 'notifications' ? '0 4px 15px rgba(99, 102, 241, 0.35)' : 'none'
          }}
          onClick={() => setActiveView('notifications')}
        >
          ✉️ Envoyer notifications
        </button>
        <button
          style={{
            padding: '12px 24px', 
            borderRadius: '12px', 
            border: 'none', 
            background: activeView === 'received' ? 'linear-gradient(135deg, #6366f1, #3b82f6)' : 'transparent', 
            color: activeView === 'received' ? '#ffffff' : '#94a3b8', 
            cursor: 'pointer',
            fontWeight: '600',
            transition: 'all 0.25s ease',
            boxShadow: activeView === 'received' ? '0 4px 15px rgba(99, 102, 241, 0.35)' : 'none'
          }}
          onClick={() => setActiveView('received')}
        >
          🔔 Mes notifications
        </button>
        <button
          style={{
            padding: '12px 24px', 
            borderRadius: '12px', 
            border: 'none', 
            background: activeView === 'stats' ? 'linear-gradient(135deg, #6366f1, #3b82f6)' : 'transparent', 
            color: activeView === 'stats' ? '#ffffff' : '#94a3b8', 
            cursor: 'pointer',
            fontWeight: '600',
            transition: 'all 0.25s ease',
            boxShadow: activeView === 'stats' ? '0 4px 15px rgba(99, 102, 241, 0.35)' : 'none'
          }}
          onClick={() => setActiveView('stats')}
        >
          📊 Statistiques
        </button>
      </nav>

      <div style={{ borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '20px', marginBottom: '30px' }}>
        <h2 style={{ margin: 0, color: '#ffffff' }}>Bienvenue, {user?.prenom} {user?.name}</h2>
        <span className="badge bg-primary text-white" style={{ marginTop: '8px' }}>Rôle: Agent d'Assurances</span>
        <p style={{ color: '#94a3b8', marginTop: '12px', marginBottom: 0, fontSize: '0.95rem' }}>
          Vous gérez les dossiers et transmettez les sinistres à l'expert. La validation finale est effectuée par l'expert agréé.
        </p>
      </div>

      {activeView === 'dossiers' && (
        <div style={{ marginTop: 20 }}>
          <ListeSinistres />
        </div>
      )}

      {activeView === 'notifications' && (
        <div style={{ marginTop: 20, maxWidth: 700 }}>
          <h3 style={{ color: '#ffffff', marginBottom: '8px' }}>Envoyer une notification</h3>
          <p style={{ color: '#94a3b8', fontSize: '0.9rem', marginBottom: '24px' }}>
            Envoyer un message à un client, un expert ou un administrateur lié à un dossier de sinistre.
          </p>
          <form onSubmit={handleSendNotification} style={{ display: 'grid', gap: 20, background: 'rgba(31, 41, 55, 0.25)', padding: '24px', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.05)' }}>
            <label style={{ display: 'grid', gap: 8, fontWeight: '600', color: '#cbd5e1' }}>
              Destinataire (rôle)
              <select value={recipientRole} onChange={e => setRecipientRole(e.target.value)}>
                <option value="client">Client</option>
                <option value="expert">Expert</option>
                <option value="agent">Agent</option>
                <option value="admin">Administrateur</option>
              </select>
            </label>

            <label style={{ display: 'grid', gap: 8, fontWeight: '600', color: '#cbd5e1' }}>
              Dossier de sinistre concerné
              <select value={selectedDossierId} onChange={e => setSelectedDossierId(e.target.value)}>
                {agentSinistres.map(s => (
                  <option key={s.id} value={s.id}>
                    #{s.id} - {s.titre || 'Sinistre'} ({s.statut})
                  </option>
                ))}
              </select>
            </label>

            <label style={{ display: 'grid', gap: 8, fontWeight: '600', color: '#cbd5e1' }}>
              Message de la notification
              <textarea
                value={notificationMessage}
                onChange={e => setNotificationMessage(e.target.value)}
                placeholder="Écrivez le message de votre notification..."
                rows={4}
                required
              />
            </label>

            <button type="submit" disabled={sendingNotif || !selectedDossierId || !notificationMessage} style={{ marginTop: '10px' }}>
              {sendingNotif ? 'Envoi en cours...' : 'Envoyer la notification'}
            </button>

            {sendResult && (
              <div style={{
                color: sendResult.success ? '#34d399' : '#f87171', 
                fontWeight: '600', 
                marginTop: '10px',
                padding: '12px',
                borderRadius: '8px',
                background: sendResult.success ? 'rgba(52,211,153,0.1)' : 'rgba(248,113,113,0.1)',
                border: sendResult.success ? '1px solid rgba(52,211,153,0.2)' : '1px solid rgba(248,113,113,0.2)'
              }}>
                {sendResult.success ? '✅ ' : '❌ '} {sendResult.message}
              </div>
            )}

            {loadingSinistres && <p style={{ color: '#94a3b8' }}>Chargement des dossiers...</p>}
            {!loadingSinistres && agentSinistres.length === 0 && (
              <p style={{ color: '#f87171' }}>Aucun sinistre disponible pour envoyer une notification.</p>
            )}
          </form>
        </div>
      )}

      {activeView === 'received' && (
        <div style={{ marginTop: 20 }}>
          <ListeNotifications />
        </div>
      )}

      {activeView === 'stats' && (
        <div style={{ marginTop: 20 }}>
          <h3 style={{ color: '#ffffff', marginBottom: '24px' }}>Tableau de bord des statistiques</h3>
          {loadingStats && <p style={{ color: '#94a3b8' }}>Chargement des statistiques...</p>}
          {statsError && <p style={{ color: '#f87171' }}>{statsError}</p>}
          {stats && (
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: 20 }}>
              <div style={{ background: 'rgba(31, 41, 55, 0.35)', padding: '24px', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.06)', boxShadow: '0 10px 30px rgba(0,0,0,0.2)' }}>
                <div style={{ fontSize: 13, color: '#94a3b8', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Total sinistres</div>
                <div style={{ fontSize: 36, fontWeight: 800, marginTop: 8, color: '#ffffff' }}>{stats.total_sinistres}</div>
              </div>
              <div style={{ background: 'rgba(31, 41, 55, 0.35)', padding: '24px', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.06)', boxShadow: '0 10px 30px rgba(0,0,0,0.2)' }}>
                <div style={{ fontSize: 13, color: '#fbbf24', textTransform: 'uppercase', letterSpacing: '0.5px' }}>En attente</div>
                <div style={{ fontSize: 36, fontWeight: 800, marginTop: 8, color: '#fbbf24' }}>{stats.sinistres_en_attente}</div>
              </div>
              <div style={{ background: 'rgba(31, 41, 55, 0.35)', padding: '24px', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.06)', boxShadow: '0 10px 30px rgba(0,0,0,0.2)' }}>
                <div style={{ fontSize: 13, color: '#38bdf8', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Transférés à l'expert</div>
                <div style={{ fontSize: 36, fontWeight: 800, marginTop: 8, color: '#38bdf8' }}>{stats.sinistres_transfere_expert}</div>
              </div>
              <div style={{ background: 'rgba(31, 41, 55, 0.35)', padding: '24px', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.06)', boxShadow: '0 10px 30px rgba(0,0,0,0.2)' }}>
                <div style={{ fontSize: 13, color: '#34d399', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Validés</div>
                <div style={{ fontSize: 36, fontWeight: 800, marginTop: 8, color: '#34d399' }}>{stats.sinistres_valides}</div>
              </div>
              <div style={{ background: 'rgba(31, 41, 55, 0.35)', padding: '24px', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.06)', boxShadow: '0 10px 30px rgba(0,0,0,0.2)' }}>
                <div style={{ fontSize: 13, color: '#f87171', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Refusés</div>
                <div style={{ fontSize: 36, fontWeight: 800, marginTop: 8, color: '#f87171' }}>{stats.sinistres_refuses}</div>
              </div>
              <div style={{ background: 'rgba(31, 41, 55, 0.35)', padding: '24px', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.06)', boxShadow: '0 10px 30px rgba(0,0,0,0.2)' }}>
                <div style={{ fontSize: 13, color: '#94a3b8', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Dossiers ouverts</div>
                <div style={{ fontSize: 36, fontWeight: 800, marginTop: 8, color: '#ffffff' }}>{stats.dossiers_ouverts}</div>
              </div>
              <div style={{ background: 'rgba(31, 41, 55, 0.35)', padding: '24px', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.06)', boxShadow: '0 10px 30px rgba(0,0,0,0.2)' }}>
                <div style={{ fontSize: 13, color: '#94a3b8', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Total dossiers</div>
                <div style={{ fontSize: 36, fontWeight: 800, marginTop: 8, color: '#ffffff' }}>{stats.total_dossiers}</div>
              </div>
              <div style={{ background: 'rgba(31, 41, 55, 0.35)', padding: '24px', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.06)', boxShadow: '0 10px 30px rgba(0,0,0,0.2)' }}>
                <div style={{ fontSize: 13, color: '#6366f1', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Clients distincts</div>
                <div style={{ fontSize: 36, fontWeight: 800, marginTop: 8, color: '#a5b4fc' }}>{stats.clients_distincts}</div>
              </div>
            </div>
          )}
        </div>
      )}
    </div>
  );
}
