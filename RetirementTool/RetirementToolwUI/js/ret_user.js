var curr_year = new Date().getFullYear(); //curr_year = 2021
var time = []
var money_over_time = []
var goal
var wealth = []
var networth = 0;
var money, income, savings, r1, bad_r1, r2, inf_rate, years, accum_years, distr_years, savings_per_year, bad_savings_per_year;

var segmented_401k_percent_dom = 0;
var segmented_401k_percent_int = 0;

var segmented_401k_percent_dom1 = 0;
var segmented_401k_percent_int1 = 0;
var segmented_401k_percent_dom2 = 0;
var segmented_401k_percent_int2 = 0;

var segmented_401k_percent_bonds = 0;

var segmented_brok_percent_dom = 0;
var segmented_brok_percent_int = 0;

var segmented_brok_percent_dom1 = 0;
var segmented_brok_percent_int1 = 0;
var segmented_brok_percent_dom2 = 0;
var segmented_brok_percent_int2 = 0;

var segmented_brok_percent_bonds = 0;

var selected_stock_options = [];
var stock_selection_values = [];

if (existing_stocks != null) {
    selected_stock_options = existing_stocks.split(",");
}

function inside401Kversusoutside() {

    if (existing_stock_values != null) {

        var currentWidth = 900;

        var margin = { top: 0.1 * currentWidth, right: 100, bottom: 0.1 * currentWidth, left: 100 },
            width = currentWidth / 3,
            height = 320 - margin.top - margin.bottom;

        var svg = d3.select("#segmentedBarGraph")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            .attr("viewBox", "0 0 " + (width + margin.right + margin.left) + " " + (250))
            .attr("width", "100%")
            .append("g")
            .attr("transform",
                "translate(" + (margin.left + 35) + "," + (margin.top - 50) + ")");

        var subgroups = ['domestic', 'international', 'bonds'];
        segmented_401k_percent_dom = segmented_401k_percent_dom1 + segmented_401k_percent_dom2;
        segmented_brok_percent_dom = segmented_brok_percent_dom1 + segmented_brok_percent_dom2;
        segmented_401k_percent_int = segmented_401k_percent_int1 + segmented_401k_percent_int2;
        segmented_brok_percent_int = segmented_brok_percent_int1 + segmented_brok_percent_int2;

        var data = [{ group: "401K", domestic: segmented_401k_percent_dom, international: segmented_401k_percent_int, bonds: segmented_401k_percent_bonds },
        { group: "Brokerage", domestic: segmented_brok_percent_dom, international: segmented_brok_percent_int, bonds: segmented_brok_percent_bonds }]

        var groups = d3.map(data, function (d) { return (d.group) }).keys()

        // Add X axis
        var x = d3.scaleBand()
            .domain(groups)
            .range([0, width])
            .padding([0.5])
        svg.append("g")
            .attr("transform", "translate(0," + height + ")")
            .attr("class", "axisWhite")
            .call(d3.axisBottom(x).tickSizeOuter(0));

        // Add Y axis
        var y = d3.scaleLinear()
            .domain([0, 100])
            .range([height, 0]);
        svg.append("g")
            .attr("class", "axisWhite")
            .call(d3.axisLeft(y));


        var color = d3.scaleOrdinal()
            .domain(subgroups)
            .range(['#ff3459', '#7be0b0', '#ffc700'])

        //stack the data? --> stack per subgroup
        var stackedData = d3.stack()
            .keys(subgroups)
            (data)

        // Show the bars
        svg.append("g")
            .selectAll("g")
            // Enter in the stack data = loop key per key = group per group
            .data(stackedData)
            .enter().append("g")
            .attr("fill", function (d) { return color(d.key); })
            .selectAll("rect")
            // enter a second time = loop subgroup per subgroup to add all rectangles
            .data(function (d) { return d; })
            .enter().append("rect")
            .attr("x", function (d) { return x(d.data.group); })
            .attr("y", function (d) { return y(d[1]); })
            .attr("height", function (d) { return y(d[0]) - y(d[1]); })
            .attr("width", x.bandwidth())

        svg.append("text")
            .style("font-size", 12)
            .attr("transform",
                "translate(" + (width / 2) + " ," +
                (height + margin.top - 10) + ")")
            .style("text-anchor", "middle")
            .style("fill", "white")
            .text("Account");

        svg.append("circle").attr("cx", (width / 2) - 100).attr("cy", height + margin.top - 60).attr("r", 6).style("fill", "#ff3459")
        svg.append("circle").attr("cx", (width / 2)).attr("cy", height + margin.top - 60).attr("r", 6).style("fill", "#7be0b0")
        svg.append("circle").attr("cx", (width / 2) + 100).attr("cy", height + margin.top - 60).attr("r", 6).style("fill", "#ffc700")

        svg.append("text").attr("x", (width / 2) - 130).attr("y", height + margin.top - 40).text("Domestic").style("font-size", 11).style("fill", "white").attr("alignment-baseline", "middle")
        svg.append("text").attr("x", (width / 2) - 30).attr("y", height + margin.top - 40).text("International").style("font-size", 11).style("fill", "white").attr("alignment-baseline", "middle")
        svg.append("text").attr("x", (width / 2) + 84).attr("y", height + margin.top - 40).text("Bonds").style("font-size", 11).style("fill", "white").attr("alignment-baseline", "middle")


        // text label for the y axis
        svg.append("text")
            .style("font-size", 12)
            .attr("transform", "rotate(-90)")
            .attr("y", 0 - margin.top)
            .attr("x", 0 - (height / 2))
            .attr("dy", "1em")
            .style("text-anchor", "middle")
            .style("fill", "white")
            .text("Percent");
    }
}

function netWorth() {
    networth = parseInt(savings);
    if (existing_stock_values != null) {
        var temp_arr_stock = existing_stock_values.split(",");
        for (var i = 0; i < temp_arr_stock.length; ++i) {
            networth += parseInt(temp_arr_stock[i]);
        }
    }
}

//piechart for portfolios
function portfolio_chart() {
    if (portfolio != "null") { //piechart will only show up if the user has been assigned a portfolio

        var width_pie_chart = 1200;
        var height_pie_chart = 600;
        var radius_pie_chart;

        if (width_pie_chart > height_pie_chart) {
            radius_pie_chart = (height_pie_chart / 2) - 20;
        } else {
            radius_pie_chart = width_pie_chart / 2;
        }

        var svg_pie_chart = d3.select("#pie-chart")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet") //for responsiveness
            .attr("viewBox", "0 0 " + (width_pie_chart) + " " + (height_pie_chart)) //for responsiveness
            //.classed("svg-content", true)
            //.attr("width", width_pie_chart)
            //.attr("height", height_pie_chart)
            .attr("width", "100%")
            .attr("height", "100%")
            .append("g")
            .attr("transform", "translate(" + width_pie_chart / 2 + "," + height_pie_chart / 2 + ")");


        //goal percentages for each of the following categories
        var portfolio_percents = { "Domestic Large": 1, "International Small": 1, "International Large": 1, "Bonds": 1, "Domestic Small": 1 }
        portfolio_percents['Domestic Large'] = dom_large_percent;
        portfolio_percents['Domestic Small'] = dom_small_percent;
        portfolio_percents['International Large'] = int_large_percent;
        portfolio_percents['International Small'] = int_small_percent;
        portfolio_percents['Bonds'] = bonds_percent;

        //setting colors for each category
        var color = d3.scaleOrdinal()
            .domain(["Domestic Large", "International Small", "International Large", "Bonds", "Domestic Small"])
            .range(["#8A00BA", "#00AAFF", "#31DE7C", "#FFCD00", "#FF617B"]);//FF617B
        //.range(["#f4c042", "#1a75be", "#709931", "#dc3545", "#fb6340"]);

        var pie = d3.pie()
            .sort(null) // Do not sort group by size
            .value(function (d) { return d.value; })
        var data_ready = pie(d3.entries(portfolio_percents))

        var arc = d3.arc()
            .innerRadius(radius_pie_chart * 0.4) // This is the size of the donut hole
            .outerRadius(radius_pie_chart * 0.8)

        // Another arc that won't be drawn. Just for labels positioning
        var outerArc = d3.arc()
            .innerRadius(radius_pie_chart * 1.4)
            .outerRadius(radius_pie_chart * 0.4)

        svg_pie_chart
            .selectAll('allPolylines')
            .data(data_ready)
            .enter()
            .append('polyline')
            .attr("stroke", "white")
            .style("fill", "none")
            .attr("stroke-width", 1)
            .attr('points', function (d) {
                var posA = arc.centroid(d); // line insertion in the slice
                var posB = outerArc.centroid(d); // line break: we use the other arc generator that has been built only for that
                var posC = outerArc.centroid(d); // Label position = almost the same as posB
                var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2 // we need the angle to see if the X position will be at the extreme right or extreme left
                posC[0] = radius_pie_chart * 0.95 * (midangle < Math.PI ? 1 : -1); // multiply by 1 or -1 to put it on the right or on the left
                return [posA, posB, posC]
            })

        svg_pie_chart
            .selectAll('allSlices')
            .data(data_ready)
            .enter()
            .append('path')
            .attr('d', arc)
            .attr('fill', function (d) { return (color(d.data.key)) })
            .attr("stroke", "white")
            .style("stroke-width", "2px")

        svg_pie_chart
            .selectAll('allLabels')
            .data(data_ready)
            .enter()
            .append('text')
            .style("font-size", 20)
            .style("fill", "white")
            .text(function (d) { return d.data.key })
            .attr('transform', function (d) {
                var pos = outerArc.centroid(d);
                var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
                pos[0] = radius_pie_chart * 0.99 * (midangle < Math.PI ? 1 : -1);
                return 'translate(' + pos + ')';
            })
            .style('text-anchor', function (d) {
                var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
                return (midangle < Math.PI ? 'start' : 'end')
            })

        svg_pie_chart
            .selectAll('allSlices')
            .data(data_ready)
            .enter()
            .append('text')
            .text(function (d) { return d.data.value + '%' })
            .attr("transform", function (d) { return "translate(" + arc.centroid(d) + ")"; })
            .style("text-anchor", "middle")
            .style("font-size", 20)
            .style("fill", "white");

        /*svg_pie_chart.append("g")
            .attr("transform", "translate(" + (0) + "," + (-height_pie_chart / 2 + 25) + ")")
            .append("text")
            .style("font-size", "16px")
            .style("text-decoration", "underline")
            .style("text-anchor", "middle")
            .style("fill", "white")
            .text("Recommended Portfolio - " + portfolio.toUpperCase().slice(1, -1))*/


        //COPY OF PIE CHART

        var copy_svg_pie_chart = d3.select("#copy_pie-chart")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet") //for responsiveness
            .attr("viewBox", "0 0 " + (width_pie_chart) + " " + (height_pie_chart)) //for responsiveness
            .attr("width", "100%")
            .attr("height", "100%")
            .append("g")
            .attr("transform", "translate(" + width_pie_chart / 2 + "," + height_pie_chart / 2 + ")");

        copy_svg_pie_chart
            .selectAll('allPolylines')
            .data(data_ready)
            .enter()
            .append('polyline')
            .attr("stroke", "white")
            .style("fill", "none")
            .attr("stroke-width", 1)
            .attr('points', function (d) {
                var posA = arc.centroid(d); // line insertion in the slice
                var posB = outerArc.centroid(d); // line break: we use the other arc generator that has been built only for that
                var posC = outerArc.centroid(d); // Label position = almost the same as posB
                var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2 // we need the angle to see if the X position will be at the extreme right or extreme left
                posC[0] = radius_pie_chart * 0.95 * (midangle < Math.PI ? 1 : -1); // multiply by 1 or -1 to put it on the right or on the left
                return [posA, posB, posC]
            })

        copy_svg_pie_chart
            .selectAll('allSlices')
            .data(data_ready)
            .enter()
            .append('path')
            .attr('d', arc)
            .attr('fill', function (d) { return (color(d.data.key)) })
            .attr("stroke", "white")
            .style("stroke-width", "2px")

        copy_svg_pie_chart
            .selectAll('allLabels')
            .data(data_ready)
            .enter()
            .append('text')
            .style("font-size", 20)
            .style("fill", "white")
            .text(function (d) { return d.data.key })
            .attr('transform', function (d) {
                var pos = outerArc.centroid(d);
                var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
                pos[0] = radius_pie_chart * 0.99 * (midangle < Math.PI ? 1 : -1);
                return 'translate(' + pos + ')';
            })
            .style('text-anchor', function (d) {
                var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
                return (midangle < Math.PI ? 'start' : 'end')
            })

        copy_svg_pie_chart
            .selectAll('allSlices')
            .data(data_ready)
            .enter()
            .append('text')
            .text(function (d) { return d.data.value + '%' })
            .attr("transform", function (d) { return "translate(" + arc.centroid(d) + ")"; })
            .style("text-anchor", "middle")
            .style("font-size", 20)
            .style("fill", "white");
    } else {
        document.getElementById("portfolio_description").innerText = "Looks like you don't have a portfolio yet. Answer the questions in the \'My Savings\' section."
    }
}

