<!DOCTYPE html>
<html lang="fr">
    <?php require_once 'app/views/partials/head.php'; ?>
<body>
    <?php require_once 'app/views/partials/header.php'; ?>
    
    <main>
        <?php require_once __DIR__ . "/pages/{$view}.php"; ?>
    </main>

    <?php require_once 'app/views/partials/footer.php'; ?>
    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html> 
