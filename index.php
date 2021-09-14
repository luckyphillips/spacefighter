<HTML>
<HEAD>
<TITLE>Game</TITLE>
<STYLE>
body 
{
  background-image: url('space2.gif');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
}
.container
{
    position:absolute;
    margin:0;
    left:50%;
    top:20px;
    transform: translate(-50%);
    width:802px;
    height:802px;
/*     border:2px solid #F00; */
}
.enemy
{
    position:absolute;
    background-image: url("enemy.png?1");
    background-repeat: no-repeat, repeat;
    width:100px;
    height:100px;
    left:0px;
    top:100px;
    z-index:3;
}
.shipcontainer
{
    position:absolute;
    bottom:0px;
    left:0px;
    right:0px;
}
.ship
{
    position:absolute;
    background-image: url("ship.png?1");
    background-repeat: no-repeat, repeat;
    width:100px;
    height:100px;
    left:0px;
    bottom:0px;
    z-index:3;
}
.shot
{
    position:absolute;
    width:10px;
    height:20px;
    background-color: #00C;    
}
.scores
{
    position:absolute;
    left:0px;
    top:30px;
    width:100%;
    background-color:#333;
    color:#CCC;
    opacity: 0.8;
    border-radius: 30px;
    padding:20px;
    
}
.eachScore
{
    position:relative;
    left:50px;
    font-size:30px;
/*     background-color:#333; */
    color:#CCC;
}
.scoretable
{
    position:relative;
    width:250px;
    display: inline-block;
    vertical-align:top;
}

.finalscorecontainer
{
    position:absolute;
    left:100px;
    width:300px;
    height:300px;
    border-radius:20px;
    background-color:#666;
    color:#DDD;
    padding:20px;
}

</STYLE>


</HEAD>
<body>


<SCRIPT>
function log(txt)
{
    console.log(txt);
}

var container=document.createElement("div");
container.className="container";
document.body.appendChild(container);

var moveEnemy=null;
var shotsfired=0;
var startTimer=new Date().getTime();
var totalTime=0;
var lowestScore=0;

function moveX(e,x,y)
{
    moveEnemy=setInterval(function()
    {
        if(e.offsetLeft!=x)
        {
            e.style.left=(e.offsetLeft>x?e.offsetLeft-10:e.offsetLeft+10)+"px";
            enemy.X=e.offsetLeft;
        }
        if(e.offsetTop!=y)
        {
            e.style.top=(e.offsetTop>y?e.offsetTop-10:e.offsetTop+10)+"px";
            enemy.Y=e.offsetTop;
        }
        if(e.offsetLeft==x && e.offsetTop==y)
        {
            clearInterval(moveEnemy);
            x=Math.floor(Math.random() * 75)*10;
            y=Math.floor(Math.random() * 55)*10;
            moveX(e,x,y);
        }
    }
    ,50);
}

function Enemy()
{
    this.enemy=document.createElement("div");
    this.enemy.className="enemy";
    var x=Math.floor(Math.random() * 75)*10;
    var y=Math.floor(Math.random() * 55)*10;
    this.X=this.enemy.offsetLeft;
    this.Y=this.enemy.offsetTop;
    
//     m=null;
    moveX(this.enemy,x,y); 
    container.appendChild(this.enemy);    
}


var shipcontainer=document.createElement("div");
shipcontainer.className="shipcontainer";
container.appendChild(shipcontainer);
var ship=document.createElement("div");
ship.className="ship";
shipcontainer.appendChild(ship);
var enemy=new Enemy;


var moving=null;
var ismoving="none";


const xhttp = new XMLHttpRequest();
xhttp.onload = function() 
{
    lowestScore = this.responseText;
}
xhttp.open("GET", "scores.php?getlowest=1", true);
xhttp.send();    

function TopScore()
{
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() 
    {
        const data = JSON.parse(this.responseText);
        var scores=document.createElement("DIV");
        scores.className="scores";
        
        container.appendChild(scores);
        var a=document.createElement("div");
        a.className="eachScore";
        a.style.color="#FCC";
        
        a.innerHTML="TOP SCORES";
        scores.appendChild(a);
        
        
        
        for(i=0;i<data.length;i++)
        {
            a=document.createElement("div");
            a.className="eachScore";
            b=document.createElement("span");
            b.className="scoretable";
            b.innerHTML=data[i][0];
            c=document.createElement("span");
            c.className="scoretable";
            c.style.width="150px";
            c.innerHTML=data[i][1];
            d=document.createElement("span");
            d.className="scoretable";
            d.style.width="350px"
            d.innerHTML=data[i][2];            
            a.appendChild(b);
            a.appendChild(c);
            a.appendChild(d);
            scores.appendChild(a);
        }
    }
    xhttp.open("GET", "scores.php?getscore=1", true);
    xhttp.send();    
}


