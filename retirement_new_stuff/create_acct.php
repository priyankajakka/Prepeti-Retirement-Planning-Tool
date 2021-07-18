<?php
session_start();
$output = NULL;
//$date=date('m/d/Y');
$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));

$today = date('Y-m-d');

if (isset($_POST["submit"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    /*$birthday = $birthday->format('Y-m-d');*/
    //print gettype($birthday);

    $diff = date_diff(date_create($birthday), date_create($today));
    $curr_age =  (int) floor($diff->days / 365.25);

    // $curr_age = (int) $_POST['curr_age'];
    $ret_age = (int) $_POST['ret_age'];
    $life = (int) $_POST['life'];

    $income = (int) $_POST['income'];
    $money = (int) $_POST['money'];
    $savings = (int) $_POST['savings'];
    $savings_req = (int) $_POST['savings_req'];
    $savings_req_bad = (int) $_POST['savings_req_bad'];
    $networth = (int) $_POST['networth'];
    $reg_date = $_POST['reg_date'];
    $user_portfolio = $_POST['portfolio'];
    //print($reg_date);

    if (
        empty($username) || empty($password) || empty($full_name) || empty($email)
        || empty($curr_age) || empty($ret_age) || empty($life) || empty($income)
        || empty($money) || empty($savings)
    ) {
        $output = "Please enter all fields.";
    } else {
        $mysqli = new MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');

        $username = $mysqli->real_escape_string($username);
        $password = $mysqli->real_escape_string($password);
        $full_name = $mysqli->real_escape_string($full_name);
        $email = $mysqli->real_escape_string($email);
        $birthday = $mysqli->real_escape_string($birthday);
        $reg_date = $mysqli->real_escape_string($reg_date);
        $user_portfolio = $mysqli->real_escape_string($user_portfolio);

        $query = $mysqli->query("SELECT id FROM Login_info WHERE email = '$email'");
        $query2 = $mysqli->query("SELECT id FROM Login_info WHERE username = '$username'");

        if ($query->num_rows == 1) {
            $output = "Looks like an account is already registered with this email address. Try logging in.";
        } else if ($query2->num_rows == 1) {
            $output = "Username is already in use! Enter another username.";
        } else {
            $sql = "INSERT INTO Login_info (username, password, full_name, email) VALUES ('$username', '$password', '$full_name', '$email')";
            mysqli_query($mysqli, $sql);

            $sql = "UPDATE Login_info SET
                curr_age=$curr_age,
                ret_age=$ret_age ,
                life=$life,
                income=$income,
                money=$money ,
                savings=$savings,
                Savings_req_time = $savings_req,
                Savings_req_time_bad = $savings_req_bad,
                Date = '$reg_date',
                Date_networth = '$reg_date',
                Networth_over_time = $networth,
                portfolio = '$user_portfolio',
                birthday = '$birthday'
                WHERE username='$username'";

            mysqli_query($mysqli, $sql);

            $_SESSION['loggedin'] = TRUE;
            $_SESSION['user'] = $username;
            $_SESSION['curr_age'] = $curr_age;
            $_SESSION['ret_age'] = $ret_age;
            $_SESSION['life'] = $life;

            header("location: http://localhost/~sjakka/RetirementToolwUI/retirement_user.php");
            exit;
        }
    }
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        fieldset {
            margin-top: 10%;
            text-align: center;
        }
    </style>
</head>

<body>
    <fieldset>
        <div style="font-size:50; color:black"><strong><?php echo "Create an account! <p/>"; ?></strong></div>
        <?php echo htmlspecialchars($date->format('m/d/Y h:i')); ?><br><br><br>

        <form name="create_acct_form" method="POST">
            <div style="color:black">Enter username <input type="TEXT" name="username" /></div>
            </p>
            <div style="color:black">Enter password <input type="password" name="password" /></div>
            </p>
            <div style="color:black">Enter full name <input type="full_name" name="full_name" /></div>
            </p>
            <div style="color:black">Enter email address <input type="email" name="email" /></div>
            </p>

            <!--<div id = "curr_age_q">
            How old are you?: <br><input type="number" name="curr_age" id="curr_age" onkeyup = "updateSavingsReq()"
            style="text-align: center">
            <br><br>
        </div>-->

            <div id="birthday_q">
                When is your birthday? (MM/DD/YY): <br><input type="date" name="birthday" id="birthday" onkeyup="updateSavingsReq()" style="text-align: center">
                <br><br>
            </div>

            <div id="ret_age_q">
                At what age do you plan to retire?: <br><input type="number" name="ret_age" id="ret_age" onkeyup="updateSavingsReq()" style="text-align: center">
                <br><br>
            </div>

            <div id="life_q">
                Life expectancy?: <br> <input type="number" name="life" id="life" onkeyup="updateSavingsReq()" style="text-align: center">
                <br><br>
            </div>

            <div id="income_q">
                What is your income?: <br><input type="number" name="income" id="income" onkeyup="updateSavingsReq()" style="text-align: center">
                <br><br>
            </div>

            <div id="money_q">
                How much money do you need per year?: <br><input type="number" name="money" id="money" onkeyup="updateSavingsReq()" style="text-align: center">
                <br><br>
            </div>

            <div id="savings_q">
                How much money do you have saved?: <br> <input type="number" name="savings" id="savings" onkeyup="updateSavingsReq()" style="text-align: center">
                <br><br>
            </div>

            <input name='savings_req' type=hidden>
            <input name='savings_req_bad' type=hidden>
            <input name='networth' type=hidden>
            <input name='reg_date' type=hidden>
            <input name='portfolio' type=hidden>

            <input style="color:black" type="SUBMIT" name="submit" value="Create account" />
        </form>
        <div style="color:black"><?php echo $output; ?></div>
        <div style="color:black"><?php echo "Already have an account? <p/>"; ?></div>
        <a href="http://localhost/~sjakka/RetirementToolwUI/ret_login.php" style="margin-left:10; color:black;">
            <button class="tablink">Return to login page</button>
        </a>
        <a href="http://localhost/~sjakka/RetirementToolwUI/retirement_guest.php" style="margin-left:10; color:black;">
            <button class="tablink">Continue as guest.</button>
        </a>

    </fieldset>
</body>

<script>
    function updateSavingsReq() {
        var reg_date = <?php echo json_encode($date->format('m/d/Y')); ?>;


        var curr_bday = parseInt(document.getElementById("birthday").value);
        var curr_year = new Date().getFullYear();

        var curr_age = parseInt(curr_year - curr_bday);

        var ret_age = parseInt(document.getElementById("ret_age").value);
        var life = parseInt(document.getElementById("life").value);
        var income = parseInt(document.getElementById("income").value);
        var money = parseInt(document.getElementById("money").value);
        var savings = parseInt(document.getElementById("savings").value);
        var networth = savings;
        var portfolio;

        console.log(curr_bday + " " + curr_year + " " + curr_age);

        var r1 = 0.07;
        var bad_r1 = 0.06;
        var r2 = 0.04;
        var inf_rate = 0.03; //inflation rate
        var years = life - curr_age; //years left in life
        var accum_years = ret_age - curr_age; //accumulation years
        var distr_years = life - ret_age; //distribution years

        var money_over_time = []

        for (let i = 1; i <= years + 1; i++) {
            var adjusted_money = money * Math.pow(1 + inf_rate, i - 1)
            money_over_time.push(adjusted_money)
        }

        var goal = money_over_time[accum_years + 1] * ((1 / (parseFloat(r2) - inf_rate)) - (Math.pow((1 + inf_rate), distr_years) / ((parseFloat(r2) - inf_rate) * Math.pow((1 + parseFloat(r2)), distr_years))))

        var PV_goal = goal / Math.pow((1 + parseFloat(r1)), accum_years)
        var gap = PV_goal - savings
        var savings_per_year = gap / ((1 / parseFloat(r1)) - (1 / (parseFloat(r1) * Math.pow(1 + parseFloat(r1), accum_years))))

        var bad_savings_per_year = goal / Math.pow((1 + parseFloat(bad_r1)), accum_years);
        var bad_gap = bad_savings_per_year - savings;
        bad_savings_per_year = bad_gap / ((1 / parseFloat(bad_r1)) - (1 / (parseFloat(bad_r1) * Math.pow(1 + parseFloat(bad_r1), accum_years))));

        console.log(savings_per_year + " " + bad_savings_per_year + " " + reg_date);
        document.create_acct_form.reg_date.value = reg_date;
        document.create_acct_form.savings_req.value = parseInt(savings_per_year);
        document.create_acct_form.savings_req_bad.value = parseInt(bad_savings_per_year);
        document.create_acct_form.networth.value = parseInt(networth);
        console.log(document.create_acct_form.savings_req.value + " " + document.create_acct_form.savings_req_bad.value + " " + document.create_acct_form.reg_date.value)

        if (accum_years >= 15) {
            portfolio = "aggressive";
        } else if (accum_years >= 12) {
            portfolio = "moderately aggressive";
        } else if (accum_years >= 10) {
            portfolio = "moderate";
        } else if (accum_years >= 5) {
            portfolio = "moderately conservative";
        } else {
            portfolio = "conservative";
        }

        document.create_acct_form.portfolio.value = portfolio;
    }
</script>

</html>