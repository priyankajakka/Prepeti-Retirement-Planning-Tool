var curr_year = new Date().getFullYear(); //2021
var time = []
var money_over_time = []
var goal
var wealth = []

var selected_stock_options = [];

function reset_stock_list() {

    selected_stock_options = existing_stocks.split(",");

    var ul = document.getElementById("stock_option");
    var li = ul.getElementsByTagName("li");
    var innertext = "You selected: \n"

    for (var i = 0; i < li.length; ++i) {
        for (var j = 0; j < selected_stock_options.length; ++j) {
            if (li[i].textContent === selected_stock_options[j]) {
                li[i].classList.toggle('checked');
                innertext += selected_stock_options[j] + "\n"
            }
        }
    }
    document.getElementById("stocks_selection").innerText = innertext;

    var div = document.getElementById('money_in_stocks');
    while (div.firstChild) {
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

function showAllStockOptions() {
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
        confirmStocks();
    }, false);
    stock_options = [];
}

function showSelection() {
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

function confirmStocks() {
    var stock = selected_stock_options.join();
    document.Form.stocks_list.value = stock;

    var div = document.getElementById('money_in_stocks');
    while (div.firstChild) {
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

var stock_selection_values = [];

function confirmStockValues() {
    for (var i = 0; i < selected_stock_options.length; i++) {
        stock_selection_values.push(document.getElementById(selected_stock_options[i]).value);
    }
    var stock_values = stock_selection_values.join();
    document.Form.stocks_values_list.value = stock_values;
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

if(existing_stocks != null && existing_stock_values != null){
    reset_stock_list();
}


function result() {

    //document.getElementById("age_slider").value = document.getElementById("curr_age").value
    //document.getElementById("ret_age_slider").value = document.getElementById("ret_age").value
    document.getElementById("money_slider").value = document.getElementById("money").value
    document.getElementById("savings_slider").value = document.getElementById("savings").value
    //document.getElementById("life_slider").value = document.getElementById("life").value
    document.getElementById("income_slider").value = document.getElementById("income").value

    var currentWidth = parseInt(d3.select('#graph').style('width'), 10)
    if (currentWidth > 2000) {
        currentWidth = 2000;
    }

    var money = document.getElementById('money').value;
    var income = document.getElementById('income').value;
    var savings = document.getElementById('savings').value;
    //var age = document.getElementById('curr_age').value; //current age
    //var life = document.getElementById('life').value; //life expectancy
    var r1 = 0.07;
    var r2 = 0.04;
    var inf_rate = 0.03; //inflation rate
    var years = life - curr_age; //years left in life
    var accum_years = ret_age - curr_age; //accumulation years
    var distr_years = life - ret_age; //distribution years

    d3.selectAll("svg").remove();
    money_over_time = [] //inflation adjusted M per year
    time = []
    wealth = [] //wealth per year

    //inflation adjusted M per year
    for (let i = 1; i <= years + 1; i++) {
        var adjusted_money = money * Math.pow(1 + inf_rate, i - 1)
        money_over_time.push(adjusted_money)
        time.push(i)
    }

    //goal money before retirement
    goal = money_over_time[accum_years + 1] * ((1 / (parseFloat(r2) - inf_rate)) - (Math.pow((1 + inf_rate), distr_years) / ((parseFloat(r2) - inf_rate) * Math.pow((1 + parseFloat(r2)), distr_years))))
    wealth.push(savings);

    var PV_goal = goal / Math.pow((1 + parseFloat(r1)), accum_years)
    var gap = PV_goal - savings
    var savings_per_year = gap / ((1 / parseFloat(r1)) - (1 / (parseFloat(r1) * Math.pow(1 + parseFloat(r1), accum_years))))

    //wealth per year in accumulation years
    for (let i = 2; i < accum_years + 1; i++) {
        var total_wealth = (wealth[i - 2] * (1 + parseFloat(r1))) + savings_per_year;
        wealth.push(total_wealth)
    }
    wealth.push(goal)

    //wealth per year in distribution years
    for (let i = accum_years + 2; i <= life; i++) {
        var total_wealth = (wealth[i - 2] * (1 + parseFloat(r2))) - money_over_time[i - 1];
        if (total_wealth >= 0) {
            wealth.push(total_wealth)
        } else {
            wealth.push(0)
            break;
        }
    }

    var m = [50, 190, 100, 190]; // margins, m[0], m[2] = top/below, m[1] = right, m[3] = left
    var w = currentWidth - m[1] - m[3]; // width
    var h = 400 - m[0] - m[2]; // height

    //graph for inflation adjusted M
    var graph = d3.select("#graph")
        .append("svg")
        .attr("width", w + m[1] + m[3])
        .attr("height", h + m[0] + m[2])
        .append("svg:g")
        .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

    var x = d3.scale.linear().domain([1, money_over_time.length]).range([0, w]);
    var y = d3.scale.linear().domain([0, d3.max(money_over_time)]).range([h, 0]);

    var xAxis = d3.svg.axis().scale(x).tickSize(-h).tickSubdivide(true).tickFormat(function (d) { return (d + curr_year - 1); });;

    graph.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + h + ")")
        .call(xAxis);

    graph.append("text")
        .attr("transform",
            "translate(" + (w / 2) + " ," +
            (h + m[0]) + ")")
        .style("text-anchor", "middle")
        .text("Year");

    var yAxisLeft = d3.svg.axis().scale(y).ticks(4).orient("left");

    graph.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(-25,0)")
        .call(yAxisLeft);

    var line = d3.svg.line()
        .x(function (d, i) {
            return x(i + 1);
        })
        .y(function (d) {
            return y(d);
        })

    graph
        .append("text")
        .attr("x", (w / 2))
        .attr("y", 0 - (m[0] / 2))
        .attr("text-anchor", "middle")
        .style("font-size", "16px")
        .style("text-decoration", "underline")
        .text("Inflation adjusted M vs Year")

    //shading for graph 1 (inflation adjusted M)
    var area = d3.svg.area()
        .x(function (d, i) {
            return x(i + 1);
        })
        .y1(function (d) {
            return y(d);
        })
        .y0(y(0))

    //shading
    graph
        .datum(money_over_time)
        .append("path")
        .attr("d", area)
        .style("stroke-width", 2)
        .style("fill", "blue")
        .style("stroke", "blue")
        .style("opacity", .6)

    //highlight retirement year in graph 1 (inflation adjusted M)
    /*if (document.getElementById("curr_age").value.length != 0 && document.getElementById("ret_age").value.length != 0) {
        graph
            .append("circle")
            .attr("cx", x(accum_years + 1))
            .attr("cy", y(money_over_time[accum_years]))
            .attr("r", 5)
            .attr("fill", "red")
    }*/

    //splitting graph 2 into accumulation and distr years
    var wealth1 = wealth.slice(0, accum_years + 1)
    var wealth2 = wealth.slice(accum_years, wealth.length)

    //graph for wealth per year
    var graph2 = d3.select("#graph2")
        .append("svg")
        .attr("width", w + m[1] + m[3])
        .attr("height", h + m[0] + m[2])
        .append("svg:g")
        .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

    graph2.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + h + ")")
        .call(xAxis);

    graph2.append("text")
        .attr("transform",
            "translate(" + (w / 2) + " ," +
            (h + m[0]) + ")")
        .style("text-anchor", "middle")
        .text("Year");

    var y2 = d3.scale.linear().domain([0, d3.max(wealth)]).range([h, 0]);
    var yAxisLeft2 = d3.svg.axis().scale(y2).ticks(4).orient("left");

    var dotted = d3.svg.line()
        .x(function (d, i) {
            return x(i + 1);
        })
        .y(function (d) {
            return y2(d);
        })

    graph2.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(-25,0)")
        .call(yAxisLeft2);

    graph2
        .append("text")
        .attr("x", (w / 2))
        .attr("y", 0 - (m[0] / 2))
        .attr("text-anchor", "middle")
        .style("font-size", "16px")
        .style("text-decoration", "underline")
        .text("Wealth vs Year")

    var line2 = d3.svg.line()
        .x(function (d, i) {
            return x(i + 1);
        })
        .y(function (d) {
            return y2(d);
        })

    // area graphs - y1 sets top, y0 sets the lowest y value
    var area2 = d3.svg.area()
        .x(function (d, i) {
            return x(i + 1);
        })
        .y1(function (d) {
            return y2(d);
        })
        .y0(y2(0));

    var areaRetire = d3.svg.area()
        .x(function (d, i) {
            return x(i + 1 + accum_years);
        })
        .y1(function (d) {
            return y2(d);
        })
        .y0(y2(0));


    graph2 //before retirement
        .datum(wealth1)
        .append("path")
        .attr("d", area2)
        .style("stroke-width", 2)
        .style("fill", "orange")
        .style("stroke", "orange")
        .style("opacity", .6)


    graph2 // after retirement graph
        .datum(wealth2)
        .append("path")
        .attr("d", areaRetire)
        .style("stroke-width", 2)
        .style("fill", "purple")
        .style("stroke", "purple")
        .style("opacity", .6)

    graph2 // vertical line of apex 
        .append("line")
        .attr("x1", x(accum_years + 1))
        .attr("y1", 0)
        .attr("x2", x(accum_years + 1))
        .attr("y2", h)
        .style("stroke-dasharray", ("4, 4"))
        .style("stroke", "red")

    //circle moving along line for graph 1 (inflation adjusted M)
    var focus = graph
        .append('g')
        .append('circle')
        .style("fill", "none")
        .attr("stroke", "black")
        .attr('r', 8.5)
        .style("opacity", 0)

    var focusText = graph
        .append('g')
        .append('text')
        .style("opacity", 0)
        .attr("text-anchor", "left")
        .attr("alignment-baseline", "middle")

    graph
        .append('rect')
        .style("fill", "none")
        .style("pointer-events", "all")
        .attr('width', w)
        .attr('height', h)
        .on('mouseover', mouseover)
        .on('mousemove', mousemove)
        .on('mouseout', mouseout);

    function mouseover() {
        focus.style("opacity", 1)
        focusText.style("opacity", 1)
    }

    function mousemove() {
        var x0 = x.invert(d3.mouse(this)[0]);
        var i = d3.bisect(time, x0);
        selectedData = money_over_time[i - 1]
        focus
            .attr("cx", x(i))
            .attr("cy", y(selectedData))
        focusText
            .html("Year:" + (curr_year + i - 1) + "  ,  " + "M:" + parseInt(selectedData))
            .attr("x", x(i) + 15)
            .attr("y", y(selectedData) - 35)
    }
    function mouseout() {
        focus.style("opacity", 0)
        focusText.style("opacity", 0)
    }

    //circle moving along line for graph 2 (wealth vs year)
    var focus2 = graph2
        .append('g')
        .append('circle')
        .style("fill", "none")
        .attr("stroke", "black")
        .attr('r', 8.5)
        .style("opacity", 0)

    var focusText2 = graph2
        .append('g')
        .append('text')
        .style("opacity", 0)
        .attr("text-anchor", "left")
        .attr("alignment-baseline", "middle")

    graph2
        .append('rect')
        .style("fill", "none")
        .style("pointer-events", "all")
        .attr('width', w)
        .attr('height', h)
        .on('mouseover', mouseover2)
        .on('mousemove', mousemove2)
        .on('mouseout', mouseout2);

    function mouseover2() {
        focus2.style("opacity", 1)
        focusText2.style("opacity", 1)
    }

    function mousemove2() {
        var x0 = x.invert(d3.mouse(this)[0]);
        var i = d3.bisect(time, x0);
        selectedData = wealth[i - 1]
        focus2
            .attr("cx", x(i))
            .attr("cy", y2(selectedData))
        focusText2
            .html("Year:" + (curr_year + i - 1) + "  ,  " + "M:" + parseInt(selectedData))
            .attr("x", x(i) + 15)
            .attr("y", y2(selectedData) - 35)
    }
    function mouseout2() {
        focus2.style("opacity", 0)
        focusText2.style("opacity", 0)
    }

    //summary portion
    var text = "";
    text += "Retirement Year = " + (curr_year + accum_years) + "\n"
    text += "Years in Retirement = " + (distr_years) + "\n"
    text += "Annual Income = " + (income) + "\n"
    text += "Money you need per year = " + (money) + "\n"
    text += "Portfolio Growth Rate (Accumulation Years) = " + parseInt(r1 * 100) + "%\n"
    text += "Portfolio Growth Rate (Distribution Years) = " + parseInt(r2 * 100) + "%\n"
    if (savings_per_year > 0) {
        text += "Savings required each year: $" + parseInt(savings_per_year)
    } else {
        text += "You have enough savings to live comfortably during retirement";
    }

    document.getElementById('summary').innerText = text
}

//var age_slider = document.getElementById("age_slider")
//var ret_age_slider = document.getElementById("ret_age_slider")
var money_slider = document.getElementById("money_slider")
var savings_slider = document.getElementById("savings_slider")
//var life_slider = document.getElementById("life_slider")
var income_slider = document.getElementById("income_slider")

var rangeValue = function () {
    //var newAge = age_slider.value;
    //var newRetAge = ret_age_slider.value;
    var newMoney = money_slider.value;
    var newSavings = savings_slider.value;
    //var newLife = life_slider.value;
    var newIncome = income_slider.value;

    //document.getElementById("curr_age").value = newAge;
    //document.getElementById("ret_age").value = newRetAge;
    document.getElementById("money").value = newMoney;
    document.getElementById("savings").value = newSavings;
    //document.getElementById("life").value = newLife;
    document.getElementById("income").value = newIncome;
    result()
}

//age_slider.addEventListener("input", rangeValue);
//ret_age_slider.addEventListener("input", rangeValue);
money_slider.addEventListener("input", rangeValue);
savings_slider.addEventListener("input", rangeValue);
//life_slider.addEventListener("input", rangeValue);
income_slider.addEventListener("input", rangeValue);

result()

window.addEventListener('resize', result); //to make graphs responsive
