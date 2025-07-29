import React, { useState, useEffect } from 'react';
import type { Candidate, CandidateSearchParams } from '../types';
import { candidateService } from '../services/api';

const CandidateList: React.FC = () => {
  const [candidates, setCandidates] = useState<Candidate[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [searchQuery, setSearchQuery] = useState('');
  const [filters, setFilters] = useState<CandidateSearchParams>({
    status: '',
    position: '',
    experience_min: undefined,
    experience_max: undefined,
  });

  useEffect(() => {
    fetchCandidates();
  }, [filters]);

  const fetchCandidates = async () => {
    try {
      setLoading(true);
      const response = await candidateService.getCandidates(filters);
      setCandidates(response.data);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Adaylar yüklenirken bir hata oluştu.');
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = async () => {
    if (searchQuery.trim()) {
      try {
        setLoading(true);
        const response = await candidateService.searchCandidates(searchQuery);
        setCandidates(response.data);
      } catch (err: any) {
        setError(err.response?.data?.message || 'Arama sırasında bir hata oluştu.');
      } finally {
        setLoading(false);
      }
    } else {
      fetchCandidates();
    }
  };

  const handleFilterChange = (key: keyof CandidateSearchParams, value: any) => {
    setFilters(prev => ({
      ...prev,
      [key]: value
    }));
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'active': return 'bg-green-100 text-green-800 border-green-200';
      case 'inactive': return 'bg-slate-100 text-slate-800 border-slate-200';
      case 'hired': return 'bg-blue-100 text-blue-800 border-blue-200';
      case 'rejected': return 'bg-red-100 text-red-800 border-red-200';
      default: return 'bg-slate-100 text-slate-800 border-slate-200';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'active': return 'Aktif';
      case 'inactive': return 'Pasif';
      case 'hired': return 'İşe Alındı';
      case 'rejected': return 'Reddedildi';
      default: return status;
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
          <p className="text-slate-600 font-medium">Adaylar yükleniyor...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Header */}
        <div className="mb-8">
          <div className="flex items-center space-x-4 mb-6">
            <div className="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div>
              <h1 className="text-3xl font-bold text-slate-900">Adaylar</h1>
              <p className="text-slate-600 mt-1">Tüm adayları görüntüleyin ve yönetin</p>
            </div>
          </div>

          {/* Search and Filters */}
          <div className="bg-white rounded-3xl shadow-lg border border-slate-200 p-6">
            <div className="space-y-6">
              {/* Search Bar */}
              <div className="flex gap-4">
                <div className="flex-1 relative">
                  <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg className="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                  <input
                    type="text"
                    placeholder="Aday ara..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    className="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    onKeyPress={(e) => e.key === 'Enter' && handleSearch()}
                  />
                </div>
                <button
                  onClick={handleSearch}
                  className="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 font-semibold"
                >
                  Ara
                </button>
              </div>

              {/* Filters */}
              <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select
                  value={filters.status}
                  onChange={(e) => handleFilterChange('status', e.target.value)}
                  className="px-4 py-3 border border-slate-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white"
                >
                  <option value="">Tüm Durumlar</option>
                  <option value="active">Aktif</option>
                  <option value="inactive">Pasif</option>
                  <option value="hired">İşe Alındı</option>
                  <option value="rejected">Reddedildi</option>
                </select>

                <input
                  type="text"
                  placeholder="Pozisyon"
                  value={filters.position || ''}
                  onChange={(e) => handleFilterChange('position', e.target.value)}
                  className="px-4 py-3 border border-slate-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white"
                />

                <input
                  type="number"
                  placeholder="Min deneyim (yıl)"
                  value={filters.experience_min || ''}
                  onChange={(e) => handleFilterChange('experience_min', e.target.value ? parseInt(e.target.value) : undefined)}
                  className="px-4 py-3 border border-slate-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white"
                />

                <input
                  type="number"
                  placeholder="Max deneyim (yıl)"
                  value={filters.experience_max || ''}
                  onChange={(e) => handleFilterChange('experience_max', e.target.value ? parseInt(e.target.value) : undefined)}
                  className="px-4 py-3 border border-slate-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white"
                />
              </div>
            </div>
          </div>
        </div>

        {/* Error Message */}
        {error && (
          <div className="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
            <div className="flex items-center">
              <svg className="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
              </svg>
              <span className="text-sm text-red-700 font-medium">{error}</span>
            </div>
          </div>
        )}

        {/* Candidates Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {candidates.map((candidate) => (
            <div
              key={candidate.id}
              className="bg-white rounded-3xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 group"
            >
              <div className="flex items-start justify-between mb-6">
                <div className="flex items-center space-x-4">
                  <div className="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <span className="text-white font-bold text-lg">
                      {candidate.first_name && candidate.last_name ? 
                        `${candidate.first_name[0]}${candidate.last_name[0]}`.toUpperCase() : '?'}
                    </span>
                  </div>
                  <div>
                    <h3 className="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors duration-200">
                      {candidate.first_name && candidate.last_name ? 
                        `${candidate.first_name} ${candidate.last_name}` : 'İsimsiz Aday'}
                    </h3>
                    <p className="text-sm text-slate-600">{candidate.email || 'E-posta belirtilmemiş'}</p>
                  </div>
                </div>
                <span className={`px-3 py-1 text-xs font-bold rounded-full border ${getStatusColor(candidate.status)}`}>
                  {getStatusText(candidate.status)}
                </span>
              </div>

              <div className="space-y-4">
                <div className="flex items-center">
                  <div className="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center mr-3">
                    <svg className="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6" />
                    </svg>
                  </div>
                  <span className="text-sm font-medium text-slate-700">{candidate.current_position || 'Pozisyon belirtilmemiş'}</span>
                </div>

                <div className="flex items-center">
                  <div className="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center mr-3">
                    <svg className="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <span className="text-sm font-medium text-slate-700">{candidate.experience_level || 'Deneyim belirtilmemiş'}</span>
                </div>

                {candidate.phone && (
                  <div className="flex items-center">
                    <div className="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center mr-3">
                      <svg className="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                    </div>
                    <span className="text-sm font-medium text-slate-700">{candidate.phone}</span>
                  </div>
                )}

                <div>
                  <span className="text-sm font-bold text-slate-700 mb-2 block">Yetenekler:</span>
                  <div className="flex flex-wrap gap-2">
                    {candidate.skills && candidate.skills.length > 0 ? (
                      <>
                        {candidate.skills.slice(0, 3).map((skill, index) => (
                          <span
                            key={index}
                            className="px-3 py-1 text-xs bg-gradient-to-r from-slate-100 to-slate-200 text-slate-700 rounded-full font-medium"
                          >
                            {skill}
                          </span>
                        ))}
                        {candidate.skills.length > 3 && (
                          <span className="px-3 py-1 text-xs bg-gradient-to-r from-slate-100 to-slate-200 text-slate-700 rounded-full font-medium">
                            +{candidate.skills.length - 3} daha
                          </span>
                        )}
                      </>
                    ) : (
                      <span className="px-3 py-1 text-xs bg-gradient-to-r from-slate-100 to-slate-200 text-slate-700 rounded-full font-medium">
                        Yetenek belirtilmemiş
                      </span>
                    )}
                  </div>
                </div>
              </div>

              <div className="mt-6 flex gap-3">
                <button className="flex-1 px-4 py-3 text-sm bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 font-semibold transform hover:scale-105">
                  Detayları Gör
                </button>
                <button className="px-4 py-3 text-sm bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500 transition-all duration-200 font-semibold">
                  Düzenle
                </button>
              </div>
            </div>
          ))}
        </div>

        {candidates.length === 0 && !loading && (
          <div className="text-center py-16">
            <div className="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
              <svg className="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <p className="text-slate-500 text-xl font-bold mb-2">Henüz aday bulunmuyor.</p>
            <p className="text-slate-400 text-sm">Yeni adaylar eklediğinizde burada görünecek.</p>
          </div>
        )}
      </div>
    </div>
  );
};

export default CandidateList; 