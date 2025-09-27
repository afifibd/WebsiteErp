# Website ERP

Website ERP ini adalah project pribadi yang terdiri dari:
- **Backend** → Laravel 10 (folder `backend-erp`)
- **Frontend** → Vue 3 + Vite (folder `frontend-erp`)

## Cara Menjalankan
### Backend
```bash
cd backend-erp
composer install
php artisan migrate
php artisan serve

cd frontend-erp
npm install
npm run dev
