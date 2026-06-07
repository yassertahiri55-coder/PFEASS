import React, { useEffect, useState } from 'react';
import axios from 'axios';

export default function ListeNotifications() {
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem('token');
    const headers = token ? { Authorization: `Bearer ${token}` } : {};
    axios.get('http://localhost:8000/api/notifications', { headers })
      .then(res => {
        const filtered = res.data.filter(n => {
          const senderRole = (n.sender_role || '').toString().toLowerCase();
          const message = (n.message || '').toString().toLowerCase();
          return ['expert', 'admin'].includes(senderRole)
            || message.includes('(expert)')
            || message.includes('(admin)');
        });
        setNotifications(filtered);
      })
      .catch(() => setNotifications([]))
      .finally(() => setLoading(false));
  }, []);

  return (
    <div className="main-container">
      <h3>Vos notifications</h3>
      {loading ? <p>Chargement...</p> : (
        <table>
          <thead>
            <tr>
              <th>Message</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            {notifications.length === 0 ? (
              <tr><td colSpan={2} style={{textAlign:'center'}}>Aucune notification</td></tr>
            ) : (
              notifications.map(n => (
                <tr key={n.id}>
                  <td>{n.message}</td>
                  <td>{new Date(n.created_at).toLocaleString()}</td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      )}
    </div>
  );
}
