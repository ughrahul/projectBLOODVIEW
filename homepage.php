<!DOCTYPE html>
<html>
<head>
  <title>BloodView - HOMEPAGE</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      margin: 0;
      padding: 50px;
    }

    .container {
      max-width: 500px;
      height: 590px;
      margin: auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0.5, 0.5, 0.5,1);
    }

    h1 {
      color: red;
      text-align: center;
      font-size: 40px;
    }

    h2 {
      text-align: left;
      margin-top: 50px;
       font-size: 30px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    select,
    input[type="date"],
    input[type="text"] {
      width: 100%;
      padding: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      margin-bottom: 2px;
    }

    /* Display buttons as inline-block elements */
    .submit-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }

    input[type="submit"] {
      flex: 1;
      padding: 20px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 4px;
      max-width: 200px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
    background-color: #45a049;
    transform: scale(1.05);
    transition: background-color 0.3s, transform 0.3s;
  }

    /* Styling for "Go to Record Page" link button */
    .header-link {
      display: block;
      text-align: right;
      margin-top: -30px; /* Adjusted to move it up a bit */
      margin-right: 20px;
      text-decoration: none;
      color: blue; /* Changed the text color to blue */
      transition: color 0.3s;
    }
    .header-link:hover {
    color: darkblue; /* Change color on hover */
  }

    /* Styling for the Confirmation Popup */
    .confirmation-popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

    .confirmation-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      max-width: 400px;
      text-align: center;
    }

    .confirmation-content h2 {
      margin-bottom: 10px;
    }

    .button-container {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .accept-button,
    .decline-button {
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .accept-button {
      background-color: #4CAF50;
      color: #fff;
    }

    .decline-button {
      background-color: #FF3333;
      color: #fff;
    }

    .record-link {
  float: right; /* Add this line to float it to the right */
  margin-top: -9px; /* Adjust the top margin to align it properly */
  display: inline-block;
  padding: 5px 10px;
  background-color: #3498db;
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.3s, transform 0.3s;
}

.record-link:hover {
  background-color: #2980b9;
  transform: scale(1.05);
}


  </style>
</head>
<body>
  <div class="container">

    <h1><span class="title" style="color: red; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;">BLOOD-VIEW </span><span class="emoji">🩸</span></h1>
    <a href="record1.php" class="record-link">Go to Record Page &#9658;</a>


    <h2>Data Entry </h2>

    <form action="withdraw.php" method="post">

      <label for="bloodType">Blood Type:</label>
      <select id="bloodType" name="bloodType">
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
      </select>

      <br><br>

      <label for="recordDate">Record Date:</label>
      <input type="date" id="recordDate" name="recordDate">

      <br><br>

      <label for="bloodPints">Blood Pints:</label>
      <input type="text" id="bloodPints" name="bloodPints">

      <br><br>

      <!-- Buttons side by side -->
      <div class="submit-buttons">
        <input type="submit" value="Deposit" onclick="showConfirmation('deposit'); return false;">
        <input type="submit" value="Withdraw" onclick="showConfirmation('withdraw'); return false;">
      </div>
    </form>

    <form action="logout.php" method="post">
      <button style="background-color: #FF5555; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-size: 16px; cursor: pointer; margin-top: 20px; transition: background-color 0.3s, transform 0.3s; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);" type="submit" name="logout">Logout</button>
    </form>
  </div>

  <!-- Popup Confirmation Window -->
  <div id="confirmationPopup" class="confirmation-popup">
    <div class="confirmation-content">
      <h2>Confirm Action</h2>
      <p>Are you sure you want to perform this action?</p>
      <div class="button-container">
        <button class="accept-button" onclick="performAction()">Accept</button>
        <button class="decline-button" onclick="hideConfirmation()">Decline</button>
      </div>
    </div>
  </div>



   <script>
    function showConfirmation(actionType) {
      const confirmationPopup = document.getElementById('confirmationPopup');
      confirmationPopup.style.display = 'block';

      const acceptButton = document.querySelector('.accept-button');
      acceptButton.onclick = () => {
        performAction(actionType);
        hideConfirmation();
      };

      const declineButton = document.querySelector('.decline-button');
      declineButton.onclick = () => {
        hideConfirmation();
      };
    }

    function hideConfirmation() {
      const confirmationPopup = document.getElementById('confirmationPopup');
      confirmationPopup.style.display = 'none';
    }

    function performAction(actionType) {
      console.log('Performing ' + actionType + ' action');
      const bloodType = document.getElementById('bloodType').value;
      const recordDate = document.getElementById('recordDate').value;
      const bloodPints = document.getElementById('bloodPints').value

      


      if (!bloodType || !recordDate || !bloodPints) {
        alert("Please fill in all required fields.");
        return;
      }

      const xhr = new XMLHttpRequest();
      xhr.open('POST', (actionType === 'deposit') ? 'deposit.php' : 'withdraw.php', true); // Use 'withdraw.php' for withdrawal
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function () {
        console.log('Ready state: ' + xhr.readyState + ', Status: ' + xhr.status + ', Response: ' + xhr.responseText);
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            // Success
            alert(`${actionType.toUpperCase()} action performed successfully!`);
            // Reset form fields
            document.getElementById('bloodType').value = '';
            document.getElementById('recordDate').value = '';
            document.getElementById('bloodPints').value = '';
          } else {
            // Error
            alert('An error occurred. Please try again later.');
          }
          hideConfirmation();
        }
      };
      const data = `action=${actionType}&bloodType=${bloodType}&recordDate=${recordDate}&bloodPints=${bloodPints}`;
      xhr.send(data);
    }
  </script>

<script>
document.getElementById('bloodType').addEventListener('submit', function (e) {
  e.preventDefault(); // Prevent the default form submission
  
  const bloodTypeInput = document.getElementById('bloodType').value;
  
  // Format the blood type if needed
  if (!bloodTypeInput.includes('+')) {
    document.getElementById('bloodType').value = bloodTypeInput + '+';
  }
  
  // Submit the form
  this.submit();
});
</script>


  
</body>
</html>
