<?php
session_start();
if (!isset($_SESSION['plan']) || !isset($_SESSION['price'])) {
    header("Location: 7plan.php");
    exit();
}
$plan = $_SESSION['plan'];
$price = $_SESSION['price'];

// Database connection
$conn = mysqli_connect("localhost", "root", "", "nexer");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['registerusername'];
    $cardNumber = $_POST['cardNumber'];
    $expDate = $_POST['expDate'];
    $cvv = $_POST['cvv'];
    $cardName = $_POST['cardName'];

    // Check if the user already has a credit card record
    $checkQuery = "SELECT * FROM credit WHERE Username = '$username'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // Update existing record
        $updateQuery = "UPDATE credit SET CardNumber='$cardNumber', Expiration='$expDate', CVV='$cvv', CardName='$cardName' WHERE Username='$username'";
        if (mysqli_query($conn, $updateQuery)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        // Insert new record
        $insertQuery = "INSERT INTO credit (Username, CardNumber, Expiration, CVV, CardName) VALUES ('$username', '$cardNumber', '$expDate', '$cvv', '$cardName')";
        if (mysqli_query($conn, $insertQuery)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
        }
    }

    // Redirect to login page
    header("Location: 9login.php");
    exit();
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Payment/paymentstyle.css">
    <title>Nexer Sign Up</title>
    <style>
        /* Styles for toast message */
        .toast {
            visibility: hidden;
            max-width: 300px;
            margin: 0 auto;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 16px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 17px;
            bottom: 10px;
        }

        .toast.show {
            visibility: visible;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @keyframes fadein {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        @keyframes fadeout {
            from {opacity: 1;}
            to {opacity: 0;}
        }
    </style>
    <script>
        function showToast(message) {
            var toast = document.getElementById("toast");
            toast.innerText = message;
            toast.className = "toast show";
            setTimeout(function() { toast.className = toast.className.replace("show", ""); }, 3000);
        }

        function checkExpiration() {
            var expDateInput = document.getElementById("my2Input").value;
            var currentDate = new Date();
            var enteredDate = new Date(expDateInput);
            
            // Check if entered date is before the current date
            if (enteredDate < currentDate) {
                showToast("The card has expired. Please Enter a New Card!");
                return false;
            } else {
                showToast("Registered Successfully!");
                setTimeout(function() { document.getElementById("paymentForm").submit(); }, 3000); // Submit form after 3 seconds
                return true;
            }
        }
    </script>

</head>
<body>
    <div class="wrapper">
        <header>
            <div class="netflixLogo">
                <a id="logo" href="1index.php"><img src="Payment/Assets/logo.png" alt="Logo Image"></a>
            </div>
            <nav class="sub-nav">
                <a href="#"><i class="fas fa-search sub-nav-logo"></i></a>
                <a href="#"><i class="fas fa-bell sub-nav-logo"></i></a>
                <a href="1index.php">Sign Out</a>
            </nav>
        </header>
        <div class="divider"></div>

        <div class="container">
            <h1>Set up your credit or debit card</h1>
            <form id="paymentForm" method="POST" action="4car.php" onsubmit="return checkExpiration()">
                <div class="cardnum">
                    <input type="text" id="myInput" name="cardNumber" placeholder="Card number" maxlength="15" required>
                </div>
                <div class="expcvv">
                    <input type="text" id="my2Input" name="expDate" placeholder="Expiration date" required>
                    <input type="text" id="my3Input" name="cvv" placeholder="CVV" maxlength="3" required>
                </div>
                <div class="namecard">
                    <input type="text" id="myInput" name="cardName" placeholder="Name on card" required>
                </div>
                <div class="input-container">
                    <input type="text" id="my5Input" value="<?php echo $price; ?>" readonly>
                </div>
                <button class="button" type="submit">Next</button>
            </form>
        </div>
        <div id="toast" class="toast"></div>
    </div>
</body>
</html>
