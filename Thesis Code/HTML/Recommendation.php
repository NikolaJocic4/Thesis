<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/recommendation.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.7/css/all.css">
    <title>Document</title>
    <style>
        .container {
            display: flex;
            justify-content: space-between; /* Adjust spacing between the two pentagons */
        }
        .item {
            flex: 1;
            margin: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 500px; /* Adjust height as needed */
            border: 1px solid #ccc; /* For visualization purposes */
        }
        canvas {
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>
<body>
      
      

      <button id="premierLeagueBtn">Premier League</button>
    <button id="laLigaBtn">La Liga</button>
    <button id="bundesligaBtn">Bundesliga</button>
    <button id="seriaABtn">Serie A</button>
    <button id="league1Btn">Ligue 1</button>

    <div id="squadList"></div>
    <div id="playerList"></div>
    <div id="playerTable"></div>

    <div style="font-family: Lato">&nbsp;</div>
    <!-- Your HTML content can go here -->

    <div class="container">
        <!-- First container for the first pentagon -->
        <div class="item" id="pentagon1">
            <canvas id="canvas1" width="500" height="500"></canvas>
        </div>
        <!-- Second container for the second pentagon -->
        <div class="item" id="pentagon2">
            <canvas id="canvas2" width="500" height="500"></canvas>
        </div>
    </div>

    <main></main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
        // Function to handle button clicks
        $('button').click(function(){
            var league = $(this).text(); // Get the text of the clicked button
            $.ajax({
                url: 'Teams.php', // PHP script to handle the request
                method: 'POST',
                data: {league: league}, // Send the selected league to PHP script
                success: function(response){
                  console.log("Response from Teams.php:", response); // Debug statement
                    $('#squadList').html(response); // Display the squad names
                },
                error: function(xhr, status, error){
                    console.error(error);
                }
            });
        });

        // Function to handle player click
        $('#squadList').on('click', '.player', function(){
            var squad = $(this).text(); // Get the text of the clicked player
            $.ajax({
                url: 'Players.php', // PHP script to handle the request
                method: 'POST',
                data: {squad: squad}, // Send the selected squad to PHP script
                success: function(response){
                  console.log("Response from Players.php:", response); // Debug statement
                    $('#playerList').html(response); // Display the players
                },
                error: function(xhr, status, error){
                    console.error(error);
                }
            });
        });
    });

    //BIG FUNCTION/
    $(document).ready(function(){
    // Define mapping of position abbreviations to full names
    var positionMap = {
        'GK': 'goalkeepers',
        'DF': 'defenders',
        'MF': 'midfielders',
        'FW': 'forwards',
        'MFFW': 'attackingfielders',
        'FWMF': 'midfieldwingers',
        'MFDF': 'deepmakers',
        'DFMF': 'fullbacks',
        'FWDF': 'playmakers',
        'DFFW': 'wingbacks'
        // Add more positions as needed
    };

    // Function to handle player button click
    $('#playerList').on('click', '.player-btn', function(){
        var playerInfo = $(this).data('player').split(' - ');
        var playerName = playerInfo[0]; // Get player name
        var playerPosition = positionMap[playerInfo[1]];
        
        // Check if the position abbreviation exists in the mapping
        if(playerPosition) {
            // Construct the table name for the player's position
            var tableName = playerPosition + 'stats';

            // Send AJAX request to retrieve the player's table
            $.ajax({
                url: 'getPlayerTable.php',
                method: 'POST',
                data: {playerName: playerName, tableName: tableName}, // Pass playerName as parameter
                success: function(response){
                    // Check if the player is within the top 20%
                    if(response.trim() === "Good"){
                        $('#playerTable').html('<p>Player is good enough.</p>'); // Display message
                    } else {
                        $('#playerTable').html(response); // Display the player's table
                        $.ajax({
                url: 'testPlayerData.php',
                method: 'POST',
                data: {playerName: playerName, tableName: tableName}, // Pass playerName and tableName as parameters
                dataType: 'json', // Specify that the expected response is JSON
                success: function(response) {
                    console.log("response: ", JSON.stringify(response));
                    var result1 = response.result1; 
                    var result2 = response.result2; 

                    // Now you can use result1 and result2 as needed
                    console.log("Result 1:", result1);
                    console.log("Result 2:", result2);

                    // You can also call separate functions to handle result1 and result2
                    drawPentagon('canvas1', result1);
                    drawPentagon('canvas2', result2);
                },
                error: function(xhr, status, error){
                    console.error(error);
                }
            });
                    }
                },
                error: function(xhr, status, error){
                    console.error(error);
                }
            });
        } else {
            console.error('Position abbreviation not found in mapping:', playerInfo[1]);
        }
    });
});
//END OF BIG FUNCTION//


