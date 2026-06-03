<?php
$dashboards = [
    'custom' => 'NA9VY Custom',
    'default' => 'Stock / Default',
];
$current = $_GET['d'] ?? 'custom';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>URFD SIN Dashboard Switcher</title>
    <style>
        body { 
            margin:0; 
            padding:0; 
            background:#111; 
            font-family:Arial, sans-serif; 
            color: #ddd;           /* overall text color */
        }
        
        .navbar {
            background:#1a1a1a; 
            padding:15px; 
            text-align:center; 
            border-bottom:3px solid #333;
        }
        
        .navbar strong {
            color: #FF8C00;        /* Orange - URFD SIN */
            font-size: 1.3em;
        }
        
        .navbar a {
            color:#0f0; 
            margin:0 18px; 
            text-decoration:none; 
            font-size:1.15em;
        }
        .navbar a:hover { color:#ff0; }
        .navbar a.active { color:#ff4444; text-decoration:underline; font-weight:bold; }
        
        iframe {
            width:100%; 
            height:calc(100vh - 78px); 
            border:none;
        }
    </style>
</head>
<body>
<div class="navbar">
    <strong>URFD SIN — Dashboard Switcher:</strong>
    <?php foreach($dashboards as $dir => $name): ?>
        <a href="?d=<?= $dir ?>" class="<?= $current === $dir ? 'active' : '' ?>">
            <?= $name ?>
        </a>
    <?php endforeach; ?>
</div>
<iframe src="<?= htmlspecialchars($current) ?>/index.php" title="Dashboard"></iframe>
</body>
</html>
