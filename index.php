<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<!-- Rules:
- Une cellule morte possédant exactement trois voisines vivantes devient vivante. 
- Une cellule vivante possédant deux ou trois voisines vivantes reste vivante. 
- Sinon elle meurt.-->
<?php 
//if(isset($_SESSION['world'])){
//  session_destroy();
//}
session_start();
?>

<form method="post">
Taille de la grille: <input type="text" name="size" value="30" size="1" maxlength="2"><br>
<input type="submit" value="Générer un départ aléatoire" name="submit">
</form> 

<form method="post">
<input type="submit" value="next" name="next" id ="next">
</form> 

<form method="post">
<input type="submit" value="play" name="play" id ="play">
</form> 


<form method="post">
<input type="submit" value="stop" name="stop" id ="stop">
</form> 

<?php
function randomMatrix($size){
    global $world; //je sais que c'est pas recommandé mais fuck j'ai perdu une journée là dessus
    $world=array();
    for ($x = 0; $x <= $size; $x++){ 
        for ($y = 0; $y <= $size; $y++) { 
        $world[$x][$y]=rand(0, 1);
        }
    }
    return $world;
}

//
if(isset($_POST['submit']))
{
    $size = $_POST['size'];
    randomMatrix($size);
        echo "<table class='center'>";
        for ($x=0; $x<=$size; $x++){
            echo "<tr>";
            for ($y=0; $y<=$size; $y++){
                if ($world[$x][$y] == 1){
                    echo "<td class='alive'></td>";
                }
                if ($world[$x][$y] == 0){
                    echo "<td class='dead'></td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        $_SESSION['world']=$world;
        $_SESSION['size']=$size;
        $_SESSION['frame']=0;
    }

// On définit la notion de voisins, on les compte en s'assurant de rester dans les limites de la matrice.
function voisins($world, $size, $tick){
    echo "<div id='loopTable'>";
    echo "frame n°".$tick;
    echo "<table class='center'>";
    for ($x=0; $x <= $size; $x++) {
        echo "<tr>";
        for ($y = 0; $y <= $size; $y++) {
            $aliveNeighbours[$x][$y] = 0;
            if ($x > 0 && $world[$x-1][$y] == 1) {
                $aliveNeighbours[$x][$y]++;
            }
            if ($x < $size && $world[$x+1][$y] == 1) {
                $aliveNeighbours[$x][$y]++;
            }
            if ($y > 0 && $world[$x][$y-1] == 1) {
                $aliveNeighbours[$x][$y]++;
            }
            if ($y < $size && $world[$x][$y+1] == 1) {
                $aliveNeighbours[$x][$y]++;
            }
            if ($x > 0 && $y > 0 && $world[$x-1][$y-1] == 1) {
                $aliveNeighbours[$x][$y]++;
            }
            if ($x > 0 && $y < $size && $world[$x-1][$y+1] == 1) {
                $aliveNeighbours[$x][$y]++;
            }
            if ($x < $size && $y > 0 && $world[$x+1][$y-1] == 1) {
                $aliveNeighbours[$x][$y]++;
            }
            if ($x < $size && $y < $size && $world[$x+1][$y+1] == 1) {
                $aliveNeighbours[$x][$y]++;
            }
            // on applique les règles
            if($world[$x][$y]==1 && $aliveNeighbours[$x][$y]>1 && $aliveNeighbours[$x][$y]<4){
                $new_world[$x][$y]=1;
            }
            else if($world[$x][$y]==0 && $aliveNeighbours[$x][$y]==3){
                $new_world[$x][$y]=1;
            }
            else{
                $new_world[$x][$y]=0;
            }
            //nouvelle matrice
            if ($new_world[$x][$y] == 1){
                echo "<td class='alive'></td>";
            }
            if ($new_world[$x][$y] == 0){
                echo "<td class='dead'></td>";
            }
            $_SESSION['world']=$new_world;
        }
        echo "</tr>";
    }
    echo "</table></div>";
}

// bouton next
if(isset($_POST['next'])){
    $_SESSION['frame']++;
    voisins($_SESSION['world'], $_SESSION['size'], $_SESSION['frame']);
}

$_SESSION['play']=0;

//play
function loop(){
    while($_SESSION['play']==1) {
        $_SESSION['frame']++;
        echo "<script type=\"text/javascript\"> 
        $('div').empty();
        </script>";//Jquery c'est puissant
        voisins($_SESSION['world'], $_SESSION['size'], $_SESSION['frame']);
        ob_flush();// Envoie le tampon de sortie
        flush(); // Vide les tampons de sortie du système
        sleep(1);// fais dodo
}
}

//bouton play
if(isset($_POST['play'])){
    $_SESSION['play']=1;
    loop();
}

//bouton stop - saute 3 frames -
if(isset($_POST['stop'])){
    $_SESSION['play']=0;
}
//Overwrite values in for-loop PHP
//https://stackoverflow.com/questions/34530435/overwrite-values-in-for-loop-php
?>
</body>
</html>