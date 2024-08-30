<?php
session_start();
$uploadsDir = getcwd() . "/uploads";
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}
if (!isset($_SESSION['dir'])) {
    $_SESSION['dir'] = bin2hex(random_bytes(8));
}

$SANDBOX = $uploadsDir . "/" . md5("！＠Q＃＄％︿＆＊（DEVCORE" . $_SESSION['dir']);
if (!file_exists($SANDBOX)) {
    mkdir($SANDBOX, 0777, true);
}
if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    $filename = basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], "$SANDBOX/" . $filename)) {
        echo "<script>alert('File uploaded! " . "$SANDBOX/" . $filename . " ');</script>";
    }
}

?>

<form enctype='multipart/form-data' action='index.php' method='post'>
    <input type='file' name='file'>
    <input type="submit" value="upload"></p>
</form>
