<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Institute of Computer Science and Digital Innovation'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }
        
        .conference-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- Navigation will be included here -->
    <?php include 'nav.php'; ?>

    <!-- Main Content Area -->
    <div class="container-fluid p-0">
        <?php 
        // Conference header for all pages
        if (!isset($hide_header) || !$hide_header): 
        ?>
        <div class="conference-header text-center">
            <div class="container">
                <h1 class="display-4 fw-bold">ICSDI 2026</h1>
                <p class="lead">Institute of Computer Science and Digital Innovation</p>
                <p><i class="fas fa-map-marker-alt"></i> ucsi</p>
            </div>
        </div>
        <?php endif; ?>

        <main>
            <!-- Page specific content will be inserted here -->
            <?php 
            if (isset($content)) {
                echo $content;
            }
            ?>
        </main>
    </div>

    <!-- Footer will be included here -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Dynamic JS -->
</body>
</html>