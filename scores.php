<?
$score = file("scores.txt") or die("Unable to open file scores.txt!");


foreach($score as $line)
{
    $l[]=explode(",",$line);
}

function sortByScore($a, $b){
	return $a[1] > $b[1];
}
usort($l, 'sortByScore');

$newL=[];
$max=count($l)>10?10:count($l);
for($i=0;$i<$max;$i++)
{
    $newL[]=$l[$i];
}

if($_GET["getscore"])
{
    echo json_encode($newL);
}

if($_GET["getlowest"])
{
    $a=array_reverse($newL);
    echo $a[0][1];
}

if($_POST["putscore"])
{
    $fp = fopen('scores.txt', 'a');//opens file in append mode.
    $string = $_POST["name"];
    $name = preg_replace("/[^a-zA-Z ]/", "", $string)

    $s = $_POST["score"];
    $score = preg_replace("/[^0-9]/", "", $s);

    fwrite($fp, $name.",".$score.",".date('F jS Y')."\n");
    fclose($fp);
}
?> 
