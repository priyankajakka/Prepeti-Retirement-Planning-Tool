<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: http://localhost/~sjakka/RetirementTool/RetirementToolwUI/retirement_guest.php");
    exit;
}

$mysqli = new MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
$curr_username = $_SESSION['user'];

if (isset($_REQUEST['submitPopupInfo'])) {
    $money = (int) $_POST['money'];
    $savings = (int) $_POST['savings'];
    $income = (int) $_POST['income'];

    $sql = "UPDATE Login_info SET
    money = $money,
    savings = $savings,
    income = $income
    WHERE username='$curr_username'";

    mysqli_query($mysqli, $sql);

    $stocks_output = $_REQUEST['stocks_list'];
    $stocks = explode(",", $_REQUEST['stocks_list']);

    $stocks_blob = "";
    $stocks_blob = implode(",", $stocks);

    $sql = "UPDATE Login_info SET stocks = '$stocks_blob' WHERE username = '$curr_username'";
    mysqli_query($mysqli, $sql);

    $stocks_values = explode(",", $_REQUEST['stocks_values_list']);
    $stocks_values_blob = "";
    $stocks_values_blob = implode(",", $stocks_values);

    $sql = "UPDATE Login_info SET stock_values = '$stocks_values_blob' WHERE username = '$curr_username'";
    mysqli_query($mysqli, $sql);

    $dates_networth = explode(",", $_POST['dates_networth_over_time']);
    $dates_networth_blob = "";
    $dates_networth_blob = implode(",", $dates_networth);

    $sql = "UPDATE Login_info SET Date_networth = '$dates_networth_blob' WHERE username = '$curr_username'";
    if (mysqli_query($mysqli, $sql)) {
    }

    $networth_over_time = explode(",", $_POST['networth_over_time_stocks']);
    $networth_over_time_blob = "";
    $networth_over_time_blob = implode(",", $networth_over_time);

    $sql = "UPDATE Login_info SET Networth_over_time = '$networth_over_time_blob' WHERE username = '$curr_username'";
    if (mysqli_query($mysqli, $sql)) {
    }


    $dates = explode(",", $_POST['dates_over_time']);
    $dates_blob = "";
    $dates_blob = implode(",", $dates);
    //print $dates_blob;

    $sql = "UPDATE Login_info SET Date = '$dates_blob' WHERE username = '$curr_username'";
    if (mysqli_query($mysqli, $sql)) {
    }

    $savings_req_over_time = explode(",", $_POST['savings_req_over_time']);
    $savings_req_over_time_blob = "";
    $savings_req_over_time_blob = implode(",", $savings_req_over_time);

    //print $savings_req_over_time_blob;

    $sql = "UPDATE Login_info SET Savings_req_time = '$savings_req_over_time_blob' WHERE username = '$curr_username'";
    if (mysqli_query($mysqli, $sql)) {
    }

    $savings_req_over_time_bad = explode(",", $_POST['savings_req_over_time_bad']);
    $savings_req_over_time_bad_blob = "";
    $savings_req_over_time_bad_blob = implode(",", $savings_req_over_time_bad);

    //print $savings_req_over_time_bad_blob;

    $sql = "UPDATE Login_info SET Savings_req_time_bad = '$savings_req_over_time_bad_blob' WHERE username = '$curr_username'";
    if (mysqli_query($mysqli, $sql)) {
    }

    header("location: http://localhost/~sjakka/RetirementTool/RetirementToolwUI/retirement_user.php");
    exit;
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

$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));

$query1 = $mysqli->query("SELECT * FROM Login_info WHERE username = '$curr_username'");
$query = $query1->fetch_assoc();

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/retirementplswork.css">
    <style>
        fieldset {
            text-align: center;
        }

        #updateInfoPlease fieldset:not(:first-of-type) {
            display: none
        }

        .form-control-inline {
            min-width: 0;
            width: auto;
            display: inline;
        }

        .modal-backdrop {
            background-color: #188bc0;
        }

        .modal-lg {
            max-width: 60% !important;
        }
    </style>
</head>

