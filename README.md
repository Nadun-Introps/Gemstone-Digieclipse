
# Gemstone-Digieclipse

## 🧰 Prerequisites

Make sure the following are installed on your machine:

- [GitHub Desktop](https://desktop.github.com/)
- [Git](https://git-scm.com/)
- [XAMPP (PHP 8.2)](https://www.apachefriends.org/download.html) – for MySQL and PHP
- [Composer](https://getcomposer.org/) – for PHP dependency management
- [Node.js & npm](https://nodejs.org/) – for Node dependencies
- Code editor like [VS Code](https://code.visualstudio.com/)

---

## 🧪 Setting Up MySQL with XAMPP

1. Download and install **XAMPP (PHP 8.2)** from the [official website](https://www.apachefriends.org/download.html)
2. **Add PHP to your system PATH variable** (if not automatically done):
   - Search **"Environment Variables"** in Start Menu → open **"Edit the system environment variables"**
   - Click **Environment Variables**
   - Under **System Variables**, select `Path` → click **Edit**
   - Click **New** → Add this:
     ```
     C:\xampp\php
     ```
   - Click **OK** to close all windows
3. Open the **XAMPP Control Panel**
4. Start the **Apache** and **MySQL** modules
5. Open **phpMyAdmin** (usually at `http://localhost/phpmyadmin`)
6. Create a new database (e.g., `project_db`)

---

## ⚙️ Composer & PHP Path Setup (Windows)

1. **Install Composer** from the official website (Before this you should've installed XAMPP):  
   👉 [https://getcomposer.org/download/](https://getcomposer.org/download/)

2. During installation, make sure to **select the correct `php.exe` path** from XAMPP:  
   ```
   C:\xampp\php\php.exe
   ```

3. Open a new **Command Prompt** and verify:
   ```bash
   php -v
   composer -v
   ```

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

## ⚙️ Laravel (PHP) Setup

```bash
cd your-repo-name

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

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
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# Run database migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Serve the application
php artisan serve
```

Your Laravel app should now be running at:  
🔗 [http://localhost:8000](http://localhost:8000)

---

## ✅ Summary Checklist

- [x] Set up MySQL, PHP using XAMPP and add PHP to PATH (PHP 8.2)
- [x] Install Composer
- [x] Clone project with GitHub Desktop    
- [x] Install PHP and Node.js dependencies   
- [x] Configure `.env` file  
- [x] Run migrations and seed the database  
- [x] Start Laravel development server

---

## 🛠 Troubleshooting

- Ensure **MySQL is running** in XAMPP
- Use `php artisan config:clear` for `.env` issues
- Double-check `.env` DB credentials
- Use `npm run dev` if styles/scripts don't compile
