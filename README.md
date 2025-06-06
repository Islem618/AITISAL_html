AITISAL – Tailored Social Network

📌 Overview

AITISAL is a lightweight social networking platform designed for students.

It offers real-time messaging, friend recommendations, user profiles, interest-based suggestions, and more — all without intrusive ads.

This project was developed as part of the **Advanced Algorithms and Programming** course at ISEP.

---

 ⚙️ Requirements

[XAMPP](https://www.apachefriends.org/) (with **Apache** and **MySQL**)
 PHP ≥ 8.0
 A modern web browser (Chrome, Firefox, Edge...)

---

 🚀 How to Launch

1. Install XAMPP and start both:

   * Apache server
   * MySQL database

2. Place the project files inside the `htdocs` folder (e.g., `C:\xampp\htdocs\AITISAL`).

3. Import the database:

Open [phpMyAdmin](http://localhost/phpmyadmin)
Create a new database (e.g., `siteweb`)
Import the provided SQL dump (`siteweb.sql`) into it

4. Edit `db_connect.php` (if needed) to match your local database credentials:

   ```php
   $pdo = new PDO("mysql:host=localhost;dbname=siteweb", "root", "");
   ```

5. Launch the application:

 Open your browser and go to:
     [http://localhost/AITISAL](http://localhost/AITISAL)

---

✅ Notes

Make sure `uploads/` folders are writable for media and photos.

Use `Ctrl+F5` to refresh the browser cache if you don’t see new changes.
Default login credentials depend on your imported data.