<body>

    <!-- Modal -->
    <div class="modal fade" id="updateInfoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background-color:black;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Welcome back!</h5>
                </div>
                <div class="modal-body">
                    <form name="Form" method="post" onsubmit="return confirmStockValues()">
                        <div id="updateInfoPlease" style="width: 80%;margin:auto;">
                            <fieldset>
                                <div class="">
                                    <h2 class="fs-title">Update your information</h2>
                                    <br><br>
                                    <div class="">
                                        <div class="">
                                            <label for="income" class="control-label">What is your income?</label>
                                            <input class="form-control form-control-inline" style="color: white;" type="number" name="income" id="income" value=<?php echo strval($query["income"]); ?> style="text-align: center"></input>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="">
                                        <div class="">
                                            <label for="money" class="control-label">How much money do you need per year?</label>
                                            <input class="form-control form-control-inline" style="color: white;" type="number" name="money" id="money" value=<?php echo strval($query["money"]); ?> style="text-align: center"></input>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="">
                                        <div class="">
                                            <label for="savings" class="control-label">How much money do you have saved?</label>
                                            <input class="form-control form-control-inline" style="color: white;" type="number" name="savings" id="savings" value=<?php echo strval($query["savings"]); ?> style="text-align: center"></input>
                                        </div>
                                    </div>
                                </div>
                                <br><br>
                                <input type="button" name="next" class="next action-button ButtonNeon" value="Next" />
                                <p id="output_finances"></p>
                            </fieldset>
                            <fieldset>
                                <div class="">
                                    <h2 class="fs-title">Almost there</h2>

                                    <div class="row flex-center">
                                        <div class="col-md-12">
                                            <div data-aos="fade-up" class="m-b-15">
                                                <div style="margin:0; padding:0;">
                                                    <div class="card bg-transparent">
                                                        <br>
                                                        <h5 class="fw-bold" id="stock_options_header" style="margin:5px">Select your stock options</h5>
                                                        <div class="help-tip">
                                                            <p>401(k) options are highlighted in blue, while ETF options are highlighted in pink. Please select the following investment options that apply to you.</p>
                                                        </div>
                                                        <input type="text" id="searchbar" placeholder="Search for stock options.."></input>
                                                        <nav>
                                                            <div id="stock_option_div">
                                                                <ul class="stock_option" id="stock_option"></ul>
                                                            </div>
                                                        </nav>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br><br>
                                    </div>

                                    <p id="stocks_selection"></p>
                                    <br>
                                    <br>
                                    <div id="money_in_stocks"></div>
                                    <br>
                                    <!--<form name="Form" method="post" onsubmit="return confirmStockValues()">-->
                                    <input id='stocks_list' name='stocks_list' type="hidden"></input>
                                    <input id='stocks_values_list' name='stocks_values_list' type="hidden"></input>
                                    <input id='networth_over_time_stocks' name='networth_over_time_stocks' type="hidden"></input>
                                    <input id='dates_networth_over_time' name='dates_networth_over_time' type="hidden"></input>
                                    <input id='savings_req_over_time' name='savings_req_over_time' type=hidden></input>
                                    <input id='savings_req_over_time_bad' name='savings_req_over_time_bad' type=hidden></input>
                                    <input id='dates_over_time' name='dates_over_time' type=hidden></input>

                                    <!--</form>-->
                                </div>
                                <input type="button" name="previous" class="previous action-button-previous ButtonNeon" value="Previous" />
                                <input class="ButtonNeon" name="submitPopupInfo" type="submit" value="Take me to my plan"></input>
                            </fieldset>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</body>


