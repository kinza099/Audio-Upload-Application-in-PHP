<?php
include 'connection.php';
include 'layout.php';


$sql = "SELECT * FROM audios";
$result = $conn->query($sql);
?>

<div class="container">
    <h1 class="my-4">Audio Library</h1>
    <ul class="list-group">
        <?php while($row = $result->fetch_assoc()) { ?>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-4">
                        <h5>Audio Name:</h5>
                        <p><?php echo htmlspecialchars($row['name']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <h5>Description:</h5>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <h5>Image:</h5>
                        <img src="uploads/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-fluid">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <h5>Audio:</h5>
                        <audio controls>
                            <source src="uploads/audio/<?php echo htmlspecialchars($row['audio_file']); ?>" type="audio/<?php echo htmlspecialchars(pathinfo($row['audio_file'], PATHINFO_EXTENSION)); ?>">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="edit.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <a href="delete.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger btn-sm">Delete</a>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>
