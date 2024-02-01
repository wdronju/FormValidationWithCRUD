<?php
include('config.php');

$get_id = $_GET['id'];
echo $get_id;

//Data Cleaning Function
function cleanup($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
}

if (isset($_POST['Update'])) {
    $name =  cleanup($_POST['name']);
    $email = cleanup($_POST['email']);
    $phone = cleanup($_POST['phone']);
    $country = cleanup($_POST['country'] ?? null);
    $gender = cleanup($_POST['gender'] ?? null);
    $password = cleanup($_POST['password'] ?? null);
    $conferm_password = cleanup($_POST['conferm_password'] ?? null);
    $skills = $_POST['skills'] ?? null;


    // File information
    $fileName = $_FILES['photo']['name'];
    // $old_file = $_FILES['old_photo'];
    $tempName = $_FILES['photo']['tmp_name'];

    

    //file validation
    $allowed_file = ['jpg', 'png', 'gif', 'jpeg'];
    $fileExtension = explode('.', $fileName);
    $fileActualExtension = strtolower(end($fileExtension));

    if (empty($fileName)) {
        $errorFile = "Please select a photo";
    } elseif (!in_array($fileActualExtension, $allowed_file)) {
        $errorFile = "Please Select a valid file format.";
    } else {
        //check dir uploads
        if (!is_dir('uploads')) {
            mkdir('uploads');
        }
        //creat New Name File
        $fileNewName = str_shuffle(date('HisAFdYDyl')) . '.' . $fileActualExtension;

        //upload file
        $fileUpload = move_uploaded_file($tempName, 'uploads/' . $fileNewName);
        if ($fileUpload) {
            $fileUploadSuccess = "File Uploaded Successfully";
        } else {
            $errorFile = "Something went wrong!";
        }
    }

    //Name  Validation
    if (empty($name)) {
        $error_name = "Name is Requerd";
    } elseif (!preg_match("/^[a-zA-Z. ]*$/", $name)) {
        $error_name = "Only letters and White spaces are allowed";
    } else {
        $correctName = $name;
    }


    //email validation
    if (empty($email)) {
        $error_Email = "Email is Requerd";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_Email = "Email formate not valid";
    } else {
        $correctEmail = $email;
    }


    //phone number validation
    if (empty($phone)) {
        $error_phone = "Phone Number is Requerd";
    } elseif (!preg_match("/^[0-9]{11}$/", $phone)) {
        $error_phone = "Phone number must be only Number and exactly 11 digits";
    } else {
        $correct_phone = $phone;
    }


    //gender Validation
    if (empty($gender)) {
        $errorGender = "Gender must be Required";
    } else {
        $correct_gender = $gender;
    }

    //Validation for Skills
    if (empty($skills)) {
        $error_skill = "Please select Your skills";
    } else {
        $correct_skills = $skills;

        $all_skills = implode(', ', $correct_skills);
    }

    //Country Validation    
    if (empty($country)) {
        $error_country = "Please select your country";
    } else {
        $correct_country = $country;
    }


    // Password validation
    if (empty($password)) {
        $error_password = "Please enter a new password";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[@#$%^&*]).{8,}$/", $password)) {
        $error_password = "Password should contain at least one uppercase letter, one lowercase letter, one special character (@#$%^&*), and be at least 8 characters long";
    } else {
        // Password is valid
        $correct_pass = $password;
        
    }

    if(empty($conferm_password)){
        $error_conferm_password ="Conferm Password is Required";
    }elseif($password != $conferm_password){
        $error_conferm_password ="Passwords and conferm password not matched";

    }else{
        $correct_pass = $conferm_password;
    }
}

// if($fileName != ''){
//     $update_file_name = $_FILES['photo']['name'];
// }else{
//     $update_file_name =  $old_file;
// }


session_start();

if (isset($_POST['Update'])) {

    if (empty($error_name) && empty($error_Email) && empty($error_phone) && empty($errorGender) && empty($error_skill) && empty($error_country) && empty($errorFile)) {
        
        // Assuming $userId is the ID of the user you want to update
        $userId = $_POST['user_id'];

        $sql_u = "UPDATE `users` SET 
                    `name`='$correctName',
                    `email`='$correctEmail',
                    `phone`='$correct_phone',
                    `gender`='$correct_gender',
                    `skills`='$all_skills',
                    `country`='$correct_country',
                    `image`='$fileNewName'
                WHERE `id` = $userId";

        $result = $conn->query($sql_u);

        if (!$result) {
            echo "Data update failed.";
        } else {
            $_SESSION['success'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Alhamdulillah!</strong> Data Updated Successfully
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";

            header('location:index.php');
        }
    }
}













?>

<!-- Header  -->
<?php include('header.php'); ?>




<div class="container my-5">
    <div class="row">
        <h1 class="text-center">Edit Data</h1>
        <div class="m-auto shadow p-5 col-md-8">

        <?php
        
        $sql = "SELECT * FROM `users` WHERE id = '$get_id'";
        $result = $conn->query($sql);
        if($result->num_rows == 0) {
            echo"No result Found";
          }else{
            while($row = $result->fetch_assoc()){

            // Let's Explod the Skills data 
            $skills_new = explode(', ',$row['skills']);
          
        
        ?>
            <form action="" method="post" enctype="multipart/form-data">

                <!-- //Name input -->
                <div class="form-group form-floating mb-4">
                    <input class="form-control <?= isset($error_name) ? 'is-invalid' : null; ?> <?= isset($correctName) ? 'is-valid' : null; ?>" type="text" id="name" name='name' placeholder="" value='<?=$row['name']??null; ?>'>
                    <label for="name" class="">Your Name</label>
                    <div class="invalid-feedback">
                        <?= $error_name; ?>
                    </div>
                    <div class="valid-feedback">
                        <?= $correctName; ?>
                    </div>
                </div>


                <!-- //Email input -->

                <div class="form-group form-floating mb-4">
                    <input class="form-control <?= isset($error_Email) ? 'is-invalid' : null; ?> <?= isset($correctEmail) ? 'is-valid' : null; ?>" type="text" id="email" name='email' placeholder="" value='<?=$row['email']??null; ?>'>
                    <label for="name" class="">Your Email</label>
                    <div class="invalid-feedback">
                        <?= $error_Email; ?>
                    </div>
                    <div class="valid-feedback">
                        <?= $correctEmail; ?>
                    </div>
                </div>

                <!-- //Phone input -->

                <div class="form-group form-floating mb-4">
                    <input type="tel" name="phone" id="phone" placeholder="" class="form-control <?= isset($error_phone) ? 'is-invalid' : null; ?> <?= isset($correct_phone) ? 'is-valid' : null; ?>" value="<?=$row['phone']??null; ?>">
                    <label for="phone">Your Phone Number</label>
                    <div class="invalid-feedback">
                        <?= $error_phone ?? null; ?>
                    </div>

                    <div class="valid-feedback">
                        <?= $correct_phone ?? null; ?>
                    </div>
                </div>


                <!-- Gender Radio Buttons -->
                <div class="mt-4 p-3 border rounded <?= isset($errorGender) ? 'border-danger' : null; ?> <?= isset($correct_gender) ? 'border-success' : null; ?> ">
                    <div class=" form-check-inline">
                        <label class="form-check-label"><b>Gender:</b></label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="male" <?= isset($row['gender']) && $row['gender'] == "male" ? "checked" : null; ?>>
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="female" <?= isset($row['gender']) && $row['gender']== "female" ? "checked" : null; ?>>
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                </div>
                <div class=" text-danger mb-3 mt-1 ">
                    <?= $errorGender ?? null; ?>
                </div>
                <div class=" text-success mb-3 mt-1 ">
                    <?= $correct_gender ?? null; ?>
                </div>

                <!-- Gender Radio Buttons End-->



                <!-- Skills CheckBox Start-->
                <div class="mt-4 p-3 border rounded <?= isset($error_skill) ? "border-danger" : null; ?> <?= isset($correct_skills) ? "border-success" : null; ?> ">
                    <div class=" form-check-inline ">
                        <b>Skills:</b>
                    </div>
                    
                    <div class="form-check form-check-inline ">
                        <input type="checkbox" name="skills[]" value="HTML" id="html" <?php if(in_array('HTML',$skills_new)){echo "checked";}?>>
                        <label for="html" class="form-check-label">HTML</label>
                    </div>

                    <div class="form-check-inline ">
                        <input type="checkbox" name="skills[]" value="CSS" id="css" <?php if(in_array('CSS',$skills_new)){echo "checked";}?>>
                        <label for="css" class="form-check-label">CSS</label>
                    </div>

                    <div class=" form-check-inline ">
                        <input type="checkbox" name="skills[]" value="JS" id="js" <?php if(in_array('JS',$skills_new)){echo "checked";}?>>
                        <label for="js" class="form-check-label">JS</label>
                    </div>

                    <div class=" form-check-inline ">
                        <input type="checkbox" name="skills[]" value="PHP" id="php" <?php if(in_array('PHP',$skills_new)){echo "checked";}?>>
                        <label for="php" class="form-check-label">PHP</label>
                    </div>

                </div>
                <div class=" text-danger mb-3 mt-1 ">
                    <?= $error_skill ?? null; ?>
                </div>

                <div class=" text-success mb-3 mt-1 ">
                    <?php
                    if (isset($skills)) {
                        foreach ($skills as $skill) {
                            echo $skill . ", ";
                        }
                    }

                    ?>
                </div>


                <!-- Skills CheckBox End-->


                <!-- Select Country  start-->
                <select class="form-select py-3 <?= isset($correct_country) ? 'border-success' : null; ?> <?= isset($error_country) ? 'border-danger' : null; ?>" aria-label="Default select example" name='country'>
                    <option value="" disabled selected>Select Your Country</option>
                    <option value="Bangladesh" <?= isset($row['country']) && $row['country'] == 'Bangladesh' ? 'selected' : null; ?>>Bangladesh</option>
                    <option value="India" <?= isset($row['country']) && $row['country'] == 'India' ? 'selected' : null; ?>>India</option>
                    <option value="Pakistan" <?= isset($row['country']) && $row['country'] == 'Pakistan' ? 'selected' : null; ?>>Pakistan</option>

                </select>
                <div class=" text-danger mb-3 mt-1 ">
                    <?= $error_country ?? null; ?>
                </div>

                <div class=" text-success mb-3 mt-1 ">
                    <?php
                    echo $correct_country ?? null;

                    ?>
                </div>
                <!-- Select Country  end-->


                <!-- Password Validation-->
                <div class="form-group form-floating mb-4">
                    <input type="password" name="password" id="password" placeholder="" class="form-control <?= isset($error_password) ? 'is-invalid' : null; ?> <?= isset($correct_pass) ? 'is-valid' : null; ?>">
                    <label for="phone">Password</label>

                    <div>
                        <input type="checkbox" id="showPass"> <label for="showPass" class="form-check-label">Show Password</label>
                    </div>

                    <div class="invalid-feedback">
                        <?= $error_password ?? null; ?>
                    </div>

                    <div class="valid-feedback">
                    <?= password_hash($correct_pass, PASSWORD_BCRYPT) ?? null; ?>
                    </div>

                    <!-- js for show password  -->
                    <script>
                        let password = document.getElementById("password");
                        let checkbox = document.getElementById("showPass");

                        checkbox.addEventListener('change', function() {
                            if (checkbox.checked) {
                                password.type = 'text';
                            } else {
                                password.type = 'password';
                            }
                        });
                    </script>
                </div>


                <!-- conferm pass  -->
                <div class="form-group form-floating mb-4">
                    <input type="password" name="conferm_password" id="conferm_password" placeholder="" class="form-control <?= isset($error_conferm_password) ? 'is-invalid' : null; ?> <?= isset($correct_pass) ? 'is-valid' : null; ?>">
                    <label for="conferm_password">Conferm Password</label>

                    <div>
                        <input type="checkbox" id="conferm_showPass"> <label for="conferm_showPass" class="form-check-label">Show Password</label>
                    </div>

                    <div class="invalid-feedback">
                        <?= $error_conferm_password ?? null; ?>
                    </div>

                    <div class="valid-feedback">
                        <?= password_hash($correct_pass, PASSWORD_BCRYPT) ?? null; ?>
                    </div>

                    <!-- js for show password  -->
                    <!-- js for show password  -->
                    <script>
                        let conferm_password = document.getElementById("conferm_password");
                        let conferm_checkbox = document.getElementById("conferm_showPass");

                        conferm_checkbox.addEventListener('change', function() {
                            if (conferm_checkbox.checked) {
                                conferm_password.type = 'text';
                            } else {
                                conferm_password.type = 'password';
                            }
                        });
                    </script>

                </div>
                <!-- Password Validation end-->




                <!-- File Upload Start-->



                <div class="mb-3">
                    <label for="formFile" class="form-label">Upload Your Photo</label>
                    <input class=" py-3 form-control <?= isset($errorFile) ? 'border-danger' : null; ?> <?= isset($fileUploadSuccess) ? 'border-success' : null; ?>" name='photo' type="file" id="formFile">
                    <input type="hidden" name="old_photo" value="<?=$row['image'];?>" id="">
                    <div class="text-danger">
                        <?= $errorFile ?? null; ?>
                    </div>
                    <div class="text-success">
                        <?= $fileUploadSuccess ?? null; ?><br><br>



                    </div>
                    <img  style="max-width: 200px;" src="uploads/<?=$row['image']?? null;?>" alt=""><br>
                    <b>Prev Image Name:</b><span> <?=$row['image']??null;?></span>
                </div>

                <!-- File Upload End-->

                <!-- Hidden id  -->
                <input type="hidden" name="user_id" value="<?=$row['id'];?>">



                <input type="submit" value="Update" class="btn btn-lg btn-success mt-3" name='Update'>
            </form>
            <?php } }?>
        </div>

    </div>
</div>






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>