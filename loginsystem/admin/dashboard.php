<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']==0)) {
  header('location:logout.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin Dashboard</title>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Arvo'>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arvo', serif;
            background: linear-gradient(135deg, #e0fce4 0%, #fefefe 100%);
            overflow-x: hidden;
            position: relative;
            color: #1e293b;
        }

        h1.mt-4 {
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .breadcrumb {
            background: transparent;
            padding-left: 0;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #64748b;
        }

        .card {
            border-radius: 0.5rem;
            box-shadow: 0 2px 6px rgb(100 116 139 / 0.1);
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgb(100 116 139 / 0.2);
        }

        .card-body {
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-footer {
            background-color: rgba(255, 255, 255, 0.1);
            border-top: none;
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-footer a.small {
            color: #f1f5f9;
            font-weight: 500;
            text-decoration: none;
        }

        .card-footer a.small:hover {
            text-decoration: underline;
        }

        .bg-primary {
            background-color: #2563eb !important;
        }

        .bg-primary .card-body,
        .bg-primary .card-footer {
            color: #ffffff !important;
        }

        .bg-warning {
            background-color: #f59e0b !important;
        }

        .bg-warning .card-body,
        .bg-warning .card-footer {
            color: #ffffff !important;
        }

        .bg-success {
            background-color: #16a34a !important;
        }

        .bg-success .card-body,
        .bg-success .card-footer {
            color: #ffffff !important;
        }

        .bg-danger {
            background-color: #dc2626 !important;
        }

        .bg-danger .card-body,
        .bg-danger .card-footer {
            color: #ffffff !important;
        }

        .card-footer .small i.fas.fa-angle-right {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }

            .card-body {
                font-size: 1rem;
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .card-footer {
                justify-content: center;
            }
        }
    </style>

</head>
<body class="sb-nav-fixed">
   <?php include_once('includes/navbar.php');?>
    <div id="layoutSidenav">
      <?php include_once('includes/sidebar.php');?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
<?php
$query=mysqli_query($con,"select id from users");
$totalusers=mysqli_num_rows($query);
?>

                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Total Registered Users 
                                    <span style="font-size:22px;"> <?php echo $totalusers;?></span></div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small" href="manage-users.php">View Details</a>
                                    <div class="small"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <?php
// Ganti 'created_at' sesuai nama kolom sebenarnya
$query1 = mysqli_query($con, "SELECT id FROM users WHERE DATE(created_at) = CURRENT_DATE - INTERVAL 1 DAY");

if (!$query1) {
    die("Query failed: " . mysqli_error($con)); // Tampilkan pesan jika query error
}

$yesterdayregusers = mysqli_num_rows($query1);
?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>
</html>
<?php } ?>
