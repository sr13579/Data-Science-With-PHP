<?php
class Matrix{

    function zeros($rows, $cols) { // Initialize the zero matrix $m=rows X $n=columns
        $zeroMatrix = [];
        for ($i = 0; $i < $rows; $i++) {
            $zeroMatrix[$i] = array_fill(0.0, $cols, 0.0);
        }
        return $zeroMatrix;
    }
    function identity($size) { // Initialize the identity matrix
        $identityMatrix = [];
        for ($i = 0; $i < $size; $i++) {
            $identityMatrix[$i] = array_fill(0, $size, 0);
            $identityMatrix[$i][$i] = 1; // Set the main diagonal to 1
        }
        return $identityMatrix;
    }
    
    function transpose($matrix) {
        $transposedMatrix = array();
        foreach ($matrix as $row => $columns) {
            foreach ($columns as $column => $value) {
                $transposedMatrix[$column][$row] = $value;
            }
        }
        return $transposedMatrix;
    }

    function diag($matrix) {//Create diagonal matrix
        // Get the dimensions of the matrix
        $rows = count($matrix);
        $cols = count($matrix[0]);
    
        if ($rows !== $cols) {        // Check if the matrix is square
            throw new Exception("Matrix must be square to extract diagonal values.");
        }
        $diagonalValues = [];        // Initialize an array to store diagonal values
        for ($i = 0; $i < $rows; $i++) {        // Extract the diagonal values
            $diagonalValues[] = $matrix[$i][$i];
        }    
        return $diagonalValues;
    }

    function multiply($matrix1, $matrix2) {
        // Get the dimensions of the matrices
        $rowsMatrix1 = count($matrix1);
        $colsMatrix1 = count($matrix1[0]);
        $rowsMatrix2 = count($matrix2);
        $colsMatrix2 = count($matrix2[0]);
        
        // Check if the matrices can be multiplied
        if ($colsMatrix1 !== $rowsMatrix2) {
            throw new Exception("Number of columns in the first matrix must be equal to the number of rows in the second matrix.");
        }
    
        // Initialize the result matrix with zeros. This part is to intialize the result container
        $result = $this->zeros($rowsMatrix1,$colsMatrix2);
    
        // Perform matrix multiplication
        for ($i = 0; $i < $rowsMatrix1; $i++) {
            for ($j = 0; $j < $colsMatrix2; $j++) {
                for ($k = 0; $k < $colsMatrix1; $k++) {
                    $result[$i][$j] += $matrix1[$i][$k] * $matrix2[$k][$j];
                }
            }
        }
    
        return $result;
    }

    public function scalarMultiply($matrix, $scalar) {
        // Initialize an empty array to store the result
        $result = array();
    
        // Loop through each row in the matrix
        foreach ($matrix as $row) {
            // Initialize an empty array to store the result row
            $resultRow = array();
    
            // Loop through each element in the row
            foreach ($row as $element) {
                // Multiply the element by the scalar and add it to the result row
                $resultRow[] = $element * $scalar;
            }
            // Add the result row to the result matrix
            $result[] = $resultRow;
        }
    
        // Return the resulting matrix
        return $result;
    }

    function determinant($matrix) {
        $n = count($matrix);
        if ($n == 1) {
            return $matrix[0][0];
        } elseif ($n == 2) {
            return $matrix[0][0] * $matrix[1][1] - $matrix[0][1] * $matrix[1][0];
        }
        $det = 0;
        for ($i = 0; $i < $n; $i++) {
            $subMatrix = array();
            for ($j = 1; $j < $n; $j++) {
                $row = array();
                for ($k = 0; $k < $n; $k++) {
                    if ($k != $i) {
                        $row[] = $matrix[$j][$k];
                    }
                }
                $subMatrix[] = $row;
            }
            $det += ($i % 2 == 0 ? 1 : -1) * $matrix[0][$i] * $this->determinant($subMatrix);
        }
        return $det;
    }

    
     // Function to compute the dot product of two matrices using foreach
public function dotProduct($matrixA, $matrixB) {
    // Check dimensions to ensure matrices can be multiplied
    $rowsA = count($matrixA);
    $colsA = count($matrixA[0]);
    $rowsB = count($matrixB);
    $colsB = count($matrixB[0]);

    if ($colsA != $rowsB) {
        throw new Exception('Matrices cannot be multiplied: Number of columns in matrixA must equal number of rows in matrixB.');
    }

    // Initialize result matrix with dimensions rowsA x colsB filled with zeros
    $result = array_fill(0, $rowsA, array_fill(0, $colsB, 0));

    // Compute dot product
    foreach ($matrixA as $i => $rowA) {
        foreach ($matrixB[0] as $j => $colB) {
            $sum = 0;
            foreach ($rowA as $k => $valueA) {
                $sum += $valueA * $matrixB[$k][$j];
            }
            $result[$i][$j] = $sum;
        }
    }

    return $result;
}

