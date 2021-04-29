<?php
ob_start();
include('templates/header.php');
include('config/connect.php');


$staffEmail = '';
$id_to_delete = '';
$errors = array('id'=>'', 'staff_email'=>'');
if(isset($_POST['submit'])){

    $id_to_delete = $_POST['id'];

    if(empty($_POST['id'])){
        $errors['id'] = 'ID is required. <br />';
    }
    else{
        $sql_check_id = "SELECT email_id FROM staff_email WHERE email_id = $id_to_delete";
        $result_check_id = mysqli_query($conn, $sql_check_id);

        if(mysqli_num_rows($result_check_id) == 0){
            $errors['id'] = 'ID is not found in the table. <br />';
        
        }
    }

    if(empty($_POST['staff_email'])){
        $errors['staff_email'] = 'Email is required. <br />';
    }
    else{
        $staffEmail = $_POST['staff_email'];
        
        if(mysqli_num_rows($result_check_id) > 0){

            $sql = "SELECT email FROM staff_email WHERE email_id = $id_to_delete";
            $result = mysqli_query($conn, $sql);
            $result_array = mysqli_fetch_array($result);

            if($staffEmail != $result_array[0]){
                $errors['staff_email'] = 'The id and email do not match!';
            }
            
        }
    }


    if(array_filter($errors)){
        //
    }
    
    else{
        $id_to_delete = $_POST['id'];


        $sql = "DELETE FROM staff_email WHERE email_id = $id_to_delete";

        if(mysqli_query($conn, $sql)){
            header('Location: staff_email.php');
        }
        else{
            echo 'query error: ' . mysqli_error($conn);
        }
    
    }


}

ob_end_flush();

?>

<!DOCTYPE html>
<html>

<body>

    <form action="#" class="white" method="POST">
        <label>id that you wish to delete: </label>
        <input type="text" name="id" value="<?php echo htmlspecialchars($id_to_delete) ?>">
        <div class="red-text"><?php echo $errors['id']; ?></div>
        <label>Email: </label>
        <input type="email" name="staff_email" value="<?php echo htmlspecialchars($staffEmail) ?>">
        <div class="red-text"><?php echo $errors['staff_email']; ?></div>        
        <div class="center">
            <input type="submit" name="submit" value="Next" class="btn brand z-depth-0">
        </div>
    </form>

    <form action="delete.php" method="POST">
        <input type="hidden" name="id" value="">
        <input type="submit" name="back" value="Back to previous page" class=" right btn brand z-depth-0">

    </form>

</body>

</html>