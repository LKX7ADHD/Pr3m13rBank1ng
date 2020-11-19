<?php
//              TODO:  ask user to upload again

function upload_avatar() {
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["avatar"]["name"]);
    $extension = end($temp);
    if ((($_FILES["avatar"]["type"] == "image/gif")
            || ($_FILES["avatar"]["type"] == "image/jpeg")
            || ($_FILES["avatar"]["type"] == "image/jpg")
            || ($_FILES["avatar"]["type"] == "image/pjpeg")
            || ($_FILES["avatar"]["type"] == "image/x-png")
            || ($_FILES["avatar"]["type"] == "image/png"))
        && ($_FILES["avatar"]["size"] < 1000000)
        && in_array($extension, $allowedExts)) {
        $filename = md5_file($_FILES["avatar"]["tmp_name"]);

        if ($_FILES["avatar"]["error"] > 0) {
            redirect('register.php', $_FILES["avatar"]["error"], 'error');
        } else {
            if (!file_exists("images/avatars/" . $filename)) {
                move_uploaded_file($_FILES["avatar"]["tmp_name"],
                    "images/avatars/" . $filename);
            }
            return true;
        }
    } else {
        redirect('register.php', 'Invalid File Type!', 'error');
    }
}
