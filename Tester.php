<?php
class Tester{



    // Function to display a 2D array (matrix) in matrix form
    function display($matrix) {
        $rows = count($matrix);
        $cols = count($matrix[0]);
        echo "<br>";

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                echo $matrix[$i][$j] . "\t";
            }
            echo "<br>";
        }
    }

    
}
?>