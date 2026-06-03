<?php
// index.php - Main layout with better mobile support
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Southern Indiana Network</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #111; color: #ddd; font-size: 14px; }
        .container { max-width: 1400px; }
        .table-responsive { margin-bottom: 20px; }
        @media (max-width: 768px) {
            .table td, .table th { font-size: 13px; padding: 6px 4px; }
            h3 { font-size: 1.4em; }
        }
    </style>
</head>
<body>

<?php
// Include your navigation / header here (whatever you currently have)
include("header.php");   // or whatever your header file is
?>

<div class="container">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-md-3 col-sm-4">
            <?php include("menu.php"); ?>   <!-- or your left menu file -->
        </div>

        <!-- Main Content Area -->
        <div class="col-md-9 col-sm-8">
            <?php
            // This is where your pages (users.php, etc.) get loaded
            if (isset($_GET['page'])) {
                $page = basename($_GET['page']);
                include($page . ".php");
            } else {
                include("users.php");
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>
