var curr_year = new Date().getFullYear(); //2021
var time = []
var money_over_time = []
var goal
var wealth = []

function result() {

    var currentWidth = 1500;

    var money = document.getElementById('money').value;
    var savings = document.getElementById('savings').value;
    var age = document.getElementById('curr_age').value; //current age
    var life = document.getElementById('life').value; //life expectancy
    var r1 = 0.07;
    var r2 = 0.04;
    var inf_rate = 0.03; //inflation rate
    var years = document.getElementById('life').value - document.getElementById('curr_age').value; //years left in life
    var accum_years = document.getElementById('ret_age').value - document.getElementById('curr_age').value; //accumulation years
    var distr_years = document.getElementById('life').value - document.getElementById('ret_age').value; //distribution years

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
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2]))
        .attr("width", "100%")
        .append("svg:g")
        .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

    var x = d3.scaleLinear().domain([1, money_over_time.length]).range([0, w]);
    var y = d3.scaleLinear().domain([0, Math.max.apply(Math, money_over_time)]).range([h, 0]);
    var xAxis = d3.axisBottom(x).tickSize(-h).tickFormat(function (d) { return (d + curr_year - 1); });//.tickSubdivide(true)

    graph.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + h + ")")
        .style("font-size", 16)
        .call(xAxis);

    graph.append("text")
        .style("fill", "white")
        .style("font-size", 20)
        .attr("transform",
            "translate(" + (w / 2) + " ," +
            (h + m[0]) + ")")
        .style("text-anchor", "middle")
        .text("Year");

    graph.append("text")
        .style("fill", "white")
        .style("font-size", 20)
        .attr("transform", "rotate(-90)")
        .attr("y", 0 - m[1] + 30)
        .attr("x", 0 - (h / 2))
        .attr("dy", "1em")
        .style("text-anchor", "middle")
        .text("$");


    var yAxisLeft = d3.axisLeft(y).ticks(4)

    graph.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(-25,0)")
        .style("font-size", 14)
        .call(yAxisLeft);

    var area = d3.area()
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
        .style("fill", "#00B2EE") //blue
        .style("stroke", "#00B2EE")

    graph
        .append("circle")
        .attr("cx", x(accum_years + 1))
        .attr("cy", y(money_over_time[accum_years]))
        .attr("r", 5)
        .attr("fill", "red")

    graph
        .append("text")
        .style("fill", "white")
        .attr("x", x(years))
        .attr("y", y(money_over_time[money_over_time.length - 1]))
        .attr("dx", ".71em")
        .attr("dy", ".35em")
        .style("font-size", 18)
        .text((years + curr_year) + " - $" + parseInt(money_over_time[money_over_time.length - 1]))


    //splitting graph 2 into accumulation and distr years
    var wealth1 = wealth.slice(0, accum_years + 1)
    var wealth2 = wealth.slice(accum_years, wealth.length)

    //graph for wealth per year
    var graph2 = d3.select("#graph2")
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2]))
        .attr("width", "100%")
        .append("svg:g")
        .attr("transform", "translate(" + m[3] + "," + m[0] + ")");
    graph2.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + h + ")")
        .style("font-size", 16)
        .call(xAxis);
    graph2.append("text")
        .style("fill", "white")
        .style("font-size", 20)
        .attr("transform",
            "translate(" + (w / 2) + " ," +
            (h + m[0]) + ")")
        .style("text-anchor", "middle")
        .text("Year");

    graph2.append("text")
        .style("fill", "white")
        .style("font-size", 20)
        .attr("transform", "rotate(-90)")
        .attr("y", 0 - m[1] + 20)
        .attr("x", 0 - (h / 2))
        .attr("dy", "1em")
        .style("text-anchor", "middle")
        .text("$");

    var y2 = d3.scaleLinear().domain([0, Math.max.apply(Math, wealth)]).range([h, 0]);
    var yAxisLeft2 = d3.axisLeft(y2).ticks(4)


    graph2.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(-25,0)")
        .style("font-size", 14)
        .call(yAxisLeft2);

    var area2 = d3.area()
        .x(function (d, i) {
            return x(i + 1);
        })
        .y1(function (d) {
            return y2(d);
        })
        .y0(y2(0));


    var areaRetire = d3.area()
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
        .style("fill", "#6EE7A2 ")//orange //#1DA237 6EE7A2 
        .style("stroke", "#6EE7A2 ")
    //.style("opacity", .8)

    graph2 // after retirement graph
        .datum(wealth2)
        .append("path")
        .attr("d", areaRetire)
        .style("stroke-width", 2)
        .style("fill", '#f55870') //purple
        .style("stroke", '#f55870')
    //.style("opacity", .8)

    graph2 // vertical line of apex 
        .append("line")
        .attr("x1", x(accum_years + 1))
        .attr("y1", 0)
        .attr("x2", x(accum_years + 1))
        .attr("y2", h)
        .style("stroke-dasharray", ("4, 4"))
        .style("stroke", "red")


    graph2
        .append("text")
        .style("fill", "white")
        .style("font-size", 18)
        .attr("x", x(accum_years + 1))
        .attr("y", y2(wealth2[0]))
        .attr("dx", ".71em")
        .attr("dy", ".35em")
        .text((accum_years + curr_year) + " - $" + parseInt(wealth2[0]))

    //circle moving along line for graph 1 (inflation adjusted M)
    var focus = graph
        .append('g')
        .append('circle')
        .style("fill", "none")
        .attr("stroke", "#D9EB4B")
        .attr("stroke-width", 4)
        .attr('r', 8.5)
        .style("opacity", 0)

    var focusText = graph
        .append('g')
        .append('text')
        .style("fill", "#D9EB4B")
        .style("font-size", 18)
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
        focusText.style("font-size", "30px")
    }

    function mousemove() {
        var x0 = x.invert(d3.mouse(this)[0]);
        var i = d3.bisect(time, x0);
        selectedData = money_over_time[i - 1]
        focus
            .attr("cx", x(i))
            .attr("cy", y(selectedData))
        focusText
            .html("Year: " + (curr_year + i - 1) + "  ,  " + "$: " + parseInt(selectedData))
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
        .attr("stroke", "#8A2BE2")
        .attr("stroke-width", 4)
        .attr('r', 8.5)
        .style("opacity", 0)

    var focusText2 = graph2
        .append('g')
        .append('text')
        .style("fill", "#8A2BE2")
        .style("font-size", 18)
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
        focusText2.style("font-size", "30px")
    }

    function mousemove2() {
        var x0 = x.invert(d3.mouse(this)[0]);
        var i = d3.bisect(time, x0);
        selectedData = wealth[i - 1]
        focus2
            .attr("cx", x(i))
            .attr("cy", y2(selectedData))
        focusText2
            .html("Year:" + (curr_year + i - 1) + "  ,  " + "$:" + parseInt(selectedData))
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
    text += "Money you need per year = " + (money) + "\n"
    text += "Portfolio Growth Rate (Accumulation Years) = " + parseInt(r1 * 100) + "%\n"
    text += "Portfolio Growth Rate (Distribution Years) = " + parseInt(r2 * 100) + "%\n"
    if (savings_per_year > 0) {
        text += "Savings required each year: $" + parseInt(savings_per_year)
    } else {
        text += "You have enough savings to live comfortably during retirement";
    }

    document.getElementById('table_ret_year').innerText = curr_year + accum_years;
    document.getElementById('table_distr_years').innerText = distr_years;
    document.getElementById('table_money_per_year').innerText = "$" + money;
    document.getElementById('table_r1').innerText = parseInt(r1 * 100) + "%";
    document.getElementById('table_r2').innerText = parseInt(r2 * 100) + "%";
    document.getElementById('table_savings_req').innerText = "$" + parseInt(savings_per_year);
}


var rangeValue = function () {
    result()
}

result()

window.addEventListener('resize', result); //to make graphs responsive
