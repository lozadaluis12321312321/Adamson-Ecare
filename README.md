# AdU eCare+: A Digital Healthcare Solution for the Adamson University Community

[![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.0.30-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mariadb.org)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Design Thinking](https://img.shields.io/badge/Methodology-Design%20Thinking-0284c7?style=for-the-badge)](https://adamson.edu.ph)

**AdU eCare+** is a comprehensive, responsive university healthcare management web application developed as a **Design Thinking initiative** for **Adamson University**. The platform modernizes clinic services delivery for students, faculty, and administrative staff, providing seamless digital access to consultations, appointments, medical certifications, health inquiries, and university announcements.

---

## 🌟 Key Features

### 🎨 Creative Aesthetic & Dark Mode
- **White and Light Blue Gradient Aesthetic**: Creative palette featuring soft blue hues, glassmorphic navigation, ambient lighting, and smooth responsive cards.
- **Persistent Dark Mode**: Toggle between crisp light mode and deep navy dark mode, with zero screen flash (FOUC) and persistent state stored in `localStorage`.

### 🏥 Clinical Services & Operating Schedule
- **Official Operating Hours**: **Monday to Saturday: 7:00 AM – 9:00 PM** (Sundays & Official Holidays: Closed).
- **Physical Facility**: Adamson University Health Services Office, Ground Floor, St. Therese Building, 900 San Marcelino St, Ermita, Manila.
- **Emergency Protocols**: Built-in emergency disclaimers clarifying portal triaging vs. emergency room escalation.

### 🦷 Dental Appointment Management
- Real-time slot availability checking preventing double bookings.
- Slot capacity enforcement and reservation locks.
- Complete booking lifecycle: *Pending &rarr; Approved &rarr; Rescheduled &rarr; Completed &rarr; Cancelled*.

### 📹 Teleconsultation Triaging
- Appointment scheduling across preferred shifts (7:30 AM to 8:30 PM).
- Case notes, triage assessment, and optional meeting links for external consultation services.

### 📜 Medical Certificate Requisitions
- Secure medical clearance requests for missed classes or sick leave validation.
- Supporting document uploads (medical prescriptions, clinical slips) saved in private storage.
- Administrative review workflow with unique certificate reference verification numbers.

### 💬 Health Inquiries & Community Support
- Interactive threaded messaging between Vincentian community members and university clinic doctors.
- Status tracking (*Submitted &rarr; In Progress &rarr; Resolved &rarr; Closed*).

### 📢 Campus Health Literacy & Announcements
- Public announcements feed with high-priority banners.
- Categorized health knowledge articles (Mental Well-Being, Dental Care, Hygiene, Nutrition).

### 🛡️ Role-Based Access Control & Security
- Strict multi-role separation: **Student**, **Faculty & Staff**, and **Health Administrator**.
- Administrative self-registration strictly prevented.
- Server-side validation, password hashing, and CSRF protection.
- Philippine Data Privacy Act of 2012 (RA 10173) compliance commitment.

---

## 👥 Seeded Test Credentials

| Role | Name | Email | Password |
|---|---|---|---|
| **Health Administrator** | Dr. Carmela Santos | `admin@adamson.edu.ph` | `Admin@AdU2026` |
| **Student** | Juan Dela Cruz | `student@adamson.edu.ph` | `Student@AdU2026` |
| **Faculty & Staff** | Prof. Roberto Reyes | `faculty@adamson.edu.ph` | `Faculty@AdU2026` |
| **Student (Alternative)** | Maria Clara | `maria.clara@adamson.edu.ph` | `Student@AdU2026` |

---

## 🛠️ Technology Stack

- **Backend**: PHP 8.0+, Laravel 9.x (Eloquent ORM, Middleware, Migrations, Seeders, Form Requests)
- **Database**: MySQL / MariaDB (via XAMPP)
- **Frontend**: Blade Components, Bootstrap 5.3, Bootstrap Icons, Vanilla JavaScript
- **Development Environment**: XAMPP on Windows (Apache & MySQL)

---

## 🚀 Installation & Local Setup

### 1. Prerequisites
- [XAMPP](https://www.apachefriends.org/) with PHP 8.0+ and MySQL / MariaDB running.
- [Composer](https://getcomposer.org/) installed globally.
- Git.

### 2. Clone the Repository
```bash
git clone https://github.com/lozadaluis12321312321/Adamson-Ecare.git
cd Adamson-Ecare
```

### 3. Install Dependencies
```bash
composer install
```

### 4. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Ensure your `.env` database configuration matches your local MySQL server:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adu_ecare
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Database Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```

### 6. Link Storage Directory
```bash
php artisan storage:link
```

### 7. Start the Development Server
```bash
php artisan serve
```
Open **[http://127.0.0.1:8000](http://127.0.0.1:8000)** in your browser.

---

## 🧪 Automated Testing

Execute the complete feature test suite verifying all 13 workflows and route safety:
```bash
php artisan test
```

---

## 📄 License & Attribution

Developed for the **Adamson University Vincentian Community** as an academic **Design Thinking** healthcare project.
All logos, institutional identities, and trademarks belong to **Adamson University**.
