<?php
session_start();
$output = NULL;
//$date=date('m/d/Y');
$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));

$today = date('Y-m-d');

$mysqli = new MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');

$query4 = $mysqli->query("SELECT email FROM Login_info;");
$emails_array = array();
while ($result = $query4->fetch_assoc()) {
    $emails_array[] = $result['email'];
}

$query4 = $mysqli->query("SELECT username FROM Login_info;");
$usernames_array = array();
while ($result = $query4->fetch_assoc()) {
    $usernames_array[] = $result['username'];
}

if (isset($_POST["submit"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    //$birthday = $birthday->format('Y-m-d');
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    <link rel="stylesheet" href="create_acct2.css">

    <style>
        fieldset {
            margin-top: 0px;
            text-align: center;
        }
    </style>
</head>

<script>
    $(document).ready(function() {

        var current_fs, next_fs, previous_fs;
        var opacity;

        $(".next1").click(function() {
            var email_present = false;
            var emails_arr = <?php echo json_encode($emails_array); ?>;

            for (var i = 0; i < emails_arr.length; ++i) {
                if (emails_arr[i] === document.getElementById("email").value) {
                    email_present = true;
                    break;
                }
            }

            if (email_present === false) {
                if (document.getElementById("full_name").value.length == 0 || document.getElementById("email").value.length == 0) {
                    document.getElementById("page1output").innerText = "Please fill all fields.";
                } else {
                    document.getElementById("page1output").innerText = "";
                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();

                    console.log(current_fs);

                    next_fs.show();
                    current_fs.animate({
                        opacity: 0
                    }, {
                        step: function(now) {
                            opacity = 1 - now;

                            current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            next_fs.css({
                                'opacity': opacity
                            });
                        },
                        duration: 600
                    });

                }

            } else {
                document.getElementById("page1output").innerText = "Looks like an account is already signed up with this email. Try logging in.";
            }
        });



        $(".next2").click(function() {

            var username_present = false;
            var usernames_arr = <?php echo json_encode($usernames_array); ?>;

            for (var i = 0; i < usernames_arr.length; ++i) {
                if (usernames_arr[i] === document.getElementById("username").value) {
                    username_present = true;
                    break;
                }
            }

            if (username_present === false) {
                if (document.getElementById("username").value.length == 0 || document.getElementById("password").value.length == 0 || document.getElementById("cpwd").value.length == 0) {
                    document.getElementById("page2output").innerText = "Please fill all fields.";
                } else if (document.getElementById("password").value != document.getElementById("cpwd").value) {
                    document.getElementById("page2output").innerText = "Passwords do not match.";
                } else {
                    document.getElementById("page2output").innerText = "";
                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();

                    console.log(current_fs);

                    next_fs.show();
                    current_fs.animate({
                        opacity: 0
                    }, {
                        step: function(now) {
                            opacity = 1 - now;

                            current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            next_fs.css({
                                'opacity': opacity
                            });
                        },
                        duration: 600
                    });

                }
            } else {
                document.getElementById("page2output").innerText = "This username is already taken. Please choose another one.";
            }

        });

        $(".next3").click(function() {
            if (document.getElementById("birthday").value.length == 0 || document.getElementById("ret_age").value.length == 0) {
                document.getElementById("page3output").innerText = "Please fill all fields.";
            } else {
                document.getElementById("page3output").innerText = "";

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                console.log(current_fs);

                next_fs.show();
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
            }
        });

        $(".next4").click(function() {
            if (document.getElementById("income").value.length == 0 || document.getElementById("money").value.length == 0 || document.getElementById("savings").value.length == 0) {
                document.getElementById("page4output").innerText = "Please fill all fields.";
            } else {
                document.getElementById("page4output").innerText = "";
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                console.log(current_fs);

                next_fs.show();
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
            }
        });


        $(".previous").click(function() {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            previous_fs.show();

            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 600
            });
        });

        $('.radio-group .radio').click(function() {
            $(this).parent().find('.radio').removeClass('selected');
            $(this).addClass('selected');
        });

        $(".submit").click(function() {
            return false;
        })

    });
</script>

<body>


    <div class="cont">
        <img style="margin-left:10px; margin-right:0;margin-top:10; margin-bottom:0;width:80px" src="UMsitting.png">
        <div class="form">
            <div class="row">
                <div class="col-md-12 mx-0">
                    <form name="create_acct_form" method="POST" id="msform">
                        <fieldset>
                            <div class="form-card">
                                <h2 class="fs-title">Get Started</h2>
                                <div style="font-size:11px">Already have an account? <strong><a style="color:#72E9A5" href="http://localhost/~sjakka/RetirementToolwUI/ret_login.php">Log in</a></strong><br>
                                    Continue as <strong><a style="color:#72E9A5" href="http://localhost/~sjakka/RetirementToolwUI/retirement_guest.php">Guest</a></strong><br>
                                </div>

                                <label style="float:left" for="full_name" class="control-label">Full Name</label>
                                <input class="form-control" type="text" id="full_name" name="full_name" placeholder="Enter your name" />

                                <label style="float:left" for="email" class="control-label">Email address</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="xyz@email.com" />
                                <br>
                                <p id="page1output"></p>

                            </div>
                            <p style="border:1px;display: inline-block; border-style:solid; border-radius:0.5rem;border-color:#2cdddd; padding-top: 0.4em;padding-bottom: 0.4em;padding-right: 1.5em;padding-left: 1.5em;">Step 1 of 5</p>
                            <input type="button" name="next1" class="next1 action-button ButtonNeon" value=&#x2192 />

                            <!--<span class="glyphicon glyphicon-arrow-right"></span>-->
                            <br>____ _ _ _ _<br>

                        </fieldset>
                        <fieldset>
                            <div class="form-card">
                                <h2 class="fs-title">Keep going</h2>

                                <label style="float:left" for="username" class="control-label">Username</label>
                                <input class="form-control" type="text" id="username" name="username" placeholder="Username" />

                                <label style="float:left" for="password" class="control-label">Password</label>
                                <input class="form-control" type="password" id="password" name="password" placeholder="..........." />

                                <label style="float:left" for="cpwd" class="control-label">Confirm Password</label>
                                <input class="form-control" type="password" id="cpwd" name="cpwd" placeholder="..........." />
                                <br>
                                <p id="page2output"></p>

                            </div>
                            <input type="button" name="previous" class="previous action-button-previous ButtonNeon" value=&#x2190 />
                            <p style="border:1px;display: inline-block; border-style:solid; border-radius:0.5rem;border-color:#2cdddd; padding-top: 0.4em;padding-bottom: 0.4em;padding-right: 1.5em;padding-left: 1.5em;">Step 2 of 5</p>
                            <input type="button" name="next2" class="next2 action-button ButtonNeon" value=&#x2192 />
                            <br>_ ____ _ _ _<br>

                        </fieldset>
                        <fieldset>
                            <div class="form-card">
                                <h2 class="fs-title">Almost there</h2>

                                <label style="float:left" for="birthday" class="control-label">Birthday</label>
                                <input class="form-control" type="date" name="birthday" id="birthday" onkeyup="updateSavingsReq()" style="text-align: left; font-size:12px">

                                <div class="form-group required">
                                    <label class="control-label">Gender</label>
                                    <div class="">
                                        <label class="checkstyle">Male
                                            <input class="form-control" type="radio" name="gender" value="Male" checked>
                                            <span class="checkmark"></span>
                                        </label>

                                        <label class="checkstyle">Female
                                            <input class="form-control" type="radio" name="gender" value="Female">
                                            <span class="checkmark"></span>
                                        </label>

                                        <label class="checkstyle">Other
                                            <input class="form-control" type="radio" name="gender" value="Other">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label for="gender" class="error" generated="true"></label>
                                    </div>
                                </div>


                                <label style="float:left" for="ret_age" class="control-label">Retirement Age</label>
                                <input class="form-control" type="number" name="ret_age" id="ret_age" placeholder="When do you plan to retire" onkeyup="updateSavingsReq()" />

                                <br>
                                <p id="page3output"></p>

                            </div>
                            <input type="button" name="previous" class="previous action-button-previous ButtonNeon" value=&#x2190 />
                            <p style="border:1px;display: inline-block; border-style:solid; border-radius:0.5rem;border-color:#2cdddd; padding-top: 0.4em;padding-bottom: 0.4em;padding-right: 1.5em;padding-left: 1.5em;">Step 3 of 5</p>
                            <input type="button" name="next3" class="next3 action-button ButtonNeon" value=&#x2192 />
                            <br>_ _ ____ _ _<br>
                        </fieldset>
                        <fieldset>
                            <div class="form-card">
                                <h2 class="fs-title">Last step</h2>

                                <label style="float:left" for="income" class="control-label">Income</label>
                                <input class="form-control" type="number" name="income" id="income" onkeyup="updateSavingsReq()" placeholder="Enter your annual income" />

                                <label style="float:left" for="money" class="control-label">Annual Expenditure</label>
                                <input class="form-control" type="number" name="money" id="money" placeholder="Enter your annual expenditure" onkeyup="updateSavingsReq()" />

                                <label style="float:left" for="savings" class="control-label">Savings</label>
                                <input class="form-control" type="number" name="savings" id="savings" placeholder="How much money do you have saved" onkeyup="updateSavingsReq()" />
                                <br>
                                <p id="page4output"></p>

                            </div>
                            <input type="button" name="previous" class="previous action-button-previous ButtonNeon" value=&#x2190 />
                            <p style="border:1px;display: inline-block; border-style:solid; border-radius:0.5rem;border-color:#2cdddd; padding-top: 0.4em;padding-bottom: 0.4em;padding-right: 1.5em;padding-left: 1.5em;">Step 4 of 5</p>
                            <input type="button" name="next4" class="next4 action-button ButtonNeon" value=&#x2192 />
                            <br>_ _ _ ____ _<br>
                        </fieldset>
                        <fieldset>
                            <div class="form-card">
                                <h2 class="fs-title">Welcome!</h2>

                                <div class="form-group required">
                                    <div class="">
                                        <label class="checkstyle">
                                            <div style="display:inline-block">I agree to Prepeti's <strong><a style="color:#72E9A5" href="#">Terms of Service</a></strong> and <strong><a style="color:#72E9A5" href="#">Privacy Policy</a></strong></div>
                                            <input class="form-control" type="checkbox" required name="privacy" value="privacyPolicy">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>

                                <br>
                                <input class="ButtonNeon" type="SUBMIT" name="submit" value="Take me to my plan" />
                                <br><br>
                            </div>
                            <input type="button" name="previous" class="previous action-button-previous ButtonNeon" value=&#x2190 />
                            <p style="border:1px;display: inline-block; border-style:solid; border-radius:0.5rem;border-color:#2cdddd; padding-top: 0.4em;padding-bottom: 0.4em;padding-right: 1.5em;padding-left: 1.5em;">Step 5 of 5</p>
                            <br>_ _ _ _ ____<br>
                        </fieldset>
                        <div style="color:black"><?php echo $output; ?></div>
                        <input name='savings_req' type=hidden>
                        <input name='savings_req_bad' type=hidden>
                        <input name='networth' type=hidden>
                        <input name='reg_date' type=hidden>
                        <input name='portfolio' type=hidden>
                        <input name="life" value="100" type=hidden id="life" />
                    </form>
                </div>
            </div>
        </div>
        <div class="sub-cont">
            <img style="margin-left:-105%; margin-right:0;margin-top:35%;width:450px" src="create_acct_pic.png">
        </div>
    </div>
</body>

<script>
    function updateSavingsReq() {
        document.getElementById("life").value = 80;
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
