# 🎓 Noqta - School Management System

A complete school management system built with Laravel 11, PostgreSQL, Tailwind CSS, and Alpine.js.

## 🚀 Features

- **User Management** - 4 user roles: Director, Teacher, Student, Parent
- **Grade Management** - Teachers add/edit grades, students view their grades
- **Attendance Tracking** - Mark and track student attendance/absences
- **Real-time Notifications** - Automatic notifications for grade changes and absences
- **Custom Dashboards** - Personalized dashboard for each user role
- **Modern UI** - Responsive interface with Tailwind CSS and Alpine.js

## 📋 Requirements

- PHP >= 8.2
- Composer
- PostgreSQL >= 13
- Node.js & NPM

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/yourusername/noqta-gestion-scolaire.git
cd noqta-gestion-scolaire
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure environment

```bash
cp .env.example .env
```

Edit `.env` and configure your PostgreSQL database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gestion_notes_absence
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 4. Create database

```bash
# Connect to PostgreSQL
psql -U postgres

# Create database
CREATE DATABASE gestion_notes_absence;

# Exit
\q
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Run migrations and seeders

```bash
php artisan migrate --seed
```

This will create all tables and populate them with test data.

### 7. Create storage link

```bash
php artisan storage:link
```

### 8. Build assets

```bash
npm run build
```

### 9. Start the server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 👤 Test Accounts

After running seeders, you can log in with:

| Role | Email | Password |
|------|-------|----------|
| Director | directeur@school.com | password |
| Teacher | enseignant1@school.com | password |
| Student | etudiant1@school.com | password |
| Parent | parent1@school.com | password |

## 🛠️ Tech Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Database**: PostgreSQL
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Charts**: Chart.js
- **Icons**: Font Awesome

## 📱 Main Features

### For Directors
- View global statistics and charts
- Manage all users, classes, and subjects
- Access to all data

### For Teachers
- Add and edit grades for their subjects
- Mark attendance for their classes
- View their class statistics

### For Students
- View their grades and attendance
- Receive real-time notifications
- Track their academic performance

### For Parents
- Monitor their child's grades
- View attendance records
- Receive notifications about important events

## 🔔 Notification System

The app features an automatic notification system:
- Students and parents are notified when grades are added/modified
- Notifications for absences and tardiness
- Real-time badge showing unread notifications
- Notification center with full history

## 🔐 Security

- Secure authentication with hashed passwords
- CSRF protection on all forms
- Role-based middleware for access control
- Server-side validation

## 📝 License

This project is open-source and available under the [MIT License](LICENSE).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📞 Support

For issues or questions, please open an issue on GitHub.

---

**© 2025 Noqta - School Management System**
