<?php
session_start();
include('../dbconnection.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'vendor') {
    header('Location: ../index.php');
    exit();
}

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Machine Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php
    include('./navbar_vendor.php');
    ?>
    <?php
      if(isset($_POST['enable']))
      {
          $machineId = $_POST['enable'];
          $enableMachine = "UPDATE  rent_product SET availability_status = 'available' where rp_id= $machineId";
          $updateResult = $con->query($enableMachine);
      
          if ($updateResult) {
            
             echo '<script type="text/javascript">
                window.location.href = "rentalmachines.php";
            </script>';
           exit();
          } else {
             
              echo "Error deleting product: " . $con->error;
          }
      } 
      ?>

    <br><br><br>
    <div class="container mt-5">
        <h2 class="text-center mb-4 display-6 font-weight-bold text-dark">View Rental Machines</h2>
        <div class="text-right mb-3">
            <a href="add_rentalmachines.php" class="btn btn-primary" name="add_item" id="add_item">Add machine</a>
        </div>

        <?php
       // $sql = "SELECT machines.*, rent_product.* FROM machines INNER JOIN rent_product ON machines.rp_id = rent_product.rp_id";
        $sql = "SELECT rent_product.* from rent_product where availability_status='available'";
        $result = $con->query($sql);
        ?>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Machine Name</th>
                    <th>Machine image</th>
                   <th>Available quantity</th>
                    <th>Action</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $machineId = $row['rp_id'];
                        $status = $row['availability_status'];

                        echo "<tr>
                                <td>{$row['rp_name']}</td>
                                <td><img src='../uploads/{$row['rp_image']}' alt='Machine Image' style='max-width: 100px;'></td>
                               
                                <td>{$row['rp_quantity']}</td>
                                <td>
                                    <a href='?id=$machineId' class='btn btn-primary'>Edit</a>
                                    " . ($status == "unavailable" ? "
                                        <form method='POST' action='' style='display: inline;'>
                                            <input type='hidden' name='id' value='$machineId'>
                                            <button type='submit' class='btn btn-danger' style='background-color: #dc3545; border-color: #dc3545;'>Disable</button>
                                        </form>" : "
                                        <form method='POST' action='' style='display: inline;'>
                                            <input type='hidden' name='enable' value='$machineId'>
                                            <button type='submit' class='btn btn-danger' style='background-color: #6c757d; border-color: #6c757d;'>Enable</button>
                                        </form>"
                                    ) . "
                                </td>
                                <td>
                                    <button type='button' class='btn btn-info' data-toggle='modal' data-target='#detailsModal{$machineId}'>More Details</button>
                                
                                    <div class='modal fade' id='detailsModal{$machineId}' tabindex='-1' role='dialog' aria-labelledby='detailsModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='detailsModalLabel'>Details of {$row['rp_name']}</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <!-- Additional details fetched from the database -->
                                                    <p><strong>Description:</strong> {$row['rp_description']}</p>
                                                    <p><strong>fare_per_hour:</strong> {$row['fare_per_hour']}</p>
                                                    <p><strong>fare_per_day:</strong> {$row['fare_per_day']}</p>
                                                   
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>";
                    }
                } else {
                    echo '<tr><td colspan="6">No machines found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>

<?php
$con->close();
?>
