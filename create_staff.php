<!DOCTYPE html>
<html>

<head>
    <meta <charset="utf-8" />
</head>

<body>

    <?php

    include('templates/header.php');
    include('config/connect.php');

    $id_to_create = '';
    $first_name = '';
    $last_name = '';
    $address_id = '';
    $picture = '';
    $store_id = '';
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $last_update = date("Y-m-d H:i:s");
    $errors = array('id' => '', 'first_name' => '', 'last_name' => '', 'address_id' => '', 'picture' => '', 'store_id' => '');


    // Initialize message variable
    $msg = "";
    $pdo = new PDO("mysql:host=localhost;dbname=" . $DATABASE_NAME . "", $USERNAME, $PASSWORD);

    // If upload button is clicked ...
    if (isset($_POST['btn'])) {

        // Get records
        $id_to_create = mysqli_real_escape_string($conn, $_POST['id']);
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $address_id = mysqli_real_escape_string($conn, $_POST['address_id']);
        $store_id = mysqli_real_escape_string($conn, $_POST['store_id']);

        if (empty($_POST['id'])) {
            $errors['id'] = 'ID is required. <br />';
        } else {
            $sql_check_id = "SELECT staff_id FROM staff WHERE staff_id = $id_to_create";
            $result_check_id = mysqli_query($conn, $sql_check_id);

            if (mysqli_num_rows($result_check_id) > 0) {
                $errors['id'] = 'ID is already in the table. Please key in another ID. <br />';
            }
        }

        if (empty($_POST['first_name'])) {
            $errors['first_name'] = 'First name is required. <br />';
        } else {
            $first_name = $_POST['first_name'];
            if (!preg_match('/^[a-zA-Z\s]+$/', $first_name)) {
                $errors['first_name'] = 'First name must be letters and/or spaces only.';
            }
        }

        if (empty($_POST['last_name'])) {
            $errors['last_name'] = 'Last name is required. <br />';
        } else {
            $last_name = $_POST['last_name'];
            if (!preg_match('/^[a-zA-Z\s]+$/', $last_name)) {
                $errors['last_name'] = 'Last name must be letters and/or spaces only.';
            }
        }

        if (empty($_POST['address_id'])) {
            $errors['address_id'] = 'Address Id is required. <br />';
        } else {
            $address_id = $_POST['address_id'];
        }


        if (empty($_POST['store_id'])) {
            $errors['store_id'] = 'Store Id is required. <br />';
        } else {
            $store_id = $_POST['store_id'];
        }

        //-----IMAGES------//
        if (!empty($_FILES['picture']['tmp_name'])) {
            $name = $_FILES['picture']['name'];
            $type = $_FILES['picture']['type'];
            $data = file_get_contents($_FILES['picture']['tmp_name']);

            // image file directory
            $target = "images/" . basename($name);
        }


        $sql = "Insert into staff (staff_id, first_name, last_name, address_id, picture, store_id, last_update) values(?,?,?,?,?,?,?)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $id_to_create);
        $stmt->bindParam(2, $first_name);
        $stmt->bindParam(3, $last_name);
        $stmt->bindParam(4, $address_id);
        $stmt->bindParam(5, $data);
        $stmt->bindParam(6, $store_id);
        $stmt->bindParam(7, $last_update);

        if (array_filter($errors)) {
            //
        } else {

            // execute query
            $result = $stmt->execute();

            if (move_uploaded_file($_FILES['picture']['tmp_name'], $target)) {
                $msg = "Image uploaded successfully";
            } else {
                $msg = "Failed to upload image";
            }

            if ($result) {
                header('Location: staff.php');
            } else {
                echo 'query error: ' . mysqli_error($conn);
            }
        }
    }

    ?>

    <form method="POST" action="create_staff.php" class="white" enctype="multipart/form-data">

        <label>Staff Id</label>
        <input type="text" name="id" value="<?php echo htmlspecialchars($id_to_create) ?>">
        <div class="red-text"><?php echo $errors['id']; ?></div>

        <label>First Name</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name) ?>">
        <div class="red-text"><?php echo $errors['first_name']; ?></div>

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($last_name) ?>">
        <div class="red-text"><?php echo $errors['last_name']; ?></div>

        <label>Address Id</label>
        <input type="text" name="address_id" value="<?php echo htmlspecialchars($address_id) ?>">
        <div class="red-text"><?php echo $errors['address_id']; ?></div>

        <label>Picture</label><br><br>
        <input type="file" name="picture" />
        <div class="red-text"><?php echo $errors['picture']; ?></div><br>

        <label>Store Id</label>
        <input type="text" name="store_id" value="<?php echo htmlspecialchars($store_id) ?>">
        <div class="red-text"><?php echo $errors['store_id']; ?></div>

        <div class="center">
            <input type="submit" name="btn" value="Next" class="btn brand z-depth-0">
        </div>
    </form>

    <form action="create.php" method="POST">
        <input type="hidden" name="id" value="">
        <input type="submit" name="back" value="Back to previous page" class=" right btn brand z-depth-0">

    </form>



</body>

</html>