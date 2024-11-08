<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sport_event";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register_event'])) {
    // Get form data
    $kod_murid = $_POST['kod_murid'];
    $nama = $_POST['nama'];
    $rumah = $_POST['rumah'];
    $kelas = $_POST['kelas'];
    $mobile = $_POST['mobile'];
    $event1 = $_POST['event1'];
    $event2 = $_POST['event2'];
    $event3 = $_POST['event3'];
    $event4 = $_POST['event4'];
    $event5 = $_POST['event5'];
    $kategori = $_POST['kategori'];

    // Check if there are duplicate events
    if ($event1 && $event2 && $event1 == $event2 ||
        $event1 && $event3 && $event1 == $event3 ||
        $event1 && $event4 && $event1 == $event4 ||
        $event1 && $event5 && $event1 == $event5 ||
        $event2 && $event3 && $event2 == $event3 ||
        $event2 && $event4 && $event2 == $event4 ||
        $event2 && $event5 && $event2 == $event5 ||
        $event3 && $event4 && $event3 == $event4 ||
        $event3 && $event5 && $event3 == $event5 ||
        $event4 && $event5 && $event4 == $event5) {
        
        echo "Error: You have selected duplicate events. Please choose different events.";
    } else {
        // Check if the student already has a record
        $sql_check = "SELECT * FROM student_events WHERE kod_murid = '$kod_murid'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            // If a record already exists, use the UPDATE statement to update event information
            $sql_update = "UPDATE student_events 
                           SET nama='$nama', rumah='$rumah', kelas='$kelas', mobile='$mobile', 
                               event1='$event1', event2='$event2', event3='$event3', event4='$event4', event5='$event5', kategori='$kategori'
                           WHERE kod_murid='$kod_murid'";

            if ($conn->query($sql_update) === TRUE) {
                echo "Update successful!";
            } else {
                echo "Update failed: " . $conn->error;
            }
        } else {
            // If no record exists, use the INSERT statement to add a new record
            $sql_insert = "INSERT INTO student_events (kod_murid, nama, rumah, kelas, mobile, event1, event2, event3, event4, event5, kategori) 
                           VALUES ('$kod_murid', '$nama', '$rumah', '$kelas', '$mobile', '$event1', '$event2', '$event3', '$event4', '$event5', '$kategori')";

            if ($conn->query($sql_insert) === TRUE) {
                echo "Registration successful!";
            } else {
                echo "Insertion failed: " . $conn->error;
            }
        }
    }
}

// Retrieve all student codes
$sql = "SELECT kod_murid, nama, rumah, kelas FROM borangacara";
$result = $conn->query($sql);

// Store individual and team event options
$individual_events = ['60m', '200m', '400m', '800m', '1500m', 'Long Jump', 'High Jump', 'Shot Put', 'Discus Throw', 'Javelin Throw'];
$team_events = ['4x100m', '4x400m'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register_events.css">
    <title>Event Registration</title>
</head>
<body>

<div class="container">
    <a class="back-button" href="index.html">Back</a>
    <h1>Event Registration</h1>

    <form action="register_events.php" method="post">
        <label for="kod_murid">Code:</label>
        <select name="kod_murid" id="kod_murid">
            <option value="">Select Student Code</option>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["kod_murid"] . "'>" . $row["kod_murid"] . " - " . $row["nama"] . "</option>";
                }
            }
            ?>
        </select>
        
        <label for="nama">Name:</label>
        <input type="text" id="nama" name="nama" readonly>

        <label for="rumah">House:</label>
        <input type="text" id="rumah" name="rumah" readonly>

        <label for="kelas">Class:</label>
        <input type="text" id="kelas" name="kelas" readonly>

        <label for="mobile">Mobile:</label>
        <input type="text" id="mobile" name="mobile">

        <label for="event1">Event1:</label>
        <select name="event1">
            <option value="">Select Individual Event</option>
            <?php foreach ($individual_events as $event) {
                echo "<option value='$event'>$event</option>";
            } ?>
        </select>

        <label for="event2">Event2:</label>
        <select name="event2">
            <option value="">Select Individual Event</option>
            <?php foreach ($individual_events as $event) {
                echo "<option value='$event'>$event</option>";
            } ?>
        </select>

        <label for="event3">Event3:</label>
        <select name="event3">
            <option value="">Select Individual Event</option>
            <?php foreach ($individual_events as $event) {
                echo "<option value='$event'>$event</option>";
            } ?>
        </select>

        <label for="event4">Event4:</label>
        <select name="event4">
            <option value="">Select Team Event</option>
            <?php foreach ($team_events as $event) {
                echo "<option value='$event'>$event</option>";
            } ?>
        </select>

        <label for="event5">Event5:</label>
        <select name="event5">
            <option value="">Select Team Event</option>
            <?php foreach ($team_events as $event) {
                echo "<option value='$event'>$event</option>";
            } ?>
        </select>

        <label for="kategori">Category:</label>
        <input type="text" id="kategori" name="kategori">

        <button type="submit" name="register_event">Submit</button>
    </form>
</div>

<script>
    document.getElementById('kod_murid').addEventListener('change', function() {
    var kodMurid = this.value;
    if (kodMurid) {
        // Retrieve the student's information and registered events from the database and auto-fill form fields
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_student_info.php?kod_murid=' + kodMurid, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var student = JSON.parse(this.responseText);
                document.getElementById('nama').value = student.nama;
                document.getElementById('rumah').value = student.rumah;
                document.getElementById('kelas').value = student.kelas;
                document.getElementById('kategori').value = student.kategori;
                // Fill in the registered events
                document.querySelector('select[name="event1"]').value = student.event1 || '';
                document.querySelector('select[name="event2"]').value = student.event2 || '';
                document.querySelector('select[name="event3"]').value = student.event3 || '';
                document.querySelector('select[name="event4"]').value = student.event4 || '';
                document.querySelector('select[name="event5"]').value = student.event5 || '';
            }
        };
        xhr.send();
    }
    });

    // Prevent selecting duplicate events
    document.querySelector('form').addEventListener('submit', function(event) {
        // Get all event values
        var event1 = document.querySelector('select[name="event1"]').value;
        var event2 = document.querySelector('select[name="event2"]').value;
        var event3 = document.querySelector('select[name="event3"]').value;
        var event4 = document.querySelector('select[name="event4"]').value;
        var event5 = document.querySelector('select[name="event5"]').value;

        // Create an array of events
        var events = [event1, event2, event3, event4, event5].filter(Boolean); // Remove empty values

        // Check if the array has duplicates
        var hasDuplicate = new Set(events).size !== events.length;

        if (hasDuplicate) {
            alert("You have selected duplicate events. Please choose different events.");
            event.preventDefault(); // Prevent form submission
        }
    });

</script>

</body>
</html>
