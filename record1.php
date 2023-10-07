<?php
session_start();
include("connect.php"); // Include the database connection

if (!isset($_SESSION['username'])) {
    header("Location: loginexample.php");
    exit();
}

// Function to calculate net availability
function calculateNetAvailability($bloodType, $records, $withdrawals) {
    $depositedPints = 0;
    $withdrawnPints = 0;

    // Calculate deposited pints
    foreach ($records as $record) {
        if ($record['blood_type'] === $bloodType) {
            $depositedPints += $record['blood_pints'];
        }
    }

    // Calculate withdrawn pints
    foreach ($withdrawals as $withdrawal) {
        if ($withdrawal['blood_type'] === $bloodType) {
            $withdrawnPints += $withdrawal['withdrawn_pints'];
        }
    }

    return $depositedPints - $withdrawnPints;
}

// Retrieve data from the database
$bloodStockQuery = "SELECT * FROM blood_stock";
$withdrawalRecordsQuery = "SELECT * FROM withdrawal_records";

$bloodStockResult = mysqli_query($con, $bloodStockQuery);
$withdrawalRecordsResult = mysqli_query($con, $withdrawalRecordsQuery);

if (!$bloodStockResult || !$withdrawalRecordsResult) {
    // Handle database query errors
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Database query error"]);
    exit;
}

// Create arrays to store data from the queries
$bloodStockData = [];
$withdrawalRecordsData = [];

while ($row = mysqli_fetch_assoc($bloodStockResult)) {
    $bloodStockData[] = $row;
}

while ($row = mysqli_fetch_assoc($withdrawalRecordsResult)) {
    $withdrawalRecordsData[] = $row;
}

// Update the $bloodGroups array to include both positive and negative blood types
$bloodGroups = ['A +', 'A-', 'B +', 'B-', 'O +', 'O-', 'AB +', 'AB-'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>BloodView - RECORDS</title>
  <style>

    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      margin: 0;
      padding: 50px;
    }
    /* Container styles */
    .container {

      max-width: 600px;
      height: 600px;
      margin: 0 auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Headings */
    .title {
      color: red;
      font-size: 40px;
      text-align: center;
      margin: 0;
    }
    h1{
       text-align: center;

    }

    h2 {
      text-align: center;
      font-size: 30px;
      margin-top: 40px;
    }

    /* Blood group styles */
    .blood-group {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .blood-group-label {
      width: 120px;
      font-weight: bold;
    }

    .blood-bar-container {
      flex: 1;
      height: 30px;
      background-color: #f2f2f2;
      border-radius: 3px;
      position: relative;
      overflow: hidden;
    }

    .blood-bar {
      height: 100%;
      width: 0;
      position: absolute;
    }

    .blood-fill-animation {
      animation: fillBar 1s ease-in-out forwards;
    }

    .blood-ml {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 14px;
      color: white;
    }

    .blood-bar-green {
      background-color: #4CAF50;
    }

    .blood-bar-yellow {
      background-color: #FFC107;
    }

    .blood-bar-red {
      background-color: #F44336;
    }

    @keyframes fillBar {
      0% {
        width: 0;
      }
      100% {
        width: 100%;
      }
    }

   .link-button-home {
  float: right; /* Float it to the right */
  margin-top: 5px; /* Adjust the top margin to align it properly */
  display: inline-block;
  padding: 5px 10px;
  background-color: #3498db;
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.3s, transform 0.3s;
}

.link-button-home:hover {
  background-color: #2980b9;
  transform: scale(1.05);
}




  </style>
</head>
<body>
  <div class="container">
    <h1><span class="title" style="color: red; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;">BLOOD-VIEW </span><span class="emoji">🩸</span></h1>
    <h2>Blood Units Available</h2>

    <?php
    // Dynamically generate blood group elements based on data
    foreach ($bloodGroups as $bloodType) {
        // Replace '+' and '-' with underscores and prefix with "blood_group_" to create valid class names
        $validClassName = 'blood_group_' . str_replace(['+', '-'], ['_plus', '_minus'], $bloodType);
        
        $netAvailability = calculateNetAvailability($bloodType, $bloodStockData, $withdrawalRecordsData);

        $cssClass = '';

        if ($netAvailability >= 15) {
            $cssClass = 'blood-bar-green';
        } elseif ($netAvailability >= 5) {
            $cssClass = 'blood-bar-yellow';
        } else {
            $cssClass = 'blood-bar-red';
        }
        
        $barWidth = min($netAvailability, 1000) / 10;
        ?>
        <div class="blood-group">
        <div class="blood-group-label"><?= $bloodType ?></div>
        <div class="blood-bar-container">
          <div class="blood-bar <?= $cssClass ?> blood-fill-animation <?= $validClassName ?>" style="width: <?= $barWidth ?>%;"></div>
          <div class="blood-ml"><?= $netAvailability ?> Pints</div>
        </div>
    </div>
    <?php } ?>

    <a href="homepage.php" class="link-button-home">&#x1F519; Go to Home Page</a>



  <script>
    // Function to update blood availability data from the server
    function updateBloodAvailabilityFromServer() {
      fetch('get_blood_availability.php') // Replace with the actual endpoint that fetches data from your database
        .then(response => response.json())
        .then(data => {
          for (const bloodType in data) {
            if (data.hasOwnProperty(bloodType)) {
              const netAvailability = data[bloodType];
              let cssClass = 'blood-bar';

              if (netAvailability >= 15) {
                cssClass += ' blood-bar-green';
              } else if (netAvailability >= 5) {
                cssClass += ' blood-bar-yellow';
              } else {
                cssClass += ' blood-bar-red';
              }

              // Modify the bloodType to use underscores instead of special characters
              const validClassName = bloodType.replace(/\+/g, '_plus').replace(/-/g, '_minus');
              const bloodBarElement = document.querySelector(`.${validClassName}`);
              if (bloodBarElement) {
                bloodBarElement.className = cssClass;
                bloodBarElement.style.width = `${Math.min(netAvailability, 1000) / 10}%`;
                bloodBarElement.querySelector('.blood-ml').textContent = `${netAvailability} ml`;
              }
            }
          }
        })
        .catch(error => {
          console.error('Error fetching blood availability data:', error);
        });
    }

    // Update blood availability graph initially
    updateBloodAvailabilityFromServer();

    // Update blood availability graph periodically (adjust the interval as needed)
    setInterval(updateBloodAvailabilityFromServer, 1000); // 2000 milliseconds = 2 seconds
  </script>
</body>
</html>
