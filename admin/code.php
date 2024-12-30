<?php

include('../config/function.php');

if(isset($_POST['saveAdmin']))
{
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = validate($_POST['is_ban'])== true ?1:0;

    if($name != '' && $email != '' && $password != '')
    {
        $emailCheck = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
        if($emailCheck)
        {
            if(mysqli_num_rows($emailCheck) > 0)
            {
                redirect('admins-create.php', 'Email Already Used By Another User.');
            }
        }
        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone,
            'is_ban' => $is_ban
        ];

        $result = insert('admins', $data);

        if($result)
        {
            redirect('admins.php', 'Data inserted successfully.');
        }
        else
        {
            redirect('admins-create.php', 'Error inserting data');
        }
    }
    else
    {
        redirect('admins-create.php', 'Please fill required fields.');
    }
}

if(isset($_POST['updateAdmin']))
{
    $adminId = validate($_POST['adminId']);

    $adminData = getById('admins', $adminId);

    if($adminData['status'] != 200)
    {
        redirect('admins-edit.php?id='.$adminId, 'Please fill required fields.');
    }

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = validate($_POST['is_ban'])== true ?1:0;


    $emailCheckQuery = "SELECT * FROM admins WHERE email='$email' AND id!='$adminId'";
    $checkedResult = mysqli_query($conn, $emailCheckQuery);
    if($checkedResult)
    {
        if(mysqli_num_rows($checkedResult) > 0)
        {
            redirect('admins-edit.php?id='.$adminId, 'Email has already been used.');
        }
    }

    if($password != '')
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); 
    }
    else
    {
        $hashed_password = $adminData['data']['password']; 
    }

    if($name != '' && $email != '')
    {
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password,
            'phone' => $phone,
            'is_ban' => $is_ban
        ];

        $result = update('admins', $adminId, $data);

        if($result)
        {
            redirect('admins-edit.php?id='.$adminId, 'Data updated successfully.');
        }
        else
        {
            redirect('admins-edit.php?id='.$adminId, 'Error updating data');
        }
    }
    else
    {
        redirect('admins-create.php', 'Please fill required fields.');
    }
}

?>