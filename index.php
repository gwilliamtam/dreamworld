<?php
$op = array_key_exists('op', $_GET) ? $_GET['op'] : null;
$email = array_key_exists('email', $_POST) ? $_POST['email'] : '';
$birthday = array_key_exists( 'birthday', $_POST) ? $_POST['birthday'] : '';
$password = array_key_exists( 'password1', $_POST) ? $_POST['password1'] : '';
$confirm_password = array_key_exists( 'confirm_password1', $_POST) ? $_POST['confirm_password1'] : '';

$flag = null;
if ($op === 'register') {
    $flag = validate($email, $birthday, $password, $confirm_password);
}

$message = null;
switch ($flag) {
    case 'bad':
        $message = "Incorrect Data - Something went wrong. <a href='index.php'>Please try again</a>";
        break;
    case 'ok':
        $message = "Congratulations! You registration is now complete";
        break;
    case 'email':
        $message = "Email can't be empty and must have the form your_email@your_domain.com";
        break;
    case 'birthday':
        $message = "Birthday can't be empty and you must 18+ years";
        break;
    case 'match':
        $message = "Your password and your confirmation password must match";
        break;
    case 'password':
        $message = "Your password must be at lease 8 characters long and must contain numbers and letters";
        break;
    case 'age':
        $message = "We are sorry but you must be at least 18 years old to register";
        break;
    case 'duplicated':
        $message = "The email you provide already exists in our system";
        break;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Dream World Partners</title>
    <link rel="stylesheet" href="dreamworld.css">
</head>
<body>
<div class="header">
    <a href="index.php">
        <img src="https://www.dream-singles.com/images/ds-logo-reward.png?20200615">
    </a>
</div>
<div class="main-content">
    <div class="form-container">
        <?php
        if (!empty($message)) {
            ?>
            <div class="form-input message">
                <p><?php echo $message ?></p>
            </div>
            <?php
        }
        if ($flag !== 'ok' && $op !== 'list') {
            ?>
            <form method="post" action="index.php?op=register">
                <div class="form-input">
                    <label>Email</label>
                    <input type="email" name="email"
                           placeholder="Enter your email"
                           value="<?php echo $email; ?>">
                </div>
                <div class="form-input">
                    <label>Birthday</label>
                    <input type="date" name="birthday"
                           placeholder="Enter your birthday (mm/dd/yyyy)"
                           value="<?php echo $birthday; ?>">
                </div>
                <div class="form-input">
                    <label>Password</label>
                    <input type="password" name="password1"
                           placeholder="Enter your password"
                           value="<?php echo $password; ?>">
                </div>
                <div class="form-input">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password1"
                           placeholder="Type your password again for confirmation"
                           value="<?php echo $confirm_password; ?>">
                </div>
                <input type="submit" value="Register" class="form-button">

            </form>
            <?php
        }
        if ($op == 'list') {
            require('User.php');
            $users = new User();
            $allUsers = $users->getAll();
            foreach($allUsers as $row) {
                echo "<div class='list'><span>{$row['email']}</span><span>{$row['birthday']}</span></div>";

            }
        }
        ?>
    </div>
</div>



</body>
</html>

<?php
function validate($email, $birthday, $password, $confirm_password)
{
    require('User.php');
    if (empty($email)) {
        return 'email';
    }
    if (empty($birthday)) {
        return 'birthday';
    }
    $date = new DateTime($birthday);
    $now = new DateTime();
    $interval = $now->diff($date);
    if ($interval->y < 18) {
        return 'age';
    }
    if ($password !== $confirm_password) {
        return 'match';
    }
    if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $password)) {
        return 'password';
    }
    $user = new User();
    if (empty($user->getByEmail($email))) {
        $user->insert($email, $password, $birthday);
        return 'ok';
    } else {
        return 'duplicated';
    }

    return null;
}


