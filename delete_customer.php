<?php
ob_start();
include('templates/header.php');
include('config/connect.php');


$customerFirstName = $customerLastName = '';
$id_to_delete = '';
$errors = array('id'=>'', 'customer_first_name'=>'', 'customer_last_name'=>'');
if(isset($_POST['submit'])){

    $id_to_delete = $_POST['id'];

    if(empty($_POST['id'])){
        $errors['id'] = 'ID is required. <br />';
    }
    else{
        $sql_check_id = "SELECT customer_id FROM customer WHERE customer_id = $id_to_delete";
        $result_check_id = mysqli_query($conn, $sql_check_id);

        if(mysqli_num_rows($result_check_id) == 0){
            $errors['id'] = 'ID is not found in the table. <br />';
        
        
        }
    }

    if(empty($_POST['customer_first_name'])){
        $errors['customer_first_name'] = 'First name is required. <br />';
    }
    else{
        $customerFirstName = $_POST['customer_first_name'];
        if(!preg_match('/^[a-zA-Z\s]+$/', $customerFirstName)){
            $errors['customer_first_name'] = 'First name must be letters and spaces only.';
        }
        elseif(mysqli_num_rows($result_check_id) > 0){

            $sql = "SELECT first_name FROM customer WHERE customer_id = $id_to_delete";
            $result = mysqli_query($conn, $sql);
            $result_array = mysqli_fetch_array($result);

            if($customerFirstName != $result_array[0]){
                $errors['customer_first_name'] = 'The id and first name do not match!';
            }
            
        }
    }


    if(empty($_POST['customer_last_name'])){
        $errors['customer_last_name'] = 'Last name is required. <br />';
    }
    else{

        $customerLastName = $_POST['customer_last_name'];
        

        if(!preg_match('/^[a-zA-Z\s]+$/', $customerLastName)){
            $errors['customer_last_name'] = 'Last name must be letters and spaces only.';
        }
        elseif(mysqli_num_rows($result_check_id) > 0){
            $sql = "SELECT last_name FROM customer WHERE customer_id = $id_to_delete";
            $result = mysqli_query($conn, $sql);
            $result_array = mysqli_fetch_array($result);

            if($customerLastName != $result_array[0]){
                $errors['customer_last_name'] = 'The id and last name do not match!';
            }
            
        }
    }


    
            
        
        

    
    

    
    if(array_filter($errors)){
        //
    }
    
    else{
        $id_to_delete = $_POST['id'];


        $sql = "DELETE FROM customer WHERE customer_id = $id_to_delete";

        if(mysqli_query($conn, $sql)){
            header('Location: customer.php');
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
        <label>First name: </label>
        <input type="text" name="customer_first_name" value="<?php echo htmlspecialchars($customerFirstName) ?>">
        <div class="red-text"><?php echo $errors['customer_first_name']; ?></div>
        <label>Last Name: </label>
        <input type="text" name="customer_last_name" value="<?php echo htmlspecialchars($customerLastName) ?>">
        <div class="red-text"><?php echo $errors['customer_last_name']; ?></div>
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