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

// -------------------
function showCalendar(int $year, int $month, int $monthCount): void {
    $dateStart = mktime(0, 0, 0, $month, 1, $year);

    for ($i = 0; $i < $monthCount; $i++) {
        $dateMonth = strtotime('+' . $i . ' month', $dateStart);
        showCalendarHeader($dateMonth);

        $weekDay = (int) date('w', $dateMonth);
        echo str_repeat("\t", $weekDay === 0 ? 6 : $weekDay - 1);

        $weekDay = $weekDay === 0 ? 7 : $weekDay;
        for ($j = 1; $j <= (int) date('t', $dateMonth); $j++) {
            if ($weekDay > 7) {
                $weekDay = 1;
                echo "\n";
            }

            $date = $j < 10 ? " $j" : $j;
            $result = getDayStatus($dateMonth, $j, $weekDay);

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
function getDayStatus(int $dateMonth, int $j, int $weekDay): int {
    $currentDate = new DateTime('@' . strtotime('+' . ($j - 1) . ' day', $dateMonth));

    if ($weekDay > 5 || in_array($currentDate->format('*-m-d'), HOLIDAYS)) {
        return -1;
    } if ($weekDay > 15) {
        return 1;
    } else {
        return 0;
    }
}