//bargraph for stocks
function bargraph() {
    if (existing_stock_values != null) {
        var dataset1 = existing_stock_values.split(",");

        var selected_stock_options_duplicate = [];

        for (var i = 0; i < selected_stock_options.length; ++i) {
            selected_stock_options_duplicate[i] = selected_stock_options[i];
        }

        for (var i = 0; i < selected_stock_options_duplicate.length; ++i) {
            if (selected_stock_options_duplicate[i] == 'Domestic Large Cap' || selected_stock_options_duplicate[i] == 'Domestic Small Cap' || selected_stock_options_duplicate[i] == 'International Large Cap' || selected_stock_options_duplicate[i] == 'International Small Cap' || selected_stock_options_duplicate[i] == 'Bonds') {
                selected_stock_options_duplicate.splice(i, 1);
                dataset1.splice(i, 1);
                --i;
            }
        }

        console.log(selected_stock_options_duplicate);
        console.log(selected_stock_options);

        var currentWidth = 900;

        var m = [100, 0.1 * currentWidth, 100, 0.1 * currentWidth]; // margins, m[0], m[2] = top/below, m[1] = right, m[3] = left
        var w = currentWidth / 3;
        var h = 550 - m[0] - m[2]; // height

        var svg_bar_chart = d3.select("#barplot")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (560))
            .attr("width", "100%")
            .append("svg:g")
            .attr("transform", "translate(" + (m[3] + 35) + "," + (m[0] - 50) + ")");

        var x = d3.scaleBand()
            .range([0, w])
            .domain(selected_stock_options_duplicate)
            .padding(0.5);

        var y = d3.scaleLinear()
            .domain([0, Math.max.apply(Math, dataset1)])
            .range([h, 0]);

        svg_bar_chart.selectAll()
            .data(dataset1)
            .enter()
            .append("rect")
            .attr("x", function (d, i) { return x(selected_stock_options_duplicate[i]); })
            .attr("y", function (d, i) { return y(d); })
            .attr("width", x.bandwidth())
            .attr("height", function (d, i) { return (h - y(d)); })
            .attr("fill", "#A117F2")
        // .style("opacity", .6);

        svg_bar_chart.selectAll("text.bar")
            .data(dataset1)
            .enter().append("text")
            .style("fill", "white")
            .attr("class", "bar")
            .attr("text-anchor", "middle")
            .attr("x", function (d, i) { return (x(selected_stock_options_duplicate[i]) + x.bandwidth() / 2); })
            .attr("y", function (d, i) { return (y(d) - 5); })
            .text(function (d, i) { return d; });

        svg_bar_chart.append("g")
            .attr("transform", "translate(0," + h + ")")
            .attr("class", "axisWhite")
            .call(d3.axisBottom(x))
            .selectAll("text")
            .attr("transform", "translate(-10,0)rotate(-45)")
            .style("font-size", 10)
            .style("text-anchor", "end");

        svg_bar_chart.append("g")
            .style("font-size", 10)
            .attr("class", "axisWhite")
            .call(d3.axisLeft(y));

        // text label for the y axis
        svg_bar_chart.append("text")
            .style("font-size", 16)
            .attr("transform", "rotate(-90)")
            .attr("y", 0 - m[0])
            .attr("x", 0 - (h / 2))
            .attr("dy", "1em")
            .style("text-anchor", "middle")
            .style("fill", "white")
            .text("Value");

        //COPY OF BAR CHART

        var copy_svg_bar_chart = d3.select("#copy_barplot")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (290))
            .attr("width", "100%")
            .append("svg:g")
            .attr("transform", "translate(" + (m[3] + 35) + "," + (m[0] - 50) + ")");

        y = d3.scaleLinear()
            .domain([0, Math.max.apply(Math, dataset1)])
            .range([h/3, 0]);

        copy_svg_bar_chart.selectAll()
            .data(dataset1)
            .enter()
            .append("rect")
            .attr("x", function (d, i) { return x(selected_stock_options_duplicate[i]); })
            .attr("y", function (d) { return y(d); })
            .attr("width", x.bandwidth())
            .attr("height", function (d) { return h/3 - y(d); })
            .attr("fill", "#A117F2")

        copy_svg_bar_chart.selectAll("text.bar")
            .data(dataset1)
            .enter().append("text")
            .style("fill", "white")
            .attr("class", "bar")
            .attr("text-anchor", "middle")
            .attr("x", function (d, i) { return x(selected_stock_options_duplicate[i]) + x.bandwidth() / 2; })
            .attr("y", function (d) { return y(d) - 5; })
            .text(function (d) { return d; });

        copy_svg_bar_chart.append("g")
            .attr("transform", "translate(0," + h/3 + ")")
            .attr("class", "axisWhite")
            .call(d3.axisBottom(x))
            .selectAll("text")
            .attr("transform", "translate(-10,0)rotate(-45)")
            .style("font-size", 10)
            .style("text-anchor", "end");

        copy_svg_bar_chart.append("g")
            .style("font-size", 10)
            .attr("class", "axisWhite")
            .call(d3.axisLeft(y));

        copy_svg_bar_chart.append("text")
            .style("font-size", 12)
            .attr("transform", "rotate(-90)")
            .attr("y", 0 - m[0])
            .attr("x", 0 - (h / 6))
            .attr("dy", "1em")
            .style("text-anchor", "middle")
            .style("fill", "white")
            .text("Value");
    }
}

//so that when user opens acct, all of their saved data will show
//everything in this function only happens once (whenever the page is reloaded/opened upon login)
function reset_stock_list() {
    var ul = document.getElementById("stock_option");
    //var li = ul.getElementsByTagName("li");
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
        console.log(div.firstChild.textContent);
        div.removeChild(div.firstChild);
    }

    //appends appropriate number of input text fields so that user can enter money in each stock
    document.getElementById("money_in_stocks").innerText += "Enter the amount of money you have in each investment: \n\n"
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
            //my changes
            var newlabel = document.createElement("Label");
            newlabel.setAttribute("for", input.id);
            newlabel.style['font-size'] = "16px";
            input.style['font-size'] = "16px";

            newlabel.classList.add("control-label");
            newlabel.innerHTML = selected_stock_options[i];
            //
            document.getElementById("money_in_stocks").appendChild(newlabel);
            document.getElementById("money_in_stocks").appendChild(input);
            document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
            document.getElementById("money_in_stocks").appendChild(document.createElement("br"));


        } else {
            input.style["height"] = "25px";
            //my changes
            var newlabel = document.createElement("Label");
            newlabel.setAttribute("for", input.id);
            newlabel.style['font-size'] = "15px";
            input.style['font-size'] = "15px";

            newlabel.classList.add("control-label");
            newlabel.innerHTML = selected_stock_options[i];
            //
            document.getElementById("money_in_stocks").appendChild(newlabel);
            document.getElementById("money_in_stocks").appendChild(input);
            document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
        }
    }
}

