<?php 
    require "config/Query.php";
    use Config\Query\Query;
    $db = new Query();   


    
    // ********************************************************************* \\
    // Select Method
    $select = $db->select('users', ['name', 'email'], 'id = ?', [1], 'i');
    $result = $select->fetch_assoc();



    // ********************************************************************* \\
    // update Method 
    $update = $db->update('users', ['name' => 'John Doe', 'email' => 'myemal@example.com'], 'id = 1');
    if($update){
        echo "Updated Successfully";
    }else{
        echo "Failed to Update";
    }


    // ********************************************************************* \\
    // insert method
    $insert = $db->insert('users', ['name' => 'John Doe', 'email' => 'john@me.com', 'password' => '123456']);
    if($insert){
        echo "Inserted Successfully";
    }else{  
        echo "Failed to Insert";
    }