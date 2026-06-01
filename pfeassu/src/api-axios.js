// Utilitaires pour appels API Laravel avec axios

import axios from 'axios';

const API_BASE = 'http://localhost:8000/api';

function getAuthHeaders() {
  const token = localStorage.getItem('token');
  return token ? { Authorization: `Bearer ${token}` } : {};
}

export async function getDossiers() {
  const res = await axios.get(`${API_BASE}/dossiers`, { headers: getAuthHeaders() });
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
