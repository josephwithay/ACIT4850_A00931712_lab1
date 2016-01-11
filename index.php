<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Yoseph Jo's Epic Tic Tac Toe</title>
    </head>
    <body>
        <hr>
        <h3>Project T-3</h3>
        <p>Tic Tac Toe</p>
        <hr>
        <?php
        new Game();
        ?>
        <hr>
    </body>
</html>
<?php
/*
 * ***************************************************************************
 * This Game class consists all the methods/fundtions for this Tic Tac Toe to
 * work.
 * ***************************************************************************
 */

class Game {

    var $position;                  //  $position is an array for the Game
    var $board = '---------';       //  Initial state of the board, which is empty.
    var $valid_value = ['x', 'o', '-'];     //  Array of valid characters
    var $invalid_value;                     //  Array of invalid characters

    /*
     * **********************************************************************
     * Constructors
     * **********************************************************************
     */

    function __construct() {
//        Checking if the 'board' variable exists
        if (isset($_GET['board'])) {

//            If the board variable exists, Check the length of the variable
            if (strlen(trim($_GET['board'])) == 0) {
//                If the board variable exists but if there's no value given
//                Assume the user is trying to start a new game. So fill the
//                variable with '---------'
                $this->board = '---------';
            } else {
//                If else ('board' variable exists and contains characters)
//                Trim any excess whitespaces and convert any upper case characters
//                to lowercase; just in case if some user has their CAPS enabled.
                $this->board = trim(strtolower($_GET['board']));
            }
        }
//      Convert the board variable (string) into an array.
        $this->position = str_split($this->board);
        $this->rules();
    }

    /*
     * ******************************************************************
     *  This function is setting game rules.
     *  1. Valid characters (x , o , -)
     *  2. Find invalid characters
     *  3. Valid character length, which is 9
     *  4. Winner found, game ends (for x and o)
     *  5. Game is tied (when all cells are filled up)
     * ******************************************************************
     */

    function rules() {
        //  Check any invalid values in the board.
        $this->invalid_value = array_diff($this->position, $this->valid_value);
        //  Several checks
        if (count($this->invalid_value, COUNT_RECURSIVE) > 0) {
            //  Goes over the array and finds for invalid characters
            //  computes message to the user
            $this->alert_user('invalid-values');
        } else if (strlen($this->board) < 9) {
            //  the length of the board variable is shorter than expected
            //  computes message to the user
            $this->alert_user('short-variable');
        } else if (strlen($this->board) > 9) {
            //  the length of the board variable is longer than expected
            //  computes message to the user
            $this->alert_user('long-variable');
        } else if ($this->board == '---------') {
            //  This is the start of the game
            $this->display(true);
            //  computes message to the user
            $this->alert_user('start-game');
        } else if ($this->winner('x')) {
            //  if the Winner is X, disable the game and compute a message to the user
            $this->display(false);
            $this->alert_user('winner-x');
        } else if ($this->winner('o')) {
            //  if the Winner is O, disable the game and compute a message to the user 
            $this->display(false);
            $this->alert_user('winner-o');
        } else if (stristr($this->board, '-') === FALSE) {
            //  the board have been filled up, but no winner yet....
            //  disable the game and compute a message to the user.
            $this->display(false);
            $this->alert_user('tied');
        } else {
            $this->pick_move();
            if ($this->winner('o')) {
                //If CPU wins disable the board and congratualate him.
                $this->display(false);
                $this->alert_user('winner-o');
            } else {
                // User continues to play.
                $this->display(true);
            }
        }
    }

    /*
     * ******************************************************************
     *  List of alerts made into one function so that the 'rules()' function can call
     *  rather than rules() function having many lines of echos
     * ******************************************************************
     */

