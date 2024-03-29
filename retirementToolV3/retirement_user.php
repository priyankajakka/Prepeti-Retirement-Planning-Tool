<?php
    session_start();
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: http://localhost/~sjakka/RetirementToolwUI/retirement_guest.php");
        exit;
    }
    
    //$date=date('m/d/Y');
    $date= new DateTime("now", new DateTimeZone('America/Los_Angeles') );
    
    $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
    $curr_username = $_SESSION['user'];

    $stmt = $mysqli->prepare("SELECT DISTINCT ret_age FROM Login_Info WHERE username = '$curr_username'");
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();
    $_SESSION['ret_age'] = $value->ret_age;

    $stmt = $mysqli->prepare("SELECT DISTINCT curr_age FROM Login_Info WHERE username = '$curr_username'");
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();
    $_SESSION['curr_age'] = $value->curr_age;

    if($_SESSION['ret_age'] - $_SESSION['curr_age'] >= 15){
        $portfolio = "aggressive";
    }else if($_SESSION['ret_age'] - $_SESSION['curr_age'] >= 12){
        $portfolio = "moderately aggressive";
    }else if($_SESSION['ret_age'] - $_SESSION['curr_age'] >= 10){
        $portfolio = "moderate";
    }else if($_SESSION['ret_age'] - $_SESSION['curr_age'] >= 5){
        $portfolio = "moderately conservative";
    }else{
        $portfolio = "conservative";
    }

    $sql = "UPDATE Login_info SET
        portfolio = '$portfolio'
        WHERE username='$curr_username'";
        
    mysqli_query($mysqli, $sql);

    if(isset($_REQUEST['submitStocks'])) {
        $stocks = explode(",", $_REQUEST['stocks_list']);
        $stocks_blob = "";
        $stocks_blob = implode(",",$stocks);
        
        $sql = "UPDATE Login_info SET stocks = '$stocks_blob' WHERE username = '$curr_username'";
        mysqli_query($mysqli, $sql);
        
        $stocks_values = explode(",", $_REQUEST['stocks_values_list']);
        $stocks_values_blob = "";
        $stocks_values_blob = implode(",",$stocks_values);
        
        $sql = "UPDATE Login_info SET stock_values = '$stocks_values_blob' WHERE username = '$curr_username'";
        mysqli_query($mysqli, $sql);
        
        $dates = explode(",", $_POST['dates_networth_over_time']);
        $dates_blob = "";
        $dates_blob = implode(",",$dates);
        //print $dates_blob;
        
        $sql = "UPDATE Login_info SET Date_networth = '$dates_blob' WHERE username = '$curr_username'";
        if(mysqli_query($mysqli, $sql)){
        }
        
        $networth_over_time = explode(",", $_POST['networth_over_time_stocks']);
        $networth_over_time_blob = "";
        $networth_over_time_blob = implode(",",$networth_over_time);
        
        $sql = "UPDATE Login_info SET Networth_over_time = '$networth_over_time_blob' WHERE username = '$curr_username'";
        if(mysqli_query($mysqli, $sql)){
        }
    }
    
    $stmt = $mysqli->prepare("SELECT DISTINCT stocks FROM Login_Info WHERE username = '$curr_username'");
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();
    $_SESSION['stocks'] = $value->stocks;
    
    $stmt = $mysqli->prepare("SELECT DISTINCT stock_values FROM Login_Info WHERE username = '$curr_username'");
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();
    $_SESSION['stock_values'] = $value->stock_values;
    
    if(isset($_POST["submit"])){
        
        $money = (int) $_POST['money'];
        $savings = (int) $_POST['savings'];
        $income = (int) $_POST['income'];
        
        $sql = "UPDATE Login_info SET
        money = $money,
        savings = $savings,
        income = $income
        WHERE username='$curr_username'";
        
        mysqli_query($mysqli, $sql);
        
        $dates = explode(",", $_POST['dates_over_time']);
        $dates_blob = "";
        $dates_blob = implode(",",$dates);
        
        $sql = "UPDATE Login_info SET Date = '$dates_blob' WHERE username = '$curr_username'";
        if(mysqli_query($mysqli, $sql)){
        }
        
        $savings_req_over_time = explode(",", $_POST['savings_req_over_time']);
        $savings_req_over_time_blob = "";
        $savings_req_over_time_blob = implode(",",$savings_req_over_time);
        
        $sql = "UPDATE Login_info SET Savings_req_time = '$savings_req_over_time_blob' WHERE username = '$curr_username'";
        if(mysqli_query($mysqli, $sql)){
        }
        
        $savings_req_over_time_bad = explode(",", $_POST['savings_req_over_time_bad']);
        $savings_req_over_time_bad_blob = "";
        $savings_req_over_time_bad_blob = implode(",",$savings_req_over_time_bad);
        
        $sql = "UPDATE Login_info SET Savings_req_time_bad = '$savings_req_over_time_bad_blob' WHERE username = '$curr_username'";
        if(mysqli_query($mysqli, $sql)){
        }

        $dates_networth = explode(",", $_POST['dates_networth_over_time']);
        $dates_networth_blob = "";
        $dates_networth_blob = implode(",",$dates_networth);
        
        $sql = "UPDATE Login_info SET Date_networth = '$dates_networth_blob' WHERE username = '$curr_username'";
        if(mysqli_query($mysqli, $sql)){
        }
        
        $networth_over_time = explode(",", $_POST['networth_over_time']);
        $networth_over_time_blob = "";
        $networth_over_time_blob = implode(",",$networth_over_time);
        
        $sql = "UPDATE Login_info SET Networth_over_time = '$networth_over_time_blob' WHERE username = '$curr_username'";
        if(mysqli_query($mysqli, $sql)){
        }
        
    }
    $query1 = $mysqli->query("SELECT * FROM Login_info WHERE username = '$curr_username'");
    $query = $query1->fetch_assoc();
    $portfolio = json_encode($query["portfolio"]);
    $query2 = $mysqli->query("SELECT * FROM Portfolio_options WHERE portfolio = $portfolio");
    $query3 = $query2->fetch_assoc();
    ?>

