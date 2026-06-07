// Utilitaires pour appels API Laravel avec axios

import axios from 'axios';

const API_BASE = 
  (typeof process !== 'undefined' && process.env && process.env.REACT_APP_API_BASE_URL) ||
  (typeof import.meta !== 'undefined' && import.meta.env && import.meta.env.VITE_API_BASE_URL) ||
  'http://localhost:8000/api';

const axiosInstance = axios.create({
  baseURL: API_BASE,
  headers: {
    Accept: 'application/json',
  },
});

function getAuthHeaders() {
  const token = localStorage.getItem('token');
  return token ? { Authorization: `Bearer ${token}` } : {};
}

export async function getDossiers() {
  const res = await axiosInstance.get('/dossiers', { headers: getAuthHeaders() });
  return res.data;
}

export async function getNotifications() {
  const res = await axios.get(`${API_BASE}/notifications`, { headers: getAuthHeaders() });
  return res.data;
}

export async function getDocuments() {
  const res = await axios.get(`${API_BASE}/documents`, { headers: getAuthHeaders() });
  return res.data;
}

export async function createDossier(data) {
  const res = await axios.post(`${API_BASE}/dossiers`, data, { headers: getAuthHeaders() });
  return res.data;
}

export async function getSinistres() {
  const res = await axios.get(`${API_BASE}/sinistres`, { headers: getAuthHeaders() });
  return res.data;
}

export async function createSinistre(data) {
  const res = await axios.post(`${API_BASE}/sinistres`, data, { headers: getAuthHeaders() });
  return res.data;
}

export async function getRendezVous() {
  const res = await axios.get(`${API_BASE}/rendezvous`, { headers: getAuthHeaders() });
  return res.data;
}

export async function uploadDocument(formData) {
  const res = await axios.post(`${API_BASE}/documents`, formData, {
    headers: { ...getAuthHeaders(), 'Content-Type': 'multipart/form-data' }
  });
  return res.data;
}

export async function deleteDocument(id) {
  const res = await axios.delete(`${API_BASE}/documents/${id}`, { headers: getAuthHeaders() });
  return res.data;
}
