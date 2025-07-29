// User types
export interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  company?: string;
  created_at: string;
  updated_at: string;
}

export interface LoginRequest {
  email: string;
  password: string;
}

export interface RegisterRequest {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  role?: string;
  company?: string;
}

export interface AuthResponse {
  user: User;
  token: string;
  message: string;
}

// Candidate types
export interface Candidate {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  phone?: string;
  summary?: string;
  experience_level?: string;
  current_position?: string;
  current_company?: string;
  expected_salary?: number;
  location?: string;
  linkedin_url?: string;
  github_url?: string;
  portfolio_url?: string;
  skills?: string[];
  education?: any[];
  experience?: any[];
  status: 'active' | 'inactive' | 'hired' | 'rejected';
  company_id?: number;
  created_by?: number;
  created_at: string;
  updated_at: string;
}

export interface CreateCandidateRequest {
  first_name: string;
  last_name: string;
  email: string;
  phone?: string;
  summary?: string;
  experience_level?: string;
  current_position?: string;
  current_company?: string;
  expected_salary?: number;
  location?: string;
  linkedin_url?: string;
  github_url?: string;
  portfolio_url?: string;
  skills?: string[];
  education?: any[];
  experience?: any[];
  status?: 'active' | 'inactive' | 'hired' | 'rejected';
}

export interface UpdateCandidateRequest extends Partial<CreateCandidateRequest> {
  status?: 'active' | 'inactive' | 'hired' | 'rejected';
}

export interface CandidateSearchParams {
  q?: string;
  status?: string;
  position?: string;
  experience_min?: number;
  experience_max?: number;
  skills?: string[];
  page?: number;
  per_page?: number;
}

export interface CandidatesResponse {
  data: Candidate[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

// Notification types
export interface Notification {
  id: number;
  type: string;
  channel: string;
  recipient_type: string;
  recipient_id: number;
  subject: string;
  content: string;
  status: string;
  sent_at?: string;
  read_at?: string;
  created_at: string;
  updated_at: string;
}

export interface NotificationsResponse {
  data: Notification[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

// API Response types
export interface ApiResponse<T> {
  data: T;
  message?: string;
  success: boolean;
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
} 