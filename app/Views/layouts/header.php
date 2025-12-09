<?php
// baseUrl should be passed from controller, if not, detect it
if (!isset($baseUrl)) {
    $config = require __DIR__ . '/../../../config/config.php';
    $baseUrl = $config['app']['url'];
    
    // Auto-detect base URL if not set correctly
    if (empty($baseUrl) || $baseUrl === 'http://localhost/medic/public') {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $baseUrl = $protocol . '://' . $host . rtrim($scriptName, '/');
    }
    $baseUrl = rtrim($baseUrl, '/');
}

$pageTitle = $pageTitle ?? 'سیستم مدیریت پزشکان';
$currentController = $currentController ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Debug: Show baseUrl -->
    <?php if (isset($_GET['debug'])): ?>
    <script>console.log('Base URL: <?php echo $baseUrl; ?>');</script>
    <?php endif; ?>
    
    <link rel="stylesheet" href="<?php echo htmlspecialchars($baseUrl); ?>/assets/css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Message Display Component -->
<div id="messageContainer" style="position: fixed; top: 20px; left: 20px; right: 20px; z-index: 9999; max-width: 500px; margin: 0 auto;">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>
<script>
// Auto-hide messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('#messageContainer .alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>

