<!DOCTYPE html>

<html>


<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="http://d3js.org/d3.v4.min.js"> </script>
  <link rel="stylesheet" href="css/retirementplswork.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <a href="http://localhost/~sjakka/RetirementTool/landingPage/index.html" style="color:white;">
    <button class="tablink ButtonNeon">Return to main page</button>
  </a>
  <a href="http://localhost/~sjakka/RetirementTool/RetirementToolwUI/ret_login.php" style="color:white;">
    <button class="tablink ButtonNeon">Sign in / Create Account</button>
  </a>

  <title>Retirement</title>

</head>
<div id="wrapper" style="text-align:center">

  <body style="color:white">
    <div class="container">
      <h1>Play</h1>
      <br>
      <br>
      <br>

      <div class="row flex-center">
        <div class="col-md-3">
          <div class="card card-body h-100 mb-3 border-neon bg-transparent rounded-3">
            <div data-aos="fade-up">
              <br>
              <h2 class="text-white" data-aos="fade-up" data-aos-delay="100"><b>Enter your information</b></h2>
              <br><br><br><br><br>
              <div class="form-group">
                <label for="curr_age" class="control-label">How old are you?</label>
                <input class="form-control" style="color: white;" type="number" name="curr_age" id="curr_age" value=25 onkeyup="result()" style="text-align: center"></input>

              </div>
              <br><br><br><br><br><br><br>
              <div class="form-group">
                <label for="curr_age" class="control-label">What is your retirement age?</label>
                <input class="form-control" style="color: white;" type="number" name="ret_age" id="ret_age" value=60 onkeyup="result()" style="text-align: center"></input>

              </div>
              <br><br><br><br><br><br><br>

              <div class="form-group">
                <label for="money" class="control-label">How much money do you need per year?</label>
                <input class="form-control" style="color: white;" type="number" name="money" id="money" value=50000 onkeyup="result()" style="text-align: center"></input>


              </div>
              <br><br><br><br><br><br><br>
              <div class="form-group">
                <label for="savings" class="control-label">How much money do you have saved?</label>
                <input class="form-control" style="color: white;" type="number" name="savings" id="savings" value=50000 onkeyup="result()" style="text-align: center"></input>


              </div>
              <br><br><br><br><br><br><br>
              <div class="form-group">
                <label for="savings" class="control-label">Life expectancy?</label>
                <input class="form-control" style="color: white;" type="number" name="life" id="life" value=90 onkeyup="result()" style="text-align: center"></input>

              </div>
              <br>
            </div>
          </div>
        </div>
        <br><br>
        <div class="col-md-9">
          <div class="bg-transparent card mb-3 rounded-3 blueBorder">
            <br>
            <h3 class="fw-bold" style="color:white">Your Cash Flow in Retirement<br /></h3>
            <div id="graph" class="aGraph"></div>
          </div>
          <br><br>
          <div class="bg-transparent card mb-3 rounded-3 blueBorder">
            <br>
            <h3 class="fw-bold" style="color:white">Wealth vs Year<br /></h3>
            <div id="graph2" class="aGraph2"></div>
          </div>
          <br><br>
          <div class="bg-transparent purpleBorder card card-body mb-3 rounded-3">
            <br>
            <h3 class="fw-bold" style="color: white;">Play Summary</h3>
            <!--<div id="summary" class="card-text summary" style = "font-size:20"></div>-->
            <table class="table table2Width" style="font-size: 11px;">
              <tbody style="font-size: 11px">
                <tr>
                  <th scope="row" style="font-size: 11px; font-weight: normal;">Retirement Year</th>
                  <td id="table_ret_year"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 11px; font-weight: normal;">Years in<br>Retirement</th>
                  <td id="table_distr_years"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 11px; font-weight: normal;">Money you need<br>per year</th>
                  <td id="table_money_per_year"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 11px; font-weight: normal;">Portfolio growth rate<br>prior Retirement</th>
                  <td id="table_r1"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 11px; font-weight: normal;">Portfolio growth rate<br>after Retirement</th>
                  <td id="table_r2"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 14px; font-weight: bold; color:white">Savings required<br>each year</th>
                  <td id="table_savings_req" style="font-size: 14px; font-weight: bold; color:white"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <p id="summary" class="summary"></p>

      <br>
      <br>
      <br>


      <script src="js/retirement.js"></script>
    </div>
  </body>
</div>

</html>
