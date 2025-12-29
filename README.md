# 🎓 Noqta - School Management System

A complete school management system built with Laravel 12, Tailwind CSS, and Alpine.js.

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
- Node.js >= 18 & NPM
- PostgreSQL >= 13

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/abrghaze/Noqta.git
cd Noqta
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node.js dependencies

```bash
npm install
```

### 4. Configure environment

```bash
cp .env.example .env
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Configure PostgreSQL Database

Edit `.env` and configure your database credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gestion_notes_absence
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Create the database in PostgreSQL (using psql or pgAdmin):

```sql
CREATE DATABASE gestion_notes_absence;
```

### 7. Run migrations and seed database

```bash
php artisan migrate --seed
```

This will create all tables and populate them with test data.

### 8. Create storage link

```bash
php artisan storage:link
```

### 9. Build frontend assets

```bash
npm run build
```

### 10. Start the server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

## 👤 Test Accounts

After running seeders, you can log in with:

| Role | Email | Password |
|------|-------|----------|
| **Director** | directeur@school.com | password |
| **Teacher** | jean.dupont@school.com | password |
| **Teacher** | marie.martin@school.com | password |
| **Student** | etudiant1@school.com | password |
| **Parent** | parent1@school.com | password |

## 🛠️ Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Database**: PostgreSQL
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Build Tool**: Vite
- **Icons**: Heroicons

## 📱 Features by Role

### 👔 Director
- View global statistics and charts
- Manage all users, classes, and subjects
- View all grades and attendance records
- Generate reports

### 👨‍🏫 Teacher
- Add and edit grades for their subjects
- Mark attendance for their classes
- View their students' statistics
- Manage their class data

### 🎓 Student
- View their grades and attendance
- Receive real-time notifications
- Track their academic performance
- View their class schedule

### 👨‍👩‍👧 Parent
- Monitor their child's grades
- View attendance records
- Receive notifications about grades and absences
- Contact teachers

## 🔔 Notification System

The app features an automatic notification system:
- ✅ Students and parents are notified when grades are added/modified
- ✅ Notifications for absences and tardiness
- ✅ Real-time badge showing unread notifications
- ✅ Notification center with full history

## 🔐 Security

- Secure authentication with hashed passwords (bcrypt)
- CSRF protection on all forms
- Role-based middleware for access control
- Server-side validation on all inputs

## 📝 License

This project is open-source and available under the [MIT License](LICENSE).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📞 Support

For issues or questions, please open an issue on GitHub.

---

**© 2025 Noqta - School Management System**
