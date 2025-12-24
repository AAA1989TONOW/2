<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_id']);
$user_role = $_SESSION['user_type'] ?? 'guest';
$user_name = $_SESSION['user_name'] ?? 'Guest';
?>
<style>
/* ============================================================
   PREMIUM ULTRA-MODERN NAVBAR THEME 2025+
   Clean • Smooth • Professional • Luxury UI
   ============================================================ */

:root {
    --nav-bg: rgba(20, 20, 20, 0.55);
    --nav-hover-bg: rgba(255, 255, 255, 0.08);
    --text-light: #e5e5e5;
    --primary: #0dcaf0;
    --transition: all .28s ease;
}

.navbar {
    background: var(--nav-bg) !important;
    backdrop-filter: blur(22px) saturate(160%);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    padding: 0.6rem 0 !important;
    transition: var(--transition);
}

.navbar.scrolled {
    background: rgba(15, 15, 15, 0.9) !important;
    padding: .4rem 0 !important;
}

.navbar-brand {
    font-size: 1.35rem;
    font-weight: 700;
    letter-spacing: .6px;
    color: #fff !important;
    text-transform: uppercase;
    display: flex;
    align-items: center;
}

.navbar-brand i {
    color: var(--primary);
    font-size: 1.2rem;
}

.navbar-nav .nav-link {
    font-size: 1rem;
    padding: 0.75rem 1.15rem !important;
    margin: 0 .15rem;
    color: var(--text-light) !important;
    font-weight: 500;
    transition: var(--transition);
    border-radius: 10px;
}

.navbar-nav .nav-link:hover {
    background: var(--nav-hover-bg);
    color: var(--primary) !important;
    transform: translateY(-1px);
}

.dropdown-menu {
    background: rgba(28, 28, 28, 0.93);
    backdrop-filter: blur(16px);
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,0.06);
    padding: 12px 0;
    animation: fadeIn .18s ease-out;
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(10px);}
    to {opacity: 1; transform: translateY(0);}
}

.dropdown-item {
    padding: 12px 20px;
    font-size: .96rem;
    color: #e8e8e8;
    transition: var(--transition);
}

.dropdown-item:hover {
    color: var(--primary) !important;
    background: rgba(255,255,255,0.07);
}

.nav-badge {
    font-size: 0.65rem;
    padding: 4px 7px;
    border-radius: 50%;
    top: -6px;
    right: -5px;
    position: absolute;
}

.search-box input {
    background: rgba(255,255,255,0.1) !important;
    border: none !important;
    color: #fff;
    border-radius: 10px 0 0 10px !important;
}

.search-box button {
    background: rgba(255,255,255,0.15) !important;
    border-radius: 0 10px 10px 0 !important;
    border: none !important;
    color: #fff;
    transition: var(--transition);
}

.search-box button:hover {
    background: rgba(255,255,255,0.25) !important;
}

.mobile-nav {
    background: rgba(15,15,15,0.95);
    backdrop-filter: blur(12px);
    border-top: 1px solid rgba(255,255,255,0.08);
}

.mobile-nav a {
    color: #ccc;
    font-size: .82rem;
    transition: .25s;
}

.mobile-nav i {
    font-size: 1.1rem;
}

.mobile-nav a:hover {
    color: var(--primary);
    transform: translateY(-3px);
}

.navbar-nav i {
    font-size: .92rem;
}
</style>

<script>
window.addEventListener("scroll", () => {
    const nav = document.querySelector(".navbar");
    nav.classList.toggle("scrolled", window.scrollY > 20);
});
</script>

<!-- ======================= NAVBAR ======================== -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm modern-nav">
    <div class="container-fluid">

        <a class="navbar-brand" href="index.php">
            <i class="fas fa-globe"></i>&nbsp; ICSDI 2026
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="mainNav" class="collapse navbar-collapse">

            <!-- LEFT MENU -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item"><a class="nav-link" href="index.php">
                    <i class="fas fa-home me-1"></i> Home</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar-week me-1"></i> Events
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="services.php?type=conference"><i class="fas fa-users me-2"></i> Conference</a></li>
                        <li><a class="dropdown-item" href="services.php?type=competition"><i class="fas fa-trophy me-2"></i> Competition</a></li>
                        <li><a class="dropdown-item" href="services.php?type=webinar"><i class="fas fa-chalkboard-teacher me-2"></i> Webinar</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="blogs.php"><i class="fas fa-newspaper me-1"></i> News</a></li>
                <li class="nav-item"><a class="nav-link" href="about_us.php"><i class="fas fa-info-circle me-1"></i> About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact_us.php"><i class="fas fa-envelope me-1"></i> Contact</a></li>

            </ul>

            <!-- RIGHT USER MENU -->
            <ul class="navbar-nav ms-auto">

                <!-- 🔵 SYSTEM USER MENU (Replaced Login/Register) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-user-cog me-2"></i> System User
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        
                        <?php if (!$is_logged_in): ?>
                            <li><a class="dropdown-item" href="login.php?action=login"><i class="fas fa-sign-in-alt me-2"></i> Login</a></li>
                            <li><a class="dropdown-item" href="register.php?action=register"><i class="fas fa-user-plus me-2"></i> Register</a></li>

                        <?php else: ?>

                            <li><a class="dropdown-item" href="System_user.php"><i class="fas fa-dashboard me-2"></i> Dashboard</a></li>

                            <?php if ($user_role == 'admin'): ?>
                                <li><a class="dropdown-item" href="../Admin_Home/my_admin.php"><i class="fas fa-tools me-2"></i> Admin Panel</a></li>
                            <?php endif; ?>

                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>

                        <?php endif; ?>
                    </ul>
                </li>

            </ul>

            <!-- SEARCH -->
            <form class="d-flex ms-3 search-box">
                <div class="input-group">
                    <input class="form-control" placeholder="Search...">
                    <button class="btn"><i class="fas fa-search"></i></button>
                </div>
            </form>

        </div>
    </div>
</nav>

<!-- MOBILE NAVIGATION -->
<div class="d-lg-none fixed-bottom mobile-nav py-2">
    <div class="container text-center">
        <div class="row">

            <div class="col"><a href="index.php"><i class="fas fa-home d-block"></i><small>Home</small></a></div>
            <div class="col"><a href="services.php"><i class="fas fa-calendar-week d-block"></i><small>Events</small></a></div>
            <div class="col"><a href="System_user.php"><i class="fas fa-user d-block"></i><small>User</small></a></div>
            <div class="col"><a href="contact_us.php"><i class="fas fa-envelope d-block"></i><small>Contact</small></a></div>

        </div>
    </div>
</div>
