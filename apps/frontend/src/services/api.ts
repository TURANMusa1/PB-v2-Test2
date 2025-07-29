import axios from 'axios';

const API_GATEWAY_URL = 'http://localhost:8000/api';

// API Gateway için axios instance
export const apiGateway = axios.create({
  baseURL: API_GATEWAY_URL,
  withCredentials: false,
});

// Request interceptor - token ekleme
apiGateway.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor - token yenileme
apiGateway.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth API fonksiyonları
export const authService = {
  login: async (email: string, password: string) => {
    const response = await apiGateway.post('/auth/login', { email, password });
    return response.data;
  },
  
  register: async (userData: any) => {
    const response = await apiGateway.post('/auth/register', userData);
    return response.data;
  },
  
  logout: async () => {
    const response = await apiGateway.post('/auth/logout');
    return response.data;
  },
  
  getProfile: async () => {
    const response = await apiGateway.get('/auth/me');
    return response.data;
  }
};

// Candidate API fonksiyonları
export const candidateService = {
  getCandidates: async (params?: any) => {
    const response = await apiGateway.get('/candidates', { params });
    return response.data;
  },
  
  getCandidate: async (id: string) => {
    const response = await apiGateway.get(`/candidates/${id}`);
    return response.data;
  },
  
  createCandidate: async (candidateData: any) => {
    const response = await apiGateway.post('/candidates', candidateData);
    return response.data;
  },
  
  updateCandidate: async (id: string, candidateData: any) => {
    const response = await apiGateway.put(`/candidates/${id}`, candidateData);
    return response.data;
  },
  
  deleteCandidate: async (id: string) => {
    const response = await apiGateway.delete(`/candidates/${id}`);
    return response.data;
  },
  
  searchCandidates: async (query: string) => {
    const response = await apiGateway.get('/candidates/search', { 
      params: { q: query } 
    });
    return response.data;
  }
};

// Notification API fonksiyonları
export const notificationService = {
  getNotifications: async () => {
    const response = await apiGateway.get('/notifications');
    return response.data;
  },
  
  markAsRead: async (id: string) => {
    const response = await apiGateway.put(`/notifications/${id}/read`);
    return response.data;
  },
  
  deleteNotification: async (id: string) => {
    const response = await apiGateway.delete(`/notifications/${id}`);
    return response.data;
  }
}; 