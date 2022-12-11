<?php
// header('Content-Type: text/html; charset=utf-8');
// echo mb_internal_encoding();
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

/**
 * getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО.
 * Возвращает как результат массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronomyc’.
 * Пример: как аргумент принимается строка «Иванов Иван Иванович»,
 * а возвращается массив [‘surname’ => ‘Иванов’ ,‘name’ => ‘Иван’, ‘patronomyc’ => ‘Иванович’].
 */
function getPartsFromFullname($string)
{
    $arr = explode(' ', $string);
    return ['surname' => $arr['0'], 'name' => $arr['1'], 'patronomyc' => $arr['2']];
}

/**
 * getFullnameFromParts принимает как аргумент три строки — фамилию, имя и отчество. Возвращает как результат их же, но склеенные через пробел.
 * Пример: как аргументы принимаются три строки «Иванов», «Иван» и «Иванович», а возвращается одна строка — «Иванов Иван Иванович».
 */
function getFullnameFromParts($surname, $name, $patronomyc)
{
    return $surname . ' ' . $name . ' ' . $patronomyc;
}


/**
 * getShortName, принимающую как аргумент строку, содержащую ФИО вида «Иванов Иван Иванович» и возвращающую строку вида «Иван И.»,
 * где сокращается фамилия и отбрасывается отчество.
 * Для разбиения строки на составляющие используйте функцию getPartsFromFullname.
 */
function getShortName($string)
{
    $arr = getPartsFromFullname($string);
    return $arr['name'] . ' ' . mb_substr($arr['surname'], 0, 1) . '.';
}

/**
 * Разработайте функцию getGenderFromName, принимающую как аргумент строку, содержащую ФИО (вида «Иванов Иван Иванович»). 
 */
function getGenderFromName($string)
{
    $arr = getPartsFromFullname($string);
    $genderIndication = 0;
    if (mb_substr($arr['surname'], -2) === 'ва') {
        $genderIndication--;
    }
    if (mb_substr($arr['name'], -1) === 'а') {
        $genderIndication--;
    }
    if (mb_substr($arr['patronomyc'], -3) === 'вна') {
        $genderIndication--;
    }
    if (mb_substr($arr['surname'], -1) === 'в') {
        $genderIndication++;
    }
    if (mb_substr($arr['name'], -1) === 'й' || mb_substr($arr['name'], -1) === 'н') {
        $genderIndication++;
    }
    if (mb_substr($arr['patronomyc'], -2) === 'ич') {
        $genderIndication++;
    }
    return $genderIndication <=> 0;
}

/**
 * Напишите функцию getGenderDescription для определения полового состава аудитории.
 * Как аргумент в функцию передается массив, схожий по структуре с массивом $example_persons_array.
 */
function getGenderDescription($arr)
{
    $arrPersons = []; //['man' => 0, 'woman' => 0, 'indefinite' => 0];
    $men = array_filter($arr, function ($string) {
        return getGenderFromName($string['fullname']) === 1;
    });
    $woman = array_filter($arr, function ($string) {
        return getGenderFromName($string['fullname']) === -1;
    });
    $indefinite = array_filter($arr, function ($string) {
        return getGenderFromName($string['fullname']) === 0;
    });
    $arrPersons['men'] = round(count($men) / count($arr) * 100, 1);
    $arrPersons['woman'] = round(count($woman) / count($arr) * 100, 1);
    $arrPersons['indefinite'] = round(count($indefinite) / count($arr) * 100, 1);
    echo 'Гендерный состав аудитории:<br>';
    echo '--------------------------------------    <br>';
    echo "Мужчины - {$arrPersons['men']}%<br>";
    echo "Женщины - {$arrPersons['woman']}%<br>";
    echo "Не удалось определить - {$arrPersons['indefinite']}%<br>";
}

/**
 * Напишите функцию getPerfectPartner для определения «идеальной» пары.
 */
function getPerfectPartner($surname, $name, $patronomyc, $arr)
{
    $surname = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE, 'UTF-8');
    $name = mb_convert_case($name, MB_CASE_TITLE_SIMPLE, 'UTF-8');
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE, 'UTF-8');
    $men = getShortName(getFullnameFromParts($surname, $name, $patronomyc));
    $womans = array_filter($arr, function ($string) {
        return getGenderFromName($string['fullname']) === -1;
    });
    $randomWoman = getShortName($womans[array_rand($womans, 1)]['fullname']);
    function randomFloat()
    {
        $int = mt_rand(50, 100);
        return $int === 100 ? $int . '%' : $int + mt_rand(50, 99) / 100 . '%';
    }
    echo "$men + $randomWoman =<br>♡ Идеально на " . randomFloat() . ' ♡';
}

/**
 * Функция для выбора случайного музчины
 */
function getRandomMen($arr)
{
    $men = array_filter($arr, function ($string) {
        return getGenderFromName($string['fullname']) === 1;
    });
    $getFullnameFromParts = getPartsFromFullname($men[array_rand($men, 1)]['fullname']);
    return $getFullnameFromParts;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Модуль 12. Типы данных. Практическая работа</title>
</head>

<body>
    <div class="container">
        <div>
            <?php getGenderDescription($example_persons_array); ?>
        </div>
        <div>
            <?php
                $men = getRandomMen($example_persons_array);
                getPerfectPartner($men['surname'], $men['name'], $men['patronomyc'], $example_persons_array);
            ?>
        </div>
    </div>
</body>

</html>