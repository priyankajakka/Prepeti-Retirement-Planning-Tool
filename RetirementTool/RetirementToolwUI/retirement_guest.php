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

  <title>Retirement</title>

</head>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 50) {
        document.getElementById('navbar_top').classList.add('fixed-top');
        document.getElementById('navbar_top').style.background = "black";
      } else {
        document.getElementById('navbar_top').classList.remove('fixed-top');
        document.getElementById('navbar_top').style.background = "transparent";
      }
    });
  });
</script>

<div id="wrapper" style="text-align:center">

  <section id="navbar">
    <div class="container">
      <nav id="navbar_top" class="navbar navbar-dark navbar-expand-md" data-navbar-on-scroll="data-navbar-on-scroll">
        <a class="navbar-brand" href="http://localhost/~sjakka/RetirementTool/landingPage/index.html">
          <img src="pictures/prepetibright-removebg-preview.png" style="width:220px" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <b-avatar class="mr-3"></b-avatar>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto nav-tabs2" id="myTab">
            <li class="dropdown">
              <a class="nav-link nav-link-icon" href="#" id="navbar-default_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-user"></span>
                <span class="nav-link-inner--text d-lg-none">Profile</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
                <a class="dropdown-item" href="http://localhost/~sjakka/RetirementTool/RetirementToolwUI/create_acct.php">Sign up</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </section>

  <br><br><br><br><br><br>

  <body style="color:white">
    <div class="container">
      <h1>Play</h1>
      <br>
      <br>
      <br>
      <div class="row flex-center">
        <div class="col-md-4">
          <div class="card card-body h-100 mb-3 border-neon bg-transparent rounded-3">
            <div data-aos="fade-up">
              <br>
              <h2 class="text-white" data-aos="fade-up" data-aos-delay="100"><b>Enter your information</b></h2>
              <br>
              <div class="form-group">
                <label for="curr_age" style = "font-size: 15px;" class="control-label">How old are you?</label>
                <input class="form-control" style="color: white;font-size: 14px;" type="number" name="curr_age" id="curr_age" value=25 onkeyup="result()" style="text-align: center"></input>

              </div>
              <br>
              <div class="form-group">
                <label for="curr_age" style = "font-size: 15px;" class="control-label">What is your retirement age?</label>
                <input class="form-control" style="color: white;font-size: 14px;" type="number" name="ret_age" id="ret_age" value=60 onkeyup="result()" style="text-align: center"></input>

              </div>
              <br>

              <div class="form-group">
                <label for="money" style = "font-size: 15px;" class="control-label">How much money do you need per year?</label>
                <input class="form-control" style="color: white;font-size: 14px;" type="number" name="money" id="money" value=50000 onkeyup="result()" style="text-align: center"></input>


              </div>
              <br>
              <div class="form-group">
                <label for="savings" style = "font-size: 15px;" class="control-label">How much money do you have saved?</label>
                <input class="form-control" style="color: white;font-size: 14px;" type="number" name="savings" id="savings" value=50000 onkeyup="result()" style="text-align: center"></input>


              </div>
              <br>
              <div class="form-group">
                <label for="savings" style = "font-size: 15px;" class="control-label">Life expectancy?</label>
                <input class="form-control" style="color: white;font-size: 14px;" type="number" name="life" id="life" value=90 onkeyup="result()" style="text-align: center"></input>
              </div>
              <br>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="h-100 bg-transparent purpleBorder card card-body mb-3 rounded-3">
            <br>
            <h3 class="fw-bold" style="color: white;">Play Summary</h3>
            <!--<div id="summary" class="card-text summary" style = "font-size:20"></div>-->
            <table class="table table2Width" style="font-size: 11px;">
              <tbody style="font-size: 14px">
                <tr>
                  <th scope="row" style="font-size: 15px; font-weight: normal;">Retirement Year</th>
                  <td id="table_ret_year" style="font-size: 14px;"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 15px; font-weight: normal;">Years in<br>Retirement</th>
                  <td id="table_distr_years" style="font-size: 14px;"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 15px; font-weight: normal;">Money you need<br>per year</th>
                  <td id="table_money_per_year" style="font-size: 14px;"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 15px; font-weight: normal;">Portfolio growth rate<br>prior Retirement</th>
                  <td id="table_r1" style="font-size: 14px;"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 15px; font-weight: normal;">Portfolio growth rate<br>after Retirement</th>
                  <td id="table_r2" style="font-size: 14px;"></td>
                </tr>
                <tr>
                  <th scope="row" style="font-size: 17px; font-weight: bold; color:white">Savings required<br>each year</th>
                  <td id="table_savings_req" style="font-size: 16px; font-weight: bold; color:white"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <br>
      <br>
      <br>
      <!--<div class="row flex-center">-->

      <div class="">
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
      </div>
      <!--</div>-->

      <br><br>




      <script src="js/retirement.js"></script>
    </div>
  </body>
</div>

</html>