    function cofactor($matrix) {
        $n = count($matrix);
        $cofactorMatrix = array();
        
        for ($i = 0; $i < $n; $i++) {
            $cofactorRow = array();
            for ($j = 0; $j < $n; $j++) {
                $subMatrix = array();
                for ($k = 0; $k < $n; $k++) {
                    if ($k != $i) {
                        $row = array();
                        for ($l = 0; $l < $n; $l++) {
                            if ($l != $j) {
                                $row[] = $matrix[$k][$l];
                            }
                        }
                        $subMatrix[] = $row;
                    }
                }
                $cofactorRow[] = ((($i + $j) % 2 == 0 ? 1 : -1) * $this->determinant($subMatrix));
            }
            $cofactorMatrix[] = $cofactorRow;
        }
        return $cofactorMatrix;
    }

    function adjoint($matrix) {
        $cofactorMatrix = $this->cofactor($matrix);
        return $this->transpose($cofactorMatrix);
    }


function inverse($matrix) {
    $det = $this->determinant($matrix);
    
    if ($det == 0) {
        throw new Exception("Matrix is singular and cannot be inverted.");
    }
    
    $adjointMatrix = $this->adjoint($matrix);
    $n = count($matrix);
    $inverseMatrix = array();
    
    for ($i = 0; $i < $n; $i++) {
        $inverseRow = array();
        
        for ($j = 0; $j < $n; $j++) {
            $inverseRow[] = $adjointMatrix[$i][$j] / $det;
        }
        
        $inverseMatrix[] = $inverseRow;
    }
    
    return $inverseMatrix;
}
 
public function scalar_matrix_multiply($scalar, $matrix) {
    $rows = count($matrix);
    $cols = count($matrix[0]);
    $result = array();

    for ($i = 0; $i < $rows; $i++) {
        $result[$i] = array();
        for ($j = 0; $j < $cols; $j++) {
            $result[$i][$j] = $scalar * $matrix[$i][$j];
        }
    }
    return $result;
}

 

   public function gaussianElimination($matrix) {
        $rows = count($matrix);
        $cols = count($matrix[0]);
    
        for ($i = 0; $i < $rows; $i++) {
            // Search for maximum in this column
            $maxEl = abs($matrix[$i][$i]);
            $maxRow = $i;
            for ($k = $i + 1; $k < $rows; $k++) {
                if (abs($matrix[$k][$i]) > $maxEl) {
                    $maxEl = abs($matrix[$k][$i]);
                    $maxRow = $k;
                }
            }
    
            // Swap maximum row with current row (column by column)
            for ($k = $i; $k < $cols; $k++) {
                $tmp = $matrix[$maxRow][$k];
                $matrix[$maxRow][$k] = $matrix[$i][$k];
                $matrix[$i][$k] = $tmp;
            }
    
            // Make all rows below this one 0 in current column
            for ($k = $i + 1; $k < $rows; $k++) {
                $c = -$matrix[$k][$i] / $matrix[$i][$i];
                for ($j = $i; $j < $cols; $j++) {
                    if ($i == $j) {
                        $matrix[$k][$j] = 0;
                    } else {
                        $matrix[$k][$j] += $c * $matrix[$i][$j];
                    }
                }
            }
        }
    
        // Back substitution to get the solution (if needed)
        $x = array_fill(0, $rows, 0);
        for ($i = $rows - 1; $i >= 0; $i--) {
            $x[$i] = $matrix[$i][$cols - 1] / $matrix[$i][$i];
            for ($k = $i - 1; $k >= 0; $k--) {
                $matrix[$k][$cols - 1] -= $matrix[$k][$i] * $x[$i];
            }
        }
        return $x;
    }