function moveShip(direction)
{
    var LR=direction=="left"?-10:10;
    shipPos=ship.offsetLeft;
    switch(direction)
    {
        case "left":
        if(shipPos>0)
        {
            ship.style.left=shipPos-10+"px";
        }
        else
        {
            clearInterval(moving);
        }
        break;
        case "right":
        if(shipPos<700)
        {
            ship.style.left=shipPos+10+"px";
        }
        else
        {
            clearInterval(moving);
        }
        break;
        
    }
}

function movebullet(shot)
{
    container.appendChild(shot);
    var m=setInterval(function()
    {
        shot.style.top=shot.offsetTop-10+"px";
//         log("enemy.offsetLeft="+enemy.X);
        if(shot.offsetTop==enemy.Y+100 && (shot.offsetLeft>enemy.X && shot.offsetLeft<enemy.X+100))
        {
            enemy.enemy.style.backgroundImage="url('explode8.gif')";
            clearInterval(moveEnemy);
            container.removeChild(shot);            
            clearInterval(m);
            clearInterval(es);
            setTimeout(function(){container.removeChild(enemy.enemy);finalScore();}, 800);
            enemy.X=0;
            enemy.Y=0;
            totalTime=new Date().getTime()-startTimer;
            log("totalTime="+totalTime);
            
        }
        
        if(shot.offsetTop==-10)
        {
            container.removeChild(shot);
            clearInterval(m);
        }
    }
    ,10);
}

function moveenemybullet(shot)
{
    container.appendChild(shot);
    var m=setInterval(function()
    {
        shot.style.top=shot.offsetTop+10+"px";
        if(shot.offsetLeft>ship.offsetLeft && shot.offsetLeft<ship.offsetLeft+100 && (shot.offsetTop>700 && shot.offsetTop<800))
        {
            ship.style.backgroundImage="url('explode8.gif')";
            clearInterval(es);
            setTimeout(function(){shipcontainer.removeChild(ship);TopScore();}, 800);
            container.removeChild(shot);            
            clearInterval(m);
        }
        
        if(shot.offsetTop==800)
        {
            container.removeChild(shot);
            clearInterval(m);
        }
    }
    ,15);
}



function finalScore()
{
    if(totalTime*shotsfired<parseInt(lowestScore))
    {
        var fs=document.createElement("DIV");
        fs.className="finalscorecontainer";
        var fsName=document.createElement("DIV");
        var fsInput=document.createElement("INPUT");
        fsInput.style.width="100%";
        fsInput.style.height="30px";
        fsInput.style.fontSize="30px";
        var fsButton=document.createElement("BUTTON");
        fsButton.innerHTML="Submit";
        
        fsName.innerHTML="Enter Your Name";
        fs.appendChild(fsName);
        fs.appendChild(fsInput);
        fs.appendChild(fsButton);
        fsName.innerHTML="Enter Your Name";
        container.appendChild(fs);
        fsButton.onclick=function()
        {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", 'scores.php', true);

            //Send the proper header information along with the request
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() 
            { // Call a function when the state changes.
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                // Request finished. Do processing here.
//                     alert(this.responseText);
                    container.removeChild(fs);
                    TopScore();
                }
            }
            xhr.send("putscore=1&name="+fsInput.value+"&score="+(totalTime*shotsfired));
        };
        // Enter new score
    }
    else
    {
//         alert("top");
        TopScore();
    }
}


function enemyshoot()
{
    var shot=document.createElement("div");
    shot.className="shot";
    shot.style.backgroundColor="#F11";
    shot.style.left=enemy.X+45+"px";
    shot.style.top=enemy.Y+"px";
    shot.style.zIndex=2;
    moveenemybullet(shot);
}
var es=setInterval(function(){enemyshoot();},1000);


function shoot()
{
    var shot=document.createElement("div");
    shot.className="shot";
    shot.style.left=ship.offsetLeft+45+"px";
    shot.style.top=710+"px";
    shot.style.zIndex=2;
    shotsfired++;
    movebullet(shot);
}

document.addEventListener('keydown', keyDownActions);
document.addEventListener('keyup', keyUpActions);

function keyUpActions(e)
{
    switch(e.keyCode)
    {
        case 37:
            if(ismoving=="left")
            {
                clearInterval(moving);
                ismoving=null;
            }
        case 39:
            if(ismoving=="right")
            {
                clearInterval(moving);
                ismoving=null;
            }
            break;
    }
}

function keyDownActions(e)
{
    switch(e.keyCode)
    {
        case 37:
            if(ismoving=="left")
            {
                break;
            }
            else
            {
                clearInterval(moving);
                moving=setInterval(moveShip,20,"left");
                ismoving="left";
            }
            break;
        case 39:
            if(ismoving=="right")
            {
                break;
            }
            else
            {
                clearInterval(moving);
                moving=setInterval(moveShip,20,"right");
                ismoving="right";
            }
            break;
        case 32:
            shoot();
            break;
    }
}
</SCRIPT>
