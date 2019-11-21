<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
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
if(isset($_SESSION['world'])){
   session_destroy();
}
session_start();
?>

<form method="post" action="index.php">
Taille de la grille: <input type="text" name="size" value="30" size="1" maxlength="2"><br>
<input type="submit" value="Générer un départ aléatoire" name="submit">
</form> 

<form method="post">
<input type="submit" value="next" name="next" id ="next">
</form> 

<!-- <button type="button" onclick="play()">Play!</button> -->

<!-- <script>
    function play(){
        setInterval(function() {document.getElementById("next").click();}, 1000)
        };
</script> -->

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
    }

// On définit la notion de voisins, on les compte en s'assurant de rester dans les limites de la matrice.
function voisins($world, $size){
    echo "<table class='center'>";
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
echo "</table>";
}
// bouton next
if(isset($_POST['next'])){
 voisins($_SESSION['world'], $_SESSION['size']);
}

?>

</body>
</html>