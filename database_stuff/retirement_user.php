<?php
    session_start();

    $date=date('m/d/Y');
    $date_arr=explode('/',$date);
    
    $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
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

    <title>Retirement</title>
<div>
<h1>Hello <b><?php echo htmlspecialchars($_SESSION["user"]); ?></b>!</h1>
</div>
    <h1>Retirement Tool</h1>
    <div id="c1">
      <br>
      <br>
      <br>
      How old are you?: <br><input type="number" name="curr_age" id="curr_age" onkeyup="result()"
        style="text-align: center"><br>
      10<input type="range" id="age_slider" min="10" max="110" value="30" onkeyup="result()">110
      <br><br>
      At what age do you plan to retire?: <br><input type="number" name="ret_age" id="ret_age" onkeyup="result()"
        style="text-align: center">
      <br>
      20<input type="range" id="ret_age_slider" min="20" max="110" value="60" onkeyup="result()">110
      <br><br>
      How much money do you need per year: <br> <input type="number" name="money" id="money" onkeyup="result()"
        style="text-align: center">
      <br>
      5000<input type="range" id="money_slider" min="5000" max="100000" value="50000" onkeyup="result()"
        style="text-align: center">100000
      <br><br>
      How much money do you have saved: <br><input type="number" name="savings" id="savings" onkeyup="result()"
        style="text-align: center">
      <br>
      0<input type="range" id="savings_slider" min="0" max="200000" value="0" onkeyup="result()"
        style="text-align: center">200000
      <br><br>
      Life expectancy?: <br> <input type="number" name="life" id="life" onkeyup="result()" style="text-align: center">
      <br>
      10<input type="range" id="life_slider" min="10" max="110" value="90" onkeyup="result()"
        style="text-align: center">110
      <br><br>
      Assumed portfolio growth rate during accumulation years (in percent): <br><input type="number" name="r1" id="r1"
        onkeyup="result()" style="text-align: center">
      <br>
      0<input type="range" id="r1_slider" min="0" max="10" value="7" onkeyup="result()" style="text-align: center">10
      <br><br>
      Assumed portfolio growth rate during distribution years (in percent): <br><input type="number" name="r2" id="r2"
        onkeyup="result()" style="text-align: center">
      <br>
      0<input type="range" id="r2_slider" min="0" max="10" value="7" onkeyup="result()" style="text-align: center">10
      <br><br><br>
      <p id="summary" class="summary"></p>
    </div>


  </head>

  <body style="background-color: #FCF9F0">
    <div id="c2">
      <br>
      <br>
      <br>
      <div id="graph" class="aGraph"></div>
      <div id="graph2" class="aGraph2"></div>
      <script src="retirement.js"></script>
    </div>
  </body>
</div>

</html>