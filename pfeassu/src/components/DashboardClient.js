import React, { useState } from 'react';
import { useSelector } from 'react-redux';

import DeclareSinistre from './DeclareSinistre';
import ListeDossiers from './ListeDossiers';
import GererDocuments from './GererDocuments';
import ListeNotifications from './ListeNotifications';

export default function DashboardClient() {
  const user = useSelector(state => state.user.user);
  const [activeView, setActiveView] = useState('dossiers');
  return (
    <div className="auth-container">
      <nav style={{display:'flex', justifyContent:'center', gap:20, marginBottom:30, background:'#f5f5f5', padding:'15px 0', borderRadius:8}}>
        <button
          style={{padding:'10px 18px', borderRadius:5, border:'1px solid #ccc', background:activeView==='sinistre'?'#e0e7ff':'#fff', cursor:'pointer'}}
          onClick={() => setActiveView('sinistre')}
        >Déclarer un sinistre</button>
        <button
          style={{padding:'10px 18px', borderRadius:5, border:'1px solid #ccc', background:activeView==='dossiers'?'#e0e7ff':'#fff', cursor:'pointer'}}
          onClick={() => setActiveView('dossiers')}
        >Consulter vos dossiers</button>
        <button
          style={{padding:'10px 18px', borderRadius:5, border:'1px solid #ccc', background:activeView==='notifications'?'#e0e7ff':'#fff', cursor:'pointer'}}
          onClick={() => setActiveView('notifications')}
        >Voir vos notifications</button>
        <button
          style={{padding:'10px 18px', borderRadius:5, border:'1px solid #ccc', background:activeView==='documents'?'#e0e7ff':'#fff', cursor:'pointer'}}
          onClick={() => setActiveView('documents')}
        >Gérer vos documents</button>
      </nav>
      <h2>Bienvenue, {user?.prenom} {user?.name} (Client)</h2>
      {activeView === 'sinistre' && <DeclareSinistre />}
      {activeView === 'dossiers' && (
        <div style={{marginTop:30}}>
          <h3>Vos dossiers en attente</h3>
          <ListeDossiers />
        </div>
      )}
      {activeView === 'notifications' && (
        <div style={{marginTop:30}}>
          <ListeNotifications />
        </div>
      )}
      {activeView === 'documents' && (
        <div style={{marginTop:30}}>
          <GererDocuments />
        </div>
      )}
    </div>
  );
}
