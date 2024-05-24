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

$squaresToCenter = 7/2;

$startX = rand(1, 8);
$startY = rand(1, 8);

$startTime = microtime(true);

// call the main program
findRoute($startX, $startY);

$endTime = microtime(true);

// print the result
echo PHP_EOL . PHP_EOL;
echo 'Start time: ' . $startTime . PHP_EOL;
echo 'End time: ' . $endTime . PHP_EOL;
echo 'Total time: ' . ($endTime - $startTime);
echo PHP_EOL . PHP_EOL;

echo PHP_EOL;
for ($i = 1; $i <= 8; $i++) {
    for ($j = 1; $j <= 8; $j++) {
        if ($boardKey = array_search([$i, $j], $board) !== false) {
            echo sprintf('%02d', array_search([$i, $j], $board) + 1) . ' ';
        }
        else {
            echo '-- ';
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
        return true;
    }

    // get the distance to the center from each possible movement
    $options = [0, 0, 0, 0, 0, 0, 0, 0];

    foreach ($movements as $key => $movement) {
        $newX = $startX + $movement[0];
        $newY = $startY + $movement[1];
        if (isValidMove($newX, $newY) && isCoordOpen([$newX, $newY])) {
            $options[$key] = distanceToCenter($newX, $newY);
        }
    }

    // sort array by value descending
    arsort($options);


    foreach ($options as $key => $option) {
        // skip if distance is 0, as it was not an allowed move
        if ($option == 0) {
            continue;
        }

        // find route on the next move
        $newX = $startX + $movements[$key][0];
        $newY = $startY + $movements[$key][1];
        
        $result = findRoute($newX, $newY);

        if ($result) {
            return true;
        }
    }

    // remove last element as this route reached a dead end
    array_pop($board);

    return false;
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

function distanceToCenter($x, $y)
{
    global $squaresToCenter;

    // distance to center
    $distanceX = abs($squaresToCenter - $x);
    $distanceY = abs($squaresToCenter - $y);

    return sqrt(pow($distanceX, 2) + pow($distanceY, 2));
}
