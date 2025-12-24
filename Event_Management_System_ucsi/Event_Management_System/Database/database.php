<?php
$host = 'localhost';
$port = '3306';
$dbname = 'icsdi_event_management';
$user = 'root';
$password = '';

try {

    // Step 1: Connect to MySQL server (no DB)
    $dsnNoDB = "mysql:host=$host;port=$port";
    $pdo = new PDO($dsnNoDB, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Step 2: Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");

    // Step 3: Connect to the new database
    $dsn = "mysql:host=$host;dbname=$dbname;port=$port";
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* ============================================================
       USERS TABLE  (Combined version)
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        user_type ENUM('admin','user') NOT NULL,
        affiliation VARCHAR(255),
        country VARCHAR(100),
        phone VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    /* ============================================================
       EVENTS TABLE
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS events (
        event_id INT AUTO_INCREMENT PRIMARY KEY,
        event_type ENUM('conference','competition','webinar','workshop') NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        start_date DATETIME NOT NULL,
        end_date DATETIME NOT NULL,
        venue VARCHAR(255),
        max_attendees INT,
        registration_fee DECIMAL(10,2) DEFAULT 0.00,
        currency VARCHAR(3) DEFAULT 'USD',
        status ENUM('draft','published','ongoing','completed','cancelled') DEFAULT 'draft',
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(user_id)
    ) ENGINE=InnoDB");

    /* ============================================================
       SESSIONS TABLE
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS sessions (
        session_id INT AUTO_INCREMENT PRIMARY KEY,
        event_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        session_type ENUM('keynote','technical','workshop','panel','poster') NOT NULL,
        start_time DATETIME NOT NULL,
        end_time DATETIME NOT NULL,
        room VARCHAR(100),
        chair_id INT,
        max_capacity INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (event_id) REFERENCES events(event_id),
        FOREIGN KEY (chair_id) REFERENCES users(user_id)
    ) ENGINE=InnoDB");

    /* ============================================================
       PAPERS TABLE (Merged)
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS papers (
        paper_id INT AUTO_INCREMENT PRIMARY KEY,
        event_id INT,
        title VARCHAR(500) NOT NULL,
        abstract TEXT,
        keywords VARCHAR(255),
        track ENUM('sustainable_development','renewable_energy','environment','technology','other'),
        file_path VARCHAR(500),
        author_id INT,
        corresponding_author_id INT,
        submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('submitted','under_review','accepted','rejected','needs_revision') DEFAULT 'submitted',
        FOREIGN KEY (event_id) REFERENCES events(event_id),
        FOREIGN KEY (author_id) REFERENCES users(user_id),
        FOREIGN KEY (corresponding_author_id) REFERENCES users(user_id)
    ) ENGINE=InnoDB");

    /* ============================================================
       PAPER AUTHORS TABLE
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS paper_authors (
        paper_author_id INT AUTO_INCREMENT PRIMARY KEY,
        paper_id INT NOT NULL,
        author_id INT NOT NULL,
        author_order INT NOT NULL,
        is_presenting BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (paper_id) REFERENCES papers(paper_id),
        FOREIGN KEY (author_id) REFERENCES users(user_id),
        UNIQUE KEY unique_paper_author (paper_id, author_id)
    ) ENGINE=InnoDB");

    /* ============================================================
       REGISTRATIONS TABLE
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS registrations (
        registration_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        event_id INT NOT NULL,
        registration_type ENUM('attendee','author','student','speaker') NOT NULL,
        payment_status ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
        payment_amount DECIMAL(10,2),
        payment_currency VARCHAR(3) DEFAULT 'USD',
        transaction_id VARCHAR(255),
        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        checked_in BOOLEAN DEFAULT FALSE,
        check_in_time DATETIME,
        FOREIGN KEY (user_id) REFERENCES users(user_id),
        FOREIGN KEY (event_id) REFERENCES events(event_id),
        UNIQUE KEY unique_user_event (user_id, event_id)
    ) ENGINE=InnoDB");

    /* ============================================================
       SESSION REGISTRATIONS TABLE
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS session_registrations (
        session_reg_id INT AUTO_INCREMENT PRIMARY KEY,
        registration_id INT NOT NULL,
        session_id INT NOT NULL,
        registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        attended BOOLEAN DEFAULT FALSE,
        attendance_time DATETIME,
        FOREIGN KEY (registration_id) REFERENCES registrations(registration_id),
        FOREIGN KEY (session_id) REFERENCES sessions(session_id),
        UNIQUE KEY unique_reg_session (registration_id, session_id)
    ) ENGINE=InnoDB");

    /* ============================================================
       REVIEWS TABLE (merged + extended)
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS reviews (
        review_id INT AUTO_INCREMENT PRIMARY KEY,
        paper_id INT NOT NULL,
        reviewer_id INT NOT NULL,
        score_originality INT,
        score_technical INT,
        score_clarity INT,
        score_significance INT,
        overall_score DECIMAL(3,2),
        comments_to_author TEXT,
        comments_to_chair TEXT,
        review_status ENUM('assigned','in_progress','completed') DEFAULT 'assigned',
        assigned_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        completed_date DATETIME,
        FOREIGN KEY (paper_id) REFERENCES papers(paper_id),
        FOREIGN KEY (reviewer_id) REFERENCES users(user_id),
        UNIQUE KEY unique_paper_reviewer (paper_id, reviewer_id)
    ) ENGINE=InnoDB");

    /* ============================================================
       CONTACT US TABLE
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS contact_us (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    /* ============================================================
       NOTIFICATIONS TABLE
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS notifications (
        notification_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        event_id INT,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        notification_type ENUM('system','event','paper','payment') NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id),
        FOREIGN KEY (event_id) REFERENCES events(event_id)
    ) ENGINE=InnoDB");

    /* ============================================================
       SETTINGS TABLE (from version 1)
    ============================================================ */
    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        setting_id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        setting_type ENUM('general','email','system') DEFAULT 'general',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    /* ============================================================
       INSERT DEFAULT ADMIN (only if none exists)
    ============================================================ */
    $checkAdmin = $db->query("SELECT COUNT(*) FROM users WHERE user_type='admin'")->fetchColumn();
    if ($checkAdmin == 0) {
        $db->prepare("INSERT INTO users (email, password, first_name, last_name, user_type, affiliation) 
                      VALUES (?, ?, ?, ?, 'admin', 'ICSDI Conference')")
           ->execute(['admin@icsdi.com', password_hash('admin123', PASSWORD_DEFAULT), 'System', 'Administrator']);
    }

    /* ============================================================
       INSERT DEFAULT SETTINGS
    ============================================================ */
    $defaultSettings = [
        ['conference_name', 'International Conference on Software Development and Innovation', 'general'],
        ['conference_year', '2025', 'general'],
        ['contact_email', 'info@icsdi.com', 'general'],
        ['smtp_host', 'smtp.gmail.com', 'email'],
        ['smtp_port', '587', 'email'],
        ['smtp_username', 'your-email@gmail.com', 'email'],
        ['smtp_password', '', 'email']
    ];

    foreach ($defaultSettings as $setting) {
        $check = $db->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $check->execute([$setting[0]]);
        if ($check->fetchColumn() == 0) {
            $db->prepare("INSERT INTO settings (setting_key, setting_value, setting_type) VALUES (?, ?, ?)")
               ->execute($setting);
        }
    }

    return $db;

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
