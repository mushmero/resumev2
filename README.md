# Mushmero Resume V2

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-%3E%3D%208.4-777BB4.svg?style=flat-square&logo=php&logoColor=white" alt="PHP Version"></a>
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11.x-FF2D20.svg?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 11"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square" alt="License"></a>
</p>

**Mushmero Resume V2** is a dynamic, self-hosted personal portfolio and interactive online resume platform. It allows professionals and software developers to maintain an up-to-date, public-facing digital CV while managing all underlying profile data, career history, visitor analytics, and system configurations through a comprehensive administrative dashboard.

---

## What This Application Contains

### 1. Public Digital Resume & Portfolio
- **Profile & Biography**: Clean personal introduction banner featuring a profile portrait, professional headline, summary bio, and quick contact details (email, phone, personal website).
- **Career & Work Experience**: Chronological work history displaying company names, roles, employment dates, and detailed bullet-point achievements.
- **Education & Credentials**: Academic qualifications mapped with educational levels, institutions, fields of study, and graduation years.
- **Skills Matrix**: Categorized technical and soft skills displayed alongside visual proficiency bars and custom technology badges.
- **Project Showcase**: Portfolio of personal and commercial projects, complete with project descriptions, tech stack indicators, and external links.
- **Languages & Fluency**: Spoken and written language fluencies with proficiency indicators.
- **Interests & Hobbies**: Personal interests section showcasing activities outside of professional work.
- **Social Media Hub**: Direct links to social networks, developer profiles (e.g., GitHub, LinkedIn, Twitter/X), and portfolio sites.

### 2. Administrative Control Center
- **Overview Dashboard**: High-level summary metrics showing counts for all career records (educations, skills, projects, experiences, languages, socials).
- **Profile Snapshot Widget**: Live preview card showing the active profile image, headline, total accumulated years of experience, and featured skill tags.
- **Interactive Visitor Map**: Global analytics visualization tracking unique site visitors plotted across an interactive SVG world map by country.
- **Daily Inspiration**: Curated wisdom quote banner integrated into the admin landing experience.

### 3. Record Management Modules
- **Multi-Profile Management**: Maintain multiple candidate profiles with the ability to activate, switch, or archive profiles dynamically.
- **Experience Manager**: Add, edit, reorder, and document employment history.
- **Education Manager**: Organize degrees, certificates, and academic institutions.
- **Project Catalog**: Manage portfolio items, descriptions, and deliverables.
- **Skillset & Proficiency Manager**: Define technical proficiencies and align them with custom mastery tiers.
- **Language & Interest Records**: Keep foreign languages and personal pursuits updated.
- **Social Link Directory**: Configure active social media links with associated branding icons.

### 4. System Settings & Customization
- **Education Levels Lookup**: Configure custom educational tiers (e.g., High School, Diploma, Bachelor's, Master's, Doctorate).
- **Proficiency Levels Lookup**: Set up standardized grading scales (e.g., Beginner, Intermediate, Advanced, Expert).
- **Status Lists**: Custom publication and visibility statuses for records.
- **Icon Library & Bulk Import**: Manage FontAwesome and custom icon badges, including bulk JSON icon import functionality.
- **Media & Attachment Storage**: Centralized file storage for avatar uploads, resume documents, and project media.
- **Access Control**: Role and permission management for administrative accounts.

---

## System Requirements

- **PHP**: `^8.4` (extensions: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`, `bcmath`)
- **Composer**: `^2.2`
- **Database**: MySQL `>= 8.0` / MariaDB `>= 10.5` (or SQLite 3 for local development/testing)
- **Web Server**: Nginx, Apache, or Laravel Octane / Herd / Valet

---

## Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/mushmero/resumev2.git
cd resumev2
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy the example environment configuration:

```bash
cp .env.example .env
```

Generate the application encryption key:

```bash
php artisan key:generate
```

Configure your database connection parameters in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=resumev2
DB_USERNAME=root
DB_PASSWORD=secret
```

### 4. Database Migrations & Seeders

Run database migrations to generate all application tables and seed initial lookups and roles:

```bash
php artisan migrate --seed
```

### 5. Create Storage Symlink

Link the application storage folder to the public web root for user uploads:

```bash
php artisan storage:link
```

### 6. Run the Local Development Server

```bash
php artisan serve
```

Access the public portfolio at `http://127.0.0.1:8000` or navigate to `/login` to access the administrative dashboard.

---

## Testing & Code Quality

Run automated unit and feature tests:

```bash
php artisan test
```

Format code style using Laravel Pint:

```bash
vendor/bin/pint
```

---

## License

This project is open-source software licensed under the [MIT license](LICENSE).
