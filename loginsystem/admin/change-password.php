<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']==0)) {
  header('location:logout.php');
} else {
    // for password change   
    if(isset($_POST['update'])) {
        $oldpassword = md5($_POST['currentpassword']); 
        $newpassword = md5($_POST['newpassword']);
        $sql = mysqli_query($con, "SELECT password FROM admin where password='$oldpassword'");
        $num = mysqli_fetch_array($sql);
        if($num > 0) {
            $adminid = $_SESSION['adminid'];
            $ret = mysqli_query($con, "update admin set password='$newpassword' where id='$adminid'");
            echo "<script>alert('Password Changed Successfully !!');</script>";
            echo "<script type='text/javascript'> document.location = 'change-password.php'; </script>";
        } else {
            echo "<script>alert('Old Password not match !!');</script>";
            echo "<script type='text/javascript'> document.location = 'change-password.php'; </script>";
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
    <title>Change password </title>
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

        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
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

    <script language="javascript" type="text/javascript">
        function valid() {
            if(document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
                alert("Password and Confirm Password Field do not match  !!");
                document.changepassword.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>

</head>
<body class="sb-nav-fixed">
    <?php include_once('includes/navbar.php');?>
    <div id="layoutSidenav">
        <?php include_once('includes/sidebar.php');?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Change Password</h1>
                    <div class="card mb-4">
                        <form method="post" name="changepassword" onSubmit="return valid();">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Current Password</th>
                                        <td><input class="form-control" id="currentpassword" name="currentpassword" type="password" value="" required /></td>
                                    </tr>
                                    <tr>
                                        <th>New Password</th>
                                        <td><input class="form-control" id="newpassword" name="newpassword" type="password" value="" required /></td>
                                    </tr>
                                    <tr>
                                        <th>Confirm Password</th>
                                        <td colspan="3"><input class="form-control" id="confirmpassword" name="confirmpassword" type="password" required /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align:center;"><button type="submit" class="btn btn-primary btn-block" name="update">Change</button></td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>
</html>
<?php } ?>
