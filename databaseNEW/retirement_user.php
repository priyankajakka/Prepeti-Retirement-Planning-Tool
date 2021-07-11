<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
      header("location: http://localhost/~sjakka/RetirementTool/retirement_guest.php");
      exit;
    }

    $date=date('m/d/Y');
    print $date;
    
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
      print $dates_blob;

      $sql = "UPDATE Login_info SET Date = '$dates_blob' WHERE username = '$curr_username'";
      if(mysqli_query($mysqli, $sql)){
      }else{
        printf("Error message: %s\n", $mysqli->error);
      }

      $savings_req_over_time = explode(",", $_POST['savings_req_over_time']);
      $savings_req_over_time_blob = "";
      $savings_req_over_time_blob = implode(",",$savings_req_over_time);
      print $savings_req_over_time_blob;

      $sql = "UPDATE Login_info SET Savings_req_time = '$savings_req_over_time_blob' WHERE username = '$curr_username'";
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
    <script src="http://d3js.org/d3.v4.min.js"> </script>
    <link rel="stylesheet" href="http://localhost/~sjakka/RetirementTool/retirementplswork.css">
    <a href="http://localhost/~sjakka/RetirementTool/ret_logout.php" style="margin-left:10;">
    <button class="tablink" >Logout</button>
    </a> 
    <a href="http://localhost/~sjakka/RetirementTool/acct_settings.php" style="margin-left:10;">
    <button class="tablink" >Account settings</button>
    </a> 

    <title>Retirement</title>
    <div>
        <h1>Hello <b><?php echo htmlspecialchars($_SESSION["user"]); ?></b>!</h1>
    </div>

    <h1>Retirement Tool</h1>
    <br>
    <br>

    <form name="Formnum1" form method = "POST" onsubmit="return updateOverTime()">

        What is your income: <br> <input type="number" name="income" id="income" value = <?php echo strval($query["income"]); ?> onkeyup="result()"
          style="text-align: center">
        <br>
        25000<input type="range" id="income_slider" min="25000" max="200000" value="100000" onkeyup="result()">200000
        <br><br>

        How much money do you need per year: <br> <input type="number" name="money" id="money" value = <?php echo strval($query["money"]); ?>  onkeyup="result()"
          style="text-align: center">
        <br>
        5000<input type="range" id="money_slider" min="5000" max="100000" value="50000" onkeyup="result()"
          style="text-align: center">100000
        <br><br>

        How much money do you have saved: <br><input type="number" name="savings" id="savings" value = <?php echo strval($query["savings"]); ?>  onkeyup="result()"
          style="text-align: center">
        <br>
        0<input type="range" id="savings_slider" min="0" max="200000" value="0" onkeyup="result()"
          style="text-align: center">200000
        <br><br>
        <input name='networth_over_time' type=hidden>
        <input name='savings_req_over_time' type=hidden>
        <input name='dates_over_time' type=hidden>
        <input style = "color:black" type = "SUBMIT" name = "submit" value = "Save Info"/>
        <br><br>
    </form>

    <div id="myDIV" class="header">
        <h2 id="stock_options_header" style="margin:5px">Select your stock options</h2>
    </div>

    <input type="text" id="searchbar" onkeyup="SearchThroughStocks()" placeholder="Search for stock options..">

    <nav>
        <div>
            <ul id="stock_option"></ul>
        </div>
    </nav>

    <br><br>
    <p id="stocks_selection"></p>
    <br><br>

    <div id = "money_in_stocks" ></div>
    <br><br>

    <form name="Form" method="post" onsubmit="return confirmStockValues()">
      <input name='stocks_list' type=hidden>
      <input name='stocks_values_list' type=hidden>
      <input name="submitStocks" type="submit"  value="Confirm selection">
    </form>

    <br><br>

    <div id="summary" class="summary"></div>
    <br>
      <br>
      <br>
      <div id="graph" class="aGraph"></div>
      <br>
      <div id="graph2" class="aGraph2"></div>
      <br>
      <div id="boxplot"></div>
      <br>
      <div id="pie-chart"></div>
      <br>
      <div id="savings_req_time"></div>
      <br>
  </head>

  <body>

      <script>
        var date = <?php echo json_encode($date); ?>;
        var date_arr = <?php echo json_encode($query["Date"]); ?>;
        var savings_req_arr = <?php echo json_encode($query["Savings_req_time"]); ?>;

        var curr_age = <?php echo $_SESSION["curr_age"]; ?>;
        var ret_age = <?php echo $_SESSION["ret_age"]; ?>;
        var life = <?php echo $_SESSION["life"]; ?>;
        var existing_stocks = <?php echo json_encode($_SESSION['stocks']); ?>;
        var existing_stock_values = <?php echo json_encode($_SESSION['stock_values']); ?>;
        var portfolio = <?php echo json_encode($portfolio); ?>;
        var dom_large_percent = <?php echo json_encode($query3["dom_large"]); ?>;
        var dom_small_percent = <?php echo json_encode($query3["dom_small"]); ?>;
        var int_large_percent = <?php echo json_encode($query3["int_large"]); ?>;
        var int_small_percent = <?php echo json_encode($query3["int_small"]); ?>;
        var bonds_percent = <?php echo json_encode($query3["bonds"]); ?>;

        console.log(portfolio + " " + dom_large_percent + " " + dom_small_percent + " " + int_large_percent + " " + int_small_percent + " " + bonds_percent);
      </script>

      <script src="ret_user.js"></script>
  </body>
</div>

</html>
