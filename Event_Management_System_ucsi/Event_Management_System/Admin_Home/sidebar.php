<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_name = $_SESSION['user_name'] ?? 'Admin';

// Get the current script name for active menu highlighting
$current_script = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar bg-dark text-white vh-100 position-fixed shadow" style="width: 250px; transition: all 0.3s;">
    <div class="sidebar-header p-3 d-flex justify-content-between align-items-center border-bottom border-secondary">
        <div class="d-flex align-items-center">
            <div class="logo-icon me-2">
                <i class="fas fa-cog"></i>
            </div>
            <h3 class="m-0 fs-5 fw-bold">Admin Panel</h3>
        </div>
        <button id="sidebarToggle" class="btn btn-outline-light btn-sm d-md-none"><i class="fas fa-bars"></i></button>
    </div>

    <!-- User Profile Section -->
    <div class="user-profile p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center">
            <div class="user-avatar me-3">
                <div class="avatar-circle bg-primary d-flex align-items-center justify-content-center">
                    <span class="fw-bold"><?php echo strtoupper(substr($user_name, 0, 1)); ?></span>
                </div>
            </div>
            <div class="user-info">
                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($user_name); ?></h6>
                <small class="text-muted">Administrator</small>
            </div>
        </div>
    </div>

    <ul class="list-unstyled sidebar-menu p-0 m-0">

        <li>
            <a class="d-flex align-items-center p-3 text-white menu-item <?php echo ($current_script == 'my_admin.php') ? 'active' : ''; ?>" href="my_admin.php">
                <div class="menu-icon"><i class="fas fa-tachometer-alt"></i></div>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>
        <!-- Events -->
        <li class="menu-group <?php echo (in_array($current_script, ['all_event.php', 'create_event.php', 'type_event.php'])) ? 'active' : ''; ?>">
            <a href="#" class="d-flex align-items-center p-3 text-white submenu-btn menu-item">
                <div class="menu-icon"><i class="fas fa-calendar-alt"></i></div>
                <span class="menu-text flex-grow-1">Events</span>
                <div class="menu-arrow"><i class="fas fa-chevron-down"></i></div>
            </a>
            <ul class="submenu list-unstyled">
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'all_event.php') ? 'active' : ''; ?>" href="all_event.php">
                    <span class="bullet me-2"></span>
                    <span>All Events</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'create_event.php') ? 'active' : ''; ?>" href="create_event.php">
                    <span class="bullet me-2"></span>
                    <span>Create Event</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'type_event.php') ? 'active' : ''; ?>" href="type_event.php">
                    <span class="bullet me-2"></span>
                    <span>Event Types</span>
                </a></li>
            </ul>
        </li>

        <!-- Sessions -->
        <li class="menu-group <?php echo (in_array($current_script, ['add_session.php','view_sessions.php'])) ? 'active' : ''; ?>">
            <a href="#" class="d-flex align-items-center p-3 text-white submenu-btn menu-item">
                <div class="menu-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <span class="menu-text flex-grow-1">Sessions</span>
                <div class="menu-arrow"><i class="fas fa-chevron-down"></i></div>
            </a>
            <ul class="submenu list-unstyled">
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'add_session.php') ? 'active' : ''; ?>" href="add_session.php">
                    <span class="bullet me-2"></span>
                    <span>Add Session</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'view_sessions.php') ? 'active' : ''; ?>" href="view_sessions.php">
                    <span class="bullet me-2"></span>
                    <span>View Session</span>
                </a></li>
            </ul>
        </li>

        <!-- Papers -->
        <li class="menu-group <?php echo (in_array($current_script, ['papers_list.php', 'review_papers.php', 'add_paper.php', 'paper_authors.php', 'paper_tracks.php'])) ? 'active' : ''; ?>">
            <a href="#" class="d-flex align-items-center p-3 text-white submenu-btn menu-item">
                <div class="menu-icon"><i class="fas fa-file-alt"></i></div>
                <span class="menu-text flex-grow-1">Papers</span>
                <div class="menu-arrow"><i class="fas fa-chevron-down"></i></div>
            </a>
            <ul class="submenu list-unstyled">
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'papers_list.php') ? 'active' : ''; ?>" href="papers_list.php">
                    <span class="bullet me-2"></span>
                    <span>All Papers</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'review_papers.php') ? 'active' : ''; ?>" href="review_papers.php">
                    <span class="bullet me-2"></span>
                    <span>Review Papers</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'add_paper.php') ? 'active' : ''; ?>" href="add_paper.php">
                    <span class="bullet me-2"></span>
                    <span>Add Paper</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'paper_authors.php') ? 'active' : ''; ?>" href="paper_authors.php">
                    <span class="bullet me-2"></span>
                    <span>Paper Authors</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'paper_tracks.php') ? 'active' : ''; ?>" href="paper_tracks.php">
                    <span class="bullet me-2"></span>
                    <span>Track Management</span>
                </a></li>
            </ul>
        </li>

        <!-- Reviews -->
        <li class="menu-group <?php echo (in_array($current_script, ['add_review.php', 'view_reviews.php'])) ? 'active' : ''; ?>">
            <a href="#" class="d-flex align-items-center p-3 text-white submenu-btn menu-item">
                <div class="menu-icon"><i class="fas fa-star"></i></div>
                <span class="menu-text flex-grow-1">Reviews</span>
                <div class="menu-arrow"><i class="fas fa-chevron-down"></i></div>
            </a>
            <ul class="submenu list-unstyled">
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'add_review.php') ? 'active' : ''; ?>" href="add_review.php">
                    <span class="bullet me-2"></span>
                    <span>Add Reviews</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'view_reviews.php') ? 'active' : ''; ?>" href="view_reviews.php">
                    <span class="bullet me-2"></span>
                    <span>View All Review</span>
                </a></li>
            </ul>
        </li>

        <!-- Registrations -->
        <li class="menu-group <?php echo (in_array($current_script, ['add_registration.php', 'view_registrations.php','checkin.php'])) ? 'active' : ''; ?>">
            <a href="#" class="d-flex align-items-center p-3 text-white submenu-btn menu-item">
                <div class="menu-icon"><i class="fas fa-clipboard-list"></i></div>
                <span class="menu-text flex-grow-1">Registrations</span>
                <div class="menu-arrow"><i class="fas fa-chevron-down"></i></div>
            </a>
            <ul class="submenu list-unstyled">
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'add_registration.php') ? 'active' : ''; ?>" href="add_registration.php">
                    <span class="bullet me-2"></span>
                    <span>Add Registrations</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'view_registrations.php') ? 'active' : ''; ?>" href="view_registrations.php">
                    <span class="bullet me-2"></span>
                    <span>All Registrations</span>
                </a></li>
                 <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'checkin.php') ? 'active' : ''; ?>" href="checkin.php">
                    <span class="bullet me-2"></span>
                    <span>CheckIn Details</span>
                </a></li>
            </ul>
        </li>
        <!-- Settings -->
        <li class="menu-group <?php echo (in_array($current_script, ['general_settings.php', 'email_settings.php', 'profile_settings.php', 'change_password.php'])) ? 'active' : ''; ?>">
            <a href="#" class="d-flex align-items-center p-3 text-white submenu-btn menu-item">
                <div class="menu-icon"><i class="fas fa-cogs"></i></div>
                <span class="menu-text flex-grow-1">Settings</span>
                <div class="menu-arrow"><i class="fas fa-chevron-down"></i></div>
            </a>
            <ul class="submenu list-unstyled">
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'general_settings.php') ? 'active' : ''; ?>" href="general_settings.php">
                    <span class="bullet me-2"></span>
                    <span>General Settings</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'email_settings.php') ? 'active' : ''; ?>" href="email_settings.php">
                    <span class="bullet me-2"></span>
                    <span>Email Settings</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'profile_settings.php') ? 'active' : ''; ?>" href="profile_settings.php">
                    <span class="bullet me-2"></span>
                    <span>Profile</span>
                </a></li>
                <li><a class="d-flex align-items-center p-2 ps-5 text-white <?php echo ($current_script == 'change_password.php') ? 'active' : ''; ?>" href="change_password.php">
                    <span class="bullet me-2"></span>
                    <span>Change Password</span>
                </a></li>
            </ul>
        </li>

        <li>
            <a class="d-flex align-items-center p-3 text-white menu-item logout-item" href="../Home/logout.php">
                <div class="menu-icon"><i class="fas fa-sign-out-alt"></i></div>
                <span class="menu-text">Logout</span>
            </a>
        </li>
    </ul>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const submenuButtons = document.querySelectorAll('.submenu-btn');

    // Function to calculate actual submenu height
    function getSubmenuHeight(submenu) {
        // Store the current display state
        const currentDisplay = submenu.style.display;
        
        // Temporarily make it visible and unset max-height to calculate actual height
        submenu.style.display = 'block';
        submenu.style.maxHeight = 'none';
        const height = submenu.scrollHeight;
        
        // Restore original state
        submenu.style.display = currentDisplay;
        submenu.style.maxHeight = '';
        
        return height;
    }

    // Function to close all submenus
    function closeAllSubmenus() {
        document.querySelectorAll('.menu-group .submenu').forEach(submenu => {
            submenu.style.maxHeight = '0';
            submenu.classList.remove('open');
            const arrow = submenu.closest('.menu-group').querySelector('.menu-arrow i');
            if (arrow) {
                arrow.classList.remove('fa-chevron-up');
                arrow.classList.add('fa-chevron-down');
            }
        });
    }

    // Auto open active submenu on page load
    document.querySelectorAll('.menu-group').forEach(group => {
        const submenu = group.querySelector('.submenu');
        const arrow = group.querySelector('.menu-arrow i');

        if (group.classList.contains('active') && submenu) {
            const height = getSubmenuHeight(submenu);
            submenu.classList.add('open');
            submenu.style.maxHeight = height + 'px';

            if (arrow) {
                arrow.classList.remove('fa-chevron-down');
                arrow.classList.add('fa-chevron-up');
            }
        }
    });

    // Submenu toggle logic
    submenuButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const parentLi = this.closest('.menu-group');
            const submenu = parentLi.querySelector('.submenu');
            const arrow = this.querySelector('.menu-arrow i');

            if (!submenu) return;

            const isOpen = submenu.classList.contains('open');

            // Close all other submenus first
            closeAllSubmenus();

            // Toggle current submenu
            if (!isOpen) {
                const height = getSubmenuHeight(submenu);
                submenu.classList.add('open');
                submenu.style.maxHeight = height + 'px';
                arrow.classList.replace('fa-chevron-down', 'fa-chevron-up');
            } else {
                submenu.classList.remove('open');
                submenu.style.maxHeight = '0';
                arrow.classList.replace('fa-chevron-up', 'fa-chevron-down');
            }
        });
    });

    // Close submenus when clicking outside on mobile
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.sidebar')) {
            closeAllSubmenus();
        }
    });

    // Sidebar toggle functionality
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                // Mobile behavior
                sidebar.classList.toggle('mobile-open');
            } else {
                // Desktop behavior - collapse/expand
                sidebar.classList.toggle('collapsed');
                if (sidebar.classList.contains('collapsed')) {
                    closeAllSubmenus();
                }
            }
        });
    }

    // Close sidebar when clicking on a menu item on mobile
    document.querySelectorAll('.sidebar-menu a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('mobile-open');
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('mobile-open');
        }
    });
});
</script>

