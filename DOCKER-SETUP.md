# Panduan Setup Docker untuk Aplikasi Kasir Koperasi

## Prasyarat
- Docker Desktop terinstall
- Docker Compose terinstall
- Git (opsional)

## Langkah-langkah Setup

### 1. Persiapan Environment
```bash
# Copy file environment untuk Docker
copy .env.docker .env

# Generate application key
docker-compose run --rm app php artisan key:generate
```

### 2. Build dan Jalankan Container
```bash
# Build dan jalankan semua service
docker-compose up -d --build

# Atau jalankan tanpa build ulang
docker-compose up -d
```

### 3. Setup Database
```bash
# Jalankan migrasi database
docker-compose exec app php artisan migrate

# Jalankan seeder (opsional)
docker-compose exec app php artisan db:seed
```

### 4. Set Permission (jika diperlukan)
```bash
# Set permission untuk storage dan cache
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
```

### 5. Akses Aplikasi
- **Aplikasi Utama**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
- **Database**: localhost:3306

## Perintah Docker Berguna

### Melihat Status Container
```bash
docker-compose ps
```

### Melihat Log
```bash
# Log semua service
docker-compose logs

# Log service tertentu
docker-compose logs app
docker-compose logs db
```

### Masuk ke Container
```bash
# Masuk ke container aplikasi
docker-compose exec app bash

# Masuk ke container database
docker-compose exec db mysql -u kasir_user -p kasir
```

### Menjalankan Artisan Command
```bash
# Clear cache
docker-compose exec app php artisan cache:clear

# Clear config
docker-compose exec app php artisan config:clear

# Clear view
docker-compose exec app php artisan view:clear
```

### Stop dan Remove Container
```bash
# Stop semua container
docker-compose down

# Stop dan hapus volume
docker-compose down -v
```

## Troubleshooting

### Permission Error
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### Database Connection Error
1. Pastikan container database sudah running
2. Periksa konfigurasi database di file `.env`
3. Restart container jika diperlukan

### Port Already in Use
```bash
# Ubah port di docker-compose.yml jika port 8000 sudah digunakan
ports:
  - "8001:80"  # Ganti 8000 ke 8001
```

## Konfigurasi Database

### Kredensial Default
- **Database**: kasir
- **Username**: kasir_user
- **Password**: kasir_password
- **Root Password**: root_password

### Backup Database
```bash
# Backup
docker-compose exec db mysqldump -u kasir_user -p kasir > backup.sql

# Restore
docker-compose exec -T db mysql -u kasir_user -p kasir < backup.sql
```

## Development Mode

Untuk development, ubah di `docker-compose.yml`:
```yaml
environment:
  - APP_ENV=local
  - APP_DEBUG=true
```

## Production Deployment

1. Set `APP_DEBUG=false` di `.env`
2. Set `APP_ENV=production` di `.env`
3. Generate APP_KEY yang kuat
4. Gunakan database password yang kuat
5. Setup SSL/HTTPS jika diperlukan