<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"] {
            width: 60%;
            max-width: 400px;
            margin: 0 auto 20px;
            display: block;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #695cfe;
            color: white;
            text-transform: uppercase;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .close-btn, .save-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .close-btn {
            background-color: #dc3545;
        }

        .save-btn:hover {
            background-color: #218838;
        }

        .close-btn:hover {
            background-color: #c82333;
        }

        .modal-content input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Student Management</h1>
    
    <input type="text" id="searchInput" placeholder="Search by name..." onkeyup="filterTable()">
    
    <table id="studentTable">
        <thead>
            <tr>
                <th>KOD_MURID</th>
                <th>NAMA</th>
                <th>KATEGORI</th>
                <th>JANTINA</th>
                <th>TING</th>
                <th>KELAS</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to the database
            $conn = new mysqli('localhost', 'root', '', 'sport_event');
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }

            // Fetch student data, group by KOD_MURID to avoid duplicates
            $sql = "SELECT kod_murid, nama, kategori, jantina, ting, kelas FROM student_events GROUP BY kod_murid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr onclick='showDetails(" . json_encode($row) . ")'>";
                    echo "<td>" . htmlspecialchars($row['kod_murid']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jantina']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ting']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No students found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>

    <div id="studentModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">Close</button>
            <h2>Student Details</h2>
            <p><strong>KOD_MURID:</strong> <span id="modalKodMurid"></span></p>
            <p><strong>NAMA:</strong> <span id="modalNama"></span></p>
            <p><strong>KATEGORI:</strong> <span id="modalKategori"></span></p>
            <p><strong>JANTINA:</strong> <span id="modalJantina"></span></p>
            <p><strong>TING:</strong> <span id="modalTing"></span></p>
            <p><strong>KELAS:</strong> <span id="modalKelas"></span></p>
            <p><strong>KEDUDUKAN:</strong> <input type="text" id="modalKedudukan"></p>
            <p><strong>MATA:</strong> <input type="text" id="modalMata"></p>
            <p><strong>REKOD:</strong> <input type="text" id="modalRekod"></p>
            <button class="save-btn" onclick="saveDetails()">Save</button>
        </div>
    </div>

    <script>
        let currentKodMurid;

        function filterTable() {
            const input = document.getElementById('searchInput').value.toUpperCase();
            const table = document.getElementById('studentTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByTagName('td')[1];
                if (nameCell) {
                    const nameValue = nameCell.textContent || nameCell.innerText;
                    rows[i].style.display = nameValue.toUpperCase().includes(input) ? '' : 'none';
                }
            }
        }

        function showDetails(row) {
            currentKodMurid = row.kod_murid;
            document.getElementById('modalKodMurid').innerText = row.kod_murid;
            document.getElementById('modalNama').innerText = row.nama;
            document.getElementById('modalKategori').innerText = row.kategoriI;
            document.getElementById('modalJantina').innerText = row.jantina;
            document.getElementById('modalTing').innerText = row.ting;
            document.getElementById('modalKelas').innerText = row.kelas;

            // Reset fields before showing modal
            document.getElementById('modalKedudukan').value = '';
            document.getElementById('modalMata').value = '';
            document.getElementById('modalRekod').value = '';

            document.getElementById('studentModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('studentModal').style.display = 'none';
        }

        function saveDetails() {
            const kedudukan = document.getElementById('modalKedudukan').value;
            const mata = document.getElementById('modalMata').value;
            const rekod = document.getElementById('modalRekod').value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "student_update.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert("Details updated successfully");
                    closeModal();
                    location.reload(); // Refresh to reflect changes
                }
            };
            xhr.send(`kod_murid=${currentKodMurid}&kedudukan=${kedudukan}&mata=${mata}&rekod=${rekod}`);
        }
    </script>
</body>
</html>
