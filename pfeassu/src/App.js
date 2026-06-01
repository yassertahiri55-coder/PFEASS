import Accueil from './components/Accueil';
import Login from './components/Login';
import Register from './components/Register';
import DashboardClient from './components/DashboardClient';
import DashboardAgent from './components/DashboardAgent';
import DetailSinistre from './components/DetailSinistre';
import './App.css';
import './structure.css';

import { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import axios from 'axios';
import { setUser } from './userSlice';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';







function App() {
  const dispatch = useDispatch();

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (token) {
      // On tente de récupérer l'utilisateur connecté
      axios.get('http://localhost:8000/api/user', {
        headers: { Authorization: `Bearer ${token}` }
      })
        .then(res => {
          dispatch(setUser(res.data));
        })
        .catch(() => {
          // Token invalide, on ne fait rien
        });
    }
  }, [dispatch]);

  return (
    <Router>
      <Navbar />
      <Routes>
        <Route path="/" element={<Accueil />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/dashboard-client" element={<DashboardClient />} />
        <Route path="/dashboard-agent" element={<DashboardAgent />} />
        <Route path="/sinistres/:id" element={<DetailSinistre />} />
      </Routes>
    </Router>
  );
}

export default App;
