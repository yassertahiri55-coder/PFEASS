import React, { useState } from 'react';
import { useSelector } from 'react-redux';

import DeclareSinistre from './DeclareSinistre';
import ListeDossiers from './ListeDossiers';
import GererDocuments from './GererDocuments';
import ListeNotifications from './ListeNotifications';
import ListeRendezVous from './ListeRendezVous';

export default function DashboardClient() {
  const user = useSelector(state => state.user.user);
  const [activeView, setActiveView] = useState('dossiers');

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
            background: activeView === 'sinistre' ? 'linear-gradient(135deg, #6366f1, #3b82f6)' : 'transparent', 
            color: activeView === 'sinistre' ? '#ffffff' : '#94a3b8', 
            cursor: 'pointer',
            fontWeight: '600',
            transition: 'all 0.25s ease',
            boxShadow: activeView === 'sinistre' ? '0 4px 15px rgba(99, 102, 241, 0.35)' : 'none'
          }}
          onClick={() => setActiveView('sinistre')}
        >
          📝 Déclarer un sinistre
        </button>
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
          📂 Consulter vos dossiers
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
          🔔 Voir vos notifications
        </button>
        <button
          style={{
            padding: '12px 24px', 
            borderRadius: '12px', 
            border: 'none', 
            background: activeView === 'documents' ? 'linear-gradient(135deg, #6366f1, #3b82f6)' : 'transparent', 
            color: activeView === 'documents' ? '#ffffff' : '#94a3b8', 
            cursor: 'pointer',
            fontWeight: '600',
            transition: 'all 0.25s ease',
            boxShadow: activeView === 'documents' ? '0 4px 15px rgba(99, 102, 241, 0.35)' : 'none'
          }}
          onClick={() => setActiveView('documents')}
        >
          📁 Gérer vos documents
        </button>
        <button
          style={{
            padding: '12px 24px', 
            borderRadius: '12px', 
            border: 'none', 
            background: activeView === 'rendezvous' ? 'linear-gradient(135deg, #6366f1, #3b82f6)' : 'transparent', 
            color: activeView === 'rendezvous' ? '#ffffff' : '#94a3b8', 
            cursor: 'pointer',
            fontWeight: '600',
            transition: 'all 0.25s ease',
            boxShadow: activeView === 'rendezvous' ? '0 4px 15px rgba(99, 102, 241, 0.35)' : 'none'
          }}
          onClick={() => setActiveView('rendezvous')}
        >
          📅 Mes rendez-vous
        </button>
      </nav>

      <div style={{ borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '20px', marginBottom: '30px' }}>
        <h2 style={{ margin: 0, color: '#ffffff' }}>Bienvenue, {user?.prenom} {user?.name}</h2>
        <span className="badge bg-success text-white" style={{ marginTop: '8px' }}>Rôle: Client Assuré</span>
      </div>

      {activeView === 'sinistre' && (
        <div className="tab-fade-in">
          <DeclareSinistre />
        </div>
      )}
      
      {activeView === 'dossiers' && (
        <div style={{ marginTop: 20 }} className="tab-fade-in">
          <h3 style={{ color: '#ffffff', marginBottom: '16px' }}>Vos dossiers actifs</h3>
          <ListeDossiers />
        </div>
      )}

      {activeView === 'notifications' && (
        <div style={{ marginTop: 20 }} className="tab-fade-in">
          <h3 style={{ color: '#ffffff', marginBottom: '16px' }}>Historique des notifications</h3>
          <ListeNotifications />
        </div>
      )}

      {activeView === 'rendezvous' && (
        <div style={{ marginTop: 20 }} className="tab-fade-in">
          <h3 style={{ color: '#ffffff', marginBottom: '16px' }}>Vos rendez-vous d'expertise</h3>
          <ListeRendezVous />
        </div>
      )}

      {activeView === 'documents' && (
        <div style={{ marginTop: 20 }} className="tab-fade-in">
          <h3 style={{ color: '#ffffff', marginBottom: '16px' }}>Mes documents justificatifs</h3>
          <GererDocuments />
        </div>
      )}
    </div>
  );
}
