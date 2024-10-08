<?php
session_start();
if (!isset($_SESSION['user'])) {
    // Redirect to login page if user is not logged in
    header("Location: /php_programs/Final_Project/Nexer/1index.php");
    exit;
}

$user = $_SESSION['user']; // Retrieve user information from session

$conn = mysqli_connect("localhost", "root", "", "nexer");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$fullname = ""; // Initialize $fullname variable
$email = ""; // Initialize $email variable

$query = "SELECT * FROM user WHERE Username = '$user'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        $fullname = $row["Name"];
        $email = $row["Email"];
        $password = $row["Password"];
    }
}

$query = "SELECT BillingDate, Plan FROM user WHERE Username='$user'";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $nextBillingDate = date("F j, Y", strtotime($row['BillingDate']));
    $currentPlan = $row['Plan'];
} else {
    // Default values if data not found
    $nextBillingDate = "Not Available";
    $currentPlan = "Not Available";
}

// Fetch credit card information
$cardNumber = $expDate = $cvv = $cardName = "";

$query = "SELECT * FROM credit WHERE Username = '$user'";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $cardNumber = $row['CardNumber'];
    $expDate = $row['Expiration'];
    $cvv = $row['CVV'];
    $cardName = $row['CardName'];
} else {
    // Insert new credit card information if no existing information found
    $newCardNumber = ""; // Set default values for new credit card information
    $newExpDate = "";
    $newCvv = "";
    $newCardName = "";

}

$digitalNumber = "";