<script>
    $(document).ready(function() {

        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;

        $(".next").click(function() {

            if (document.getElementById("income").value.length != 0 && document.getElementById("money").value.length != 0 && document.getElementById("savings").value.length != 0) {
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
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
            } else {
                document.getElementById("output_finances").innerText = "Please enter all fields.";
            }
        });

        $(".previous").click(function() {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();

            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
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
    });
</script>

<script>
    var date = <?php echo json_encode($date->format('m/d/Y')); ?>;
    var date_networth_arr = <?php echo json_encode($query["Date_networth"]); ?>;


    $('#updateInfoModal').modal({
        backdrop: 'static',
        keyboard: false
    })

    $('#updateInfoModal').modal('show');
    var existing_stocks = <?php echo json_encode($_SESSION['stocks']); ?>;
    var existing_stock_values = <?php echo json_encode($_SESSION['stock_values']); ?>;
    var networth_time_arr = <?php echo json_encode($query["Networth_over_time"]); ?>;
    var selected_stock_options = [];
    var stock_selection_values = [];

    var date_arr = <?php echo json_encode($query["Date"]); ?>;
    var savings_req_arr = <?php echo json_encode($query["Savings_req_time"]); ?>;
    var savings_req_arr_bad = <?php echo json_encode($query["Savings_req_time_bad"]); ?>;

    var curr_age = <?php echo json_encode($_SESSION['curr_age']); ?>;
    var ret_age = <?php echo json_encode($query["ret_age"]); ?>;
    var life = <?php echo json_encode($query["life"]); ?>;
    var income = parseInt(document.getElementById("income").value);

    var r1 = 0.07;
    var bad_r1 = 0.06;
    var r2 = 0.04;
    var inf_rate = 0.03; //inflation rate
    var years = life - curr_age; //years left in life
    var accum_years = ret_age - curr_age; //accumulation years
    var distr_years = life - ret_age; //distribution years

    console.log(existing_stocks);
    console.log(existing_stock_values);
    console.log(date_networth_arr);
    console.log(networth_time_arr);
    //console.log(savings);

    console.log("")
    console.log(date_arr);
    console.log(savings_req_arr);
    console.log(savings_req_arr_bad)

    if (existing_stocks != null) {
        selected_stock_options = existing_stocks.split(",");
    }
    //so that when user opens acct, all of their saved data will show
    //everything in this function only happens once (whenever the page is reloaded/opened upon login)
    function reset_stock_list() {
        var ul = document.getElementById("stock_option");
        var li = ul.getElementsByClassName("li_stock");
        var innertext = "You selected: \n"

        for (var i = 0; i < li.length; ++i) {
            for (var j = 0; j < selected_stock_options.length; ++j) {
                if (li[i].childNodes[0].textContent === selected_stock_options[j]) {
                    li[i].classList.toggle('checked'); //shows list of what stock options user previously selected
                    innertext += selected_stock_options[j] + "\n"
                }
            }
        }

        //shows list of what stock options user previously selected
        //document.getElementById("stocks_selection").innerText = innertext;

        var div = document.getElementById('money_in_stocks');
        while (div.firstChild) {
            div.removeChild(div.firstChild);
        }
        //appends appropriate number of input text fields so that user can enter money in each stock
        document.getElementById("money_in_stocks").innerText += "Enter the amount of money you have in each stock: \n\n"
        for (var i = 0; i < selected_stock_options.length; i++) {
            var input = document.createElement('input');
            input.type = "number";
            input.id = selected_stock_options[i];
            input.classList.add("form-control");
            input.style["color"] = "white";
            input.value = 0;
            input.placeholder = "$";

            if (input.id == 'Domestic Large Cap' || input.id == 'Domestic Small Cap' || input.id == 'International Large Cap' || input.id == 'International Small Cap' || input.id == 'Bonds') {
                input.classList.add("form-control-inline")
                input.style["background"] = "transparent";
                input.style["border"] = "none";
                input.style["border-bottom"] = "2px solid #FF8FCF";
                input.style["-webkit-box-shadow"] = "none";
                input.style["box-shadow"] = "none";
                input.style["border-radius"] = "0";
                input.style["height"] = "25px";
                input.style['text-align'] = "center";
                input.readOnly = true;

                var newlabel = document.createElement("Label");
                newlabel.setAttribute("for", input.id);
                newlabel.style['font-size'] = "15px";
                input.style['font-size'] = "15px";

                newlabel.classList.add("control-label");
                newlabel.innerHTML = selected_stock_options[i];

                document.getElementById("money_in_stocks").appendChild(newlabel);
                document.getElementById("money_in_stocks").appendChild(input);
                document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
                document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
                document.getElementById("money_in_stocks").appendChild(document.createElement("br"));

            } else {
                input.style["height"] = "25px";
                var newlabel = document.createElement("Label");
                newlabel.setAttribute("for", input.id);
                newlabel.style['font-size'] = "12px";
                input.style['font-size'] = "12px";

                newlabel.classList.add("control-label");
                newlabel.innerHTML = selected_stock_options[i];

                document.getElementById("money_in_stocks").appendChild(newlabel);
                document.getElementById("money_in_stocks").appendChild(input);
                document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
            }
        }
    }

    function showAllStockOptions() {
        var counter = 0;
        var stock_options = [
            'S and P 500 Index Fund', 'Large Cap Index Fund', 'Total Stock Market Index Fund', 'VTI', 'VOO', 'VV',
            'Domestic Large Cap',
            'Extended Market Index Fund', 'Small Cap Index Fund', 'VXF', 'VB',
            'Domestic Small Cap',
            'All-World ex-US Index Fund', 'Total International Stock Index Fund', 'VEU', 'VXUS', 'IXUS',
            'International Large Cap',
            'All-World ex-US Small Cap Index Fund', 'VSS',
            'International Small Cap',
            'Intermediate Term Treasury Bond Index', 'Total Bond Market Index', 'BND', 'BIV',
            'Bonds',
            'Other', '---------- END OF LIST ---------'
        ];


        var list = document.createElement('ul');
        list.className = "stock_option";
        var help_tip_content = [
            'You\'ll mostly find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with S&P 500 in the name or in the description.',
            'You\'ll mostly find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with Large Cap Index in the name or in the description. The word Index is the key.',
            'You\'ll mostly find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with Total Stock Market in the name or in the description. Make sure it is not an International fund.',
            'This is the same as Total Stock Market Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'This is the same as S&P 500 Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'This is the same as Large Cap Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'You\'ll mostly find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with Extended Market or S&P Completion Index in the name or in the description.',
            'You\'ll mostly find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with Russell 2000 or S&P 600 Small Cap Index or Small Cap Index in the name or in the description.',
            'This is the same as Extended Market Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'This is the same as Small Cap Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'You\'ll find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with All-World ex-US Index in the name or in the description. By investing in this, you are investing in mostly publicly traded BIG businesses from outside US.',
            'You\'ll find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with Total International Index in the name or in the description. By investing in this, you are investing in mostly publicly traded BIG businesses from outside US.',
            'This is the same as All-World ex-US Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'This is the same as Total International Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'This is the same as Total International Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'You\'ll find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with All-World ex-US Small Cap Index in the name or in the description. By investing in this, you are investing in publicly traded SMALL businesses from outside US.',
            'This is the same as All-World ex-US Small Cap Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'You\'ll find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with Intermediate Term Treasury Bond Index in the name or in the description. By investing in this, you are lending money to the US government on an intermediate term (5-7 years) basis.',
            'You\'ll find this or something similar to this in your retirement plan options at work. Check in the list of investment options for something with Total Bond Market Index in the name or in the description. By investing in this, you are lending money to all kinds of borrowers (government, businesses etc.) within US.',
            'This is the same as Total Bond Market Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'This is the same as Intermediate Term Treasury Bond Index Fund but in an ETF form. An ETF or an Exchange-Traded Fund is what you can buy like a stock in any account except in your retirement plan at work.',
            'For every other investment you own that isn\'t on this list, add it in this category'
        ]

        stock_options.forEach(function(option) {

            var li = document.createElement('li');
            li.textContent = option;

            if (option == 'Domestic Large Cap' || option == 'Domestic Small Cap' || option == 'International Large Cap' || option == 'International Small Cap' || option == 'Bonds') {
                li.className = "li_stock hide";
                --counter;
            } else if (option == 'VTI' || option == 'VOO' || option == 'VV' || option == 'VXF' || option == 'VB' || option == 'VEU' || option == 'VXUS' || option == 'IXUS' || option == 'VSS' || option == 'BND' || option == 'BIV') {
                li.className = "li_stock bg-light-purple";
                var help_tip = document.createElement('div');
                help_tip.classList.add("help-tip")
                var help_tip_text = document.createElement('p');

                var text = document.createTextNode(help_tip_content[counter]);

                help_tip_text.appendChild(text);
                help_tip.appendChild(help_tip_text)
                li.appendChild(help_tip);
            } else if (option != '---------- END OF LIST ---------') {
                li.className = "li_stock bg-light-green";
                var help_tip = document.createElement('div');
                help_tip.classList.add("help-tip")
                var help_tip_text = document.createElement('p');

                var text = document.createTextNode(help_tip_content[counter]);

                help_tip_text.appendChild(text);
                help_tip.appendChild(help_tip_text)
                li.appendChild(help_tip);
            }

            document.getElementById("stock_option").appendChild(li);
            ++counter;
        });

        var list = document.getElementById("stock_option")

        list.addEventListener('click', function(stock) { //this means user has clicked on a list element (stock option)

            var ul = document.getElementById("stock_option");
            var li = ul.getElementsByClassName("li_stock");
            var dom_large_selected = 0;
            var dom_small_selected = 0;
            var int_large_selected = 0;
            var int_small_selected = 0;
            var bonds_selected = 0;

            if (stock.target.tagName === 'LI') {
                console.log("TARGET " + stock.target.textContent);
                stock.target.classList.toggle('checked');

                for (var i = 0; i < li.length; ++i) {
                    if (li[i].childNodes[0].textContent == 'S and P 500 Index Fund' || li[i].childNodes[0].textContent == 'Large Cap Index Fund' || li[i].childNodes[0].textContent == 'Total Stock Market Index Fund' || li[i].childNodes[0].textContent == 'VTI' || li[i].childNodes[0].textContent == 'VOO' || li[i].childNodes[0].textContent == 'VV') {
                        if (li[i].classList.contains("checked")) {
                            ++dom_large_selected;
                        }
                    } else if (li[i].childNodes[0].textContent == 'Domestic Large Cap') {
                        if (dom_large_selected > 0) {
                            if (!li[i].classList.contains('checked')) {
                                li[i].classList.add('checked');
                            }
                        } else {
                            if (li[i].classList.contains('checked')) {
                                li[i].classList.remove('checked');
                            }
                        }
                    } else if (li[i].childNodes[0].textContent == 'Extended Market Index Fund' || li[i].childNodes[0].textContent == 'Small Cap Index Fund' || li[i].childNodes[0].textContent == 'VXF' || li[i].childNodes[0].textContent == 'VB') {
                        if (li[i].classList.contains("checked")) {
                            ++dom_small_selected;
                        }
                    } else if (li[i].childNodes[0].textContent == 'Domestic Small Cap') {
                        if (dom_small_selected > 0) {
                            if (!li[i].classList.contains('checked')) {
                                li[i].classList.add('checked');
                            }
                        } else {
                            if (li[i].classList.contains('checked')) {
                                li[i].classList.remove('checked');
                            }
                        }
                    } else if (li[i].childNodes[0].textContent == 'All-World ex-US Index Fund' || li[i].childNodes[0].textContent == 'Total International Stock Index Fund' || li[i].childNodes[0].textContent == 'VEU' || li[i].childNodes[0].textContent == 'VXUS' || li[i].childNodes[0].textContent == 'IXUS') {
                        if (li[i].classList.contains("checked")) {
                            ++int_large_selected;
                        }
                    } else if (li[i].childNodes[0].textContent == 'International Large Cap') {
                        if (int_large_selected > 0) {
                            if (!li[i].classList.contains('checked')) {
                                li[i].classList.add('checked');
                            }
                        } else {
                            if (li[i].classList.contains('checked')) {
                                li[i].classList.remove('checked');
                            }
                        }
                    } else if (li[i].childNodes[0].textContent == 'All-World ex-US Small Cap Index Fund' || li[i].childNodes[0].textContent == 'VSS') {
                        if (li[i].classList.contains("checked")) {
                            ++int_small_selected;
                        }
                    } else if (li[i].childNodes[0].textContent == 'International Small Cap') {
                        if (int_small_selected > 0) {
                            if (!li[i].classList.contains('checked')) {
                                li[i].classList.add('checked');
                            }
                        } else {
                            if (li[i].classList.contains('checked')) {
                                li[i].classList.remove('checked');
                            }
                        }
                    } else if (li[i].childNodes[0].textContent == 'Intermediate Term Treasury Bond Index' || li[i].childNodes[0].textContent == 'Total Bond Market Index' || li[i].childNodes[0].textContent == 'BND' || li[i].childNodes[0].textContent == 'BIV') {
                        if (li[i].classList.contains("checked")) {
                            ++bonds_selected;
                        }
                    } else if (li[i].childNodes[0].textContent == 'Bonds') {
                        if (bonds_selected > 0) {
                            if (!li[i].classList.contains('checked')) {
                                li[i].classList.add('checked');
                            }
                        } else {
                            if (li[i].classList.contains('checked')) {
                                li[i].classList.remove('checked');
                            }
                        }
                    }
                }

                showSelection(); //update stocks_selection list
            }
            confirmStocks(); //updates list of input text fields for user to enter money in each stock
        }, false);
    }

    function showSelection() {
        selected_stock_options = [];
        stock_selection_values = [];
        var ul = document.getElementById("stock_option");
        var li = ul.getElementsByClassName("li_stock");
        for (var i = 0; i < li.length; i++) {
            if (li[i].classList.contains("checked")) {
                selected_stock_options.push(li[i].childNodes[0].textContent)
                console.log(li[i]);
            }
        }
    }

    function confirmStocks() {
        var div = document.getElementById('money_in_stocks');
        while (div.firstChild) {
            div.removeChild(div.firstChild);
        }
        document.getElementById("money_in_stocks").innerText += "Enter the amount of money you have in each stock: \n\n"

        for (var i = 0; i < selected_stock_options.length; i++) {
            var input = document.createElement('input');
            input.type = "number";
            input.id = selected_stock_options[i];
            input.classList.add("form-control");
            input.style["color"] = "white";
            input.value = 0;
            input.placeholder = "$";

            if (input.id == 'Domestic Large Cap' || input.id == 'Domestic Small Cap' || input.id == 'International Large Cap' || input.id == 'International Small Cap' || input.id == 'Bonds') {
                input.classList.add("form-control-inline")
                input.style["background"] = "transparent";
                input.style["border"] = "none";
                input.style["border-bottom"] = "2px solid #FF8FCF";
                input.style["-webkit-box-shadow"] = "none";
                input.style["box-shadow"] = "none";
                input.style["border-radius"] = "0";
                input.style["height"] = "25px";
                input.style['font-size'] = "15px";
                input.style['text-align'] = "center";

                input.readOnly = true;
                //my changes
                var newlabel = document.createElement("Label");
                newlabel.setAttribute("for", input.id);
                newlabel.style['font-size'] = "15px";

                newlabel.classList.add("control-label");
                newlabel.innerHTML = selected_stock_options[i];
                //
                document.getElementById("money_in_stocks").appendChild(newlabel);
                document.getElementById("money_in_stocks").appendChild(input);
                document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
                document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
                document.getElementById("money_in_stocks").appendChild(document.createElement("br"));

            } else {
                input.style["height"] = "25px";
                //my changes
                var newlabel = document.createElement("Label");
                newlabel.setAttribute("for", input.id);
                newlabel.style['font-size'] = "12px";
                input.style['font-size'] = "12px";

                newlabel.classList.add("control-label");
                newlabel.innerHTML = selected_stock_options[i];
                //
                document.getElementById("money_in_stocks").appendChild(newlabel);
                document.getElementById("money_in_stocks").appendChild(input);
                document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
            }

        }
    }

    function SearchThroughStocks() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("searchbar");
        filter = input.value.toUpperCase();
        ul = document.getElementById("stock_option");
        //li = ul.getElementsByTagName("li");
        li = ul.getElementsByClassName("li_stock");
        for (i = 0; i < li.length; i++) {
            txtValue = li[i].childNodes[0].textContent;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    function updateStockValuesText() {
        if (existing_stock_values != null) {
            for (var i = 0; i < selected_stock_options.length; ++i) {
                if (document.getElementById(selected_stock_options[i]) !== null) {
                    document.getElementById(selected_stock_options[i]).value = existing_stock_values.split(",")[i];
                }
            }
        }
    }

    function confirmStockValues() {
        var money = parseInt(document.getElementById("money").value);
        var savings = document.getElementById("savings").value;
        var networth = parseInt(savings);

        var domestic_large_value = 0;
        var domestic_small_value = 0;
        var int_large_value = 0;
        var int_small_value = 0;
        var bonds_value = 0;

        for (var i = 0; i < selected_stock_options.length; i++) {

            if (selected_stock_options[i] == 'Domestic Large Cap') {
                stock_selection_values.push(domestic_large_value);
            } else if (selected_stock_options[i] == 'Domestic Small Cap') {
                stock_selection_values.push(domestic_small_value);
            } else if (selected_stock_options[i] == 'International Large Cap') {
                stock_selection_values.push(int_large_value);
            } else if (selected_stock_options[i] == 'International Small Cap') {
                stock_selection_values.push(int_small_value);
            } else if (selected_stock_options[i] == 'Bonds') {
                stock_selection_values.push(bonds_value);
            } else {
                if (document.getElementById(selected_stock_options[i]).value.length == 0) {
                    stock_selection_values.push(0);
                } else {
                    stock_selection_values.push(document.getElementById(selected_stock_options[i]).value);
                    networth += parseInt(document.getElementById(selected_stock_options[i]).value);

                    if (selected_stock_options[i] == 'S and P 500 Index Fund' || selected_stock_options[i] == 'Large Cap Index Fund' || selected_stock_options[i] == 'Total Stock Market Index Fund' || selected_stock_options[i] == 'VTI' || selected_stock_options[i] == 'VOO' || selected_stock_options[i] == 'VV') {
                        domestic_large_value += parseInt(document.getElementById(selected_stock_options[i]).value);
                    } else if (selected_stock_options[i] == 'Extended Market Index Fund' || selected_stock_options[i] == 'Small Cap Index Fund' || selected_stock_options[i] == 'VXF' || selected_stock_options[i] == 'VB') {
                        domestic_small_value += parseInt(document.getElementById(selected_stock_options[i]).value);
                    } else if (selected_stock_options[i] == 'All-World ex-US Index Fund' || selected_stock_options[i] == 'Total International Stock Index Fund' || selected_stock_options[i] == 'VEU' || selected_stock_options[i] == 'VXUS' || selected_stock_options[i] == 'IXUS') {
                        int_large_value += parseInt(document.getElementById(selected_stock_options[i]).value);
                    } else if (selected_stock_options[i] == 'All-World ex-US Small Cap Index Fund' || selected_stock_options[i] == 'VSS') {
                        int_small_value += parseInt(document.getElementById(selected_stock_options[i]).value);
                    } else if (selected_stock_options[i] == 'Intermediate Term Treasury Bond Index' || selected_stock_options[i] == 'Total Bond Market Index' || selected_stock_options[i] == 'BND' || selected_stock_options[i] == 'BIV') {
                        bonds_value += parseInt(document.getElementById(selected_stock_options[i]).value);
                    }
                }

            }
        }

        var stock = selected_stock_options.join();
        document.getElementById("stocks_list").value = stock;
        console.log("stock joined " + stock);

        var stock_values = stock_selection_values.join();
        document.getElementById("stocks_values_list").value = stock_values;

        if (date_networth_arr == null) { //first time entering data
            document.getElementById("dates_networth_over_time").value = date;
            document.getElementById("networth_over_time_stocks").value = parseInt(networth);
        } else {
            date_networth_arr = date_networth_arr.split(",");
            networth_time_arr = networth_time_arr.split(",");
            var most_recent = date_networth_arr[date_networth_arr.length - 1];

            if (most_recent == date) { //entering data again on same day
                var dates_over_time = date_networth_arr.join();
                networth_time_arr[networth_time_arr.length - 1] = parseInt(networth);

            } else { //entering data on a diff day
                date_networth_arr.push(date);
                networth_time_arr.push(parseInt(networth));
                var dates_over_time = date_networth_arr.join();
            }
            var networth_over_time = networth_time_arr.join();
            document.getElementById("dates_networth_over_time").value = dates_over_time;
            document.getElementById("networth_over_time_stocks").value = networth_over_time;
        }

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

        console.log(savings_per_year);
        console.log(bad_savings_per_year);

        //logging savings req over time data
        if (date_arr == null) { //first time entering data
            document.getElementById("dates_over_time").value = date;
            document.getElementById("savings_req_over_time").value = parseInt(savings_per_year);
            document.getElementById("savings_req_over_time_bad").value = parseInt(bad_savings_per_year);

        } else {
            date_arr = date_arr.split(",");
            savings_req_arr = savings_req_arr.split(",");
            savings_req_arr_bad = savings_req_arr_bad.split(",");
            var most_recent = date_arr[date_arr.length - 1];

            if (most_recent == date) { //entering data again on same day
                var dates_over_time = date_arr.join();
                savings_req_arr[savings_req_arr.length - 1] = parseInt(savings_per_year);
                savings_req_arr_bad[savings_req_arr_bad.length - 1] = parseInt(bad_savings_per_year);

            } else { //entering data on a diff day
                date_arr.push(date);
                savings_req_arr_bad.push(parseInt(bad_savings_per_year));
                savings_req_arr.push(parseInt(savings_per_year));
                var dates_over_time = date_arr.join();
            }
            var savings_over_time = savings_req_arr.join();
            var savings_over_time_bad = savings_req_arr_bad.join();
            document.getElementById("dates_over_time").value = dates_over_time;
            document.getElementById("savings_req_over_time").value = savings_over_time;
            document.getElementById("savings_req_over_time_bad").value = savings_over_time_bad;
        }
    }

    showAllStockOptions();

    if (existing_stocks != null && existing_stock_values != null) {
        reset_stock_list();
    }

    updateStockValuesText();
</script>

</html>