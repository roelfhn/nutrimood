<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']==0)) {
  header('location:logout.php');
} else {
    // for deleting user
    if(isset($_GET['id'])) {
        $adminid = $_GET['id'];
        $msg = mysqli_query($con, "delete from users where id='$adminid'");
        if($msg) {
            echo "<script>alert('Data deleted');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Manage Users</title>
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

        .container-fluid {
            padding: 2rem 1rem;
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

        .card-header {
            background-color: #f1f5f9;
            font-weight: 600;
            font-size: 1.1rem;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            padding: 1rem 1.5rem;
        }

        .card-body {
            padding: 1rem 1.5rem;
            background-color: #fff;
            color: #334155;
        }

        table.dataTable thead th {
            border-bottom: 2px solid #334155;
            font-weight: 700;
            color: #334155;
        }

        table.dataTable tbody tr:hover {
            background-color: #f0f9ff;
        }

        table.dataTable tbody td, table.dataTable thead th {
            padding: 0.75rem 1rem;
        }

        a {
            color: #2563eb;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        i.fa-edit {
            color: #16a34a;
            margin-right: 10px;
        }

        i.fa-trash {
            color: #dc2626;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }

            .card-header, .card-body {
                padding: 1rem;
            }

            table.dataTable tbody td, table.dataTable thead th {
                padding: 0.5rem;
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
                    <h1 class="mt-4">Manage users</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage users</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Registered User Details
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Sno.</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email Id</th>
                                        <th>Contact no.</th>
                                        <th>Reg. Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            
                                <tbody>
                                    <?php 
                                    $ret = mysqli_query($con, "select * from users");
                                    $cnt = 1;
                                    while($row = mysqli_fetch_array($ret)) { ?>
                                        <tr>
                                            <td><?php echo $cnt;?></td>
                                            <td><?php echo $row['fname'];?></td>
                                            <td><?php echo $row['lname'];?></td>
                                            <td><?php echo $row['email'];?></td>
                                            <td><?php echo $row['contactno'];?></td>
                                            <td><?php echo $row['posting_date'];?></td>
                                            <td>
                                                <a href="user-profile.php?uid=<?php echo $row['id'];?>"> 
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="manage-users.php?id=<?php echo $row['id'];?>" onClick="return confirm('Do you really want to delete');">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php $cnt = $cnt + 1; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>
</html>
<?php } ?>
