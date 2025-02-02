<?php
include 'connection.php';
include 'layout.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM audios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $description = $row['description'];
        $image = $row['image'];
        $audio = $row['audio_file'];
    } else {
        echo "Record not found.";
        exit();
    }
    $stmt->close();
} else {
    echo "Invalid request.";
    exit();
}

$nameErr = $descriptionErr = $imageErr = $audioErr = "";
$allowed_extensions = array("wav", "mp3", "ogg");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valid = true;
    $id = intval($_POST['id']);
    $image_to_save = $image;
    $audio_to_save = $audio;

    $name = test_input($_POST['name']);
    
    $description = test_input($_POST['description']);
    

    if (isset($_FILES["image"]) && $_FILES["image"]["name"]) {
        $image_name = basename($_FILES["image"]["name"]);
        $target_dir = "uploads/images/";
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

        if (in_array($image_extension, array("jpg", "jpeg", "png", "gif"))) {
            $image_to_save = time() . "_image." . $image_extension;
            $target_image_file = $target_dir . $image_to_save;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_image_file)) {
                if ($image && file_exists($target_dir . $image)) {
                    unlink($target_dir . $image);
                }
            } else {
                $imageErr = "There was an error uploading the image.";
                $valid = false;
            }
        } else {
            $imageErr = "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
            $valid = false;
        }
    }

    if (isset($_FILES["audio"]) && $_FILES["audio"]["name"]) {
        $audio_name = basename($_FILES["audio"]["name"]);
        $target_dir = "uploads/audio/";
        $audio_extension = pathinfo($audio_name, PATHINFO_EXTENSION);

        if (in_array($audio_extension, $allowed_extensions)) {
            $audio_to_save = time() . "_audio." . $audio_extension;
            $target_audio_file = $target_dir . $audio_to_save;

            if (move_uploaded_file($_FILES["audio"]["tmp_name"], $target_audio_file)) {
                if ($audio && file_exists($target_dir . $audio)) {
                    unlink($target_dir . $audio);
                }
            } else {
                $audioErr = "There was an error uploading the audio.";
                $valid = false;
            }
        } else {
            $audioErr = "Only WAV, MP3, and OGG files are allowed.";
            $valid = false;
        }
    }

    if ($valid) {
        $sql = "UPDATE audios SET name=?, description=?, image=?, audio_file=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $name, $description, $image_to_save, $audio_to_save, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $stmt->close();
    }
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Edit Audio</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . htmlspecialchars($id); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <div class="form-group">
            <label for="name">Audio Name:</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
            <span class="text-danger"><?php echo $nameErr; ?></span>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" name="description" id="description" rows="3" required><?php echo htmlspecialchars($description); ?></textarea>
            <span class="text-danger"><?php echo $descriptionErr; ?></span>
        </div>

        <div class="form-group">
            <label for="image">Upload New Image (optional):</label>
            <input type="file" class="form-control-file" name="image" id="image" accept="image/*">
            <span class="text-danger"><?php echo $imageErr; ?></span>
            <div class="mt-3">
                <?php if (!empty($image)): ?>
                    <img src="uploads/images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($name); ?>" class="img-thumbnail" style="width: 300px; height: auto;">
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="audio">Upload New Audio (optional):</label>
            <input type="file" class="form-control-file" name="audio" id="audio" accept=".mp3,.wav,.ogg">
            <span class="text-danger"><?php echo $audioErr; ?></span>
            <div class="mt-3">
                <?php if (!empty($audio)): ?>
                    <audio controls>
                        <source src="uploads/audio/<?php echo htmlspecialchars($audio); ?>" type="audio/<?php echo htmlspecialchars(pathinfo($audio, PATHINFO_EXTENSION)); ?>">
                    </audio>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center">
            <input type="submit" class="btn btn-primary" value="Update">
        </div>
    </form>
</div>