//POLYGON FUNCTION//
function drawPentagon(canvasId, playerStats) {
                    // Define colors, size, etc.
                    var player = playerStats || {};
                    player.life = player.life;
                    player.defense = player.defense;
                    player.agility = player.agility;
                    player.intellect = player.intellect;
                    player.power = player.power;

                    var statOrder = [];
                    for(var i in player){
                        console.log(i);
                        statOrder.push(i);
                    } 
                    if(statOrder.length > 5){
                        statOrder.splice(statOrder.length - 5, 5);
                    
                    }
                    
                    console.log(statOrder);

                    var statColors = {};
                    statColors.life = "#339933";
                    statColors.defense = "#333399";
                    statColors.agility = "#999933";
                    statColors.intellect = "#993399";
                    statColors.power = "#993333";

                    var polygonX = 240; 
                    var polygonY = 240;
                    var polygonSize = 120;

                    var circleSize = 56;
                    var circles = [];
                    var circleIndexes = [];
                    for(var i in statColors) circleIndexes.push({defaultColor: statColors[i], color: statColors[i], over: false});

                    var innerPolygonColor = statColors.life;
                    var innerPolygonKnobs = [];
                    for(var i in statColors) innerPolygonKnobs.push({over: false, dragging: false});

                    // Define canvas and context
                    var canvas = document.getElementById(canvasId);
                    var ctx = canvas.getContext("2d");

                    function appendElement(type, properties, parent){
                        if(parent === undefined) parent = document.body;
                        var element = document.createElement(type);
                        for(var i in properties){
                            element.setAttribute(i, properties[i]);
                        }
                        parent.appendChild(element);
                        return element;
                    }

                    String.prototype.toRGB = function(){
                        var obj;
                        var triplet = this.slice(1, this.length);
                        var colors = [];
                        var index = 0;
                        for(var i = 0; i < triplet.length; i += 2){
                            colors[index] = parseInt("0x"+triplet[i]+triplet[i+1]);
                            index ++;
                        }
                        obj = {
                            string: "rgb("+colors[0]+", "+colors[1]+", "+colors[2]+")",
                            red: colors[0],
                            green: colors[1],
                            blue: colors[2],
                        };
                        return obj;
                    };

                    function MouseHandler(){
                        var handler = this;
                        this.x = 0;
                        this.y = 0;
                        this.down = false;
                        this.clicked = false;
                        this.move = function(e){
                            handler.x = e.clientX-canvas.getBoundingClientRect().left;
                            handler.y = e.clientY-canvas.getBoundingClientRect().top;
                        };
                        this.click = function(e){
                            handler.down = true;
                            handler.clicked = true;
                        };
                        this.release = function(e){
                            handler.down = false;
                        };
                        this.over = function(element){
                            var rect = element.getBoundingClientRect();
                            return this.x < rect.right && this.x > rect.left && this.y < rect.bottom && this.y > rect.top;
                        };
                        document.onmousemove = this.move;
                        document.onmousedown = this.click;
                        document.onmouseup = this.release;
                    }
                    var vertices = [];

                    function drawRegularPolygon(x, y, fill, stroke, strokeWidth, sides, radius){
                        var arc;
                        var x;
                        var y;
                        var point;
                        var points = [];

                        ctx.beginPath();
                        ctx.fillStyle = fill;
                        ctx.strokeStyle = stroke;
                        ctx.lineWidth = strokeWidth;
                        ctx.lineJoin = 'round';
                        for(var i = 0; i <= sides+1; i ++){
                            arc = i * 2*Math.PI / sides;
                            point = {};
                            point.x = x+radius*Math.sin(arc);
                            point.y = y-radius*Math.cos(arc);
                            if(i === 0) ctx.moveTo(point.x, point.y);
                            else ctx.lineTo(point.x, point.y);
                            if(i < sides+1) points.push(point);
                        }
                        ctx.fill();
                        ctx.stroke();
                        ctx.closePath();
                        return points;
                    }
                    var circles = [];

                    function redraw(){
                        circles = [];
                        ctx.rect(0, 0, canvas.width, canvas.height);
                        ctx.fillStyle = "#000";
                        ctx.fill();

                        var polygon = drawRegularPolygon(polygonX, polygonY, "#666", "#333", 2, statOrder.length, polygonSize);
                        ctx.beginPath();
                        ctx.setLineDash([5]);
                        ctx.lineDashOffset = 10;
                        ctx.strokeStyle = "#333";
                        for(var i = 0; i < polygon.length; i ++){
                            ctx.moveTo(polygonX, polygonY);
                            ctx.lineTo(polygon[i].x, polygon[i].y);
                        }
                        ctx.stroke();
                        ctx.setLineDash([0]);

                        ctx.beginPath();
                        var index;
                        var stat;
                        var text;
                        var innerPolygonVertices = [];
                        var distX;
                        var distY;
                        var distTotal;
                        var x;
                        var y;
                        for(var i = 0; i < statOrder.length+1; i ++){
                            index = i % statOrder.length;
                            if(vertices[index] === undefined) vertices[index] = {};
                            if(innerPolygonVertices[index] === undefined) innerPolygonVertices[index] = {};
                            vertices[index].x = polygon[index].x;
                            vertices[index].y = polygon[index].y;
                            stat = player[statOrder[index]];
                            vertices[index].distX = distX = vertices[index].x-polygonX;
                            vertices[index].distY = distY = vertices[index].y-polygonY;
                            vertices[index].distTotal = Math.sqrt(distX*distX + distY*distY);
                            vertices[index].radians = Math.atan2(distY, distX);
                            x = polygonX+Math.cos(vertices[index].radians)*(vertices[index].distTotal*stat/100);
                            y = polygonY+Math.sin(vertices[index].radians)*(vertices[index].distTotal*stat/100);
                            innerPolygonVertices[index].x = x;
                            innerPolygonVertices[index].y = y;
                            ctx.lineTo(x, y);
                        }
                        ctx.globalAlpha = 0.5;
                        ctx.fillStyle = innerPolygonColor;
                        ctx.fill();

                        ctx.globalAlpha = 1;
                        ctx.strokeStyle = innerPolygonColor;
                        ctx.stroke();

                        for(var i = 0; i < innerPolygonVertices.length; i ++){
                            x = innerPolygonVertices[i].x;
                            y = innerPolygonVertices[i].y;
                            if(innerPolygonKnobs[i].over || innerPolygonKnobs[i].dragging){
                                ctx.beginPath();
                                ctx.arc(x, y, 8, 0, 2 * Math.PI, false);
                                ctx.strokeStyle = statColors[statOrder[index]];
                                ctx.stroke();
                                ctx.closePath();
                            }
                        }

                        for(var i = 0; i < statOrder.length; i ++){
                            index = i;
                            x = vertices[index].x+Math.cos(vertices[index].radians)*(circleSize+8);
                            y = vertices[index].y+Math.sin(vertices[index].radians)*(circleSize+8);
                            ctx.beginPath();
                            ctx.arc(x, y, circleSize, 0, 2 * Math.PI, false);
                            ctx.fillStyle = '#333';
                            ctx.fill();
                            ctx.closePath();
                            ctx.beginPath();
                            ctx.arc(x, y, circleSize-4, 0, 2 * Math.PI, false);
                            ctx.fillStyle = "#000000";
                            if(circleIndexes[index].over) ctx.fillStyle = statColors[statOrder[index]];
                            ctx.fill();
                            ctx.closePath();
                            ctx.beginPath();
                            ctx.arc(x, y, circleSize-6, 0, 2 * Math.PI, false);
                            ctx.fillStyle = statColors[statOrder[index]];
                            if(circleIndexes[index].over) ctx.fillStyle = "#fff";
                            ctx.fill();
                            ctx.closePath();
                            circles.push({x: x, y: y, size: circleSize-6, radius: (circleSize-6)/2, stat: statOrder[index], color: statColors[statOrder[index]]});
                            ctx.fillStyle = "#fff";
                            if(circleIndexes[index].over) ctx.fillStyle = statColors[statOrder[index]];
                            ctx.font = "10px Lato";
                            text = statOrder[index].toUpperCase();
                            stat = player[statOrder[index]]+"%";
                            ctx.fillText(text, x-ctx.measureText(text).width/2, y);
                            ctx.fillText(stat, x-ctx.measureText(stat).width/2, y+16);
                        }
                    }
                    redraw();

                    function getClosestPointOnLine(line,x,y) {
                        lerp=function(a,b,x){ return(a+x*(b-a)); };
                        var dx=line.x1-line.x0;
                        var dy=line.y1-line.y0;
                        var t=((x-line.x0)*dx+(y-line.y0)*dy)/(dx*dx+dy*dy);
                        t=Math.min(1,Math.max(0,t));
                        var lineX=lerp(line.x0, line.x1, t);
                        var lineY=lerp(line.y0, line.y1, t);
                        return({x:lineX,y:lineY});
                    }

                    function pythagorean(dx, dy){
                        return Math.sqrt(dx*dx + dy*dy);
                    }
                    var fps = 60;
                    var circle;
                    var mouse = new MouseHandler();
                    var oldColor;
                    var newColor;
                    var transitioning = false;
                    var transitionIndex = 0;
                    var transitionSteps = 10;
                    var red, green, blue, redDiff, greenDiff, blueDiff, redSpeed, greenSpeed, blueSpeed;
                    var change;
                    function loop(){
                        change = false;
                        for(var i = 0; i < circles.length; i ++){
                            var circle = circles[i];
                            distX = circle.x-mouse.x;
                            distY = circle.y-mouse.y;
                            distTotal = Math.sqrt(distX*distX + distY*distY);
                            if(distTotal < circle.size){
                                if(!circleIndexes[i].over) change = true;
                                circleIndexes[i].over = true;
                            } else {
                                if(circleIndexes[i].over) change = true;
                                circleIndexes[i].over = false;
                            }
                        }
                        for(var i = 0; i < innerPolygonKnobs.length; i ++){
                            var knob = innerPolygonKnobs[i];
                            distX = knob.x-mouse.x;
                            distY = knob.y-mouse.y;
                            distTotal = pythagorean(distX, distY);
                            if(distTotal < 8){
                                if(!knob.over) change = true;
                                knob.over = true;
                            } else {
                                if(knob.over) change = true;
                                knob.over = false;
                            }
                            if(!mouse.down) knob.dragging = false;
                            if(mouse.down && knob.over || knob.dragging){
                                for(var j = 0; j < innerPolygonKnobs.length; j ++) innerPolygonKnobs[j].dragging = false;
                                knob.dragging = true;
                                var line = {x0: polygonX, y0: polygonY, x1: vertices[i].x, y1: vertices[i].y};
                                var point = getClosestPointOnLine(line, mouse.x, mouse.y);
                                var distStart = pythagorean(point.x-polygonX, point.y-polygonY);
                                var distStartEnd = pythagorean(vertices[i].x-polygonX, vertices[i].y-polygonY);
                                var percent = distStart/distStartEnd;
                                player[statOrder[i]] = Math.round(percent*100);
                                change = true;
                            }
                        }
                        if(change) redraw();
                        setTimeout(loop, 1000/fps);
                    }
                    setTimeout(loop, 1000/fps);
                }

                //END OF POLYGON FUNCTION//
</script>


</body>
</html>
