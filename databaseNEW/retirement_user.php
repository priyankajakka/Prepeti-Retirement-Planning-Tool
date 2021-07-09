<?php
    session_start();

    $date=date('m/d/Y');
    $date_arr=explode('/',$date);
    
    $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
    $curr_username = $_SESSION['user'];

    if(isset($_REQUEST['submitStocks'])) {
      $stocks = explode(",", $_REQUEST['stocks_list']);
      $stocks_blob = "";

      $stocks_blob = implode(",",$stocks);

      $sql = "UPDATE Login_info SET stocks = '$stocks_blob' WHERE username = '$curr_username'";
      mysqli_query($mysqli, $sql);
    //}

    //if(isset($_REQUEST['submitStockValues'])) {
      $stocks_values = explode(",", $_REQUEST['stocks_values_list']);
      $stocks_values_blob = "";

      $stocks_values_blob = implode(",",$stocks_values);

      //print $stocks_values_blob;

      $sql = "UPDATE Login_info SET stock_values = '$stocks_values_blob' WHERE username = '$curr_username'";
      mysqli_query($mysqli, $sql);
    }

    $stmt = $mysqli->prepare("SELECT DISTINCT stocks FROM Login_Info WHERE username = '$curr_username'");
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();
    $_SESSION['stocks'] = $value->stocks;
    print $_SESSION['stocks'];

    $stmt = $mysqli->prepare("SELECT DISTINCT stock_values FROM Login_Info WHERE username = '$curr_username'");
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();
    $_SESSION['stock_values'] = $value->stock_values;
    print $_SESSION['stock_values'];

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
  
    }
    $query1 = $mysqli->query("SELECT * FROM Login_info WHERE username = '$curr_username'");
    $query = $query1->fetch_assoc();

?>

<!DOCTYPE html>

<html>
<div id="wrapper" style="text-align:center">

  <head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="http://d3js.org/d3.v3.min.js"> </script>
    <link rel="stylesheet" href="retirementplswork.css">
    <a href="http://localhost/~sjakka/RetirementTool/ret_logout.php" style="margin-left:10; color:white;">
    <button class="tablink" >Logout</button>
    </a> 
    <a href="http://localhost/~sjakka/RetirementTool/acct_settings.php" style="margin-left:10; color:white;">
    <button class="tablink" >Account settings</button>
    </a> 

    <title>Retirement</title>
    <div>
        <h1>Hello <b><?php echo htmlspecialchars($_SESSION["user"]); ?></b>!</h1>
    </div>

    <h1>Retirement Tool</h1>
    <!--<div id="c1">-->
    <br>
    <br>

    <form method = "POST">

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

    <!--<form name="Form" method="post" onsubmit="return confirmStocks()">
      <input name='stocks_list' type=hidden>
      <input name="submitStocks" type="submit"  value="Confirm selection">
    </form>-->

    <!--<button onclick="confirmStocks()">Confim selection</button>-->
    <br><br>

    <div id = "money_in_stocks" ></div>
    <br><br>

    <!--<button onclick="confirmStockValues()">Confim selection</button>-->

    <form name="Form" method="post" onsubmit="return confirmStockValues()">
    <input name='stocks_list' type=hidden>
      <input name='stocks_values_list' type=hidden>
      <input name="submitStocks" type="submit"  value="Confirm selection">
    </form>

    <br><br>

    <div id="summary" class="summary"></div>
    <!--</div>-->

  </head>

  <body style="background-color: #7392B7; color: black;" >
    <!--<div id="c2">-->
      <br>
      <br>
      <br>
      <div id="graph" class="aGraph"></div>
      <div id="graph2" class="aGraph2"></div>

      <script>
        var curr_age = <?php echo $_SESSION["curr_age"]; ?>;
        var ret_age = <?php echo $_SESSION["ret_age"]; ?>;
        var life = <?php echo $_SESSION["life"]; ?>;
        var existing_stocks = <?php echo json_encode($_SESSION['stocks']); ?>;
        var existing_stock_values = <?php echo json_encode($_SESSION['stock_values']); ?>;
      </script>

      <script src="ret_user.js"></script>
    <!--</div>-->
  </body>
</div>

</html>
