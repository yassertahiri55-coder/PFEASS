import React, { useState } from 'react';
import { useSelector } from 'react-redux';
import ListeSinistres from './ListeSinistres';

export default function DashboardAgent() {
  const user = useSelector(state => state.user.user);
  const [activeView, setActiveView] = useState('dossiers');
  return (
    <div className="auth-container">
      <nav style={{display:'flex', justifyContent:'center', gap:20, marginBottom:30, background:'#f5f5f5', padding:'15px 0', borderRadius:8}}>
        <button
          style={{padding:'10px 18px', borderRadius:5, border:'1px solid #ccc', background:activeView==='dossiers'?'#e0e7ff':'#fff', cursor:'pointer'}}
          onClick={() => setActiveView('dossiers')}
        >Gérer les dossiers</button>
        <button
          style={{padding:'10px 18px', borderRadius:5, border:'1px solid #ccc', background:activeView==='sinistres'?'#e0e7ff':'#fff', cursor:'pointer'}}
          onClick={() => setActiveView('sinistres')}
        >Valider sinistres</button>
        <button
          style={{padding:'10px 18px', borderRadius:5, border:'1px solid #ccc', background:activeView==='notifications'?'#e0e7ff':'#fff', cursor:'pointer'}}
          onClick={() => setActiveView('notifications')}
        >Envoyer notifications</button>
        <button
          style={{padding:'10px 18px', borderRadius:5, border:'1px solid #ccc', background:activeView==='stats'?'#e0e7ff':'#fff', cursor:'pointer'}}
          onClick={() => setActiveView('stats')}
        >Statistiques</button>
      </nav>
      <h2>Bienvenue, {user?.prenom} {user?.name} (Agent)</h2>
      {activeView === 'dossiers' && (
        <div style={{marginTop:30}}>
          <ListeSinistres />
        </div>
      )}
      {activeView === 'sinistres' && (
        <div style={{marginTop:30}}>
          <p>Validation/refus des sinistres (à implémenter)</p>
        </div>
      )}
      {activeView === 'notifications' && (
        <div style={{marginTop:30}}>
          <p>Envoi de notifications (à implémenter)</p>
        </div>
      )}
      {activeView === 'stats' && (
        <div style={{marginTop:30}}>
          <p>Statistiques (à implémenter)</p>
        </div>
      )}
    </div>
  );
}