    function alert_user($alert) {
        $restart = true;    //  Restart button is initially enabled.
        switch ($alert) {
            case 'invalid-values':
                echo 'Invalid Value found. The game cannot be proceeded.';
                echo '<br/>';
                echo 'Characters allowed: <b>X</b>, <b>O</b>, or <b>- (dash)</b>';
                echo '<br/>';
                break;
            case 'short-variable':
                echo 'The Board variable is too short. Please check if you have exactly 9 characters.';
                echo '<br/>';
                echo 'There are currently' . strlen($this->board) . ' characters entered.';
                echo '<br/>';
                echo 'Please restart the game by clicking at the refresh button below.';
                echo '<br/>';
                break;
            case 'long-variable':
                echo 'The Board variable is too long. Please check if you have exactly 9 characters.';
                echo '<br/>';
                echo 'There are currently' . strlen($this->board) . ' characters entered.';
                echo '<br/>';
                echo 'Please restart the game by clicking at the refresh button below.';
                echo '<br/>';
                break;
            case 'start-game':
                echo 'The game have been started. I will wish you good luck!';
                echo '<br/>';
                echo '<strong>Instructions:</strong>';
                echo '<br/>';
                echo 'Click on the dash to mark your character on the block you\'ve chosen.';
                $restart = false;
                break;
            case 'winner-x':
                echo 'You have defeated me! Congratulations!!';
                echo '<br/>';
                echo 'Would you like to restart the game?';
                echo '<br/>';
                break;
            case 'winner-o':
                echo 'You have been defeated, loser!!';
                echo '<br/>';
                echo 'I will defeat you again. Please restart the game.';
                echo '<br/>';
                break;
            case 'tied':
                echo 'You\'ve tried to beat me but unfortunately this game is a <strong>tie</strong>';
                echo '<br/>';
                echo 'I will give you another chance to beat me.';
                break;
            default:
                echo 'Unreachable statement that you have reached.';
                break;
        }
        if ($restart) {
            echo '<br /><br /><a href="' . $_SERVER['PHP_SELF'] . '" style="-webkit-appearance: button; -moz-appearance: button; appearance: button;   background: #6b6b6b;  font-family: Arial;  color: #ffffff;  font-size: 14px;  padding: 5px 15px 5px 15px;  text-decoration: none;">Restart Game!</a>';
        }
    }

    /*
     * ******************************************************************
     *  Display the Board for the Game
     * ******************************************************************
     */

    function display($show) {
        echo '<table border="1" cols=”3” style=”fontsize:large; fontweight:bold;”>';
        echo '<tr>'; // open the first row
        for ($pos = 0; $pos < 9; $pos++) {
            //  To show or not to show the cells
            if ($show) {
                echo $this->show_cell($pos);
            } else {
                // If there's a winner, disable the links.
                echo '<td style = "padding: 1.5em;">' . $this->position[$pos] . '</td>';
            }
            if ($pos % 3 == 2) {
                echo '</tr><tr>'; // start a new row for the next square
            }
        }
        echo '</tr>'; // close the last row
        echo '</table>';
    }

    /*
     * ******************************************************************
     *  Generates HTML for the cell.
     * ******************************************************************
     */

    function show_cell($which) {
        $token = $this->position[$which];
        // deal with the easy case
        if ($token <> '-') {
            return '<td style = "padding:1.5em;">' . $token . '</td>';
        }
        // now the hard case
        $this->newposition = $this->position;  // copy the original
        $this->newposition[$which] = 'x';      // this would be their move
        $move = implode($this->newposition);   // make a string from the board array 
        $link = '?board=' . $move;             // this is what we want the link to be
        // so return a cell containing an anchor and showing a hyphen and also make the table bigger with the padding
        return '<td><a href = ' . $link . ' style = "text-decoration: none; text-align: center; ;"><div style = "padding: 1.5em;">-</div></a></td>';
    }

    /*
     * ******************************************************************
     *  This winner function handles all the winning conditions.
     * ******************************************************************
     */

    function winner($token) {
        $won = false;
        /*
         * ---------------------------------------
         *  Horizontal Winning Condition Checking
         * ---------------------------------------
         */
        for ($row = 0; $row < 3; $row++) {
            $won = true;
            for ($col = 0; $col < 3; $col++) {
//            echo "row check for token " . $token . ": " . ($row + 1) . "," . ($col + 1) . " position: " . (3 * $row + $col) . "<br />";
//            This was done for debugging purposes.            
                if ($this->position[3 * $row + $col] != $token) {
                    $won = false; //note the negative test
                }
//            echo " result: " . $won . "<br/>";
//            This was done for debugging purposes.
            }
            if ($won) {
                return true;
            }
        }
        /*
         * ---------------------------------------
         *  Vertical Winning Condition Checking
         * ---------------------------------------
         */
        for ($col = 0; $col < 3; $col++) {
            $won = true;
            for ($row = 0; $row < 3; $row++) {
//            echo "col check for token " . $token . ": " . ($col + 1) . "," . ($row + 1) . " position: " . (3 * $col + $row) . "<br />";
//            This was done for debugging purposes.
                if ($this->position[3 * $row + $col] != $token) {
                    $won = false; //note the negative test
                }
//            echo " result: " . $won . "<br/>";
//            This was done for debugging purposes.
            }
            if ($won) {
                return true;
            }
        }
        /*
         * ---------------------------------------
         *  Diagonal Winning Condition Checking
         * ---------------------------------------
         */
        if (($this->position[0] == $token) &&
                ($this->position[4] == $token) &&
                ($this->position[8] == $token)) {
            $won = true;
        } else if (($this->position[2] == $token) &&
                ($this->position[4] == $token) &&
                ($this->position[6] == $token)) {
            $won = true;
        }
        return false;
    }

