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
        }
    }

    /*
     * ******************************************************************
     *  Display the Board for the Game
     * ******************************************************************
     */

    function display($show) {
        echo '<table cols=”3” style=”fontsize:large; fontweight:bold”>';
        echo '<tr>'; // open the first row
        for ($pos = 0; $pos < 9; $pos++) {
            //  To show or not to show the cells
            if ($show) {
                echo $this->show_cell($pos);
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
            return '<td>' . $token . '</td>';
        }
        // now the hard case
        $this->newposition = $this->position;  // copy the original
        $this->newposition[$which] = 'o';      // this would be their move
        $move = implode($this->newposition);   // make a string from the board array 
        $link = '?board=' . $move;             // this is what we want the link to be
        // so return a cell containing an anchor and showing a hyphen
        return '<td><a href=' . $link . '>-</a></td>';
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
                return $won;
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
                return $won;
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
        return $won;
    }

    /*
     * ******************************************************************
     *  The logic behind the tic tac toe AI
     * ******************************************************************
     */

    function pick_move() {
        //  Interates through the board
        for ($block = 0; $block < 8; $block++) {
            //  if there's a block with a - put an X
            if ($this->position[$block] == '-') {
                $this->position[$block] = 'x';
            }
        }
    }

    /*
     * ******************************************************************
     *  The logic behind the tic tac toe AI
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

}
?>