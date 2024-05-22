<?php

$board = [];

$movements = [
    // 1. move right-down
    [2, 1],
    // 2. move right-up
    [2, -1],
    // 3. move down-right
    [1, 2],
    // 4. move down-left
    [-1, 2],
    // 5. move left-down
    [-2, 1],
    // 6. move left-up
    [-2, -1],
    // 7. move up-left
    [-1, -2],
    // 8. move up-right
    [1, -2],
];

$startX = rand(1, 8);
$startY = rand(1, 8);

// call the main program
findRoute($startX, $startY);

// print the result
echo count($board) . PHP_EOL;
echo json_encode($board);

echo PHP_EOL;
for ($i = 1; $i <= 8; $i++) {
    foreach ($board as $key => $value) {
        if ($value[0] == $i) {
            echo sprintf('%02d', $key + 1) . ' ';
        }
    }

    echo PHP_EOL;
}


function findRoute($startX, $startY)
{
    global $board, $movements;

    // put location into board
    $board[] = [$startX, $startY];

    if (count($board) >= 64) {
        return;
    }

    // count the movements for each option
    $options = [0, 0, 0, 0, 0, 0, 0, 0];

    foreach ($movements as $key => $movement) {
        $newX = $startX + $movement[0];
        $newY = $startY + $movement[1];
        if (isValidMove($newX, $newY) && isCoordOpen([$newX, $newY])) {
            $options[$key] = countOptionsAhead($newX, $newY);
        }
    }

    // sort array by value descending
    arsort($options);


    foreach ($options as $key => $option) {
        // skip if we already know there are no movements
        if ($option == 0) {
            continue;
        }

        // find route on the next move
        $newX = $startX + $movements[$key][0];
        $newY = $startY + $movements[$key][1];
        
        findRoute($newX, $newY);
    }

    // remove last element as this route reached a dead end
    array_pop($board);
}


function isValidMove($x, $y) {
    return isValidValue($x) && isValidValue($y);
}


function isValidValue($value)
{
    return $value >= 1 && $value <= 8;
}

function isCoordOpen($coord)
{
    global $board;

    return ! in_array($coord, $board);
}

function countOptionsAhead($x, $y)
{
    global $movements;

    $count = 0;

    foreach ($movements as $movement) {
        if (isCoordOpen([$x + $movement[0], $y + $movement[1]])) {
            $count++;
        }
    }

    return $count;
}