    /*
     * ******************************************************************
     *  The logic behind the tic tac toe AI
     * ******************************************************************
     */

    function pick_move() {
        //  Check if CPU won
        $cpu = $this->possible_moves('o');
        //  If CPU thinks it's a winning spot place the character on there.
        if ($cpu != -1) {
            $this->position[$cpu] = 'o';
        } else {
            //  Else...inspect if the user can win
            $user = $this->possible_moves('x');
            //  However if there is some change for the user to win.
            //  Don't make that happen.
            if ($user != -1) {
                $this->position[$user] = 'o';
            } else {
                //  No one seems to have any advantage over this game.
                //  place the character on a random cell.
                $board = implode($this->position);
                $place = 4; //  Our AI is smart enough to take the center cell as soon as possible.
                // This would not stop unless CPU selects a random cell.
                while (substr($board, $place, 1) != '-') {
                    // Generate a random number
                    $place = rand(0, 8);
                }
                // When CPU chooses a random cell, replace '- 'with 'o'
                $new_board = substr_replace($board, 'o', $place, 1);

                // Regenerate the array to display where the AI moved.
                $this->position = str_split($new_board);
            }
        }
    }

    /*
     * ******************************************************************
     *  Checking for CPU movement
     *  Note: Similar to Winner() function.
     * ******************************************************************
     */

    function possible_moves($token) {
        /*
         * ---------------------------------------
         *  Horizontal checking for CPU
         * ---------------------------------------
         */
        for ($row = 0; $row < 3; $row++) {
            $value = 0;         // Similar to board variable but it is temporary.
            $cell_loc = 0;      // cell location.
            // Iterate through the column
            for ($col = 0; $col < 3; $col++) {
                if ($this->position[3 * $row + $col] != $token) {
                    $cell_loc = 3 * $row + $col;
                } else {
                    // It contains a character. Add a point to it
                    $value++;
                }
            }
            // If the row is 2/3 full
            if ($value == 2) {
                // Check if that cell is empty.
                if ($this->position[$cell_loc] == '-') {
                    // That cell is empty.  Take it for the win or block!
                    return $cell_loc;
                }
            }
        }
        /*
         * ---------------------------------------
         *  Vertical checking for CPU
         * ---------------------------------------
         */
        //  Same as above but orders changed.
        for ($col = 0; $col < 3; $col++) {
            $value = 0;         // Similar to board variable but it is temporary.
            $cell_loc = 0;      // cell location.
            // Iterate through the column
            for ($row = 0; $row < 3; $row++) {
                if ($this->position[3 * $row + $col] != $token) {
                    $cell_loc = 3 * $row + $col;
                } else {
                    // It contains a character. Add a point to it
                    $value++;
                }
            }
            // If the row is 2/3 full
            if ($value == 2) {
                // Check if that cell is empty.
                if ($this->position[$cell_loc] == '-') {
                    // That cell is empty.  Take it for the win or block!
                    return $cell_loc;
                }
            }
        }
        /*
         * ---------------------------------------
         *  Diagonal checking for CPU
         * ---------------------------------------
         */
        $diag_win = [[0, 4, 8], [2, 4, 6]];   //  Winning cell location for diagonal
        //  Iterate through each diagonal coordinates or cells
        foreach ($diag_win as $crd) {
            $value = 0;         // Similar to board variable but it is temporary.
            $cell_loc = 0;      // cell location.
            // Check each coordinates
            foreach ($crd as $pos) {
                if ($this->position[$pos] != $token) {
                    $cell_loc = $pos;
                } else {
                    $value++;
                }
            }
            // If that line contains two tokens out of three...
            if ($value == 2) {
                // Check if the potential win cell is empty
                if ($this->position[$cell_loc] == '-') {
                    // That cell is empty.  Take it for the win or block.
                    return $cell_loc;
                }
            }
        }
        //  Checking is done found there's no moves left.
        return -1;
    }

}
?>