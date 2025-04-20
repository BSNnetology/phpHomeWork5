<?php

$month = 4;
$year = 2025;
$monthsCount = 3;

system('clear'); // system('cls'); // windows
const HOLIDAYS = ['*-01-01', '*-01-07', '*-03-08', '*-05-01', '*-05-09', '*-06-12', '*-11-04'];

if ($year < 2000 || $year > 2050) {
    echo "Некорректно указан год (2000 - 2050)...\n";
    exit;
} elseif ($month < 1 || $month > 12) {
    echo "Некорректно указан месяц (1 - 12)...\n";
    exit;
} elseif ($monthsCount < 1 || $monthsCount > 12) {
    echo "Некорректно указано количество месяцев (1 - 12)...\n";
    exit;
}

showCalendar($year, $month, $monthsCount);
echo "\n";

// -------------------
function showCalendar(int $year, int $month, int $monthsCount): void {
    $dateStart = mktime(0, 0, 0, $month, 1, $year);   
    $workDay = 1;

    for ($i = 0; $i < $monthsCount; $i++) {
        $currentMonth = strtotime('+' . $i . ' month', $dateStart);
        showCalendarHeader($currentMonth);

        $workDay = isset($daysInMouth) ? $workDay - $daysInMouth : 1;
        $daysInMouth = (int) date('t', $currentMonth);
        $weekDay = (int) date('w', $currentMonth);

        echo str_repeat("\t", $weekDay === 0 ? 6 : $weekDay - 1);
        $weekDay = $weekDay === 0 ? 7 : $weekDay;

        for ($j = 1; $j <= $daysInMouth; $j++) {
            if ($weekDay > 7) {
                $weekDay = 1;
                echo "\n";
            }
            
            $printDate = $j < 10 ? " $j" : $j;
            $result = getDayStatus($currentMonth, $j, $weekDay, $workDay);
            showCalendarDay($printDate, $result);

            $weekDay++;
        }
        echo "\n";
    }
}

// -------------------
function showCalendarHeader(int $currentMonth): void {
    echo "\n" . date("F Y", $currentMonth) . "\n";
    echo "Mо\tTo\tWe\tTh\tFr\t";
    echo "\033[31mSa\tSu\033[0m\n";
}

// -------------------
function showCalendarDay(string $printDate, int $result): void {
    switch ($result) {
        case -1:
            echo "\033[31m$printDate\t\033[0m";
            break;
        case 1:
            echo "\033[32m$printDate\t\033[0m";;
            break;
        default:
            echo "$printDate\t";
            break;
    }
}

// -------------------
function getCurrentDate(int $day, int $currentMonth): DateTime {
    return new DateTime('@' . strtotime('+' . ($day - 1) . ' day', $currentMonth));
}

// -------------------
function checkHoligay(int $weekDay, DateTime $currentDate): bool {
    return ($weekDay > 5) || (in_array($currentDate->format('*-m-d'), HOLIDAYS));
}

// -------------------
function getDayStatus(int $currentMonth, int $day, int $weekDay, int &$workDay): int {   
    $currentDate = getCurrentDate($day, $currentMonth);
    $isHoliday = checkHoligay($weekDay, $currentDate);
    $isWorkDay = ($day === $workDay);

    if ($isWorkDay && $isHoliday) {
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

