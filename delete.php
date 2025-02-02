<?php
include 'connection.php';

$id = $_GET['id'];
$sql = "SELECT image, audio_file FROM audios WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$file_name_image = $row['image'];
$file_name_audio = $row['audio_file'];

unlink("uploads/audio/" . $file_name_audio);
unlink("uploads/images/" . $file_name_image);

$sql = "DELETE FROM audios WHERE id=$id";
$conn->query($sql);

header("Location: index.php");
exit();
?>