//creates list with possible stock options for user to choose from
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
        'Other', '---------- END OF LIST ---------'];


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

    stock_options.forEach(function (option) {

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
        } else if(option != '---------- END OF LIST ---------'){
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

    list.addEventListener('click', function (stock) { //this means user has clicked on a list element (stock option)

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

//shows stock optons selected by user
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

//updates number of text fields to take user input (money in each stock)
function confirmStocks() {
    var div = document.getElementById('money_in_stocks');
    while (div.firstChild) {
        console.log(div.firstChild.textContent);
        div.removeChild(div.firstChild);
    }
    document.getElementById("money_in_stocks").innerText += "Enter the amount of money you have in each investment: \n\n"

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
            input.style["border-bottom"] = "2px solid #ff8fcf";
            input.style["-webkit-box-shadow"] = "none";
            input.style["box-shadow"] = "none";
            input.style["border-radius"] = "0";
            input.style["height"] = "25px";
            input.style['font-size'] = "16px";
            input.style['text-align'] = "center";

            input.readOnly = true;
            //my changes
            var newlabel = document.createElement("Label");
            newlabel.setAttribute("for", input.id);
            newlabel.style['font-size'] = "16px";

            newlabel.classList.add("control-label");
            newlabel.innerHTML = selected_stock_options[i];
            //
            document.getElementById("money_in_stocks").appendChild(newlabel);
            document.getElementById("money_in_stocks").appendChild(input);
            document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
            document.getElementById("money_in_stocks").appendChild(document.createElement("br"));


        } else {
            input.style["height"] = "25px";
            //my changes
            var newlabel = document.createElement("Label");
            newlabel.setAttribute("for", input.id);
            newlabel.style['font-size'] = "15px";
            input.style['font-size'] = "15px";

            newlabel.classList.add("control-label");
            newlabel.innerHTML = selected_stock_options[i];
            //
            document.getElementById("money_in_stocks").appendChild(newlabel);
            document.getElementById("money_in_stocks").appendChild(input);
            document.getElementById("money_in_stocks").appendChild(document.createElement("br"));
        }

    }
}

//called when user confirms the stocks + values (when they click on btn)
//necessary so that we can save the data in our db
function confirmStockValues() {
    var domestic_large_value = 0;
    var domestic_small_value = 0;
    var int_large_value = 0;
    var int_small_value = 0;
    var bonds_value = 0;

    networth = parseInt(savings);
    for (var i = 0; i < selected_stock_options.length; i++) {
        console.log(document.getElementById(selected_stock_options[i]).value);

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
    document.Form.stocks_list.value = stock;

    var stock_values = stock_selection_values.join();
    document.Form.stocks_values_list.value = stock_values;

    if (date_networth_arr == null) { //first time entering data
        document.Form.dates_networth_over_time.value = date;
        document.Form.networth_over_time_stocks.value = parseInt(networth);
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
        document.Form.dates_networth_over_time.value = dates_over_time;
        document.Form.networth_over_time_stocks.value = networth_over_time;
    }
}

//to display correct stock options when user types into searchbar
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

//called first - creates list elements with possible stock options
showAllStockOptions();

//called second - updates list with user's chosen stocks (if they have chosen, else this step is skipped)
if (existing_stocks != null && existing_stock_values != null) {
    reset_stock_list();
}

//updates stock value input textfields with previous values user entered (whatever was saved in db)
var sum_stock_values = 0;
function updateStockValuesText() {

    var temp_savings_req_arr = savings_req_arr.split(",");

    if (existing_stock_values != null) {
        temp_existing_stock_values = existing_stock_values.split(",");
        for (var i = 0; i < temp_existing_stock_values.length; ++i) {
            sum_stock_values += parseInt(temp_existing_stock_values[i]);
        }

        if (sum_stock_values == 0) {
            document.getElementById("your_us_small").innerHTML = 0 + '%';
            document.getElementById("your_us_large").innerHTML = 0 + '%';
            document.getElementById("your_itl_small").innerHTML = 0 + '%';
            document.getElementById("your_itl_large").innerHTML = 0 + '%';
            document.getElementById("your_bonds").innerHTML = 0 + '%';
            document.getElementById("your_bonds").innerHTML = 0 + '%';

            document.getElementById("diff_us_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(dom_small_percent).toFixed(2) + '%';
            document.getElementById("diff_us_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(dom_large_percent).toFixed(2) + '%';
            document.getElementById("diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(int_small_percent).toFixed(2) + '%';
            document.getElementById("diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(int_large_percent).toFixed(2) + '%';
            document.getElementById("diff_bonds").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(bonds_percent).toFixed(2) + '%';

            document.getElementById("copy_your_us_small").innerHTML = 0 + '%';
            document.getElementById("copy_your_us_large").innerHTML = 0 + '%';
            document.getElementById("copy_your_itl_small").innerHTML = 0 + '%';
            document.getElementById("copy_your_itl_large").innerHTML = 0 + '%';
            document.getElementById("copy_your_bonds").innerHTML = 0 + '%';
            document.getElementById("copy_your_other").innerHTML = 0 + '%';

            document.getElementById("copy_diff_us_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(dom_small_percent).toFixed(2) + '%';
            document.getElementById("copy_diff_us_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(dom_large_percent).toFixed(2) + '%';
            document.getElementById("copy_diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(int_small_percent).toFixed(2) + '%';
            document.getElementById("copy_diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(int_large_percent).toFixed(2) + '%';
            document.getElementById("copy_diff_bonds").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(bonds_percent).toFixed(2) + '%';

        } else {
            var us_small_cap_percent = 0;
            var us_large_cap_percent = 0;
            var int_small_cap_percent = 0;
            var int_large_cap_percent = 0;
            var bonds_percent_temp = 0;

            var us_small_only401K = 0;
            var us_large_only401K = 0;
            var int_small_only401K = 0;
            var int_large_only401K = 0;
            var bonds_only401K = 0;

            var us_small_etf = 0;
            var us_large_etf = 0;
            var int_small_etf = 0;
            var int_large_etf = 0;
            var bonds_etf = 0;

            var innerTextRecOther = "";
            var innerTextRecDomSmall = "";
            var innerTextRecDomLarge = "";
            var innerTextRecIntSmall = "";
            var innerTextRecIntLarge = "";
            var innerTextRecBonds = "";
            var innerTextRecExcess = "";
            var innerTextRecDeficit = "";

            for (var i = 0; i < selected_stock_options.length; ++i) {
                if (selected_stock_options[i] == 'Domestic Large Cap' || selected_stock_options[i] == 'Domestic Small Cap' || selected_stock_options[i] == 'International Large Cap' || selected_stock_options[i] == 'International Small Cap' || selected_stock_options[i] == 'Bonds') {
                    sum_stock_values -= parseFloat(existing_stock_values.split(",")[i]);
                }
            }

            for (var i = 0; i < selected_stock_options.length; ++i) {
                if (document.getElementById(selected_stock_options[i]) !== null) {
                    document.getElementById(selected_stock_options[i]).value = existing_stock_values.split(",")[i];

                    if (selected_stock_options[i] === 'Other') {

                        innerTextRecOther = "We recommend shifting your investments to the stocks detailed in the provided list so that you can follow the outlined portfolio. ";


                        var your_percent = (100 * (parseFloat(existing_stock_values.split(",")[i]) / parseFloat(sum_stock_values))).toFixed(2);
                        document.getElementById("copy_your_other").innerHTML = your_percent + '%';
                        document.getElementById("copy_diff_other").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(0 - your_percent).toFixed(2) + '%';
                        document.getElementById("copy_diff_other_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((your_percent) * sum_stock_values) / 100).toFixed(2);

                        document.getElementById("your_other").innerHTML = your_percent + '%';
                        document.getElementById("diff_other").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + Math.abs(0 - your_percent).toFixed(2) + '%';
                        document.getElementById("diff_other_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((your_percent) * sum_stock_values) / 100).toFixed(2);

                    } else if (selected_stock_options[i] === "Extended Market Index Fund" || selected_stock_options[i] === "Small Cap Index Fund" || selected_stock_options[i] === "VXF" || selected_stock_options[i] === "VB") {
                        us_small_cap_percent += parseFloat(existing_stock_values.split(",")[i]);
                        var your_percent = (100 * (us_small_cap_percent / parseFloat(sum_stock_values))).toFixed(2);
                        document.getElementById("your_us_small").innerHTML = your_percent + '%';
                        document.getElementById("copy_your_us_small").innerHTML = your_percent + '%';

                        if (parseFloat(your_percent) < parseFloat(dom_small_percent)) {

                            document.getElementById("diff_us_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("copy_diff_us_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("diff_us_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((dom_small_percent - your_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_us_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((dom_small_percent - your_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecDomSmall = ("You currently have " + "$" + (parseFloat((dom_small_percent - your_percent) * sum_stock_values) / 100).toFixed(2) + " less money in your domestic small cap than what is recommended by your portfolio.");
                            innerTextRecDeficit += (selected_stock_options[i] + ", ")

                        } else if (parseFloat(your_percent) > parseFloat(dom_small_percent)) {
                            document.getElementById("diff_us_small").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("copy_diff_us_small").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("diff_us_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - dom_small_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_us_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - dom_small_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecDomSmall = ("You currently have " + "$" + (parseFloat((your_percent - dom_small_percent) * sum_stock_values) / 100).toFixed(2) + " more money in your domestic small cap than what is recommended by your portfolio.");
                            innerTextRecExcess += (selected_stock_options[i] + ", ")

                        } else {
                            document.getElementById("diff_us_small").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("copy_diff_us_small").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("diff_us_small_money").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - dom_small_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_us_small_money").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - dom_small_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecDomSmall = ("You currently have " + "$" + (parseFloat((dom_small_percent - your_percent) * sum_stock_values) / 100).toFixed(2) + " which matches what is recommended for domestic small cap by your portfolio.");


                        }
                        document.getElementById("diff_us_small").innerHTML += Math.abs(dom_small_percent - your_percent).toFixed(2) + '%';
                        document.getElementById("copy_diff_us_small").innerHTML += Math.abs(dom_small_percent - your_percent).toFixed(2) + '%';

                        if (selected_stock_options[i] === "Extended Market Index Fund" || selected_stock_options[i] === "Small Cap Index Fund") {
                            us_small_only401K += parseFloat(existing_stock_values.split(",")[i]);

                            var your_percent = (100 * (us_small_only401K / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_401k_percent_dom1 = parseInt(your_percent);
                            console.log("DOM 401K " + segmented_401k_percent_dom1 + " " + your_percent);

                            document.getElementById("us_small_401K_percent").innerHTML = your_percent + '%';
                            document.getElementById("us_small_401K_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        } else {
                            us_small_etf += parseFloat(existing_stock_values.split(",")[i]);
                            var your_percent = (100 * (us_small_etf / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_brok_percent_dom1 = parseInt(your_percent);
                            console.log("DOM BROK " + segmented_brok_percent_dom1 + " " + your_percent);

                            document.getElementById("us_small_etf_percent").innerHTML = your_percent + '%';
                            document.getElementById("us_small_etf_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        }

                    } else if (selected_stock_options[i] === "S and P 500 Index Fund" || selected_stock_options[i] === "Large Cap Index Fund" || selected_stock_options[i] === "Total Stock Market Index Fund" || selected_stock_options[i] === "VTI" || selected_stock_options[i] === "VOO" || selected_stock_options[i] === "VV") {
                        us_large_cap_percent += parseFloat(existing_stock_values.split(",")[i]);
                        var your_percent = (100 * (us_large_cap_percent / parseFloat(sum_stock_values))).toFixed(2);
                        document.getElementById("your_us_large").innerHTML = your_percent + '%';
                        document.getElementById("copy_your_us_large").innerHTML = your_percent + '%';

                        if (parseFloat(your_percent) < parseFloat(dom_large_percent)) {
                            document.getElementById("diff_us_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("copy_diff_us_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("diff_us_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((dom_large_percent - your_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_us_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((dom_large_percent - your_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecDomLarge = ("You currently have " + "$" + (parseFloat((dom_large_percent - your_percent) * sum_stock_values) / 100).toFixed(2) + " less money in your domestic large cap than what is recommended by your portfolio.");
                            innerTextRecDeficit += (selected_stock_options[i] + ", ")

                        } else if (parseFloat(your_percent) > parseFloat(dom_large_percent)) {
                            document.getElementById("diff_us_large").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("copy_diff_us_large").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("diff_us_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - dom_large_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_us_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - dom_large_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecDomLarge = ("You currently have " + "$" + (parseFloat((your_percent - dom_large_percent) * sum_stock_values) / 100).toFixed(2) + " more money in your domestic large cap than what is recommended by your portfolio.");
                            innerTextRecExcess += (selected_stock_options[i] + ", ")

                        } else {
                            document.getElementById("diff_us_large").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("copy_diff_us_large").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("diff_us_large_money").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - dom_large_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_us_large_money").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - dom_large_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecDomLarge = ("You currently have " + "$" + (parseFloat((your_percent - dom_large_percent) * sum_stock_values) / 100).toFixed(2) + " which matches what is recommended for domestic large cap by your portfolio.");

                        }
                        document.getElementById("diff_us_large").innerHTML += Math.abs(dom_large_percent - your_percent).toFixed(2) + '%';
                        document.getElementById("copy_diff_us_large").innerHTML += Math.abs(dom_large_percent - your_percent).toFixed(2) + '%';

                        if (selected_stock_options[i] === "S and P 500 Index Fund" || selected_stock_options[i] === "Large Cap Index Fund" || selected_stock_options[i] === "Total Stock Market Index Fund") {
                            us_large_only401K += parseFloat(existing_stock_values.split(",")[i]);

                            var your_percent = (100 * (us_large_only401K / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_401k_percent_dom2 = parseInt(your_percent);
                            console.log("DOM 401K " + segmented_401k_percent_dom2 + " " + your_percent);

                            document.getElementById("us_large_401K_percent").innerHTML = your_percent + '%';
                            document.getElementById("us_large_401K_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        } else {
                            us_large_etf += parseFloat(existing_stock_values.split(",")[i]);
                            var your_percent = (100 * (us_large_etf / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_brok_percent_dom2 = parseInt(your_percent);
                            console.log("DOM BROK " + segmented_brok_percent_dom2 + " " + your_percent);

                            document.getElementById("us_large_etf_percent").innerHTML = your_percent + '%';
                            document.getElementById("us_large_etf_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        }

                    } else if (selected_stock_options[i] === "All-World ex-US Small Cap Index Fund" || selected_stock_options[i] === "VSS") {
                        int_small_cap_percent += parseFloat(existing_stock_values.split(",")[i]);
                        var your_percent = (100 * (int_small_cap_percent / parseFloat(sum_stock_values))).toFixed(2);
                        document.getElementById("your_itl_small").innerHTML = your_percent + '%';
                        document.getElementById("copy_your_itl_small").innerHTML = your_percent + '%';

                        console.log(your_percent + " " + int_small_percent);

                        if (parseFloat(your_percent) < parseFloat(int_small_percent)) {
                            document.getElementById("diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("copy_diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("diff_itl_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((int_small_percent - your_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_itl_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((int_small_percent - your_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecIntSmall = ("You currently have " + "$" + (parseFloat((int_small_percent - your_percent) * sum_stock_values) / 100).toFixed(2) + " less money in your international small cap than what is recommended by your portfolio.");
                            innerTextRecDeficit += (selected_stock_options[i] + ", ")

                        } else if (parseFloat(your_percent) > parseFloat(int_small_percent)) {
                            document.getElementById("diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("copy_diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("diff_itl_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - int_small_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_itl_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - int_small_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecIntSmall = ("You currently have " + "$" + (parseFloat((your_percent - int_small_percent) * sum_stock_values) / 100).toFixed(2) + " more money in your international small cap than what is recommended by your portfolio.");
                            innerTextRecExcess += (selected_stock_options[i] + ", ")

                        } else {
                            document.getElementById("diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("copy_diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("diff_itl_small_money").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - int_small_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_itl_small_money").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - int_small_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecIntSmall = ("You currently have " + "$" + (parseFloat((your_percent - int_small_percent) * sum_stock_values) / 100).toFixed(2) + " which matches what is recommended for international small cap by your portfolio.");

                        }
                        document.getElementById("diff_itl_small").innerHTML += Math.abs(int_small_percent - your_percent).toFixed(2) + '%';
                        document.getElementById("copy_diff_itl_small").innerHTML += Math.abs(int_small_percent - your_percent).toFixed(2) + '%';

                        if (selected_stock_options[i] === "All-World ex-US Small Cap Index Fund") {
                            int_small_only401K += parseFloat(existing_stock_values.split(",")[i]);
                            var your_percent = (100 * (int_small_only401K / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_401k_percent_int1 = parseInt(your_percent);
                            console.log("INT 401K " + segmented_401k_percent_int1 + " " + your_percent);

                            document.getElementById("int_small_401K_percent").innerHTML = your_percent + '%';
                            document.getElementById("int_small_401K_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        } else {
                            int_small_etf += parseFloat(existing_stock_values.split(",")[i]);
                            var your_percent = (100 * (int_small_etf / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_brok_percent_int1 = parseInt(your_percent);
                            console.log("INT BROK " + segmented_brok_percent_int1 + " " + your_percent);

                            document.getElementById("int_small_etf_percent").innerHTML = your_percent + '%';
                            document.getElementById("int_small_etf_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        }

                    } else if (selected_stock_options[i] === "All-World ex-US Index Fund" || selected_stock_options[i] === "Total International Stock Index Fund" || selected_stock_options[i] === "VEU" || selected_stock_options[i] === "VXUS" || selected_stock_options[i] === "IXUS") {
                        int_large_cap_percent += parseFloat(existing_stock_values.split(",")[i]);
                        var your_percent = (100 * (int_large_cap_percent / parseFloat(sum_stock_values))).toFixed(2);
                        document.getElementById("your_itl_large").innerHTML = your_percent + '%';
                        document.getElementById("copy_your_itl_large").innerHTML = your_percent + '%';

                        if (parseFloat(your_percent) < parseFloat(int_large_percent)) {
                            document.getElementById("diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("copy_diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("diff_itl_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((int_large_percent - your_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_itl_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((int_large_percent - your_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecIntLarge = ("You currently have " + "$" + (parseFloat((int_large_percent - your_percent) * sum_stock_values) / 100).toFixed(2) + " less money than what is recommended for international large cap by your portfolio.");
                            innerTextRecDeficit += (selected_stock_options[i] + ", ")

                        } else if (parseFloat(your_percent) > parseFloat(int_large_percent)) {
                            document.getElementById("diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("copy_diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("diff_itl_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - int_large_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_itl_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - int_large_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecIntLarge = ("You currently have " + "$" + (parseFloat((your_percent - int_large_percent) * sum_stock_values) / 100).toFixed(2) + " more money than what is recommended for international large cap by your portfolio.");
                            innerTextRecExcess += (selected_stock_options[i] + ", ")

                        } else {
                            document.getElementById("diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("copy_diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("diff_itl_large_money").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - int_large_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_itl_large_money").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - int_large_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecIntLarge = ("You currently have " + "$" + (parseFloat((your_percent - int_large_percent) * sum_stock_values) / 100).toFixed(2) + " which matches what is recommended for international large cap by your portfolio.");


                        }
                        document.getElementById("diff_itl_large").innerHTML += Math.abs(int_large_percent - your_percent).toFixed(2) + '%';
                        document.getElementById("copy_diff_itl_large").innerHTML += Math.abs(int_large_percent - your_percent).toFixed(2) + '%';

                        if (selected_stock_options[i] === "All-World ex-US Index Fund" || selected_stock_options[i] === "Total International Stock Index Fund") {
                            int_large_only401K += parseFloat(existing_stock_values.split(",")[i]);

                            var your_percent = (100 * (int_large_only401K / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_401k_percent_int2 = parseInt(your_percent);
                            console.log("401K INT " + segmented_401k_percent_int2 + " " + your_percent)

                            document.getElementById("int_large_401K_percent").innerHTML = your_percent + '%';
                            document.getElementById("int_large_401K_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        } else {
                            int_large_etf += parseFloat(existing_stock_values.split(",")[i]);

                            var your_percent = (100 * (int_large_etf / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_brok_percent_int2 = parseInt(your_percent);
                            console.log("BROK INT " + segmented_brok_percent_int2 + " " + your_percent)

                            document.getElementById("int_large_etf_percent").innerHTML = your_percent + '%';
                            document.getElementById("int_large_etf_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        }

                    } else if (selected_stock_options[i] === "Intermediate Term Treasury Bond Index" || selected_stock_options[i] === "Total Bond Market Index" || selected_stock_options[i] === "BND" || selected_stock_options[i] === "BIV") {
                        bonds_percent_temp += parseFloat(existing_stock_values.split(",")[i]);
                        var your_percent = (100 * (bonds_percent_temp / parseFloat(sum_stock_values))).toFixed(2);
                        document.getElementById("your_bonds").innerHTML = your_percent + '%';
                        document.getElementById("copy_your_bonds").innerHTML = your_percent + '%';

                        if (parseFloat(your_percent) < parseFloat(bonds_percent)) {
                            document.getElementById("diff_bonds").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("copy_diff_bonds").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>";
                            document.getElementById("diff_bonds_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((bonds_percent - your_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_bonds_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat((bonds_percent - your_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecBonds = ("You currently have " + "$" + (parseFloat((bonds_percent - your_percent) * sum_stock_values) / 100).toFixed(2) + " less money than what is recommended for bonds by your portfolio.");
                            innerTextRecDeficit += (selected_stock_options[i] + ", ")

                        } else if (parseFloat(your_percent) > parseFloat(bonds_percent)) {
                            document.getElementById("diff_bonds").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("copy_diff_bonds").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>";
                            document.getElementById("diff_bonds_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - bonds_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_bonds_money").innerHTML = "<span class='glyphicon glyphicon-chevron-up' style='color:green'></span>" + "$" + (parseFloat((your_percent - bonds_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecBonds = ("You currently have " + "$" + (parseFloat((your_percent - bonds_percent) * sum_stock_values) / 100).toFixed(2) + " more money than what is recommended for bonds by your portfolio.");
                            innerTextRecExcess += (selected_stock_options[i] + ", ")

                        } else {
                            document.getElementById("diff_bonds").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("copy_diff_bonds").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>";
                            document.getElementById("diff_bonds_money").innerHTML = "<span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - bonds_percent) * sum_stock_values) / 100).toFixed(2);
                            document.getElementById("copy_diff_bonds_money").innerHTML = "span class='glyphicon glyphicon-menu-hamburger' style='color:#D3D3D3'></span>" + "$" + (parseFloat((your_percent - bonds_percent) * sum_stock_values) / 100).toFixed(2);

                            innerTextRecBonds = ("You currently have " + "$" + (parseFloat((your_percent - bonds_percent) * sum_stock_values) / 100).toFixed(2) + " which matches what is recommended for bonds by your portfolio.");


                        }
                        document.getElementById("diff_bonds").innerHTML += Math.abs(bonds_percent - your_percent).toFixed(2) + '%';
                        document.getElementById("copy_diff_bonds").innerHTML += Math.abs(bonds_percent - your_percent).toFixed(2) + '%';

                        if (selected_stock_options[i] === "Intermediate Term Treasury Bond Index" || selected_stock_options[i] === "Total Bond Market Index") {
                            bonds_only401K += parseFloat(existing_stock_values.split(",")[i]);

                            var your_percent = (100 * (bonds_only401K / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_401k_percent_bonds = parseInt(your_percent);
                            console.log("BONDS 401K " + segmented_401k_percent_bonds + " " + your_percent)

                            document.getElementById("bonds_401K_percent").innerHTML = your_percent + '%';
                            document.getElementById("bonds_401K_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        } else {
                            bonds_etf += parseFloat(existing_stock_values.split(",")[i]);

                            var your_percent = (100 * (bonds_etf / parseFloat(sum_stock_values))).toFixed(2);

                            segmented_brok_percent_bonds = parseInt(your_percent);
                            console.log("BONDS BROK " + segmented_brok_percent_bonds + " " + your_percent)

                            document.getElementById("bonds_etf_percent").innerHTML = your_percent + '%';
                            document.getElementById("bonds_etf_money").innerHTML = "$" + (parseFloat(your_percent * sum_stock_values) / 100).toFixed(2);
                        }
                    }
                }
            }

            document.getElementById("recChangesInvestments").innerText = innerTextRecDomLarge + " " + innerTextRecDomSmall + " " +  innerTextRecIntLarge + " " +  innerTextRecIntSmall + " " +  innerTextRecBonds + " " +  innerTextRecOther + " ";
            document.getElementById("recChangesInvestments").innerText +=  " You can consider selling the following investments that you have excess money in (" + innerTextRecExcess + ") and consider investing more in (" + innerTextRecDeficit + "). ";
            document.getElementById("recChangesInvestments").innerText +=  " Don’t worry about meeting these changes right away. Over time, we’ll  continue working towards meeting your planned portfolio.";

        }
    }
}

function checkOverviewTable() {
    if (document.getElementById("us_small_401K_percent").innerHTML === "") {
        document.getElementById("us_small_401K_percent").innerHTML = "0%";
        document.getElementById("us_small_401K_money").innerHTML = "$0";
    }
    if (document.getElementById("us_small_etf_percent").innerHTML === "") {
        document.getElementById("us_small_etf_percent").innerHTML = "0%";
        document.getElementById("us_small_etf_money").innerHTML = "$0";
    }
    if (document.getElementById("us_large_401K_percent").innerHTML === "") {
        document.getElementById("us_large_401K_percent").innerHTML = "0%";
        document.getElementById("us_large_401K_money").innerHTML = "$0";
    }
    if (document.getElementById("us_large_etf_percent").innerHTML === "") {
        document.getElementById("us_large_etf_percent").innerHTML = "0%";
        document.getElementById("us_large_etf_money").innerHTML = "$0";
    }
    if (document.getElementById("int_small_401K_percent").innerHTML === "") {
        document.getElementById("int_small_401K_percent").innerHTML = "0%";
        document.getElementById("int_small_401K_money").innerHTML = "$0";
    }
    if (document.getElementById("int_small_etf_percent").innerHTML === "") {
        document.getElementById("int_small_etf_percent").innerHTML = "0%";
        document.getElementById("int_small_etf_money").innerHTML = "$0";
    }
    if (document.getElementById("int_large_401K_percent").innerHTML === "") {
        document.getElementById("int_large_401K_percent").innerHTML = "0%";
        document.getElementById("int_large_401K_money").innerHTML = "$0";
    }
    if (document.getElementById("int_large_etf_percent").innerHTML === "") {
        document.getElementById("int_large_etf_percent").innerHTML = "0%";
        document.getElementById("int_large_etf_money").innerHTML = "$0";
    }
    if (document.getElementById("bonds_401K_percent").innerHTML === "") {
        document.getElementById("bonds_401K_percent").innerHTML = "0%";
        document.getElementById("bonds_401K_money").innerHTML = "$0";
    }
    if (document.getElementById("bonds_etf_percent").innerHTML === "") {
        document.getElementById("bonds_etf_percent").innerHTML = "0%";
        document.getElementById("bonds_etf_money").innerHTML = "$0";
    }
    if (document.getElementById("copy_your_other").innerHTML === "") {
        document.getElementById("copy_your_other").innerHTML = "0%";
        document.getElementById("copy_diff_other").innerHTML = "0%";
        document.getElementById("your_other").innerHTML = "0%";
        document.getElementById("diff_other").innerHTML = "0%";
        document.getElementById("copy_diff_other_money").innerHTML = "$0";
        document.getElementById("diff_other_money").innerHTML = "$0";
    }
    if (document.getElementById("copy_your_us_small").innerHTML === "") {
        document.getElementById("copy_your_us_small").innerHTML = "0%";
        document.getElementById("your_us_small").innerHTML = "0%";
    }
    if (document.getElementById("copy_your_us_large").innerHTML === "") {
        document.getElementById("copy_your_us_large").innerHTML = "0%";
        document.getElementById("your_us_large").innerHTML = "0%";
    }
    if (document.getElementById("copy_your_itl_small").innerHTML === "") {
        document.getElementById("copy_your_itl_small").innerHTML = "0%";
        document.getElementById("your_itl_small").innerHTML = "0%";
    }
    if (document.getElementById("copy_your_itl_large").innerHTML === "") {
        document.getElementById("copy_your_itl_large").innerHTML = "0%";
        document.getElementById("your_itl_large").innerHTML = "0%";
    }
    if (document.getElementById("copy_your_bonds").innerHTML === "") {
        document.getElementById("copy_your_bonds").innerHTML = "0%";
        document.getElementById("your_bonds").innerHTML = "0%";
    }
    if (document.getElementById("copy_diff_us_small").innerHTML === "") {
        document.getElementById("copy_diff_us_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + dom_small_percent + "%";
        document.getElementById("copy_diff_us_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(dom_small_percent * sum_stock_values) / 100).toFixed(2);
        document.getElementById("diff_us_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + dom_small_percent + "%";
        document.getElementById("diff_us_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(dom_small_percent * sum_stock_values) / 100).toFixed(2);

    }
    if (document.getElementById("copy_diff_us_large").innerHTML === "") {
        document.getElementById("copy_diff_us_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + dom_large_percent + "%";
        document.getElementById("copy_diff_us_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(dom_large_percent * sum_stock_values) / 100).toFixed(2);
        document.getElementById("diff_us_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + dom_large_percent + "%";
        document.getElementById("diff_us_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(dom_large_percent * sum_stock_values) / 100).toFixed(2);

    }
    if (document.getElementById("copy_diff_itl_small").innerHTML === "") {
        document.getElementById("copy_diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + int_small_percent + "%";
        document.getElementById("copy_diff_itl_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(int_small_percent * sum_stock_values) / 100).toFixed(2);
        document.getElementById("diff_itl_small").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + int_small_percent + "%";
        document.getElementById("diff_itl_small_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(int_small_percent * sum_stock_values) / 100).toFixed(2);

    }
    if (document.getElementById("copy_diff_itl_large").innerHTML === "") {
        document.getElementById("copy_diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + int_large_percent + "%";
        document.getElementById("copy_diff_itl_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(int_large_percent * sum_stock_values) / 100).toFixed(2);
        document.getElementById("diff_itl_large").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + int_large_percent + "%";
        document.getElementById("diff_itl_large_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(int_large_percent * sum_stock_values) / 100).toFixed(2);

    }
    if (document.getElementById("copy_diff_bonds").innerHTML === "") {
        document.getElementById("copy_diff_bonds").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + bonds_percent + "%";
        document.getElementById("copy_diff_bonds_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(bonds_percent * sum_stock_values) / 100).toFixed(2);
        document.getElementById("diff_bonds").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + bonds_percent + "%";
        document.getElementById("diff_bonds_money").innerHTML = "<span class='glyphicon glyphicon-chevron-down' style='color:red'></span>" + "$" + (parseFloat(bonds_percent * sum_stock_values) / 100).toFixed(2);

    }
}

document.getElementById("recChangesInvestments").innerText = "";
updateStockValuesText();
checkOverviewTable();

//function for graphs
function result() {

    //var currentWidth = parseInt(d3.select('#graph').style('width'), 10)
    /*if (currentWidth > 2000) {
        currentWidth = 2000;
    }*/
    var currentWidth = 1500;

    money = document.getElementById('money').value;
    income = document.getElementById('income').value;
    savings = document.getElementById('savings').value;
    r1 = 0.07;
    bad_r1 = 0.06;
    r2 = 0.04;
    inf_rate = 0.03; //inflation rate
    years = life - curr_age; //years left in life
    accum_years = ret_age - curr_age; //accumulation years
    distr_years = life - ret_age; //distribution years

    netWorth();
    console.log("networth " + networth);
    console.log(networth_time_arr);

    d3.selectAll("svg").remove();
    bargraph(); // barplot showing user's stocks
    portfolio_chart(); //piechart showing user's portfolio

    savings_req_over_time(); //graph showing savings req over time
    networth_over_time_graph(); //graph showing networth over time
    inside401Kversusoutside();

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
    savings_per_year = gap / ((1 / parseFloat(r1)) - (1 / (parseFloat(r1) * Math.pow(1 + parseFloat(r1), accum_years))))

    bad_savings_per_year = goal / Math.pow((1 + parseFloat(bad_r1)), accum_years);
    var bad_gap = bad_savings_per_year - savings;
    bad_savings_per_year = bad_gap / ((1 / parseFloat(bad_r1)) - (1 / (parseFloat(bad_r1) * Math.pow(1 + parseFloat(bad_r1), accum_years))));

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

    var m = [50, 150, 100, 150]; // margins, m[0], m[2] = top/below, m[1] = right, m[3] = left
    var w = currentWidth * 0.7; // width
    var h = 400 - m[0] - m[2]; // height

    //graph for inflation adjusted M
    var graph = d3.select("#graph")
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2]))
        //.classed("svg-content", true)
        //.attr("width", w + m[1] + m[3])
        //.attr("height", h + m[0] + m[2])
        .attr("width", "100%")
        // .attr("height", "100%")
        .append("svg:g")
        .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

    var x = d3.scaleLinear().domain([1, money_over_time.length]).range([0, w]);
    var y = d3.scaleLinear().domain([0, Math.max.apply(Math, money_over_time)]).range([h, 0]);
    //d3.max(money_over_time)

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

    // text label for the y axis
    graph.append("text")
        .style("fill", "white")
        .style("font-size", 20)
        .attr("transform", "rotate(-90)")
        .attr("y", 0 - m[1] + 30)
        .attr("x", 0 - (h / 2))
        .attr("dy", "1em")
        .style("text-anchor", "middle")
        .text("M");

    var yAxisLeft = d3.axisLeft(y).ticks(4)

    graph.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(-25,0)")
        .style("font-size", 14)
        .call(yAxisLeft);

    /*graph
        .append("text")
        .attr("x", (w / 2))
        .attr("y", 0 - (m[0] / 2))
        .attr("text-anchor", "middle")
        .style("font-size", "16px")
        .style("text-decoration", "underline")
        .text("Inflation adjusted M vs Year")*/

    //shading for graph 1 (inflation adjusted M)
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
    //.style("opacity", .6)

    //highlight retirement year in graph 1 (inflation adjusted M)
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


    //COPY OF ^ GRAPH

    var copy_graph = d3.select("#copy_graph")
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2]))
        .attr("width", "100%")
        .append("svg:g")
        .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

    copy_graph.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + h + ")")
        .style("font-size", 16)
        .call(xAxis);

    copy_graph.append("text")
        .style("fill", "white")
        .style("font-size", 20)
        .attr("transform",
            "translate(" + (w / 2) + " ," +
            (h + m[0]) + ")")
        .style("text-anchor", "middle")
        .text("Year");

    copy_graph.append("text")
        .style("fill", "white")
        .style("font-size", 20)
        .attr("transform", "rotate(-90)")
        .attr("y", 0 - m[1] + 30)
        .attr("x", 0 - (h / 2))
        .attr("dy", "1em")
        .style("text-anchor", "middle")
        .text("M");

    copy_graph.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(-25,0)")
        .style("font-size", 14)
        .call(yAxisLeft);

    var copy_area = d3.area()
        .x(function (d, i) {
            return x(i + 1);
        })
        .y1(function (d) {
            return y(d);
        })
        .y0(y(0))

    //shading
    copy_graph
        .datum(money_over_time)
        .append("path")
        .attr("d", copy_area)
        .style("stroke-width", 2)
        .style("fill", "#00B2EE") //blue
        .style("stroke", "#00B2EE")

    copy_graph
        .append("circle")
        .attr("cx", x(accum_years + 1))
        .attr("cy", y(money_over_time[accum_years]))
        .attr("r", 5)
        .attr("fill", "red")

    copy_graph
        .append("text")
        .style("fill", "white")
        .attr("x", x(years))
        .attr("y", y(money_over_time[money_over_time.length - 1]))
        .attr("dx", ".71em")
        .attr("dy", ".35em")
        .style("font-size", 18)
        .text((years + curr_year) + " - $" + parseInt(money_over_time[money_over_time.length - 1]))

    //COPY OF ^ GRAPH


    //splitting graph 2 into accumulation and distr years
    var wealth1 = wealth.slice(0, accum_years + 1)
    var wealth2 = wealth.slice(accum_years, wealth.length)

    //graph for wealth per year
    var graph2 = d3.select("#graph2")
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2]))
        //.classed("svg-content", true)
        //.attr("width", w + m[1] + m[3])
        //.attr("height", h + m[0] + m[2])
        .attr("width", "100%")
        //.attr("height", "100%")
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
        .text("Wealth");

    var y2 = d3.scaleLinear().domain([0, Math.max.apply(Math, wealth)]).range([h, 0]);
    //d3.max(wealth)
    var yAxisLeft2 = d3.axisLeft(y2).ticks(4)

    graph2.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(-25,0)")
        .style("font-size", 14)
        .call(yAxisLeft2);

    /*graph2
        .append("text")
        .attr("x", (w / 2))
        .attr("y", 0 - (m[0] / 2))
        .attr("text-anchor", "middle")
        .style("font-size", "16px")
        .style("text-decoration", "underline")
        .text("Wealth vs Year")*/

    // area graphs - y1 sets top, y0 sets the lowest y value
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


    //COPY OF ^ GRAPH

    var copy_graph2 = d3.select("#copy_graph2")
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2]))
        .attr("width", "100%")
        .append("svg:g")
        .attr("transform", "translate(" + m[3] + "," + m[0] + ")");


    copy_graph2.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + h + ")")
        .style("font-size", 16)
        .call(xAxis);

    copy_graph2.append("text")
        .style("fill", "white")
        .style("font-size", 20)
        .attr("transform",
            "translate(" + (w / 2) + " ," +
            (h + m[0]) + ")")
        .style("text-anchor", "middle")
        .text("Year");

    copy_graph2.append("text")
        .style("fill", "white")
        .style("font-size", 20)
        .attr("transform", "rotate(-90)")
        .attr("y", 0 - m[1] + 20)
        .attr("x", 0 - (h / 2))
        .attr("dy", "1em")
        .style("text-anchor", "middle")
        .text("Wealth");

    copy_graph2.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(-25,0)")
        .style("font-size", 14)
        .call(yAxisLeft2);

    var copy_area2 = d3.area()
        .x(function (d, i) {
            return x(i + 1);
        })
        .y1(function (d) {
            return y2(d);
        })
        .y0(y2(0));

    var copy_areaRetire = d3.area()
        .x(function (d, i) {
            return x(i + 1 + accum_years);
        })
        .y1(function (d) {
            return y2(d);
        })
        .y0(y2(0));


    copy_graph2 //before retirement
        .datum(wealth1)
        .append("path")
        .attr("d", copy_area2)
        .style("stroke-width", 2)
        .style("fill", "#6EE7A2 ")//orange //#1DA237 6EE7A2 
        .style("stroke", "#6EE7A2 ")

    copy_graph2 // after retirement graph
        .datum(wealth2)
        .append("path")
        .attr("d", copy_areaRetire)
        .style("stroke-width", 2)
        .style("fill", '#f55870') //purple
        .style("stroke", '#f55870')

    copy_graph2
        .append("line")
        .attr("x1", x(accum_years + 1))
        .attr("y1", 0)
        .attr("x2", x(accum_years + 1))
        .attr("y2", h)
        .style("stroke-dasharray", ("4, 4"))
        .style("stroke", "red")

    copy_graph2
        .append("text")
        .style("fill", "white")
        .style("font-size", 18)
        .attr("x", x(accum_years + 1))
        .attr("y", y2(wealth2[0]))
        .attr("dx", ".71em")
        .attr("dy", ".35em")
        .text((accum_years + curr_year) + " - $" + parseInt(wealth2[0]))

    //COPY OF ^ GRAPH

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

    document.getElementById('table_ret_year').innerText = curr_year + accum_years;
    document.getElementById('table_distr_years').innerText = distr_years;
    document.getElementById('table_annual_income').innerText = "$" + income;
    document.getElementById('table_money_per_year').innerText = "$" + money;
    document.getElementById('table_r1').innerText = parseInt(r1 * 100) + "%";
    document.getElementById('table_r2').innerText = parseInt(r2 * 100) + "%";
    document.getElementById('table_savings_req').innerText = "$" + parseInt(savings_per_year);

    //document.getElementById('summary').innerText = text
}

//date over time
function updateOverTime() {
    console.log(date_arr);

    //logging networth over time data
    if (date_networth_arr == null) { //first time entering data
        document.Formnum1.dates_networth_over_time.value = date;
        netWorth();
        document.Formnum1.networth_over_time.value = parseInt(networth);
    } else {
        date_networth_arr = date_networth_arr.split(",");
        networth_time_arr = networth_time_arr.split(",");
        var most_recent = date_networth_arr[date_networth_arr.length - 1];

        if (most_recent == date) { //entering data again on same day
            var dates_over_time = date_networth_arr.join();
            netWorth();
            networth_time_arr[networth_time_arr.length - 1] = parseInt(networth);

        } else { //entering data on a diff day
            date_networth_arr.push(date);
            netWorth();
            networth_time_arr.push(parseInt(networth));
            var dates_over_time = date_networth_arr.join();
        }
        var networth_over_time = networth_time_arr.join();
        document.Formnum1.dates_networth_over_time.value = dates_over_time;
        document.Formnum1.networth_over_time.value = networth_over_time;
    }


    //logging savings req over time data
    if (date_arr == null) { //first time entering data
        document.Formnum1.dates_over_time.value = date;
        document.Formnum1.savings_req_over_time.value = parseInt(savings_per_year);
        document.Formnum1.savings_req_over_time_bad.value = parseInt(bad_savings_per_year);

        // netWorth();
        // document.Formnum1.networth_over_time.value = parseInt(networth);
    } else {
        date_arr = date_arr.split(",");
        savings_req_arr = savings_req_arr.split(",");
        savings_req_arr_bad = savings_req_arr_bad.split(",");
        //networth_time_arr = networth_time_arr.split(",");
        var most_recent = date_arr[date_arr.length - 1];

        if (most_recent == date) { //entering data again on same day
            var dates_over_time = date_arr.join();
            savings_req_arr[savings_req_arr.length - 1] = parseInt(savings_per_year);
            savings_req_arr_bad[savings_req_arr_bad.length - 1] = parseInt(bad_savings_per_year);
            //netWorth();
            //networth_time_arr[networth_time_arr.length - 1] = parseInt(networth);

        } else { //entering data on a diff day
            date_arr.push(date);
            savings_req_arr_bad.push(parseInt(bad_savings_per_year));
            savings_req_arr.push(parseInt(savings_per_year));
            //netWorth();
            //networth_time_arr.push(parseInt(networth));
            var dates_over_time = date_arr.join();
        }
        var savings_over_time = savings_req_arr.join();
        var savings_over_time_bad = savings_req_arr_bad.join();
        //var networth_over_time = networth_time_arr.join();
        document.Formnum1.dates_over_time.value = dates_over_time;
        document.Formnum1.savings_req_over_time.value = savings_over_time;
        document.Formnum1.savings_req_over_time_bad.value = savings_over_time_bad;
        //document.Formnum1.networth_over_time.value = networth_over_time;
    }
}

function networth_over_time_graph() {
    if (date_networth_arr == null || date_networth_arr.split(",").length == 1) {
        document.getElementById("trackData_description").innerText = "You don't have enough data to show right now."
        // console.log("no graph today");
    } else {
        //console.log("yep we r gonna graph this shit");
        data_date = date_networth_arr.split(",");
        data_networth = networth_time_arr.split(",");

        /*var currentWidth = parseInt(d3.select('#savings_req_time').style('width'), 10)
        if (currentWidth > 2000) {
            currentWidth = 2000;
        }*/
        var currentWidth = 1500;
        var m = [50, 150, 100, 150]; // margins, m[0], m[2] = top/below, m[1] = right, m[3] = left
        var w = currentWidth * 0.7; // width
        var h = 400 - m[0] - m[2]; // height

        //graph for networth over time
        var graph = d3.select("#networth_time")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2]))
            //.classed("svg-content", true)
            //.attr("width", w + m[1] + m[3])
            //.attr("height", h + m[0] + m[2] + 150)
            .attr("width", "100%")
            //.attr("height", "100%")
            .append("svg:g")
            .attr("transform", "translate(" + m[3] + "," + m[0] + ")");


        var x = d3.scaleTime().rangeRound([0, w]);
        var xAxis = d3.axisBottom(x).tickSize(-h).tickFormat(d3.timeFormat("%m-%d-%Y"));;
        var parseTime = d3.timeParse("%m/%d/%Y");
        x.domain(d3.extent(data_date, function (d, i) { console.log(data_date[i]); return parseTime(data_date[i]); }));

        var y = d3.scaleLinear().domain([0, Math.max.apply(Math, data_networth)]).range([h, 0]);

        graph.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + h + ")")
            .style("font-size", 12)
            .call(xAxis);

        graph.select('.x.axis')
            .selectAll("text")
            .style("text-anchor", "end")
            .attr("dx", "-.8em")
            .attr("dy", ".15em")
            .attr("transform", "rotate(-45)");

        graph.append("text")
            .style("fill", "white")
            .style("font-size", 16)
            .attr("transform",
                "translate(" + (w / 2) + " ," +
                (h + m[0] + 40) + ")")
            .style("text-anchor", "middle")
            .text("Date");

        // text label for the y axis
        graph.append("text")
            .style("fill", "white")
            .style("font-size", 16)
            .attr("transform", "rotate(-90)")
            .attr("y", 0 - m[1] + 30)
            .attr("x", 0 - (h / 2))
            .attr("dy", "1em")
            .style("text-anchor", "middle")
            .text("Networth");

        var yAxisLeft = d3.axisLeft(y).ticks(4)

        graph.append("g")
            .attr("class", "y axis")
            .attr("transform", "translate(-25,0)")
            .style("font-size", 12)
            .call(yAxisLeft);

        /*graph
            .append("text")
            .attr("x", (w / 2))
            .attr("y", 0 - (m[0] / 2))
            .attr("text-anchor", "middle")
            .style("font-size", "16px")
            .style("text-decoration", "underline")
            .text("Your networth over time")*/

        //shading
        var area = d3.area()
            .x(function (d, i) { return x(parseTime(data_date[i])); })
            .y1(function (d) {
                return y(d);
            })
            .y0(y(0))

        //shading
        graph
            .datum(data_networth)
            .append("path")
            .attr("d", area)
            .style("stroke-width", 2)
            .style("fill", "#FFAA01")//green
            .style("stroke", "#FFAA01")
        //.style("opacity", .8)

        graph.selectAll("myCircles")
            .data(data_networth)
            .enter().append("circle")
            .attr("fill", "#00ffa1")
            .attr("stroke", "none")
            .attr("cx", function (d, i) { return x(parseTime(data_date[i])); })
            .attr("cy", function (d) { return y(d); })
            .attr("r", 5);

        graph
            .append("text")
            .style("fill", "white")
            .attr("x", x(parseTime(data_date[data_date.length - 1])))
            .attr("y", y(data_networth[data_networth.length - 1]))
            .attr("dx", ".71em")
            .attr("dy", ".35em")
            .style("font-size", 14)
            .text(data_date[data_date.length - 1] + " - $" + data_networth[data_networth.length - 1])


        //COPY OF GRAPH

        var copy_graph = d3.select("#copy_networth_time")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2]))
            .attr("width", "100%")
            .append("svg:g")
            .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

        copy_graph.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + h + ")")
            .style("font-size", 12)
            .call(xAxis);

        copy_graph.select('.x.axis')
            .selectAll("text")
            .style("text-anchor", "end")
            .attr("dx", "-.8em")
            .attr("dy", ".15em")
            .attr("transform", "rotate(-45)");

        copy_graph.append("text")
            .style("fill", "white")
            .style("font-size", 16)
            .attr("transform",
                "translate(" + (w / 2) + " ," +
                (h + m[0] + 40) + ")")
            .style("text-anchor", "middle")
            .text("Date");

        copy_graph.append("text")
            .style("fill", "white")
            .style("font-size", 16)
            .attr("transform", "rotate(-90)")
            .attr("y", 0 - m[1] + 30)
            .attr("x", 0 - (h / 2))
            .attr("dy", "1em")
            .style("text-anchor", "middle")
            .text("Networth");

        var yAxisLeft = d3.axisLeft(y).ticks(4)

        copy_graph.append("g")
            .attr("class", "y axis")
            .attr("transform", "translate(-25,0)")
            .style("font-size", 12)
            .call(yAxisLeft);


        copy_graph
            .datum(data_networth)
            .append("path")
            .attr("d", area)
            .style("stroke-width", 2)
            .style("fill", "#FFAA01")//green
            .style("stroke", "#FFAA01")

        copy_graph.selectAll("myCircles")
            .data(data_networth)
            .enter().append("circle")
            .attr("fill", "#00ffa1")
            .attr("stroke", "none")
            .attr("cx", function (d, i) { return x(parseTime(data_date[i])); })
            .attr("cy", function (d) { return y(d); })
            .attr("r", 5);

        copy_graph
            .append("text")
            .style("fill", "white")
            .attr("x", x(parseTime(data_date[data_date.length - 1])))
            .attr("y", y(data_networth[data_networth.length - 1]))
            .attr("dx", ".71em")
            .attr("dy", ".35em")
            .style("font-size", 14)
            .text(data_date[data_date.length - 1] + " - $" + data_networth[data_networth.length - 1])
    }


}

function savings_req_over_time() {
    if (date_arr == null || date_arr.split(",").length == 1) {
        // console.log("no graph today");
    } else {
        //console.log("yep we r gonna graph this shit");
        data_date = date_arr.split(",");
        data_savings = savings_req_arr.split(",");
        data_savings_bad = savings_req_arr_bad.split(",");

        /*var currentWidth = parseInt(d3.select('#savings_req_time').style('width'), 10)
        if (currentWidth > 2000) {
            currentWidth = 2000;
        }*/
        var currentWidth = 1500;
        var m = [50, 150, 100, 150]; // margins, m[0], m[2] = top/below, m[1] = right, m[3] = left
        var w = currentWidth * 0.7; // width
        var h = 400 - m[0] - m[2]; // height

        //graph for savings req over time
        var graph = d3.select("#savings_req_time")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2] + 90))
            //.classed("svg-content", true)
            //.attr("width", w + m[1] + m[3])
            //.attr("height", h + m[0] + m[2] + 150)
            .attr("width", "100%")
            //.attr("height", "100%")
            .append("svg:g")
            .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

        var x = d3.scaleTime().rangeRound([0, w]);
        var xAxis = d3.axisBottom(x).tickSize(-h).tickFormat(d3.timeFormat("%m-%d-%Y"));
        var parseTime = d3.timeParse("%m/%d/%Y");
        x.domain(d3.extent(data_date, function (d, i) { console.log(data_date[i]); return parseTime(data_date[i]); }));

        var y = d3.scaleLinear().domain([Math.min.apply(Math, data_savings), Math.max.apply(Math, data_savings_bad)]).range([h, 0]);

        graph.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + h + ")")
            .style("font-size", 12)
            .call(xAxis)

        graph.append("circle").attr("cx", w / 2 - 50).attr("cy", h + m[0] + 70).attr("r", 6).style("fill", "red")
        graph.append("circle").attr("cx", w / 2 - 50).attr("cy", h + m[0] + 100).attr("r", 6).style("fill", "#14ff65")
        graph.append("text").attr("x", w / 2 - 30).attr("y", h + m[0] + 70).text("most u can save").style("font-size", 18).style("fill", "white").attr("alignment-baseline", "middle")
        graph.append("text").attr("x", w / 2 - 30).attr("y", h + m[0] + 100).text("least u can save").style("font-size", 18).style("fill", "white").attr("alignment-baseline", "middle")

        graph.select('.x.axis')
            .selectAll("text")
            .style("text-anchor", "end")
            .attr("dx", "-.8em")
            .attr("dy", ".15em")
            .attr("transform", "rotate(-45)");

        graph.append("text")
            .style("fill", "white")
            .style("font-size", 16)
            .attr("transform",
                "translate(" + (w / 2) + " ," +
                (h + m[0] + 40) + ")")
            .style("text-anchor", "middle")
            .text("Date");

        // text label for the y axis
        graph.append("text")
            .style("fill", "white")
            .style("font-size", 16)
            .attr("transform", "rotate(-90)")
            .attr("y", 0 - m[1] + 30)
            .attr("x", 0 - (h / 2))
            .attr("dy", "1em")
            .style("text-anchor", "middle")
            .text("Savings Required");

        var yAxisLeft = d3.axisLeft(y).ticks(4)

        graph.append("g")
            .attr("class", "y axis")
            .attr("transform", "translate(-25,0)")
            .style("font-size", 12)
            .call(yAxisLeft);

        /*graph
            .append("text")
            .attr("x", (w / 2))
            .attr("y", 0 - (m[0] / 2))
            .attr("text-anchor", "middle")
            .style("font-size", "16px")
            .style("text-decoration", "underline")
            .text("Savings required over time")*/

        // define the 1st line
        var valueline = d3.line()
            .x(function (d, i) { return x(parseTime(data_date[i])); })
            .y(function (d) { return y(d); });

        // define the 2nd line
        var valueline2 = d3.line()
            .x(function (d, i) { return x(parseTime(data_date[i])); })
            .y(function (d) { return y(d); });

        graph.append("path")
            .data([data_savings])
            .attr("class", "line-7")
            .attr("d", valueline);

        graph.append("path")
            .data([data_savings_bad])
            .attr("class", "line-6")
            .attr("d", valueline2);

        graph.selectAll("myCircles")
            .data(data_savings)
            .enter().append("circle")
            .attr("fill", "#00ffa1")
            .attr("stroke", "none")
            .attr("cx", function (d, i) { return x(parseTime(data_date[i])); })
            .attr("cy", function (d) { return y(d); })
            .attr("r", 5);

        graph.selectAll("myCircles")
            .data(data_savings_bad)
            .enter().append("circle")
            .attr("fill", "#00ffa1")
            .attr("stroke", "none")
            .attr("cx", function (d, i) { return x(parseTime(data_date[i])); })
            .attr("cy", function (d) { return y(d); })
            .attr("r", 5);

        graph
            .append("text")
            .style("fill", "white")
            .style("font-size", 14)
            .attr("x", x(parseTime(data_date[data_date.length - 1])))
            .attr("y", y(data_savings[data_savings.length - 1]))
            .attr("dx", ".71em")
            .attr("dy", ".35em")
            .text(data_date[data_date.length - 1] + " - $" + data_savings[data_savings.length - 1])

        graph
            .append("text")
            .style("fill", "white")
            .style("font-size", 14)
            .attr("x", x(parseTime(data_date[data_date.length - 1])))
            .attr("y", y(data_savings_bad[data_savings_bad.length - 1]))
            .attr("dx", ".71em")
            .attr("dy", ".35em")
            .text(data_date[data_date.length - 1] + " - $" + data_savings_bad[data_savings_bad.length - 1])



        //COPY OF GRAPH
        var copy_graph = d3.select("#copy_savings_req_time")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            .attr("viewBox", "0 0 " + (w + m[1] + m[3]) + " " + (h + m[0] + m[2] + 90))
            .attr("width", "100%")
            .append("svg:g")
            .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

        copy_graph.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + h + ")")
            .style("font-size", 12)
            .call(xAxis)

        copy_graph.append("circle").attr("cx", w / 2 - 50).attr("cy", h + m[0] + 70).attr("r", 6).style("fill", "red")
        copy_graph.append("circle").attr("cx", w / 2 - 50).attr("cy", h + m[0] + 100).attr("r", 6).style("fill", "#14ff65")
        copy_graph.append("text").attr("x", w / 2 - 30).attr("y", h + m[0] + 70).text("most u can save").style("font-size", 18).style("fill", "white").attr("alignment-baseline", "middle")
        copy_graph.append("text").attr("x", w / 2 - 30).attr("y", h + m[0] + 100).text("least u can save").style("font-size", 18).style("fill", "white").attr("alignment-baseline", "middle")

        copy_graph.select('.x.axis')
            .selectAll("text")
            .style("text-anchor", "end")
            .attr("dx", "-.8em")
            .attr("dy", ".15em")
            .attr("transform", "rotate(-45)");

        copy_graph.append("text")
            .style("fill", "white")
            .style("font-size", 16)
            .attr("transform",
                "translate(" + (w / 2) + " ," +
                (h + m[0] + 40) + ")")
            .style("text-anchor", "middle")
            .text("Date");

        copy_graph.append("text")
            .style("fill", "white")
            .style("font-size", 16)
            .attr("transform", "rotate(-90)")
            .attr("y", 0 - m[1] + 30)
            .attr("x", 0 - (h / 2))
            .attr("dy", "1em")
            .style("text-anchor", "middle")
            .text("Savings Required");

        copy_graph.append("g")
            .attr("class", "y axis")
            .attr("transform", "translate(-25,0)")
            .style("font-size", 12)
            .call(yAxisLeft);

        copy_graph.append("path")
            .data([data_savings])
            .attr("class", "line-7")
            .attr("d", valueline);

        copy_graph.append("path")
            .data([data_savings_bad])
            .attr("class", "line-6")
            .attr("d", valueline2);

        copy_graph.selectAll("myCircles")
            .data(data_savings)
            .enter().append("circle")
            .attr("fill", "#00ffa1")
            .attr("stroke", "none")
            .attr("cx", function (d, i) { return x(parseTime(data_date[i])); })
            .attr("cy", function (d) { return y(d); })
            .attr("r", 5);

        copy_graph.selectAll("myCircles")
            .data(data_savings_bad)
            .enter().append("circle")
            .attr("fill", "#00ffa1")
            .attr("stroke", "none")
            .attr("cx", function (d, i) { return x(parseTime(data_date[i])); })
            .attr("cy", function (d) { return y(d); })
            .attr("r", 5);

        copy_graph
            .append("text")
            .style("fill", "white")
            .style("font-size", 14)
            .attr("x", x(parseTime(data_date[data_date.length - 1])))
            .attr("y", y(data_savings[data_savings.length - 1]))
            .attr("dx", ".71em")
            .attr("dy", ".35em")
            .text(data_date[data_date.length - 1] + " - $" + data_savings[data_savings.length - 1])

        copy_graph
            .append("text")
            .style("fill", "white")
            .style("font-size", 14)
            .attr("x", x(parseTime(data_date[data_date.length - 1])))
            .attr("y", y(data_savings_bad[data_savings_bad.length - 1]))
            .attr("dx", ".71em")
            .attr("dy", ".35em")
            .text(data_date[data_date.length - 1] + " - $" + data_savings_bad[data_savings_bad.length - 1])
    }
}

result()

window.addEventListener('resize', result); //to make graphs responsive
