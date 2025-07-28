# 🧠 PeopleBox ATS v2

Modern ATS (Applicant Tracking System) - Clean Architecture + Microservices

## 🏗️ Proje Yapısı

```
/peoplebox-v2/
├── apps/
│   ├── frontend/                 → React App
│   ├── api-gateway/             → Laravel API giriş noktası
│   ├── services/
│   │   ├── auth/                → Auth microservice (Laravel)
│   │   ├── candidate/           → Aday işlemleri servisi (Laravel)
│   │   ├── notification/        → Bildirimler ve Queue servisi
│   │   └── ai/                  → OpenAI destekli analiz servisi (ops.)
├── packages/
│   └── shared-types/            → Ortak TypeScript tipleri ve modeller
├── docker/                      → Ortamlar için docker-compose
├── .env                         → Ortak yapılandırma
└── README.md
```

## 🚀 Geliştirme Aşamaları

- [x] **Aşama 1:** Auth mikroservisini oluştur, token üretimi
- [x] **Aşama 2:** Candidate CRUD + Meilisearch entegrasyonu
- [ ] **Aşama 3:** Queue servisinde e-posta + log sistemi
- [ ] **Aşama 4:** Frontend bileşenlerini oluştur: Login, CandidateList
- [ ] **Aşama 5:** .env ile servis bağımlılıklarını tanımla
- [ ] **Aşama 6:** Test ve docker-compose local ortamları entegre et
- [ ] **Aşama 7:** AI servisi ile CV sıralama önerisi ekle

## 🛠️ Teknolojiler

- **Frontend:** React + Vite + TypeScript + Tailwind CSS
- **Backend:** Laravel (Mikroservisler)
- **Database:** PostgreSQL
- **Search:** Meilisearch + Laravel Scout
- **Queue:** Redis + Laravel Queue
- **AI:** OpenAI API (opsiyonel)

## 📦 Kurulum

```bash
# Proje klasörüne git
cd peoplebox-v2

# Auth servisini başlat
cd apps/services/auth
composer install
php artisan migrate
php artisan db:seed
php artisan serve --port=8002

# Candidate servisini başlat
cd apps/services/candidate
composer install
php artisan migrate
php artisan db:seed
php artisan serve --port=8003

# API Gateway'i başlat
cd apps/api-gateway
composer install
php artisan serve --port=8000
```

## 🔧 Environment

`.env` dosyasını kopyalayın ve gerekli değerleri doldurun:

```bash
cp .env.example .env
``` 