<!DOCTYPE html>
<html lang="en">
    <div id="wrapper" style="text-align:center;">
        <head>
            <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible">
            <meta content="width=device-width, initial-scale=1, maximum-scale=5" name="viewport">
            <script src="http://d3js.org/d3.v4.min.js"> </script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
            <link rel="stylesheet" href="http://localhost/~sjakka/RetirementToolwUI/retirementplswork.css">


            <script>
                document.addEventListener("DOMContentLoaded", function(){
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        document.getElementById('navbar_top').classList.add('fixed-top');
                        document.getElementById('navbar_top').style.background = "#1c1a1f";
                        // add padding top to show content behind navbar
                        navbar_height = document.querySelector('.navbar').offsetHeight;
                        document.body.style.paddingTop = navbar_height + 'px';
                    } else {
                        document.getElementById('navbar_top').classList.remove('fixed-top');
                        document.getElementById('navbar_top').style.background = "#1c1a1f";
                        // remove padding top from body
                        //document.body.style.paddingTop = '0';
                    } 
                });
                }); 

                $(document).ready(function(){
                                $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                                                            localStorage.setItem('activeTab', $(e.target).attr('href'));
                                                            });
                                var activeTab = localStorage.getItem('activeTab');
                                if(activeTab){
                                $('#myTab a[href="' + activeTab + '"]').tab('show');
                                }
                                });
            </script>
            <title>Retirement</title>
        </head>
        <body style="background-color:#1c1a1f; color: white;">

            <section id="navbar">
                <div class="container">
                    <nav id = "navbar_top" class="navbar navbar-dark navbar-expand-lg" data-navbar-on-scroll="data-navbar-on-scroll">
                        <a class="navbar-brand" data-toggle="tab" href="#overview">
                        <img src="logo5.png" style = "width:220px" alt="">
                        </a>
                        <!--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>-->

                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon">
                                <i class="fa fa-bars fa-lg"></i>
                            </span>
                        </button>

                        <b-avatar class="mr-3"></b-avatar>
                
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto" id = "myTab">
                                <li class="nav-item active">
                                    <a class="nav-link" data-toggle="tab" href="#overview">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#portfolio">Portfolio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#menu2">Savings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#menu3">Investments</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#menu4">My Data</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#menu5">Contact</a>
                                </li>

                                <li class="dropdown">
                                    <a class="nav-link nav-link-icon" href="#" id="navbar-default_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="glyphicon glyphicon-user"></span>
                                        <span class="nav-link-inner--text d-lg-none">Profile</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
                                        <a class="dropdown-item" href="http://localhost/~sjakka/RetirementToolwUI/acct_settings.php">Account Settings</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="http://localhost/~sjakka/RetirementToolwUI/ret_logout.php">Logout</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </section>
            <br><br><br><br>
            <!-- Header End -->
            <div class="container">
                <div class="tab-content">
                    <div id="overview" class="tab-pane fade in active">
                        <br><br>
                        <h1>My Retirement Plan</h1>
                        <br><br>

                        <div class="row flex-center">
                            <div class="col-md-8">
                                <div class = "col-md-12">
                                    <div data-aos="fade-up" class="m-b-15">
                                        <div style = "margin:0; padding:0;">
                                            <div class="card h-100 mb-3 bg-dark rounded-3">
                                                <h5 class="card-header">
                                                    <a class="collapsed d-block" data-toggle="collapse" href="#collapse-collapsed" aria-expanded="true" aria-controls="collapse-collapsed" id="heading-collapsed">
                                                        <i class="fa fa-chevron-down pull-right"></i>
                                                        <h3 class="fw-bold" style = "color:white">Inflation Adjusted M<br/>vs Year</h3>
                                                    </a>
                                                </h5>
                                                <div id="collapse-collapsed" class="collapse" aria-labelledby="heading-collapsed">
                                                    <div class="card-body">
                                                        <div id="copy_graph" class="aGraph"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="card h-100 mb-3 bg-dark rounded-3">
                                                <h5 class="card-header">
                                                    <a class="collapsed d-block" data-toggle="collapse" href="#collapse-collapsed2" aria-expanded="true" aria-controls="collapse-collapsed" id="heading-collapsed">
                                                        <i class="fa fa-chevron-down pull-right"></i>
                                                        <h3 class="fw-bold" style = "color:white">Wealth vs Year<br/></h3>
                                                    </a>
                                                </h5>
                                                <div id="collapse-collapsed2" class="collapse" aria-labelledby="heading-collapsed">
                                                    <div class="card-body">
                                                        <div id="copy_graph2" class="aGraph2"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100 pinkBorder mb-3 bg-darkest rounded-3">
                                    <br>
                                    <h3 class="fw-bold" style = "color: white;">Your Finances</h3>
                                    <div id="summary" class="summary" style = "font-size:20"></div>
                                </div>
                            </div>
                        </div>

                        <br>
                        
                        <div class="row flex-center">
                            <div class = "col-md-12">
                                <div data-aos="fade-up" class="m-b-15">
                                    <div style = "margin:0; padding:0;">
                                        <div class="">
                                            <br><br>
                                            <h3 class="fw-bold" style = "color: white;">Are you on track for Retirement?</h3>
                                            <div class="help-tip">
                                                <p>We can write stuff in here about this table and yeah.</p>
                                            </div>
                                            <br>
                                            <table class="table table2Width" style = "font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th style = "font-weight: normal;" scope="col">Stock</th>
                                                        <th style = "font-weight: normal;" scope="col">Your Investment</th>
                                                        <th style = "font-weight: normal;" scope="col">Recommended Investment</th>
                                                        <th style = "font-weight: normal;" scope="col">Difference (%)</th>
                                                        <th style = "font-weight: normal;" scope="col">Difference ($)</th>
                                                    </tr>
                                                </thead>
                                                <tbody style = "font-size: 10px">
                                                    <tr>
                                                        <th scope="row" style = "font-size: 10px; font-weight: normal;">US small cap</th>
                                                        <td id = "copy_your_us_small"></td>
                                                        <td id = "copy_rec_us_small"></td>
                                                        <td id = "copy_diff_us_small"></td>
                                                        <td id = "copy_diff_us_small_money"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" style = "font-size: 10px; font-weight: normal;">US large cap</th>
                                                        <td id = "copy_your_us_large"></td>
                                                        <td id = "copy_rec_us_large"></td>
                                                        <td id = "copy_diff_us_large"></td>
                                                        <td id = "copy_diff_us_large_money"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" style = "font-size: 10px; font-weight: normal;">Intl small cap</th>
                                                        <td id = "copy_your_itl_small"></td>
                                                        <td id = "copy_rec_itl_small"></td>
                                                        <td id = "copy_diff_itl_small"></td>
                                                        <td id = "copy_diff_itl_small_money"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" style = "font-size: 10px; font-weight: normal;">Intl large cap</th>
                                                        <td id = "copy_your_itl_large"></td>
                                                        <td id = "copy_rec_itl_large"></td>
                                                        <td id = "copy_diff_itl_large"></td>
                                                        <td id = "copy_diff_itl_large_money"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br><br>

                        <div class="row flex-center">
                            <div class="col-md-7">
                                <div class="col-md-12 card h-100 pinkBorder mb-3 bg-darkest rounded-3">
                                    <br>
                                    <h3 class="fw-bold" style = "color: white;">Your Investments</h3>
                                    <div id="summary" class="summary" style = "font-size:20"></div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class = "col-md-12">
                                    <div data-aos="fade-up" class="m-b-15">
                                        <div style = "margin:0; padding:0;">
                                            <div class="card h-100 mb-3 bg-dark rounded-3">
                                                <h5 class="card-header">
                                                    <a class="collapsed d-block" data-toggle="collapse" href="#collapse-pie-chart" aria-expanded="true" aria-controls="collapse-collapsed" id="heading-collapsed">
                                                        <i class="fa fa-chevron-down pull-right"></i>
                                                        <h3 class="fw-bold" style = "color:white">Your Portfolio<br/></h3>
                                                    </a>
                                                </h5>
                                                <div id="collapse-pie-chart" class="collapse" aria-labelledby="heading-collapsed">
                                                    <div class="card-body">
                                                    <div id="copy_pie-chart" style = "color:white;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="card h-100 mb-3 bg-dark rounded-3">
                                                <h5 class="card-header">
                                                    <a class="collapsed d-block" data-toggle="collapse" href="#collapse-stocks" aria-expanded="true" aria-controls="collapse-collapsed" id="heading-collapsed">
                                                        <i class="fa fa-chevron-down pull-right"></i>
                                                        <h3 class="fw-bold" style = "color:white">Your Stocks<br/></h3>
                                                    </a>
                                                </h5>
                                                <div id="collapse-stocks" class="collapse" aria-labelledby="heading-collapsed">
                                                    <div class="card-body">
                                                    <div id="copy_barplot"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br><br>

                        <div class="row flex-center">
                            <div class="col-md-8">
                                <div class = "col-md-12">
                                    <div data-aos="fade-up" class="m-b-15">
                                        <div style = "margin:0; padding:0;">
                                            <div class="card h-100 mb-3 bg-dark rounded-3">
                                                <h5 class="card-header">
                                                    <a class="collapsed d-block" data-toggle="collapse" href="#collapse-networth" aria-expanded="true" aria-controls="collapse-collapsed" id="heading-collapsed">
                                                        <i class="fa fa-chevron-down pull-right"></i>
                                                        <h3 class="fw-bold" style = "color:white">Networth over Time<br/></h3>
                                                    </a>
                                                </h5>
                                                <div id="collapse-networth" class="collapse" aria-labelledby="heading-collapsed">
                                                    <div class="card-body">
                                                    <div id="copy_networth_time"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="card h-100 mb-3 bg-dark rounded-3">
                                                <h5 class="card-header">
                                                    <a class="collapsed d-block" data-toggle="collapse" href="#collapse-savings" aria-expanded="true" aria-controls="collapse-collapsed" id="heading-collapsed">
                                                        <i class="fa fa-chevron-down pull-right"></i>
                                                        <h3 class="fw-bold" style = "color:white">Savings Required over Time<br/></h3>
                                                    </a>
                                                </h5>
                                                <div id="collapse-savings" class="collapse" aria-labelledby="heading-collapsed">
                                                    <div class="card-body">
                                                    <div id="copy_savings_req_time"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100 pinkBorder mb-3 bg-darkest rounded-3">
                                    <br>
                                    <h3 class="fw-bold" style = "color: white;">Required Savings Rate</h3>
                                    <p>The required savings rate could go up or down depending upon what the markets do from time to time and what you set aside every year. If the curves continue to stay where they are or perk up, that means you might have to save a bit more to meet your goals. And this is a very aggressive savings plan with lots of wiggle room for changes later on so nothing big to worry about as long as you continue doing at least the bare minimum and ideally the max required.</p>
                                </div>
                            </div>
                        </div>

                        <br><br>
                        <div class="row flex-center">
                            <div class="col-md-12">
                                <div data-aos="fade-up" class="m-b-15">
                                    <div style = "margin:0; padding:0;">
                                        <div class="card h-100 pinkBorder mb-3 bg-darkest rounded-3">
                                            <br>
                                            <h3 class="fw-bold" style = "color: white;">Summary</h3>
                                            <div id="finalSummary" class="summary" style = "font-size:20"></div>                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div id="portfolio" class="tab-pane fade">
                        <br><br>
                        <h1>My Portfolio - <?php echo (trim($portfolio, '"'));?></h1>
                        <br><br>
                        <div id="pie-chart" style = "color:white;"></div>
                        <p id = "portfolio_description"></p>
                        <br>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <br><br>
                        <h1>My Savings</h1>
                        <br><br><br>
                        <section>
                            <div class="row flex-center">
                                <div class="col-md-6">
                                    <div data-aos="fade-up">
                                        <br>
                                        <h2 class="text-white" data-aos="fade-up" data-aos-delay="100"><b>Update your information</b></h2>
                                        <form name="Formnum1" form method = "POST" onsubmit="return updateOverTime()">
                                            <div class="form-group">
                                                <label for="income" class="control-label">What is your income?</label>
                                                <input class="form-control" style = "color: white;" type="number" name="income" id="income" value =
                                                    <?php echo strval($query["income"]); ?> onkeyup="result()"
                                                    style="text-align: center"></input>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <label for="money" class="control-label">How much money do you need per year?</label>
                                                <input class="form-control" style = "color: white;" type="number" name="money" id="money" value =
                                                    <?php echo strval($query["money"]); ?>  onkeyup="result()"
                                                    style="text-align: center"></input>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <label for="savings" class="control-label">How much money do you have saved?</label>
                                                <input class="form-control" style = "color: white;" type="number" name="savings" id="savings" value =
                                                    <?php echo strval($query["savings"]); ?>  onkeyup="result()"
                                                    style="text-align: center"></input>
                                            </div>
                                            <input name='networth_over_time' type=hidden></input>
                                            <input name='dates_networth_over_time' type=hidden></input>
                                            <input name='savings_req_over_time' type=hidden></input>
                                            <input name='savings_req_over_time_bad' type=hidden></input>
                                            <input name='dates_over_time' type=hidden></input>
                                            <div data-aos="fade-up" data-aos-delay="300">
                                                <input class="btn btn-lg btn-info rounded-pill" style = "color:black" type = "SUBMIT" name = "submit" value = "Save Info"/></input>
                                            </div>
                                            <br>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <img src="savingspiggy.png" style = "width:100%;" alt="" class="img-fluid" data-aos="fade-left">
                                </div>
                            </div>
                        </section>
                        <br>
                        <div style = "margin:0; padding:0;">
                            <div class="card mb-3 bg-dark rounded-3">
                                <br>
                                <h3 class="fw-bold">Inflation Adjusted M<br/>vs Year</h3>
                                <div id="graph" class="aGraph"></div>
                            </div>
                        </div>
                        <br><br>
                        <div style = "margin:0; padding:0;">
                            <div class="card mb-3 bg-dark rounded-3">
                                <br>
                                <h3 class="fw-bold">Wealth vs Year<br/></h3>
                                <div id="graph2" class="aGraph2"></div>
                            </div>
                        </div>
                        <br><br>
                    </div>
                    <div id="menu3" class="tab-pane fade">
                        <br><br>
                        <h1>My Investments</h1>
                        <br><br>

                        <div class="row flex-center">
                            <div class = "col-md-12">
                                <div data-aos="fade-up" class="m-b-15">
                                    <div style = "margin:0; padding:0;">
                                        <div class="card h-100 mb-3 blueBorder bg-darkest rounded-3">
                                            <br>
                                            <h3 class="fw-bold" id="stock_options_header" style="margin:5px">Select your stock options</h3>
                                            <br>
                                            <input type="text" id="searchbar" onkeyup="SearchThroughStocks()" placeholder="Search for stock options.."></input>
                                            <nav>
                                                <div id = "stock_option_div">
                                                    <ul class = "stock_option" id="stock_option"></ul>
                                                </div>
                                            </nav>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                        </div>
                        <br><br>


                        <div class="row flex-center">
                            <div class="col-md-5">
                                <div class = "col-md-12">
                                    <div data-aos="fade-up" class="m-b-15">
                                        <div style = "margin:0; padding:0;">
                                            <div class="card h-100 mb-3 noBorder bg-darkest rounded-3">
                                                <p id="stocks_selection"></p>
                                                <br>
                                                <br>
                                                <div id = "money_in_stocks" ></div>
                                                <br>
                                                <form name="Form" method="post" onsubmit="return confirmStockValues()">
                                                    <input name='stocks_list' type=hidden></input>
                                                    <input name='stocks_values_list' type=hidden></input>
                                                    <input name='networth_over_time_stocks' type=hidden></input>
                                                    <input name='dates_networth_over_time' type=hidden></input>
                                                    <div data-aos="fade-up" data-aos-delay="300">
                                                        <input class="btn btn-lg btn-info rounded-pill" style = "color:black" name="submitStocks" type="submit"  value="Confirm selection"></input>
                                                    </div>
                                                </form>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="card h-100 purpleBorder mb-3 bg-darkest rounded-3">
                                    <br>
                                    <h3 class="fw-bold" style = "color: white;">Your Stocks</h3>
                                    <div id="barplot"></div>
                                </div>
                            </div>
                        </div>

                        <br><br>
                        <div class="row flex-center">
                            <div class="col-md-7">
                                <div data-aos="fade-up">
                                    <div class="card h-100 blueBorder mb-3 bg-darkest rounded-3">
                                        <br><br>
                                        <h3 class="fw-bold" style = "color: white;">Are you on track for Retirement?</h3>
                                        <br>
                                        <table class="table tableWidth" style = "font-size: 11px;">
                                            <thead>
                                                <tr>
                                                    <th style = "font-weight: normal;" scope="col">Stock</th>
                                                    <th style = "font-weight: normal;" scope="col">Your Investment</th>
                                                    <th style = "font-weight: normal;" scope="col">Recommended Investment</th>
                                                    <th style = "font-weight: normal;" scope="col">Difference</th>
                                                </tr>
                                            </thead>
                                            <tbody style = "font-size: 10px">
                                                <tr>
                                                    <th scope="row" style = "font-size: 10px; font-weight: normal;">US small cap</th>
                                                    <td id = "your_us_small"></td>
                                                    <td id = "rec_us_small"></td>
                                                    <td id = "diff_us_small"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" style = "font-size: 10px; font-weight: normal;">US large cap</th>
                                                    <td id = "your_us_large"></td>
                                                    <td id = "rec_us_large"></td>
                                                    <td id = "diff_us_large"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" style = "font-size: 10px; font-weight: normal;">Intl small cap</th>
                                                    <td id = "your_itl_small"></td>
                                                    <td id = "rec_itl_small"></td>
                                                    <td id = "diff_itl_small"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" style = "font-size: 10px; font-weight: normal;">Intl large cap</th>
                                                    <td id = "your_itl_large"></td>
                                                    <td id = "rec_itl_large"></td>
                                                    <td id = "diff_itl_large"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br><br>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card h-100 mb-3 bg-darkest rounded-3">
                                    <img src="invest.png" style = "width:85%;text-align: center;margin: auto;justify-content: center;  top: 50%;left: 50%;" alt="" class="img-fluid" data-aos="fade-left">
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                    <div id="menu4" class="tab-pane fade">
                        <br><br>
                        <h1>Track my Data</h1>
                        <p id = "trackData_description"></p>
                        <br><br>
                        <div style = "margin:0; padding:0;">
                            <div class="card mb-3 bg-dark rounded-3">
                                <br>
                                <h3 class="fw-bold" style = "color: white;">Networth over Time<br/></h3>
                                <div id="networth_time"></div>
                            </div>
                            <br>
                            <div class="card mb-3 bg-dark rounded-3">
                                <br>
                                <h3 class="fw-bold" style = "color: white;">Savings Required<br/>over Time</h3>
                                <div id="savings_req_time"></div>
                            </div>
                        </div>
                    </div>
                    <div id="menu5" class="tab-pane fade">
                        <br><br>
                        <h1>Contact Us!</h1>
                        <form target="_blank" action="https://formsubmit.co/priyankajakka@gmail.com" method="POST">
                            <input type="hidden" name="_next" value="http://localhost/~sjakka/RetirementToolwUI/retirement_user.php"></input>
                            <input type="hidden" name="_autoresponse" value="Thank you for submitting the contact form! We will get back to you very soon!"></input>
                            <div class="form-group">
                                <div class="form-row">
                                    <div id="name" class="col">
                                        <input type="text" name="name" class="form-control" placeholder="Full Name" required></input>
                                    </div>
                                    <div class="col">
                                        <input type="email" name="email" class="form-control" placeholder="Email Address" required></input>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea placeholder="Your Message" class="form-control" name="message" rows="10" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-lg btn-dark btn-block">Submit Form</button>
                        </form>
                    </div>
                </div>
            </div>
        </body>
    </div>
    <script>
        var date = <?php echo json_encode($date->format('m/d/Y'));?>;
        var date_arr = <?php echo json_encode($query["Date"]);?>;
        
        var date_networth_arr = <?php echo json_encode($query["Date_networth"]);?>;
        
        var savings_req_arr = <?php echo json_encode($query["Savings_req_time"]);?>;
        var savings_req_arr_bad = <?php echo json_encode($query["Savings_req_time_bad"]);?>;
        var networth_time_arr = <?php echo json_encode($query["Networth_over_time"]);?>;
        var curr_age = <?php echo $_SESSION["curr_age"];?>;
        var ret_age = <?php echo $_SESSION["ret_age"];?>;
        var life = <?php echo $_SESSION["life"];?>;
        var existing_stocks = <?php echo json_encode($_SESSION['stocks']);?>;
        var existing_stock_values = <?php echo json_encode($_SESSION['stock_values']);?>;
        var portfolio = <?php echo json_encode($portfolio);?>;
        var dom_large_percent = <?php echo json_encode($query3["dom_large"]);?>;
        var dom_small_percent = <?php echo json_encode($query3["dom_small"]);?>;
        var int_large_percent = <?php echo json_encode($query3["int_large"]);?>;
        var int_small_percent = <?php echo json_encode($query3["int_small"]);?>;
        var bonds_percent = <?php echo json_encode($query3["bonds"]);?>;
        console.log(portfolio + " " + dom_large_percent + " " + dom_small_percent + " " + int_large_percent + " " + int_small_percent + " " + bonds_percent);
        
        document.getElementById("rec_us_small").innerHTML = dom_small_percent + '%';
        document.getElementById("rec_us_large").innerHTML = dom_large_percent + '%';
        document.getElementById("rec_itl_small").innerHTML = int_small_percent + '%';
        document.getElementById("rec_itl_large").innerHTML = int_large_percent + '%';

        document.getElementById("copy_rec_us_small").innerHTML = dom_small_percent + '%';
        document.getElementById("copy_rec_us_large").innerHTML = dom_large_percent + '%';
        document.getElementById("copy_rec_itl_small").innerHTML = int_small_percent + '%';
        document.getElementById("copy_rec_itl_large").innerHTML = int_large_percent + '%';

        var summaryText = "To comfortably retire by the year " + (parseInt(ret_age) + parseInt(new Date().getFullYear()));
        summaryText += ", you as a family will need to save anywhere from $" + savings_req_arr.split(",")[savings_req_arr.split(",").length - 1];
        summaryText += " to $" + savings_req_arr_bad.split(",")[savings_req_arr_bad.split(",").length - 1];
        summaryText += " each year from now till retirement. This includes the amounts you are saving in your respective 401(k)s so probably not a big gap to fill. And this does not include whatever Social Security income you will collect in retirement so that along with your savings should provide for a very comfortable retirement to your family. Any increase or decrease in the annual savings rate along with what the capital markets are expected to deliver in terms of returns could have an impact on your ability to retire."
        summaryText += "\n\n";
        document.getElementById("finalSummary").innerText = summaryText;
        
    </script>
    <script src="ret_user.js"></script>
</html>