
<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); 
    exit(); 
}
?>
<?php
if (isset($_POST['submit'])){
    
    // If there are any error messages, display them and stop further processing
    
    $kname = $_POST["kname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $district = $_POST["districtDropdown"];
    $block = $_POST["block"];
    $state = $_POST["state"];
    $checkDuplicateQuery = "SELECT * FROM krishi_bhavan WHERE email = '$email' OR mobile = '$phone'";
    $duplicateResult = $con->query($checkDuplicateQuery);
    
    if ($duplicateResult->num_rows > 0) {
        echo "<script>alert('Error: The email or phone number already exists. Please enter correct values.');</script>";
    } else
    $checkDuplicateQuery = "SELECT kb.krishibhavan_name FROM krishi_bhavan kb INNER JOIN block b ON kb.block_id = b.block_id WHERE kb.krishibhavan_name = '$kname' AND b.block_name = '$block'";
    $duplicateResult = $con->query($checkDuplicateQuery);

    if ($duplicateResult->num_rows > 0) {
        echo "<script>alert('Error: the krishibhavan already exist..Please enter valid data..');</script>";
    } else {
    $getBlockIdQuery = "SELECT block_id FROM block WHERE block_name = '$block'";
    $blockIdResult = $con->query($getBlockIdQuery);

    if ($blockIdResult->num_rows > 0) {
        $row = $blockIdResult->fetch_assoc();
        $blockId = $row["block_id"];
    // Insert data into the krishibhavan table
    $sql = "INSERT INTO krishi_bhavan (krishibhavan_name, block_id, email, mobile) VALUES ('$kname', '$blockId', '$email', '$phone')";

    if ($con->query($sql) == TRUE) {
        echo "<script>alert('Record added successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}
}}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Set a light background color */
        }
        /* .container-fluid {
            padding: 50px; 
        } */
        .card {
            border: none; 
            border-radius: 10px; 
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); 
            background-color: #ffffff; 
            margin-top: 40px;
            
        }
       
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php
include('navbar/navbar_admin.php');
?>
<div class="container-fluid">
    <div class="row d-flex justify-content-center">
        <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">
            <div class="card">
                <h5 class="text-center mb-4">Add Krishibhavan</h5>
                <form class="form-card" method="POST"  onsubmit="return validateForm()">
                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label class="form-control-label px-3">Krishibhavan Name<span class="text-danger"> *</span></label>
                            <input type="text" id="kname" name="kname" class="form-control" placeholder="Enter Krishibhavan Name" oninput="validateName(this.value)"
                                        required>
                                    <div id="kname-warning" class="invalid-feedback"></div>
                                    <div id="kname-error" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label class="form-control-label px-3">Email<span class="text-danger"> *</span></label>
                            <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email" oninput="validateEmail(this.value)"
                                        required>
                                    <div id="email-warning" class="invalid-feedback"></div>
                                    <div id="email-error" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label class="form-control-label px-3">Phone no.<span class="text-danger"> *</span></label>
                            <input type="number" id="phone" name="phone" class="form-control" placeholder="Enter the Krishibhavan contact number" oninput="validatePhone(this.value)"
                                        required>
                                    <div id="phone-warning" class="invalid-feedback"></div>
                                    <div id="phone-error" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="districtDropdown" class="form-control-label">District<span class="text-danger"> *</span></label>
                            <select id="districtDropdown" name="districtDropdown" class="form-control" onchange="updateBlocks()" onsubmit="validateDistrict(this.value)"
                                        required>
                                    
                                <option value="">Select an option</option>
                                <option value="kasargod">Kasargod</option>
                                <option value="kannur">Kannur</option>
                                <option value="kozhikode" >Kozhikode</option>
                                <option value="wayanad" >Wayanad</option>
                                <option value="malappuram" >Malappuram</option>
                                <option value="palakkad" >Palakkad</option>
                                <option value="thrissur" >Thrissur</option>
                                <option value="ernakulam" >Ernakulam</option>
                                <option value="idukki">Idukki</option>
                                <option value="kottayam" >Kottayam</option>
                                <option value="pathanamthitta">Pathanamthitta</option>
                                <option value="alappuzha">Alappuzha</option>
                                <option value="kollam">Kollam</option>
                                <option value="thiruvananthappuram">Thiruvananthappuram</option>
                            </select>
                        </div>
                     </div>
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="block" class="form-control-label">Block<span class="text-danger"> *</span></label>
                            <select id="block" name="block" class="form-control" onsubmit="validateBlock(this.value)"
                                        required>
                                    
                            <option value="">Select an option</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="state" class="form-control-label">State<span class="text-danger"> *</span></label>
                            <select id="state" name="state" class="form-control" onsubmit="validateState(this.value)"
                                        required>
                                  
                            <option value="">Select an option</option>
                            <option value="kerala">Kerala</option>
                            </select>
                        </div>
                    </div>
<script>
    var districtBlocks = {
        "kasargod": ["kanhangad", "kanhangad", "kasargod","manjeshwar","nileshwar","parappa"],
        "kannur": ["edakkad", "irikkur", "iritty"],
        "kozhikode":["kakkur","koduvally"],
        "wayanad":["kalpetta","mananthavady"],
        "malappuram":["manjeri","kondotty"],
        "palakkad":["agali","alathur"],
        "thrissur":["anthikkad","chalakudy","chavakkad"],
        "ernakulam":["aluva","angamaly"],
        "idukki":["adimaly","kattappana"],
        "kottayam":["erattupetta","pala"],
        "pathanamthitta":["adoor","konni"],
        "alappuzha":["cherthala","chengannur"],
        "kollam":["anchal","chavara"],
        "thiruvananthappuram":["aryancode","attingal"]
    };

    function updateBlocks() {
        var selectedDistrict = document.getElementById("districtDropdown").value;
        var blocks = districtBlocks[selectedDistrict] || [];
        var blockDropdown = document.getElementById("block");
        blockDropdown.innerHTML = "";
        blocks.forEach(function (block) {
            var option = document.createElement("option");
            option.value = block;
            option.text = block;
            blockDropdown.appendChild(option);
        });
    }
    updateBlocks();
</script>
            <div class="row justify-content-center">
                <div class="form-group col-sm-6 text-center">
                    <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Register">
                </div>

                    </div>
               
            </div>
        </div>
    </div>
</div>

</form>

<br><br><br><br><br><br><br>
<?php
include('footer/footer.php');
?>
<script>
    function validateEmail(email){
            const emailRegex = /^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
            const emailInput = document.getElementById('email');
            const emailWarning = document.getElementById('email-warning');
            const emailError = document.getElementById('email-error');
           
            if (email === '') {
                emailWarning.textContent = 'Warning: Email field is empty.';
                emailInput.style.border = '2px solid red';
                emailWarning.style.color = 'red';
                emailError.textContent = '';
                return false; // Return false to prevent form submission
            }

            if (emailRegex.test(email)) {
                emailInput.style.border = '2px solid green';
                emailWarning.textContent = '';
                emailError.textContent = '';
                return true; // Return true if validation is successful
            } else {
                emailInput.style.border = '2px solid red';
                emailWarning.textContent = '';
                emailError.style.color = 'red';
                emailError.textContent = 'Error: Invalid email address';
                return false; // Return false to prevent form submission
            }
        }
        
    function validateName(kname) {
    const knameInput = document.getElementById('kname');
    const knameWarning = document.getElementById('kname-warning');
    const knameError = document.getElementById('kname-error');
    kname = kname.trim();

    // Validate first name
    if (kname === '') {
        knameWarning.textContent = 'Warning: Krishibhavan name field is empty.';
        knameInput.classList.add('is-invalid');
        knameError.textContent = '';
        return false;
    } else if (kname.length < 3) {
        knameInput.classList.add('is-invalid');
        knameWarning.textContent = '';
        knameError.textContent = 'Error: Krishibhavan name should contain at least 3 letters.';
        return false;
    } 
    // else if (!/^[a-zA-Z]+$/.test(kname)) {
    //     knameInput.classList.add('is-invalid');
    //     knameWarning.textContent = '';
    //     knameError.textContent = 'Error: Krishibhavan Name should not contain numbers or special characters.';
    //     return false;
    // }
     else if (kname.length > 30) {
        knameInput.classList.add('is-invalid');
        knameWarning.textContent = '';
        knameError.textContent = 'Error: Krishibhavan name exceeds the maximum character limit of 30.';
        return false;
    } else if (/^(.)\1+$/i.test(kname)) {
        knameInput.classList.add('is-invalid');
        knameWarning.textContent = '';
        knameError.textContent = 'Error: Krishibhavan name should be meaningful and not consist of repeating characters.';
        return false;
    } else {
        knameInput.classList.remove('is-invalid');
        knameInput.style.border = '2px solid green';
        knameWarning.textContent = '';
        knameError.textContent = '';
        return true;
    }
}
function validateDistrict(district) {
    const districtInput = document.getElementById('district');
    
    if (district === '' || district === null) {
        districtWarning.textContent = 'Warning: Please select a district.';
        districtInput.classList.add('is-invalid');
        districtError.textContent = '';
        return false;
    } else {
        districtInput.classList.remove('is-invalid');
        districtInput.style.border = '2px solid green';
        districtWarning.textContent = '';
        districtError.textContent = '';
        return true;
    }
}


function validateBlock(block) {
    const blockInput = document.getElementById('block');
    
    

    // Validate first name
    if (block === '') {
        blockWarning.textContent = 'Warning: block  field is empty.';
        blockInput.classList.add('is-invalid');
        blockError.textContent = '';
        return false;
    }
    else {
        blockInput.classList.remove('is-invalid');
        blockInput.style.border = '2px solid green';
        blockWarning.textContent = '';
        blockError.textContent = '';
        return true;
    }
}

function validateState(state) {
    const stateInput = document.getElementById('state');
    const stateWarning = document.getElementById('state-warning');
    const stateError = document.getElementById('state-error');
    

    // Validate first name
    if (state === '') {
        stateWarning.textContent = 'Warning: state  field is empty.';
        stateInput.classList.add('is-invalid');
        stateError.textContent = '';
        return false;
    }
    else {
        stateInput.classList.remove('is-invalid');
        stateInput.style.border = '2px solid green';
        stateWarning.textContent = '';
        stateError.textContent = '';
        return true;
    }
}
    function validatePhone(phone) {
    const phoneRegex = /^[0-9]{10}$/;
    const phoneInput = document.getElementById('phone');
    const phoneWarning = document.getElementById('phone-warning');
    const phoneError = document.getElementById('phone-error');

    if (phone === '') {
        phoneInput.style.border = '2px solid red';
        phoneWarning.style.color = 'red';
        phoneError.textContent = '';
        return false; // Return false to prevent form submission
    }

    if (phoneRegex.test(phone)) {
        if (/(\d)\1{5}/.test(phone)) {
            phoneInput.style.border = '2px solid red';
            phoneWarning.textContent = '';
            phoneError.style.color = 'red';
            phoneError.textContent = 'Error: Phone number should not contain repeating digits.';
            return false; // Return false to prevent form submission
        } else {
            phoneInput.style.border = '2px solid green';
            phoneWarning.textContent = '';
            phoneError.textContent = '';
            return true; // Return true if validation is successful
        }
    } else {
        phoneInput.style.border = '2px solid red';
        phoneWarning.textContent = '';
        phoneError.style.color = 'red';
        phoneError.textContent = 'Error: Invalid phone number. Please enter a 10-digit number.';
        return false; // Return false to prevent form submission
    }
}
function validateForm() {
    // Check both email, name, and phone validation results
    const isNameValid = validateName(document.getElementById('kname').value);
    const isEmailValid = validateEmail(document.getElementById('email').value);
    const isPhoneValid = validatePhone(document.getElementById('phone').value);
    const isDistrictValid = validateDistrict(document.getElementById('district').value);
    const isBlockValid  = validateBlock(document.getElementById('block').value);
    const isStateValid = validateState(document.getElementById('state').value);
    // Only allow form submission if all validations are true
    const isValid = isNameValid && isEmailValid && isPhoneValid && isBlockValid && isDistrictValid && isStateValid ;
    return isValid;
}

</script>

</body>
</html>
