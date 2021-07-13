<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
      header("location: http://localhost/~sjakka/RetirementToolwUI/retirement_guest.php");
      exit;
    }

    //$date=date('m/d/Y');
    $date= new DateTime("now", new DateTimeZone('America/Los_Angeles') );
    //print $date;
    
    $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
    $curr_username = $_SESSION['user'];

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

      $dates = explode(",", $_POST['dates_over_time']);
      $dates_blob = "";
      $dates_blob = implode(",",$dates);
      //print $dates_blob;

      $sql = "UPDATE Login_info SET Date = '$dates_blob' WHERE username = '$curr_username'";
      if(mysqli_query($mysqli, $sql)){
      }else{
        printf("Error message: %s\n", $mysqli->error);
      }

      $networth_over_time = explode(",", $_POST['networth_over_time_stocks']);
      $networth_over_time_blob = "";
      $networth_over_time_blob = implode(",",$networth_over_time);
      //print "STOCK ";
      //print $networth_over_time_blob;

      $sql = "UPDATE Login_info SET Networth_over_time = '$networth_over_time_blob' WHERE username = '$curr_username'";
      if(mysqli_query($mysqli, $sql)){
      }else{
        printf("Error message: %s\n", $mysqli->error);
      }
    }

    $stmt = $mysqli->prepare("SELECT DISTINCT stocks FROM Login_Info WHERE username = '$curr_username'");
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();
    $_SESSION['stocks'] = $value->stocks;
    //print $_SESSION['stocks'];

    $stmt = $mysqli->prepare("SELECT DISTINCT stock_values FROM Login_Info WHERE username = '$curr_username'");
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();
    $_SESSION['stock_values'] = $value->stock_values;
    //print $_SESSION['stock_values'];

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
      //print $dates_blob;

      $sql = "UPDATE Login_info SET Date = '$dates_blob' WHERE username = '$curr_username'";
      if(mysqli_query($mysqli, $sql)){
      }else{
        printf("Error message: %s\n", $mysqli->error);
      }

      $savings_req_over_time = explode(",", $_POST['savings_req_over_time']);
      $savings_req_over_time_blob = "";
      $savings_req_over_time_blob = implode(",",$savings_req_over_time);
      //print $savings_req_over_time_blob;

      $sql = "UPDATE Login_info SET Savings_req_time = '$savings_req_over_time_blob' WHERE username = '$curr_username'";
      if(mysqli_query($mysqli, $sql)){
      }else{
        printf("Error message: %s\n", $mysqli->error);
      }

      $savings_req_over_time_bad = explode(",", $_POST['savings_req_over_time_bad']);
      $savings_req_over_time_bad_blob = "";
      $savings_req_over_time_bad_blob = implode(",",$savings_req_over_time_bad);
      print $savings_req_over_time_bad_blob;

      $sql = "UPDATE Login_info SET Savings_req_time_bad = '$savings_req_over_time_bad_blob' WHERE username = '$curr_username'";
      if(mysqli_query($mysqli, $sql)){
      }else{
        printf("Error message: %s\n", $mysqli->error);
      }

      $networth_over_time = explode(",", $_POST['networth_over_time']);
      $networth_over_time_blob = "";
      $networth_over_time_blob = implode(",",$networth_over_time);
      //print $networth_over_time_blob;

      $sql = "UPDATE Login_info SET Networth_over_time = '$networth_over_time_blob' WHERE username = '$curr_username'";
      if(mysqli_query($mysqli, $sql)){
      }else{
        printf("Error message: %s\n", $mysqli->error);
      }
  
    }
    $query1 = $mysqli->query("SELECT * FROM Login_info WHERE username = '$curr_username'");
    $query = $query1->fetch_assoc();
    $portfolio = json_encode($query["portfolio"]);
    $query2 = $mysqli->query("SELECT * FROM Portfolio_options WHERE portfolio = $portfolio");
    $query3 = $query2->fetch_assoc();
?>