<style>
/* Sidebar base styling */
.sidebar {
    background: linear-gradient(180deg, #1a1d23 0%, #0f1217 100%);
    z-index: 1000;
    overflow-y: auto;
}

.sidebar-header {
    background-color: rgba(0, 0, 0, 0.2);
}

.logo-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    border-radius: 6px;
    color: white;
    font-size: 12px;
}

/* User profile section */
.user-profile {
    background-color: rgba(255, 255, 255, 0.05);
}

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    font-size: 16px;
}

/* Menu items styling */
.sidebar-menu {
    padding-top: 10px;
}

.menu-item {
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    position: relative;
    overflow: hidden;
    text-decoration: none;
    cursor: pointer;
}

.menu-item:hover {
    background-color: rgba(255, 255, 255, 0.08);
    border-left-color: #6a11cb;
}

.menu-item.active {
    background-color: rgba(106, 17, 203, 0.2);
    border-left-color: #6a11cb;
}

.menu-group.active > .submenu-btn {
    background-color: rgba(106, 17, 203, 0.15);
    border-left-color: #6a11cb;
}

.menu-icon {
    width: 20px;
    text-align: center;
    margin-right: 12px;
    font-size: 16px;
    color: #8a94a6;
    flex-shrink: 0;
}

.menu-item.active .menu-icon,
.menu-item:hover .menu-icon,
.menu-group.active .menu-icon {
    color: #6a11cb;
}

