<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<!-- 
To do:
définir un cadre/quadrillage
définir cells et leur état dead or alive
définir les règles
    A dead cell with exactly three living neighbours becomes alive.
    A living cell with two or three living neighbours remains alive.
    In all other cases, the cell becomes (or remains) dead. 
définir la "temporalité" n-1
position aléatoire des cells vivantes
Next gen / Refresh

Backlog :
modif vitesse
modif règles 
Nombres de cells de départ aléatoires
Positionnement manuel des cells de départ

Rules:
- Une cellule morte possédant exactement trois voisines vivantes devient vivante. 
- Une cellule vivante possédant deux ou trois voisines vivantes reste vivante. 
- Sinon elle meurt.
-->

<?php 
if(isset($_SESSION['world'])){
   session_destroy();
}
session_start();
?>

<form method="post" action="index.php">
Taille de la grille: <input type="text" name="size" value="20"><br>
<input type="submit" value="Générer un départ aléatoire" name="submit">
</form> 

<form method="post" action="index.php">
<input type="submit" value="next" name="next">
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
    global $firstWorld;
    $firstWorld=randomMatrix($size);
        echo "<table border='1'>";
        for ($x=0; $x<=$size; $x++){
            echo "<tr>";
            for ($y=0; $y<=$size; $y++){
                echo "<td>";
                echo $world[$x][$y];
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        $_SESSION['world']=$world;
    }

// On définit la notion de voisins, on les compte en s'assurant de rester dans les limites de la matrice.
function voisins($world, $size){
    echo "<table border='1'>";
    for ($x=0; $x <= $size; $x++) {
        echo "<tr>";
        for ($y = 0; $y <= $size; $y++) {
            $liveNeighbours[$x][$y] = 0;
            if ($x > 0 && $world[$x-1][$y] == 1) {
                $liveNeighbours[$x][$y]++;
            }
            if ($x < $size && $world[$x+1][$y] == 1) {
                $liveNeighbours[$x][$y]++;
            }
            if ($y > 0 && $world[$x][$y-1] == 1) {
                $liveNeighbours[$x][$y]++;
            }
            if ($y < $size && $world[$x][$y+1] == 1) {
                $liveNeighbours[$x][$y]++;
            }
            if ($x > 0 && $y > 0 && $world[$x-1][$y-1] == 1) {
                $liveNeighbours[$x][$y]++;
            }
            if ($x > 0 && $y < $size && $world[$x-1][$y+1] == 1) {
                $liveNeighbours[$x][$y]++;
            }
            if ($x < $size && $y > 0 && $world[$x+1][$y-1] == 1) {
                $liveNeighbours[$x][$y]++;
            }
            if ($x < $size && $y < $size && $world[$x+1][$y+1] == 1) {
                $liveNeighbours[$x][$y]++;
            }
            // on applique les règles
            if($world[$x][$y]==1 && $liveNeighbours[$x][$y]>1 && $liveNeighbours[$x][$y]<4){
                $new_world[$x][$y]=1;
            }
            else if($world[$x][$y]==0 && $liveNeighbours[$x][$y]==3){
                $new_world[$x][$y]=1;
            }
            // else if($world[$x][$y]==0 && $liveNeighbours<2){
            else{
                $new_world[$x][$y]=0;
            }
            //nouvelle matrice
            echo "<td>";
            echo $new_world[$x][$y];
            echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";
}

if(isset($_POST['next'])){
 voisins($_SESSION['world'], 20);
}
// for($x=0;$x<$size;$x++)
// { 
//     for($y=0;$y<$size;$y++) 
//     {
//         echo $new_world[$x][$y]." ";
//     }
//         echo "</p>";
// }

?>

</body>
</html>