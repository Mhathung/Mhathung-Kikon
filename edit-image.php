<?php
include 'includes/config.php';
session_start();
$userid = $_SESSION['id'];

if (isset($_POST['update_profile'])) {

    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = '../uploaded_img/' . $update_image;

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image is too large';
        } else {
            // Remove the old image from the server if it exists
            if (!empty($fetch['image'])) {
                $old_image_path = '../uploaded_img/' . $fetch['image'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

            $image_update_query = mysqli_query($con, "UPDATE `users` SET image = '$update_image' WHERE id='$userid'") or die('Query failed');
            if ($image_update_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
                $message[] = 'Image updated successfully!';
            } else {
                $message[] = 'Failed to update image.';
            }
        }
    }
}

?>

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/image.css">

</head>
<body>

    <div class="update-profile">
        <?php
        $select = mysqli_query($con, "SELECT * FROM `users` WHERE id='$userid'") or die('Query failed');
        if (mysqli_num_rows($select) > 0) {
            $fetch = mysqli_fetch_assoc($select);
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <?php
            if ($fetch['image'] == '') {
                echo '<img src="images/default-avatar.png" alt="Default Avatar">';
            } else {
                echo '<img src="uploaded_img/' . $fetch['image'] . '" alt="User Avatar">';
            }
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '<div class="message">' . $msg . '</div>';
                }
            }
            ?>
            <div class="flex">
                <div class="inputBox">
                    <span>Update your pic:</span>
                    <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
                </div>
                <input type="submit" value="Update Profile" name="update_profile" class="btn">
                <a href="welcome.php" class="btn">Go Back</a>
            </div>
        </form>
    </div>

</body>

</html>