<?php
session_start();
if (!isset($_SESSION['plan']) || !isset($_SESSION['price']) || !isset($_SESSION['registerusername'])) {
    header("Location: 7plan.php");
    exit();
}
$plan = $_SESSION['plan'];
$price = $_SESSION['price'];
$username = $_SESSION['registerusername']; // Correct session variable

$conn = mysqli_connect("localhost", "root", "", "nexer");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $number = mysqli_real_escape_string($conn, $_POST['mobile_number']);

    // Check if the mobile number is already in use
    $numberCheckQuery = "SELECT * FROM digital WHERE DigitalNumber = '$number'";
    $numberResult = mysqli_query($conn, $numberCheckQuery);

    if (mysqli_num_rows($numberResult) > 0) {
        // Mobile number is already in use
        echo "<script>showToast('Mobile number is already in use');</script>";
    } else {
        // Check if the user already has a digital record
        $checkQuery = "SELECT * FROM digital WHERE Username = '$username'";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            // Update existing record
            $updateQuery = "UPDATE digital SET DigitalNumber='$number' WHERE Username='$username'";
            if (mysqli_query($conn, $updateQuery)) {
                echo "<script>showToast('Record updated successfully');</script>";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } else {
            // Insert new record
            $insertQuery = "INSERT INTO digital (Username, DigitalNumber) VALUES ('$username', '$number')";
            if (mysqli_query($conn, $insertQuery)) {
                echo "<script>showToast('New record created successfully');</script>";
            } else {
                echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
            }
        }

        // Redirect to login page
        header("Location: 9login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Payment/paymentstyle.css">
    <title>Nexer Sign Up</title>
    <style>
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

        function checkPhoneNumber(event) {
            event.preventDefault(); // Prevent form submission
            
            var phoneNumberInput = document.getElementById("my4Input").value;

            if (phoneNumberInput.length < 11) {
                showToast("Please enter a valid mobile number.");
                return false;
            } else {
                showToast("Registered Successfully!");
                setTimeout(function() { document.getElementById("gcash-form").submit(); }, 3000); // Submit the form after 3 seconds
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
            <h1>Set up GCash</h1>
            <h3>Enter your GCash mobile number.</h3>
            <form id="gcash-form" method="POST" action="5digital.php" onsubmit="return checkPhoneNumber(event);">
                <div class="Gcash">
                    <input type="text" name="mobile_number" id="my4Input" placeholder="Mobile number" maxlength="11" required>
                </div>
                <div class="input-container">
                    <input type="text" id="my5Input" value="<?php echo $price; ?>" readonly>
                </div>
                <button type="submit" class="button">Next</button>
            </form>
        </div>
    </div>
    <div id="toast" class="toast"></div>
</body>
</html>
