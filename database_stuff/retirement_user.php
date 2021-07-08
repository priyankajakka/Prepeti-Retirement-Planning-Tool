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
    <!--<div id="c1">-->
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
      What is your income: <br> <input type="number" name="income" id="income" onkeyup="result()"
        style="text-align: center">
      <br>
      25000<input type="range" id="income_slider" min="25000" max="200000" value="100000" onkeyup="result()">200000
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
      <!--Assumed portfolio growth rate during accumulation years (in percent): <br><input type="number" name="r1" id="r111"
        onkeyup="result()" style="text-align: center">
      <br>
      0<input type="range" id="r1_slider" min="0" max="10" value="7" onkeyup="result()" style="text-align: center">10
      <br><br>
      Assumed portfolio growth rate during distribution years (in percent): <br><input type="number" name="r2" id="r222"
        onkeyup="result()" style="text-align: center">
      <br>
      0<input type="range" id="r2_slider" min="0" max="10" value="7" onkeyup="result()" style="text-align: center">10-->
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
      <button onclick="confirmStocks()">Confim selection</button>
      <br><br>
      <div id = "money_in_stocks" ></div>
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
      <script src="retirement.js"></script>
    <!--</div>-->

    <script>
      var selected_stock_options = [];

      function showAllStockOptions() {
        var innertext = "";
        var stock_options = ['US large cap', 'US mid cap', 'US small cap', 
        'International Small Cap', 'International mid', 'International Large'];
        var list = document.createElement('ul');
        stock_options.forEach(function (option) {
                          var li = document.createElement('li');
                          li.textContent = option;
                          document.getElementById("stock_option").appendChild(li);
                          });

        var list = document.querySelector('ul');
        list.addEventListener('click', function (stock) {
            if (stock.target.tagName === 'LI') {
              stock.target.classList.toggle('checked');
              showSelection();
            }
        }, false);
        stock_options = [];
    }

    function showSelection(){
        selected_stock_options = [];
        var ul = document.getElementById("stock_option");
        var li = ul.getElementsByTagName("li");
        var innertext = "You selected: \n"
        for (var i = 0; i < li.length; i++) {
          if (li[i].classList.contains("checked")) {
                innertext += li[i].innerHTML + "\n"
                selected_stock_options.push(li[i].innerHTML)
          }
        }
        document.getElementById("stocks_selection").innerText = innertext;
    }

    function confirmStocks(){
      var div = document.getElementById('money_in_stocks');
      while(div.firstChild){
          div.removeChild(div.firstChild);
      }
      document.getElementById("money_in_stocks").innerText += "Enter the amount of money you have in each stock: \n"
      for (var i = 0; i < selected_stock_options.length; i++) {
        document.getElementById("money_in_stocks").innerHTML += (selected_stock_options[i] + ": ");
        var input = document.createElement('input');
        input.type = "number";
        input.id = selected_stock_options[i];
        document.getElementById("money_in_stocks").appendChild(input);
        document.getElementById("money_in_stocks").appendChild(document.createElement("br")); 
      }
    }

    function SearchThroughStocks() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("searchbar");
        filter = input.value.toUpperCase();
        ul = document.getElementById("stock_option");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            txtValue = li[i].textContent;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
    showAllStockOptions();
    </script>
  </body>
</div>

</html>
