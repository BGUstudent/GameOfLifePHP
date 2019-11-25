<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css?family=Tomorrow&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

<?php 
session_start(); //On démarre une session afin de conserver certaines variables
?>

<!-- bouton de génération de grille aléatoire -->
<div  class="buttons">
    <table>
        <tr>
            <form method="post">
            Choose grid size : <input style="width:29px;"  type="text" name="size" value="30" size="1" maxlength="2"><br>
        <tr>
            <input type="submit" value="Generate a random grid " name="submit">
        </tr>  
            </form> 
        </tr>
        
        <!-- bouton next -->
        <tr>
            <td>
                <form method="post">
                <input type="submit" value="next" name="next" id ="next">
                </form> 
            </td>

            <!-- bouton play -->
            <td>
                <form method="post">
                <input type="submit" value="play" name="play" id ="play">
                </form> 
            </td>

            <!-- stop button : skip 3 frames -->
            <td>
                <form method="post">
                <input type="submit" value="stop" name="stop" id ="stop">
                </form> 
            </td>
        </tr>
    </table>
</div>

<br><br><br><br><br><br><br><br> <!-- Only God can judge me -->

<div class="rules">
    White cells are living cells<br>
    <br>
    Black cells are dead cells<br><br>
    ---<br><br>
    Any live cell with two or three neighbors survives<br><br>
    Any dead cell with three live neighbors becomes alive<br><br>
    All other live cells die in the next generation
</div>

<?php
// fonction permettant de retourner une matrice aléatoire
function randomMatrix($size){
    global $world; //je sais que c'est pas recommandé mais fuck j'ai perdu une journée là dessus
    $world=array(); // Declare an empty matrix
    for ($x = 0; $x <= $size; $x++){  // L'imbrication de 2 boucles for permet de définir un quadrillage
        for ($y = 0; $y <= $size; $y++) { // de taille size²
        $world[$x][$y]=rand(0, 1); // On remplit chaque cellule de 0 ou 1 aléatoirement
        }
    }
    return $world;
}

// Draw the random matrix
if(isset($_POST['submit'])){ // If you click on the button
    $size = $_POST['size']; // Get the size 
    randomMatrix($size); // Call the function with size parameter
        echo "<table class='center'>";//Draw table
        for ($x=0; $x<=$size; $x++){
            echo "<tr>";
            for ($y=0; $y<=$size; $y++){
                if ($world[$x][$y] == 1){ // If cell==0, Draw live cell
                    echo "<td class='alive'></td>";
                }
                if ($world[$x][$y] == 0){ // If cell==1, Draw dead cell
                    echo "<td class='dead'></td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        $_SESSION['world']=$world; //Store the world coordinates state in a super global 
        $_SESSION['size']=$size; //Store the chosen size in a super global 
        $_SESSION['frame']=0; //Store the generation n° in a super global, starting at gen 0
    }

// Define every cell's neibourhood; make sure to stay in the table's limits in order to avoid errors
function voisins($world, $size, $tick){
    echo "<div class='loopTable'>";
    echo "<div class='frame'>";
    echo "frame n°".$tick;//Just print the generation n°
    echo "</div>";
    echo "<table class='center'>";
    for ($x=0; $x <= $size; $x++) { // as usual
        echo "<tr>";
        for ($y = 0; $y <= $size; $y++) { // for loop nested in another one
            $aliveNeighbours[$x][$y] = 0; // for every cell with [X;Y] coordinates
            if ($x > 0 && $world[$x-1][$y] == 1) { // Check if x-1 exists, then check if x-1 neigbour is alive
                $aliveNeighbours[$x][$y]++; // Alive neigbours count +1
            }
            if ($x < $size && $world[$x+1][$y] == 1) { // Check if x+1 exists, then if x+1 neigbour is alive
                $aliveNeighbours[$x][$y]++; // Alive neigbours count +1...
            }
            if ($y > 0 && $world[$x][$y-1] == 1) { // Check if y-1 exists, then if y-1 neigbour is alive
                $aliveNeighbours[$x][$y]++;
            }
            if ($y < $size && $world[$x][$y+1] == 1) { // Check if y+1 exists, then if y+1 neigbour is alive
                $aliveNeighbours[$x][$y]++;
            }
            if ($x > 0 && $y > 0 && $world[$x-1][$y-1] == 1) { // Check if x-1 and y-1 exist, then if [x-1;y-1] neigbour is alive...
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
            // Applying rules (I should have wrote another function)
            if($world[$x][$y]==1 && $aliveNeighbours[$x][$y]>1 && $aliveNeighbours[$x][$y]<4){
                $new_world[$x][$y]=1; // Any live cell with two or three neighbors survives
            }
            else if($world[$x][$y]==0 && $aliveNeighbours[$x][$y]==3){
                $new_world[$x][$y]=1; // Any dead cell with three live neighbors becomes a live cell.
            }
            else{
                $new_world[$x][$y]=0; // All other live cells die in the next generation.
            }
            //Draw new matrix
            if ($new_world[$x][$y] == 1){  
                echo "<td class='alive'></td>";
            }
            if ($new_world[$x][$y] == 0){
                echo "<td class='dead'></td>";
            }
            $_SESSION['world']=$new_world; // Store the new world matrix
        }
        echo "</tr>";
    }
    echo "</table><br><br>";
    echo "</div>";
}

// Next button
if(isset($_POST['next'])){
    $_SESSION['frame']++; // Increase frame by 1 for each click
    voisins($_SESSION['world'], $_SESSION['size'], $_SESSION['frame']); // Call neigbour function 
}

$_SESSION['play']=0; // Set play to 0 (so it doesn't play before we ask for it)

//bouton play
if(isset($_POST['play'])){
    $_SESSION['play']=1; // Set play to 1
    loop(); // Start loop function
}

//Function play
function loop(){
    while($_SESSION['play']==1) { // while playing :
        $_SESSION['frame']++; // Increase frame by 1 for each click
        echo "<script type=\"text/javascript\"> 
        $('.loopTable').empty(); 
        </script>"; // Jquery is powerful
        voisins($_SESSION['world'], $_SESSION['size'], $_SESSION['frame']); // Call voisins
        ob_flush(); // Envoie le tampon de sortie
        flush(); // Vide les tampons de sortie du système
        sleep(1); // fais dodo
    }
}

//Stop button - skip 3 frames -
if(isset($_POST['stop'])){
    $_SESSION['play']=0; // Set play to 0 to stop the loop
}

//Overwrite values in for-loop PHP
//https://stackoverflow.com/questions/34530435/overwrite-values-in-for-loop-php
?>
</body>
</html>