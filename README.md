# Gemstone-Digieclipse

## 🧰 Prerequisites

Make sure the following are installed on your machine:

- [GitHub Desktop](https://desktop.github.com/)
- [Git](https://git-scm.com/)
- [XAMPP (PHP 8.2)](https://www.apachefriends.org/download.html) – for MySQL and PHP
- [Composer](https://getcomposer.org/) – for PHP dependency management
- [Node.js & npm](https://nodejs.org/) – for node dependancies
- Code editor like [VS Code](https://code.visualstudio.com/)

---

## 📥 Clone the Project Using GitHub Desktop

1. Open **GitHub Desktop**
2. Go to **File > Clone Repository**
3. Paste the repository URL:

   ```
   https://github.com/Nadun-Introps/Gemstone-Digieclipse
   ```

4. Select the destination folder and click **Clone**

---

## 🧪 Setting Up MySQL with XAMPP

1. Download and install **XAMPP (PHP 8.2)** from the [official website](https://www.apachefriends.org/download.html)
2. Open the **XAMPP Control Panel**
3. Start the **Apache** and **MySQL** modules
4. Open **phpMyAdmin** (usually at `http://localhost/phpmyadmin`)
5. Create a new database (e.g., `project_db`)

---

## ⚙️ Laravel (PHP) Setup

```bash
cd your-repo-name

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

🔧 **Edit the `.env` file and configure database connection:**

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DB_NAME
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# Run database migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Install Node dependencies
npm install

# Serve the application
php artisan serve
```

Your Laravel app should now be running at [http://localhost:8000](http://localhost:8000)


## ✅ Summary Checklist

- [x] Clone project with GitHub Desktop  
- [x] Install PHP and Node.js dependencies  
- [x] Set up MySQL using XAMPP (PHP 8.2)  
- [x] Configure `.env` files  
- [x] Run migrations and seed the database  
- [x] Start servers for Laravel

---

## 🛠 Troubleshooting

- Make sure MySQL is running in XAMPP
- Use `php artisan config:clear` if Laravel shows `.env` issues
- Double-check database credentials
- Use `npm run dev` if needed

---