.menu-text {
    flex: 1;
    font-weight: 500;
    white-space: nowrap;
}

.menu-arrow {
    transition: transform 0.3s ease;
    font-size: 12px;
    color: #8a94a6;
    flex-shrink: 0;
    margin-left: auto;
}

/* Submenu styling - FIXED */
.submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease;
    background-color: rgba(0, 0, 0, 0.2);
    display: block !important; /* Force display block */
}

.submenu.open {
    overflow: visible;
}

.submenu li a {
    transition: all 0.2s ease;
    position: relative;
    font-size: 14px;
    text-decoration: none;
    display: flex;
    align-items: center;
}

.submenu li a:hover {
    background-color: rgba(255, 255, 255, 0.05);
    padding-left: 55px;
}

.submenu li a.active {
    background-color: rgba(106, 17, 203, 0.2);
    color: #6a11cb;
}

.submenu li a .bullet {
    width: 4px;
    height: 4px;
    background-color: #8a94a6;
    border-radius: 50%;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.submenu li a:hover .bullet,
.submenu li a.active .bullet {
    background-color: #6a11cb;
    width: 6px;
    height: 6px;
}

/* Logout item styling */
.logout-item {
    margin-top: 10px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.logout-item:hover {
    background-color: rgba(220, 53, 69, 0.2);
    border-left-color: #dc3545;
}

/* Sidebar collapse */
#sidebar.collapsed {
    width: 70px;
}

#sidebar.collapsed .sidebar-header h3,
#sidebar.collapsed .user-profile,
#sidebar.collapsed .menu-text,
#sidebar.collapsed .menu-arrow {
    display: none;
}

#sidebar.collapsed .sidebar-menu li a {
    text-align: center;
    padding: 15px 5px;
    justify-content: center;
}

#sidebar.collapsed .menu-icon {
    margin-right: 0;
    font-size: 18px;
}

/* Mobile responsiveness - FIXED */
@media (max-width: 768px) {
    #sidebar {
        width: 250px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    #sidebar.mobile-open {
        transform: translateX(0);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
    }
    
    #sidebarToggle {
        display: block !important;
    }

    /* Ensure submenus are visible when open on mobile */
    .submenu.open {
        position: static;
        visibility: visible;
        opacity: 1;
    }
}

/* Scrollbar styling */
.sidebar::-webkit-scrollbar {
    width: 5px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Ensure proper display of submenus */
.submenu {
    display: block !important;
    visibility: visible !important;
}
</style>