<?php
include 'connection.php';
include 'layout.php';

$allowed_extensions = array("wav", "mp3", "ogg");

$nameErr = $descriptionErr = $imageErr = $audioErr = "";
$name = $description = $image_file_name = $audio_file_name = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valid = true;

    if (empty($_POST['name'])) {
        $nameErr = "Audio name is required";
        $valid = false;
    } else {
        $name = test_input($_POST['name']);
    }

    if (empty($_POST['description'])) {
        $descriptionErr = "Description is required";
        $valid = false;
    } else {
        $description = test_input($_POST['description']);
    }

    if (empty($_FILES["image"]["name"])) {
        $imageErr = "Image is required";
        $valid = false;
    } else {
        $image_name = basename($_FILES["image"]["name"]);
        $target_dir = "uploads/images/";
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image = time() . "_image." . $image_extension;
        $target_image_file = $target_dir . $image;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_image_file)) {
            $imageErr = "There was an error uploading the image.";
            $valid = false;
        }
    }

    if (empty($_FILES["audio"]["name"])) {
        $audioErr = "Audio file is required";
        $valid = false;
    } else {
        $audio_name = basename($_FILES["audio"]["name"]);
        $target_dir = "uploads/audio/";
        $audio_extension = pathinfo($audio_name, PATHINFO_EXTENSION);

        if (!in_array($audio_extension, $allowed_extensions)) {
            $audioErr = "Only WAV, MP3, and OGG files are allowed.";
            $valid = false;
        } else {
            $audio_file_name = time() . "_audio." . $audio_extension;
            $target_audio_file = $target_dir . $audio_file_name;

            if (!move_uploaded_file($_FILES["audio"]["tmp_name"], $target_audio_file)) {
                $audioErr = "There was an error uploading the audio.";
                $valid = false;
            }
        }
    }

    if ($valid) {
        $sql = "INSERT INTO audios (name, description, image, audio_file) 
                VALUES ('$name', '$description', '$image', '$audio_file_name')";

        if ($conn->query($sql)) {
            header("Location:index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Add New Audio</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Audio Name:</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter audio name" value="<?php echo htmlspecialchars($name); ?>">
            <span class="text-danger"><?php echo $nameErr; ?></span>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter description"><?php echo htmlspecialchars($description); ?></textarea>
            <span class="text-danger"><?php echo $descriptionErr; ?></span>
        </div>

        <div class="form-group">
            <label for="image">Upload Image:</label>
            <input type="file" class="form-control-file" name="image" id="image" accept="image/*">
            <span class="text-danger"><?php echo $imageErr; ?></span>
        </div>

        <div class="form-group">
            <label for="audio">Upload Audio (MP3, WAV, OGG):</label>
            <input type="file" class="form-control-file" name="audio" id="audio" accept=".mp3,.wav,.ogg">
            <span class="text-danger"><?php echo $audioErr; ?></span>
        </div>

        <div class="text-center">
            <input type="submit" class="btn btn-primary" value="Upload">
        </div>
    </form>
</div>
