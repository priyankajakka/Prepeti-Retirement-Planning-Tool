<!DOCTYPE html>

<html>


<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="http://d3js.org/d3.v4.min.js"> </script>
  <link rel="stylesheet" href="retirementplswork.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <a href=# style="color:white;">
    <button class="tablink ButtonNeon">Return to main page</button>
  </a>
  <a href="http://localhost/~sjakka/RetirementToolwUI/ret_login.php" style="color:white;">
    <button class="tablink ButtonNeon">Sign in / Create Account</button>
  </a>

  <title>Retirement</title>

</head>
<div id="wrapper" style="text-align:center">

  <body style="color:white">
    <div class="container">
      <h1>Retirement Tool</h1>
      <br>
      <br>
      <br>

      <div class="row flex-center">
        <div class="col-md-3">
          <div class="card card-body h-100 mb-3 border-neon bg-transparent rounded-3">
            <div data-aos="fade-up">
              <br>
              <h2 class="text-white" data-aos="fade-up" data-aos-delay="100"><b>Enter your information</b></h2>

              <div class="form-group">
                <label for="curr_age" class="control-label">What is your age?</label>
                <input class="form-control" style="color: white;" type="number" name="curr_age" id="curr_age" value=25 onkeyup="result()" style="text-align: center"></input>

                <div class="d-flex justify-content-center my-4">
                  <span class="font-weight-bold indigo-text mr-2 mt-1">10</span>
                  <form class="range-field w-50">
                    <input class="border-0" type="range" id="age_slider" min="10" max="110" onkeyup="result()" />
                  </form>
                  <span class="font-weight-bold indigo-text ml-2 mt-1">110</span>
                </div>

              </div>
              <br>
              <div class="form-group">
                <label for="curr_age" class="control-label">What is your retirement age?</label>
                <input class="form-control" style="color: white;" type="number" name="ret_age" id="ret_age" value=60 onkeyup="result()" style="text-align: center"></input>

                <div class="d-flex justify-content-center my-4">
                  <span class="font-weight-bold indigo-text mr-2 mt-1">20</span>
                  <form class="range-field w-50">
                    <input class="border-0" type="range" id="ret_age_slider" min="20" max="110" onkeyup="result()" />
                  </form>
                  <span class="font-weight-bold indigo-text ml-2 mt-1">110</span>
                </div>

              </div>
              <br>
              <div class="form-group">
                <label for="money" class="control-label">What is your income?</label>
                <input class="form-control" style="color: white;" type="number" name="income" id="income" value=50000 onkeyup="result()" style="text-align: center"></input>

                <div class="d-flex justify-content-center my-4">
                  <span class="font-weight-bold indigo-text mr-2 mt-1">25000</span>
                  <form class="range-field w-50">
                    <input class="border-0" type="range" id="income_slider" min="25000" max="200000" onkeyup="result()" />
                  </form>
                  <span class="font-weight-bold indigo-text ml-2 mt-1">200000</span>
                </div>


              </div>
              <br>
              <div class="form-group">
                <label for="money" class="control-label">How much money do you need per year?</label>
                <input class="form-control" style="color: white;" type="number" name="money" id="money" value=50000 onkeyup="result()" style="text-align: center"></input>

                <div class="d-flex justify-content-center my-4">
                  <span class="font-weight-bold indigo-text mr-2 mt-1">5000</span>
                  <form class="range-field w-50">
                    <input class="border-0" type="range" id="money_slider" min="5000" max="200000" onkeyup="result()" />
                  </form>
                  <span class="font-weight-bold indigo-text ml-2 mt-1">200000</span>
                </div>

              </div>
              <br>
              <div class="form-group">
                <label for="savings" class="control-label">How much money do you have saved?</label>
                <input class="form-control" style="color: white;" type="number" name="savings" id="savings" value=50000 onkeyup="result()" style="text-align: center"></input>

                <div class="d-flex justify-content-center my-4">
                  <span class="font-weight-bold indigo-text mr-2 mt-1">0</span>
                  <form class="range-field w-50">
                    <input class="border-0" type="range" id="savings_slider" min="0" max="200000" onkeyup="result()" />
                  </form>
                  <span class="font-weight-bold indigo-text ml-2 mt-1">200000</span>
                </div>


              </div>
              <br>
              <div class="form-group">
                <label for="savings" class="control-label">Life expectancy?</label>
                <input class="form-control" style="color: white;" type="number" name="life" id="life" value=90 onkeyup="result()" style="text-align: center"></input>

                <div class="d-flex justify-content-center my-4">
                  <span class="font-weight-bold indigo-text mr-2 mt-1">10</span>
                  <form class="range-field w-50">
                    <input class="border-0" type="range" id="life_slider" min="10" max="110" onkeyup="result()" />
                  </form>
                  <span class="font-weight-bold indigo-text ml-2 mt-1">110</span>
                </div>

              </div>
              <br>
            </div>
          </div>
        </div>
        <br><br>
        <div class="col-md-9">
          <div class="bg-transparent card mb-3 rounded-3 blueBorder">
            <br>
            <h3 class="fw-bold" style="color:white">Inflation adjusted M<br /></h3>
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
            <h3 class="fw-bold" style="color: white;">Your Finances</h3>
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
                  <th scope="row" style="font-size: 11px; font-weight: normal;">Annual Income</th>
                  <td id="table_annual_income"></td>
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


      <script src="retirement.js"></script>
    </div>
  </body>
</div>

</html>