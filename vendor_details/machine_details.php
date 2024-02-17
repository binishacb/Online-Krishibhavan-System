<?php
include('../dbconnection.php');
session_start();
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'vendor') {
    header('Location: ../index.php'); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php
    include('./navbar_vendor.php');
    if(isset($_POST['enable']))
    {
        $machineId = $_POST['enable'];
        $enableMachine = "UPDATE  machines SET status = 0 where machine_id= $machineId";
        $updateResult = $con->query($enableMachine);
    
        if ($updateResult) {
          
           echo '<script type="text/javascript">
              window.location.href = "machine_details.php";
          </script>';
         exit();
        } else {
           
            echo "Error deleting product: " . $con->error;
        }
    } 
    ?>


    <br><br><br><br>
    <div class="container mt-4">
    <h2 class="text-center mb-4 display-6 font-weight-bold text-dark">View Machines</h2>

        <!-- Add Item Button -->
        <div class="text-right mb-3">
        <a href="add_machine.php" class="btn btn-primary" name="add_item" id="add_item">Add Item</a>

        </div>

        <?php
        $sql = "SELECT machines.*, machines.status,machines.machine_id, machine_type.type_name, buy_product.product_price, buy_product.discount, buy_product.sales_price 
                FROM machines 
                LEFT JOIN machine_type ON machines.type_id = machine_type.type_id
                LEFT JOIN buy_product ON machines.bp_id = buy_product.bp_id  WHERE machines.m_quantity > 0";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            echo '<div class="table-responsive">
                    <table class="table table-striped table-hover table-responsive-md">
                        <thead class="thead-dark">
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                             
                                <th>Image </th>
                                <th>Quantity(No.)</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . $row['machine_name'] . '</td>
                        <td>' . $row['type_name'] . '</td>
                       
                        <td><img src="../uploads/' . $row['machine_image'] . '" alt="Machine Image" style="max-width: 100px;"></td>
                        <td>' .$row['m_quantity'] . '</td>
                        <td>
                            <a href="edit_machine.php?id=' . $row['machine_id'] . '" class="btn btn-primary">Edit</a>
                            <br><br><br>';
                           
                          
                            // Assuming $row is the row fetched from the database
                            $machineId = $row['machine_id'];
                            $status = $row['status']; // Assuming the status column name is 'status'
                            
                            if ($status == 0) {
                                // Button shows "Delete" when status is 0
                                echo '<form method="POST" action="delete_machine.php" style="display: inline;">
                                        <input type="hidden" name="id" value="' . $machineId . '">
                                        <button type="submit" class="btn btn-danger" style="background-color: #dc3545; border-color: #dc3545;">Disable</button>
                                      </form>';
                            } elseif ($status == 1) {
                                // Button shows "Deleted" when status is 1
                                echo '<form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="enable" value="' . $machineId . '">
                                <button type="submit" class="btn btn-danger" style="background-color: #6c757d; border-color: #6c757d;">Enable</button>
                              </form>';
                                //echo '<span class="btn btn-secondary" name= "enable" id = "enable" style="background-color: #6c757d; border-color: #6c757d;">Enable</span>';
                            }
                            
                            echo '</td>';?>
                            <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#detailsModal<?php echo $row['machine_id']; ?>">More Details</button></td>

                            <!-- Modal for each machine -->
                            <div class="modal fade" id="detailsModal<?php echo $row['machine_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailsModalLabel"> Details of <?php echo $row['machine_name']; ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Additional details fetched from the database -->
                                            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                                            <p><strong>Discount (%):</strong> <?php echo $row['discount']; ?>%</p>
                                            <p><strong>Sales Price (INR):</strong> <?php echo $row['sales_price']; ?></p>
                    
                                            <!-- Display userlog as a clickable link if it's a PDF file -->
                                            <?php if (isset($row['userlog']) && pathinfo($row['userlog'], PATHINFO_EXTENSION) == 'pdf'): ?>
                                                <p><strong>User Log:</strong> <a href="../uploads/<?php echo $row['userlog']; ?>" target="_blank">View PDF</a></p>
                                            <?php else: ?>
                                                <p><strong>User Log:</strong> <?php echo $row['userlog']; ?></p>
                                            <?php endif; ?>
                                            <!-- You can add more fields as needed -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                 
                            
<?php
            }
            echo '</tr>';

            echo '</tbody></table></div>';
        } else {
            echo '<p class="alert alert-info">No products found.</p>';
        }

        $con->close();
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    


</body>

</html>
