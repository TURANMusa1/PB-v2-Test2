import React, { useState, useEffect } from 'react';
import { useAuth } from '../contexts/AuthContext';
import { candidateService, notificationService } from '../services/api';
import type { Candidate, Notification } from '../types';

const Dashboard: React.FC = () => {
  const { user } = useAuth();
  const [candidates, setCandidates] = useState<Candidate[]>([]);
  const [notifications, setNotifications] = useState<Notification[]>([]);
  const [loading, setLoading] = useState(true);
  const [stats, setStats] = useState({
    totalCandidates: 0,
    activeCandidates: 0,
    hiredCandidates: 0,
    unreadNotifications: 0,
  });

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      setLoading(true);
      
      // Fetch candidates
      const candidatesResponse = await candidateService.getCandidates();
      const candidatesData = candidatesResponse.data;
      setCandidates(candidatesData);

      // Fetch notifications
      const notificationsResponse = await notificationService.getNotifications();
      const notificationsData = notificationsResponse.data;
      setNotifications(notificationsData);

      // Calculate stats
      const activeCandidates = candidatesData.filter((c: any) => c.status === 'active').length;
      const hiredCandidates = candidatesData.filter((c: any) => c.status === 'hired').length;
      const unreadNotifications = notificationsData.filter((n: any) => !n.is_read).length;

      setStats({
        totalCandidates: candidatesData.length,
        activeCandidates,
        hiredCandidates,
        unreadNotifications,
      });
    } catch (error) {
      console.error('Dashboard data fetch failed:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
          <p className="text-slate-600 font-medium">Yükleniyor...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Welcome Message */}
        <div className="mb-8">
          <div className="flex items-center space-x-4 mb-4">
            <div className="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center">
              <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <h1 className="text-3xl font-bold text-slate-900">Hoş Geldiniz, {user?.name || 'Kullanıcı'}!</h1>
              <p className="text-slate-600 mt-1">PeopleBox ATS'e hoş geldiniz. İşte güncel durumunuz:</p>
            </div>
          </div>
        </div>

        {/* Stats Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div className="bg-white rounded-3xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-slate-600 mb-1">Toplam Aday</p>
                <p className="text-3xl font-bold text-slate-900">{stats.totalCandidates}</p>
              </div>
              <div className="p-3 rounded-2xl bg-blue-50">
                <svg className="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-3xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-slate-600 mb-1">Aktif Aday</p>
                <p className="text-3xl font-bold text-slate-900">{stats.activeCandidates}</p>
              </div>
              <div className="p-3 rounded-2xl bg-green-50">
                <svg className="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-3xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-slate-600 mb-1">İşe Alınan</p>
                <p className="text-3xl font-bold text-slate-900">{stats.hiredCandidates}</p>
              </div>
              <div className="p-3 rounded-2xl bg-indigo-50">
                <svg className="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6" />
                </svg>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-3xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-slate-600 mb-1">Okunmamış Bildirim</p>
                <p className="text-3xl font-bold text-slate-900">{stats.unreadNotifications}</p>
              </div>
              <div className="p-3 rounded-2xl bg-amber-50">
                <svg className="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 17h5l-5 5v-5zM4.19 4H20c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4.19C3.65 20 3.12 19.78 2.72 19.37L.5 17.13C.18 16.81 0 16.38 0 15.94V4.06c0-.44.18-.87.5-1.19L2.72.63C3.12.22 3.65 0 4.19 0z" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          {/* Recent Candidates */}
          <div className="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden">
            <div className="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100">
              <h2 className="text-xl font-bold text-slate-900 flex items-center">
                <svg className="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Son Eklenen Adaylar
              </h2>
            </div>
            <div className="p-6">
              {candidates.slice(0, 5).length > 0 ? (
                <div className="space-y-4">
                  {candidates.slice(0, 5).map((candidate) => (
                    <div key={candidate.id} className="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-2xl hover:from-slate-100 hover:to-slate-200 transition-all duration-200 group">
                      <div className="flex items-center space-x-4">
                        <div className="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                          <span className="text-white font-bold text-sm">
                            {candidate.first_name && candidate.last_name ? 
                              `${candidate.first_name[0]}${candidate.last_name[0]}`.toUpperCase() : '?'}
                          </span>
                        </div>
                        <div>
                          <h3 className="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors duration-200">
                            {candidate.first_name && candidate.last_name ? 
                              `${candidate.first_name} ${candidate.last_name}` : 'İsimsiz Aday'}
                          </h3>
                          <p className="text-sm text-slate-600">{candidate.current_position || 'Pozisyon belirtilmemiş'}</p>
                        </div>
                      </div>
                      <div className="flex items-center space-x-3">
                        <span className={`px-3 py-1 text-xs font-bold rounded-full ${
                          candidate.status === 'active' ? 'bg-green-100 text-green-800' :
                          candidate.status === 'hired' ? 'bg-blue-100 text-blue-800' :
                          'bg-slate-100 text-slate-800'
                        }`}>
                          {candidate.status === 'active' ? 'Aktif' :
                           candidate.status === 'hired' ? 'İşe Alındı' : candidate.status}
                        </span>
                        <button className="text-blue-600 hover:text-blue-800 text-sm font-semibold transition-colors duration-200">
                          Detay
                        </button>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="text-center py-12">
                  <div className="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg className="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                  </div>
                  <p className="text-slate-500 font-medium">Henüz aday bulunmuyor.</p>
                  <p className="text-slate-400 text-sm mt-1">Yeni adaylar eklediğinizde burada görünecek.</p>
                </div>
              )}
            </div>
          </div>

          {/* Recent Notifications */}
          <div className="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden">
            <div className="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100">
              <h2 className="text-xl font-bold text-slate-900 flex items-center">
                <svg className="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 17h5l-5 5v-5zM4.19 4H20c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4.19C3.65 20 3.12 19.78 2.72 19.37L.5 17.13C.18 16.81 0 16.38 0 15.94V4.06c0-.44.18-.87.5-1.19L2.72.63C3.12.22 3.65 0 4.19 0z" />
                </svg>
                Son Bildirimler
              </h2>
            </div>
            <div className="p-6">
              {notifications.slice(0, 5).length > 0 ? (
                <div className="space-y-4">
                  {notifications.slice(0, 5).map((notification) => (
                    <div key={notification.id} className={`flex items-start space-x-4 p-4 rounded-2xl transition-all duration-200 ${
                      notification.read_at ? 'bg-slate-50' : 'bg-blue-50 border-l-4 border-blue-500'
                    }`}>
                      <div className={`flex-shrink-0 w-3 h-3 rounded-full mt-2 ${
                        notification.type === 'success' ? 'bg-green-500' :
                        notification.type === 'warning' ? 'bg-amber-500' :
                        notification.type === 'error' ? 'bg-red-500' :
                        'bg-blue-500'
                      }`}></div>
                      <div className="flex-1 min-w-0">
                        <h3 className="text-sm font-semibold text-slate-900 truncate">
                          {notification.subject || 'Başlıksız Bildirim'}
                        </h3>
                        <p className="text-sm text-slate-600 mt-1">{notification.content || 'Mesaj içeriği yok'}</p>
                        <p className="text-xs text-slate-500 mt-2 font-medium">
                          {notification.created_at ? new Date(notification.created_at).toLocaleDateString('tr-TR') : 'Tarih belirtilmemiş'}
                        </p>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="text-center py-12">
                  <div className="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg className="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 17h5l-5 5v-5zM4.19 4H20c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4.19C3.65 20 3.12 19.78 2.72 19.37L.5 17.13C.18 16.81 0 16.38 0 15.94V4.06c0-.44.18-.87.5-1.19L2.72.63C3.12.22 3.65 0 4.19 0z" />
                    </svg>
                  </div>
                  <p className="text-slate-500 font-medium">Henüz bildirim bulunmuyor.</p>
                  <p className="text-slate-400 text-sm mt-1">Yeni bildirimler geldiğinde burada görünecek.</p>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard; 