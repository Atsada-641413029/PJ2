# Construction Materials Management System

## 🚀 Quick Start with Docker

### Prerequisites
- Docker Desktop installed
- Docker Compose installed

### Starting the Application

1. **Start all services:**
```bash
docker-compose up -d
```

2. **Access the application:**
- **Website**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081

3. **Stop all services:**
```bash
docker-compose down
```

### Default Credentials

#### Application Login
- **Admin**: admin@constructionmart.com / password
- **Seller**: seller@example.com / password
- **Customer**: customer@example.com / password

#### Database
- **Host**: localhost:3306
- **Database**: construction_mart
- **Username**: construction_user
- **Password**: construction_pass
- **Root Password**: root_password

#### phpMyAdmin
- **Server**: mysql
- **Username**: root
- **Password**: root_password

## 📁 Project Structure

```
PJ2/
├── docker-compose.yml          # Docker services configuration
├── nginx/
│   └── default.conf           # Nginx web server configuration
├── database/
│   └── init.sql              # Database initialization script
├── src/                      # Application source code
│   ├── index.php            # Landing page
│   ├── login.php            # Login page
│   ├── register.php         # Registration page
│   └── assets/              # Static assets
│       ├── css/
│       │   ├── style.css    # Main design system
│       │   └── auth.css     # Authentication styles
│       └── js/
│           └── auth.js      # Authentication utilities
├── spec-dev.md              # Development specification
└── README.md               # This file
```

## 🐳 Docker Services

### 1. Nginx (Web Server)
- **Port**: 8080
- **Purpose**: Serves PHP application
- **Image**: nginx:alpine

### 2. PHP-FPM
- **Version**: PHP 8.2
- **Purpose**: Processes PHP files
- **Image**: php:8.2-fpm

### 3. MySQL
- **Port**: 3306
- **Version**: MySQL 8.0
- **Purpose**: Database server
- **Image**: mysql:8.0

### 4. phpMyAdmin
- **Port**: 8081
- **Purpose**: Database management interface
- **Image**: phpmyadmin:latest

## 🛠️ Development Commands

### View logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f nginx
docker-compose logs -f php
docker-compose logs -f mysql
```

### Restart services
```bash
# All services
docker-compose restart

# Specific service
docker-compose restart nginx
```

### Access container shell
```bash
# PHP container
docker exec -it construction_mart_php sh

# MySQL container
docker exec -it construction_mart_mysql bash
```

### Database operations
```bash
# Import SQL file
docker exec -i construction_mart_mysql mysql -uroot -proot_password construction_mart < database/backup.sql

# Export database
docker exec construction_mart_mysql mysqldump -uroot -proot_password construction_mart > database/backup.sql
```

## 📝 Development Phases

### ✅ Phase 1: UI/Frontend - Authentication (Completed)
- [x] Design system (CSS)
- [x] Login page
- [x] Register page
- [x] Landing page

### 🔄 Phase 2: UI/Frontend - Admin Panel (In Progress)
- [ ] Admin dashboard
- [ ] Shop approval interface
- [ ] User management

### 📋 Phase 3: UI/Frontend - Seller Panel
- [ ] Seller dashboard
- [ ] Product management
- [ ] Order management

### 🛒 Phase 4: UI/Frontend - Customer Panel
- [ ] Product search
- [ ] Shopping cart
- [ ] Checkout

### 🔌 Phase 5: Backend Integration
- [ ] Database connection
- [ ] Authentication system
- [ ] CRUD operations

### 🤖 Phase 6: AI Integration
- [ ] Google Gemini API
- [ ] Price comparison
- [ ] Recommendation engine

## 🔧 Troubleshooting

### Port already in use
```bash
# Change ports in docker-compose.yml
ports:
  - "8090:80"  # Change 8080 to 8090
```

### Permission issues
```bash
# On Linux/Mac, fix permissions
sudo chown -R $USER:$USER src/
```

### Database connection issues
```bash
# Restart MySQL container
docker-compose restart mysql

# Check MySQL logs
docker-compose logs mysql
```

## 📚 Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Nginx Documentation](https://nginx.org/en/docs/)

## 👥 Team

- Development: Construction Mart Team
- AI Integration: Google Gemini 2.5 Flash

## 📄 License

This project is for educational purposes.
