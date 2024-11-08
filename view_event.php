<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Events Participants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
            display: inline-block;
            margin-right: 10px;
        }
        select, button {
            padding: 5px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color:#695cfe;
            color: #fff;
        }
        .event-title {
            font-size: 20px;
            margin-top: 20px;
            color: #444;
        }
    </style>
</head>
<body>
    <h1>Select Event to View Participants</h1>
    <form method="GET" action="">
        <div class="form-group">
            <label for="acara">Acara:</label>
            <select name="acara" id="acara">
                <option value="All">All</option>
                <option value="60m">60m</option>
                <option value="200m">200m</option>
                <option value="400m">400m</option>
                <option value="800m">800m</option>
                <option value="1500m">1500m</option>
                <option value="Lompat Jauh">Lompat Jauh</option>
                <option value="Lompat Tinggi">Lompat Tinggi</option>
                <option value="Lontar Peluru">Lontar Peluru</option>
                <option value="Lempar Cakera">Lempar Cakera</option>
                <option value="Rejam Lembing">Rejam Lembing</option>
                <option value="4x100m">4x100m</option>
                <option value="4x400m">4x400m</option>
            </select>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori:</label>
            <select name="kategori" id="kategori">
                <option value="All">All</option>
                <option value="L13">L13</option>
                <option value="L15">L15</option>
                <option value="L18">L18</option>
                <option value="P13">P13</option>
                <option value="P15">P15</option>
                <option value="P18">P18</option>
            </select>
        </div>
        <button type="submit">Filter</button>
    </form>

    <?php
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'sport_event');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get filter values
    $acara = isset($_GET['acara']) ? $_GET['acara'] : 'All';
    $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'All';

    // Define all possible events
    $events = ['60m', '200m', '400m', '800m', '1500m', 'Lompat Jauh', 'Lompat Tinggi', 'Lontar Peluru', 'Lempar Cakera', 'Rejam Lembing', '4x100m', '4x400m'];

    // Loop through events and display results for each
    foreach ($events as $event) {
        // If a specific acara is selected and it's not "All", skip the other events
        if ($acara != 'All' && $acara != $event) {
            continue;
        }

        echo "<div class='event-title'>Event: $event</div>";

        // SQL query for each event
        $sql = "SELECT kod_murid, nama, kategori, ting, kelas, jantina, rumah, kedudukan, mata, rekod 
                FROM student_events 
                WHERE ('$event' = event1 OR '$event' = event2 OR '$event' = event3 OR '$event' = event4 OR '$event' = event5)";

        if (!empty($kategori) && $kategori != 'All') {
            $sql .= " AND kategori = '$kategori'";
        }

        $result = $conn->query($sql);
        $uniqueRecords = [];
        while ($row = $result->fetch_assoc()) {
            $key = $row['kod_murid']; // Use the student code as the unique key
            if (!isset($uniqueRecords[$key])) {
                $uniqueRecords[$key] = $row; // Store only unique entries based on 'kod_murid'
            }
        }

        if (count($uniqueRecords) > 0) {
            echo "<table>
                    <tr>
                        <th>Kategori</th>
                        <th>Kod Murid</th>
                        <th>Nama</th>
                        <th>Ting</th>
                        <th>Kelas</th>
                        <th>Jantina</th>
                        <th>Rumah</th>
                        <th>Kedudukan</th>
                        <th>Mata</th>
                        <th>Rekod</th>
                    </tr>";
            foreach ($uniqueRecords as $row) {
                echo "<tr>
                        <td>{$row['kategori']}</td>
                        <td>{$row['kod_murid']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['ting']}</td>
                        <td>{$row['kelas']}</td>
                        <td>{$row['jantina']}</td>
                        <td>{$row['rumah']}</td>
                        <td>{$row['kedudukan']}</td>
                        <td>{$row['mata']}</td>
                        <td>{$row['rekod']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No participants found for the event: $event.</p>";
        }
    }

    $conn->close();
    ?>
</body>
</html>
