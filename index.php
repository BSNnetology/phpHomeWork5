<?php

$month = 4;
$year = 2025;
$monthCount = 3;

system('clear'); // system('cls'); // windows
const HOLIDAYS = ['*-01-01', '*-01-07', '*-03-08', '*-05-01', '*-05-09', '*-06-12', '*-11-04'];

if ($year < 2000 || $year > 2050) {
    echo "Некорректно указан год (2000 - 2050)...\n";
    exit;
} elseif ($month < 1 || $month > 12) {
    echo "Некорректно указан месяц (1 - 12)...\n";
    exit;
} elseif ($monthCount < 1 || $monthCount > 12) {
    echo "Некорректно указано количество месяцев (1 - 12)...\n";
    exit;
}

showCalendar($year, $month, $monthCount);
echo "\n";

// -------------------
function showCalendar(int $year, int $month, int $monthCount): void {
    $dateStart = mktime(0, 0, 0, $month, 1, $year);   
    $workDay = 1;

    for ($i = 0; $i < $monthCount; $i++) {
        $dateMonth = strtotime('+' . $i . ' month', $dateStart);
        showCalendarHeader($dateMonth);

        $workDay = isset($daysInMouth) ? $workDay - $daysInMouth : 1;
        $daysInMouth = (int) date('t', $dateMonth);
        $weekDay = (int) date('w', $dateMonth);

        echo str_repeat("\t", $weekDay === 0 ? 6 : $weekDay - 1);
        $weekDay = $weekDay === 0 ? 7 : $weekDay;

        for ($j = 1; $j <= (int) date('t', $dateMonth); $j++) {
            if ($weekDay > 7) {
                $weekDay = 1;
                echo "\n";
            }
            
            $date = $j < 10 ? " $j" : $j;
            $result = getDayStatus($dateMonth, $j, $weekDay, $workDay);
            showCalendarDay($date, $result);

            $weekDay++;
        }
        echo "\n";
    }
}

// -------------------
function showCalendarHeader(int $dateMonth): void {
    echo "\n" . date("F Y", $dateMonth) . "\n";
    echo "Mо\tTo\tWe\tTh\tFr\t";
    echo "\033[31mSa\tSu\033[0m\n";
}

// -------------------
function getCurrentDate(int $j, int $dateMonth): DateTime {
    return new DateTime('@' . strtotime('+' . ($j - 1) . ' day', $dateMonth));
}

// -------------------
function showCalendarDay(string $date, int $result): void {
    switch ($result) {
        case -1:
            echo "\033[31m$date\t\033[0m";
            break;
        case 1:
            echo "\033[32m$date\t\033[0m";;
            break;
        default:
            echo "$date\t";
            break;
    }
}

// -------------------
function checkHoligay(int $weekDay, DateTime $currentDate): bool {
    return ($weekDay > 5) || (in_array($currentDate->format('*-m-d'), HOLIDAYS));
}

// -------------------
function getDayStatus(int $dateMonth, int $j, int $weekDay, int &$workDay): int {   
    $currentDate = getCurrentDate($j, $dateMonth);
    $isHoliday = checkHoligay($weekDay, $currentDate);
    $isWorkDay = ($j === $workDay);

    if (($j === $workDay) && $isHoliday) {
        $workDay++;
    }

    if ($isHoliday ) {
        return -1;
    }  elseif ($isWorkDay) {
        $workDay += 3;
        return 1;
    } else {
        return 0;
    }
}

