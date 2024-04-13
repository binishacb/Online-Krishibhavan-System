<?php
include('../dbconnection.php');
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Machines</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>

        .card {
            height: 100%; 
            display: flex;
            flex-direction: column;
        }

       
        .card-img-top {
            flex-grow: 1;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <?php include('../navbar/navbar_farmer_market.php'); ?>
    <br>
    <h2 class="text-center mt-3 mb-3">Rental machines</h2>
    <br>
    <div class="container">
    
        <div class="card-columns">
            <?php
            // Fetch available machines
            $viewMachinesQuery = "
    SELECT rent_product.*, vendor.shopName,rent_product.rp_quantity as rp_qnt,
           CASE 
               WHEN rent_product.availability_status = 'available' THEN NULL
               ELSE (SELECT MIN(return_date) FROM rental_orders WHERE rental_orders.rp_id = rent_product.rp_id)
           END AS closest_return_date
    FROM rent_product 
    LEFT JOIN vendor ON rent_product.vendor_id = vendor.vendor_id
    WHERE (rent_product.availability_status = 'available' AND rent_product.rp_quantity > 0)
          OR ( rent_product.rp_quantity = 0)";

            $viewMachinesResult = $con->query($viewMachinesQuery);

            if ($viewMachinesResult->num_rows > 0) {
                while ($machineRow = $viewMachinesResult->fetch_assoc()) {
                   
                    $rp_qnt = $machineRow['rp_qnt'];
echo $rp_qnt;
                    ?>
                    
                    <div class="card">
                        <img src="../uploads/<?php echo $machineRow['rp_image']; ?>" class="card-img-top" alt="<?php echo $machineRow['rp_name']; ?>" style="height: 300px; width: 100%;" >
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $machineRow['rp_name']; ?></h5>
                          
                            <p class="card-text">Fare per hour: INR <?php echo $machineRow['fare_per_hour']; ?></p>

                            <?php
                           if($rp_qnt == 0)
                           {
                            ?>
                            <p >return_date: <?php echo $machineRow['closest_return_date'];?></p>
                            <?php
                           }

                           ?>
                           




                            <a href="checkout_rentalmachine.php?id=<?php echo $machineRow['rp_id']; ?>&fare_per_hour=<?php echo $machineRow['fare_per_hour']; ?>" class="btn btn-primary">Rent Now</a>

                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#machineDetailsModal<?php echo $machineRow['rp_id']; ?>">
                                
                                More Details
                            </button>
                        </div>
                    </div>

                    <div class="modal fade" id="machineDetailsModal<?php echo $machineRow['rp_id']; ?>" tabindex="-1"
                        role="dialog" aria-labelledby="machineDetailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="machineDetailsModalLabel"><?php echo $machineRow['rp_name']; ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Description:</strong> <?php echo $machineRow['rp_description']; ?></p>
                                    <p><strong>Security Amount:</strong> INR <?php echo $machineRow['security_amt']; ?></p>
                                    <p><strong>Vendor:</strong> <?php echo $machineRow['shopName']; ?></p>
                              
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p class="text-center">No available machines.</p>';
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