    public function subtraction($matrixA, $matrixB) {
        // Check if matrices have the same dimensions
        $rowsA = count($matrixA);
        $colsA = count($matrixA[0]);
        $rowsB = count($matrixB);
        $colsB = count($matrixB[0]);
    
        if ($rowsA != $rowsB || $colsA != $colsB) {
            throw new Exception("Matrices must have the same dimensions for subtraction.");
        }
    
        // Initialize an empty array to store the result
        $result = array();
        // Loop through each row
        for ($i = 0; $i < $rowsA; $i++) {
            // Initialize an empty array to store the result row
            $resultRow = array();
            // Loop through each column
            for ($j = 0; $j < $colsA; $j++) {
                // Subtract the corresponding elements
                $resultRow[] = $matrixA[$i][$j] - $matrixB[$i][$j];
            }
            // Add the result row to the result matrix
            $result[] = $resultRow;
        }
        // Return the resulting matrix
        return $result;
    }

    function add($matrix1, $matrix2) {
        // Check if matrices are the same size
        $rows1 = count($matrix1);
        $cols1 = count($matrix1[0]);
        $rows2 = count($matrix2);
        $cols2 = count($matrix2[0]);
    
        if ($rows1 != $rows2 || $cols1 != $cols2) {
            throw new Exception("Matrices must be of the same size.");
        }
    
        // Initialize the result matrix
        $result = array();
    
        // Perform the addition
        for ($i = 0; $i < $rows1; $i++) {
            $result[$i] = array();
            for ($j = 0; $j < $cols1; $j++) {
                $result[$i][$j] = $matrix1[$i][$j] + $matrix2[$i][$j];
            }
        }
    
        return $result;
    }

    public function sum($matrix,$axis){
        if($axis){}
        else{
            $sum=0;
            foreach($matrix as $row){
foreach($row as $element){
    $sum+=$element;
}
            }
            return $sum;
        }
    }

    function sumAlongAxis($matrix, $axis = 0, $keepdims = true) {
        $rows = count($matrix);
        $cols = count($matrix[0]);
    
        if ($axis == 0) {
            // Sum along columns
            $result = array_fill(0, 1, array_fill(0, $cols, 0));
            for ($i = 0; $i < $rows; $i++) {
                for ($j = 0; $j < $cols; $j++) {
                    $result[0][$j] += $matrix[$i][$j];
                }
            }
        } elseif ($axis == 1) {
            // Sum along rows
            $result = array_fill(0, $rows, array_fill(0, 1, 0));
            for ($i = 0; $i < $rows; $i++) {
                for ($j = 0; $j < $cols; $j++) {
                    $result[$i][0] += $matrix[$i][$j];
                }
            }
        } else {
            throw new Exception("Invalid axis value. Axis must be 0 or 1.");
        }
    
        if (!$keepdims) {
            // Remove extra dimension if keepdims is false
            if ($axis == 0) {
                $result = $result[0];
            } else {
                foreach ($result as &$row) {
                    $row = $row[0];
                }
            }
        }
    
        return $result;
    }
    function elementwiseMultiply($matrix1, $matrix2) {
        // Get the dimensions of the matrices
        $rows = count($matrix1);
        $cols = count($matrix1[0]);
    
        // Initialize the result matrix with zeros
        $result = array_fill(0, $rows, array_fill(0, $cols, 0));
    
        // Check if both matrices have the same dimensions
        if ($rows != count($matrix2) || $cols != count($matrix2[0])) {
            throw new Exception("Matrices must have the same dimensions for element-wise multiplication.");
        }
    
        // Perform element-wise multiplication
        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                $result[$i][$j] = $matrix1[$i][$j] * $matrix2[$i][$j];
            }
        }
    
        return $result;
    }
}
