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
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $baseUrl; ?>/assets/js/script.js?v=<?php echo time(); ?>"></script>
</body>
</html>