$query = "SELECT DigitalNumber FROM digital WHERE Username = '$user'";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $digitalNumber = $row['DigitalNumber'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newpassword'], $_POST['confirmpassword'])) {
        $newpassword = $_POST['newpassword'];
        $confirmpassword = $_POST['confirmpassword'];

        if ($newpassword === $confirmpassword) {
            // Update the password in the database
            $query = "UPDATE user SET Password='$newpassword' WHERE Username='$user'";
            if (mysqli_query($conn, $query)) {
                $_SESSION['user'] = $user; // Update the user information in session
                $message = "Password updated successfully!";
            } else {
                $message = "Error updating password: " . mysqli_error($conn);
            }
        } else {
            $message = "Passwords do not match!";
        }
    }

    if (isset($_POST['plan'])) {
        $newPlan = $_POST['plan'];
        // Update the plan in the database
        $query = "UPDATE user SET Plan='$newPlan' WHERE Username='$user'";
        if (mysqli_query($conn, $query)) {
            $currentPlan = $newPlan; // Update the current plan variable
            $message = "Plan updated successfully!";
        } else {
            $message = "Error updating plan: " . mysqli_error($conn);
        }
    }

    if (isset($_POST['delete_account'])) {
        $show_delete_confirmation = true;
    }

    if (isset($_POST['confirm_delete'])) {
        // Delete user-related data from the database
        $deleteUserQuery = "DELETE FROM user WHERE Username='$user'";
        $deleteCreditQuery = "DELETE FROM credit WHERE Username='$user'";
        $deleteDigitalQuery = "DELETE FROM digital WHERE Username='$user'";
        $deleteMoviesQuery = "DELETE FROM movies WHERE Username='$user'"; 
        
        if (mysqli_query($conn, $deleteUserQuery) && mysqli_query($conn, $deleteCreditQuery) && mysqli_query($conn, $deleteDigitalQuery) && mysqli_query($conn, $deleteMoviesQuery)) {
            session_destroy(); // Destroy the session
            header("Location: /php_programs/Final_Project/Nexer/1index.php"); 
            exit;
        } else {
            $message = "Error deleting account: " . mysqli_error($conn);
        }
    }
    

    if (isset($_POST['cancel_delete'])) {
        $show_delete_confirmation = false;
    }

    // Update Account Information
    if (isset($_POST['update_profile'])) {
        $newFullname = $_POST['fullname'];
        $newEmail = $_POST['email'];

        // Update the account information in the database
        $query = "UPDATE user SET Name='$newFullname', Email='$newEmail' WHERE Username='$user'";
        if (mysqli_query($conn, $query)) {
            $fullname = $newFullname;
            $email = $newEmail;
            $message = "Account information updated successfully!";
        } else {
            $message = "Error updating account information: " . mysqli_error($conn);
        }
    }

    // Update Credit Card Information
    if (isset($_POST['update_card'])) {
        $newCardNumber = $_POST['cardNumber'];
        $newExpDate = $_POST['expDate'];
        $newCvv = $_POST['cvv'];
        $newCardName = $_POST['cardName'];

        // Update the credit card information in the database
        $query = "UPDATE credit SET CardNumber='$newCardNumber', Expiration='$newExpDate', CVV='$newCvv', CardName='$newCardName' WHERE Username='$user'";
       
        if (mysqli_query($conn, $query)) {
            $cardNumber = $newCardNumber;
            $expDate = $newExpDate;
            $cvv = $newCvv;
            $cardName = $newCardName;
            $message = "Credit card information updated successfully!";
        } else {
            $message = "Error updating credit card information: " . mysqli_error($conn);
        }
    }

    if (isset($_POST['update_digital'])) {
        $digitalNumber = $_POST['digitalnumber'];
        $query1 = "UPDATE digital SET DigitalNumber='$digitalNumber' WHERE Username='$user'";
        if (mysqli_query($conn, $query1)) {
            $message = "Digital wallet information updated successfully.";
        } else {
            $message = "Error updating digital wallet information: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Account/account.css">
    <title>Nexer Account Settings</title>
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
</head>
<body>

<div class="container">
    <h1>Nexer Account Settings</h1>
    <div class="subscription-info">
        <h2>Current Subscription</h2>
        <p><strong>Plan:</strong> <?php echo $currentPlan; ?></p> <!-- Display current subscription plan -->
        <p><strong>Next Billing Date:</strong> <?php echo $nextBillingDate; ?></p> <!-- Display next billing date -->
    </div>
    
    <h2>Account Information</h2>
    <form action="#" method="post">
        <label for="fullname">Account Name</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>">

        <label for="email">Email</label>
        <input type="text" id="email" name="email" value="<?php echo $email; ?>">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" value="<?php echo $password; ?>" readonly class="readonly">

        <input type="submit" name="update_profile" value="Update Profile">
    </form>

    <h2>Payment</h2>
    <form action="#" method="post">
        <label for="cardNumber">Card Number</label>
        <input type="text" id="cardNumber" name="cardNumber" value="<?php echo $cardNumber; ?> " maxlength="15">

        <label for="expDate">Expiration Date</label>
        <input type="text" id="expDate" name="expDate" value="<?php echo $expDate; ?>">

        <label for="cvv">CVV</label>
        <input type="text" id="cvv" name="cvv" value="<?php echo $cvv; ?>" maxlength="3">

        <label for="cardName">Name on Card</label>
        <input type="text" id="cardName" name="cardName" value="<?php echo $cardName; ?>">

        <input type="submit" name="update_card" value="Update Card">

    </form>

    <h2>Digital Wallet Information</h2>
    <form action="#" method="post">
        <label for="digitalnumber">Digital Wallet Number:</label>
        <input type="text" id="digitalnumber" name="digitalnumber" value="<?php echo $digitalNumber; ?>" maxlength="11">

        <input type="submit" name="update_digital" value="Update Digital Number">
    </form>


    <h2>Subscription Settings</h2>
    <form action="#" method="post">
        <label for="plan">Change Plan</label>
        <select id="plan" name="plan">
            <option value="Basic" <?php if ($currentPlan === 'Basic') echo 'selected'; ?>>Basic</option>
            <option value="Standard" <?php if ($currentPlan === 'Standard') echo 'selected'; ?>>Standard</option>
            <option value="Premium" <?php if ($currentPlan === 'Premium') echo 'selected'; ?>>Premium</option>
        </select>
        <input type="submit" value="Update Plan">
    </form>

    <h2>Change Password</h2>
    <form action="#" method="post">
        <label for="newpassword">New Password</label>
        <input type="password" id="newpassword" name="newpassword" required>

        <label for="confirmpassword">Confirm Password</label>
        <input type="password" id="confirmpassword" name="confirmpassword" required>

        <input type="submit" value="Change Password">
    </form>
    <br>
    <form action="1index.php" method="post">
        <input type="submit" value="Logout">
    </form>
    <br>
    <form action="6homepage.php" method="get">
        <input type="submit" value="Back">
    </form>
    <br>
    <form action="#" method="post">
        <input type="submit" name="delete_account" value="Delete Account">
    </form>

    <?php if (isset($show_delete_confirmation) && $show_delete_confirmation): ?>
        <div class="delete-confirmation">
            <p>To ensure the security of your account, before the submitted cancellation application takes effect, you need to confirm the following information:</p>
            <ol>
                <li><strong>Account information:</strong> Account related information will be reset, personal data and billing info (including name, favorites, etc.) will be reset.</li>
                <br>
                <li><strong>Account related assets:</strong> After deactivation, all rights in the account will be reset, products obtained such as subscription etc. will be automatically considered given up, please be sure to know and confirm. Subscription expiry date: Not obtained yet.</li>
                <br>
                <li><strong>Deleted accounts cannot be recovered:</strong> After account cancellation, even if you re-register using the same mobile number, the old account information will not be recovered, you will register with a new user identity.</li>
            </ol>
            <form action="#" method="post">
                <input type="submit" name="confirm_delete" value="Confirm" style="margin-right: 5px;">
                &nbsp;
                <input type="submit" name="cancel_delete" value="Cancel">
            </form>
        </div>
    <?php endif; ?>
</div>
<div id="toast" class="toast"><?php echo isset($message) ? $message : ""; ?></div>
</div>

<script>
    // JavaScript function to show toast message
    function showToast() {
        var toast = document.getElementById("toast");
        toast.className = "toast show";
        setTimeout(function() { toast.className = toast.className.replace("show", ""); }, 3000);
    }

    // Show toast message if $message is set
    <?php if (isset($message)): ?>
        showToast();
    <?php endif; ?>
</script>

</body>
</html>

    