<!DOCTYPE html>
<html>
  <div id="wrapper" style="text-align:center">
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <script src="http://d3js.org/d3.v4.min.js"> </script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
      </script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js">
      </script>
      <link rel="stylesheet" href="http://localhost/~sjakka/RetirementToolwUI/retirementplswork.css">

      <script>
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


      <title>Retirement
      </title>
      <div>
        <h1>Hello <b><?php echo htmlspecialchars($_SESSION["user"]); ?></b>!</h1>
        <p>
          <b>
          <?php echo htmlspecialchars($date->format('m/d/Y h:i')); ?>
          </b>
        </p>
      </div>
    </head>
    <body>
      <div class="container">
        <ul class="nav nav-tabs" id = "myTab">
          <li id = "portfolio_tab" class="active">
            <a data-toggle="tab" href="#portfolio">My Portfolio
            </a>
          </li>
          <li id = "savings_tab">
            <a data-toggle="tab" href="#menu2">My Savings
            </a>
          </li>
          <li>
            <a data-toggle="tab" href="#menu3">My Investments
            </a>
          </li>
          <li>
            <a data-toggle="tab" href="#menu4">Track my Data
            </a>
          </li>
          <li>
            <a data-toggle="tab" href="#menu5">Contact Us
            </a>
          </li>
          <li>
            <a href="http://localhost/~sjakka/RetirementToolwUI/acct_settings.php">Account Settings
            </a>
          </li>
          <li>
            <a href="http://localhost/~sjakka/RetirementToolwUI/ret_logout.php">Logout
            </a>
          </li>
        </ul>
        <div class="tab-content">
          <div id="portfolio" class="tab-pane fade in active">
            <h3>My Portfolio
            </h3>
            <div id="pie-chart">
            </div>
            <p id = "portfolio_description">
            </p>
            <br>
          </div>
          <div id="menu2" class="tab-pane fade">
            <h3>My Savings
            </h3>
            <form name="Formnum1" form method = "POST" onsubmit="return updateOverTime()">
              What is your income: 
              <br> 
              <input type="number" name="income" id="income" value = 
                     <?php echo strval($query["income"]); ?> onkeyup="result()"
              style="text-align: center">
              <br>
              <br>
              How much money do you need per year: 
              <br> 
              <input type="number" name="money" id="money" value = 
                     <?php echo strval($query["money"]); ?>  onkeyup="result()"
              style="text-align: center">
              <br>
              <br>
              How much money do you have saved: 
              <br>
              <input type="number" name="savings" id="savings" value = 
                     <?php echo strval($query["savings"]); ?>  onkeyup="result()"
              style="text-align: center">
              <br>
              <br>
              <input name='networth_over_time' type=hidden>
              <input name='savings_req_over_time' type=hidden>
              <input name='savings_req_over_time_bad' type=hidden>
              <input name='dates_over_time' type=hidden>
              <input style = "color:black" type = "SUBMIT" name = "submit" value = "Save Info"/>
              <br>
              <br>
            </form>
            <div id="summary" class="summary">
            </div>
            <br>
            <br>
            <br>
            <div id="graph" class="aGraph">
            </div>
            <br>
            <div id="graph2" class="aGraph2">
            </div>
            <br>
          </div>
          <div id="menu3" class="tab-pane fade">
            <h3>My Investments
            </h3>
            <div id="myDIV" class="header">
              <h4 id="stock_options_header" style="margin:5px">Select your stock options
              </h4>
            </div>
            <input type="text" id="searchbar" onkeyup="SearchThroughStocks()" placeholder="Search for stock options..">
            <nav>
              <div id = "stock_option_div">
                <ul class = "stock_option" id="stock_option">
                </ul>
              </div>
            </nav>
            <br>
            <br>
            <p id="stocks_selection">
            </p>
            <br>
            <br>
            <div id = "money_in_stocks" >
            </div>
            <br>
            <br>
            <form name="Form" method="post" onsubmit="return confirmStockValues()">
              <input name='stocks_list' type=hidden>
              <input name='stocks_values_list' type=hidden>
              <input name='networth_over_time_stocks' type=hidden>
              <input name='dates_over_time' type=hidden>
              <input name="submitStocks" type="submit"  value="Confirm selection">
            </form>
            <br>
            <div id="barplot">
            </div>
            <br>
            <br>
            <br>
          </div>
          <div id="menu4" class="tab-pane fade">
            <h3>Track my Data
            </h3>
            <p id = "trackData_description">
            </p>
            <div id="savings_req_time">
            </div>
            <br>
            <div id="networth_time">
            </div>
          </div>
          <div id="menu5" class="tab-pane fade">
            <div class="container" >
                <h1>Contact Me</h1>
                <form target="_blank" action="https://formsubmit.co/priyankajakka@gmail.com" method="POST">
                  <input type="hidden" name="_next" value="http://localhost/~sjakka/RetirementToolwUI/retirement_user.php">
                  <input type="hidden" name="_autoresponse" value="Thank you for submitting the contact form! We will get back to you very soon!">
                  <!-- <input type="hidden" name="_captcha" value="false">   -->
                  <div class="form-group">
                        <div class="form-row">
                            <div id="name" class="col">
                                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                            </div>
                            <div class="col">
                                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
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
      </div>
    </body>
    <script>
      var date = <?php echo json_encode($date->format('m/d/Y'));?>;
      var date_arr = <?php echo json_encode($query["Date"]);?>;
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
    </script>
    <script src="ret_user.js">
    </script>
  </div>
</html>
