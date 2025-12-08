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

