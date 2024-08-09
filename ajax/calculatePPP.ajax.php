<?php
require '../internal/db_connect.int.php';

if(!str_contains($BASE_URL, 'localhost') && !str_contains($BASE_URL, 'tools.banksforyou.com')){
    header('Location: '.$BASE_URL);
    exit;
}

$_POST = json_decode(file_get_contents("php://input"));
// print_r($_POST);

$salary = strlen($_POST->salary) > 27 ? substr($_POST->salary, 0, 27) : $_POST->salary;
$year = $_POST->year;
$targetCountry = $_POST->targetCountry;
$sourceCountry = $_POST->sourceCountry;

// print_r($year);

$conn = connectToDatabase();

// $query = "SELECT 
//             (SELECT currency_symbol FROM countries_ppp WHERE country_code = ?) AS currency_symbol, 
//             (? / (SELECT year_? FROM countries_ppp_values WHERE CountryCode = ? LIMIT 1)) * 
//             (SELECT year_? FROM countries_ppp_values WHERE CountryCode = ? LIMIT 1) AS ppp_calculation 
//           FROM 
//             countries_ppp 
//           LIMIT 1";


// $query = "SELECT 
//             (SELECT currency_symbol FROM countries_ppp WHERE country_code = ?) AS currency_symbol, 
//             (? / (
//                 SELECT CASE 
//                     WHEN countries_ppp_values.year_2022 <> 0 THEN countries_ppp_values.year_2022 
//                     WHEN countries_ppp_values.year_2021 <> 0 THEN countries_ppp_values.year_2021 
//                     WHEN countries_ppp_values.year_2014 <> 0 THEN countries_ppp_values.year_2014 
//                     WHEN countries_ppp_values.year_2013 <> 0 THEN countries_ppp_values.year_2013 
//                 END 
//                 FROM countries_ppp_values 
//                 WHERE CountryCode = ? 
//                 LIMIT 1
//             )) * (
//                 SELECT CASE 
//                     WHEN countries_ppp_values.year_2022 <> 0 THEN countries_ppp_values.year_2022 
//                     WHEN countries_ppp_values.year_2021 <> 0 THEN countries_ppp_values.year_2021 
//                     WHEN countries_ppp_values.year_2014 <> 0 THEN countries_ppp_values.year_2014 
//                     WHEN countries_ppp_values.year_2013 <> 0 THEN countries_ppp_values.year_2013 
//                 END 
//                 FROM countries_ppp_values 
//                 WHERE CountryCode = ? 
//                 LIMIT 1
//             ) AS ppp_calculation 
//           FROM 
//             countries_ppp 
//           LIMIT 1";


$pdo = connectToDatabase();

$sql = "SELECT
countries_ppp.country_name,
country.code3l,
country.code2l,
countries_ppp.currency_symbol,
currency_names.currency_name,
CASE  
                WHEN countries_ppp_values.year_2022 <> 0 THEN countries_ppp_values.year_2022 
                WHEN countries_ppp_values.year_2021 <> 0 THEN countries_ppp_values.year_2021 
                WHEN countries_ppp_values.year_2014 <> 0 THEN countries_ppp_values.year_2014 
                WHEN countries_ppp_values.year_2013 <> 0 THEN countries_ppp_values.year_2013 
END AS index_val,
COALESCE(
    CASE
        WHEN countries_ppp_values.year_2022 <> 0 THEN 2022 
        WHEN countries_ppp_values.year_2021 <> 0 THEN 2021 
        WHEN countries_ppp_values.year_2014 <> 0 THEN 2014 
        WHEN countries_ppp_values.year_2013 <> 0 THEN 2013 
    END,
    0
) AS non_zero_year,
CASE 
    WHEN @row_number = 1 THEN 
        (? / (
            SELECT CASE  
                WHEN countries_ppp_values.year_2022 <> 0 THEN countries_ppp_values.year_2022 
                WHEN countries_ppp_values.year_2021 <> 0 THEN countries_ppp_values.year_2021 
                WHEN countries_ppp_values.year_2014 <> 0 THEN countries_ppp_values.year_2014 
                WHEN countries_ppp_values.year_2013 <> 0 THEN countries_ppp_values.year_2013 
            END 
            FROM countries_ppp_values 
            WHERE CountryCode = ? 
            LIMIT 1
        )) * (
            SELECT CASE 
                WHEN countries_ppp_values.year_2022 <> 0 THEN countries_ppp_values.year_2022 
                WHEN countries_ppp_values.year_2021 <> 0 THEN countries_ppp_values.year_2021 
                WHEN countries_ppp_values.year_2014 <> 0 THEN countries_ppp_values.year_2014 
                WHEN countries_ppp_values.year_2013 <> 0 THEN countries_ppp_values.year_2013 
            END 
            FROM countries_ppp_values 
            WHERE CountryCode = ? 
            LIMIT 1
        )
    ELSE NULL
END AS ppp_calculation,
@row_number := @row_number + 1 AS row_number
FROM 
(SELECT @row_number := 0) AS init
, countries_ppp 
INNER JOIN 
country ON countries_ppp.country_code = country.code3l 
LEFT JOIN 
currency_names ON LOWER(TRIM(countries_ppp.country_name)) = LOWER(TRIM(currency_names.country_name))
LEFT JOIN
countries_ppp_values ON countries_ppp.country_code = countries_ppp_values.CountryCode
WHERE
country_code IN (?, ?)
ORDER BY 
FIELD(country_code, ?, ?);";

// print_r($sql);
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$salary, $sourceCountry, $targetCountry, $sourceCountry, $targetCountry, $sourceCountry, $targetCountry]);    
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo "<pre>";
    // exit;
    
    // $stmt = $conn->prepare($query);
    // $stmt->execute([$targetCountry, $salary, $sourceCountry, $targetCountry]);
    // $res = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    
    // print_r($res);
    // exit;

$ppp_calculation = $res[0]['ppp_calculation'] !== null ? $res[0]['ppp_calculation'] : $res[1]['ppp_calculation'];
$year = $res[0]['non_zero_year'] < $res[1]['non_zero_year'] ? $res[0]['non_zero_year'] : $res[1]['non_zero_year'];

// if ($ppp_calculation == 0) {
    // $query = "SELECT 
    //             (SELECT currency_symbol FROM countries_ppp WHERE country_code = ?) AS currency_symbol, 
    //             (? / (SELECT year_2021 FROM countries_ppp_values WHERE CountryCode = ? LIMIT 1)) * 
    //             (SELECT year_2021 FROM countries_ppp_values WHERE CountryCode = ? LIMIT 1) AS ppp_calculation 
    //           FROM 
    //             countries_ppp 
    //           LIMIT 1";
    // $query = "SELECT
    // countries_ppp.country_name,
    // country.code3l,
    // country.code2l,
    // countries_ppp.currency_symbol, 
    // currency_names.currency_name,
    // CASE 
    //     WHEN @row_number = 1 THEN 
    //         (? / (
    //             SELECT CASE  
    //                 WHEN countries_ppp_values.year_2022 <> 0 THEN countries_ppp_values.year_2022 
    //                 WHEN countries_ppp_values.year_2021 <> 0 THEN countries_ppp_values.year_2021 
    //                 WHEN countries_ppp_values.year_2014 <> 0 THEN countries_ppp_values.year_2014 
    //                 WHEN countries_ppp_values.year_2013 <> 0 THEN countries_ppp_values.year_2013 
    //             END 
    //             FROM countries_ppp_values 
    //             WHERE CountryCode = ? 
    //             LIMIT 1
    //         )) * (
    //             SELECT CASE 
    //                 WHEN countries_ppp_values.year_2022 <> 0 THEN countries_ppp_values.year_2022 
    //                 WHEN countries_ppp_values.year_2021 <> 0 THEN countries_ppp_values.year_2021 
    //                 WHEN countries_ppp_values.year_2014 <> 0 THEN countries_ppp_values.year_2014 
    //                 WHEN countries_ppp_values.year_2013 <> 0 THEN countries_ppp_values.year_2013 
    //             END 
    //             FROM countries_ppp_values 
    //             WHERE CountryCode = ? 
    //             LIMIT 1
    //         )
    //     ELSE NULL
    // END AS ppp_calculation,
    // @row_number := @row_number + 1 AS row_number
    // FROM 
    // (SELECT @row_number := 0) AS init
    // , countries_ppp 
    // INNER JOIN 
    // country ON countries_ppp.country_code = country.code3l 
    // LEFT JOIN 
    // currency_names ON LOWER(TRIM(countries_ppp.country_name)) = LOWER(TRIM(currency_names.country_name))
    // LEFT JOIN
    // countries_ppp_values ON countries_ppp.country_code = countries_ppp_values.CountryCode
    // WHERE 
    // country_code IN (?, ?)
    // ORDER BY 
    // FIELD(country_code, ?, ?);";

    // $stmt = $conn->prepare($query);
    // $stmt->execute([$targetCountry, $salary, $sourceCountry, $targetCountry]);
    // $res = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    // $year = 2021;

    // if($res['ppp_calculation'] == 0){
    //     // $query = "SELECT (SELECT currency_symbol FROM countries_ppp WHERE country_code = '".$targetCountry."') AS currency_symbol, (".$salary."/(SELECT year_2014 FROM countries_ppp WHERE country_code = '".$sourceCountry."' LIMIT 1))*(SELECT year_2014 FROM countries_ppp WHERE country_code = '".$targetCountry."' LIMIT 1) AS ppp_calculation FROM countries_ppp LIMIT 1";
    //     $query = "SELECT (SELECT currency_symbol FROM countries_ppp WHERE country_code = ?) AS currency_symbol, (? / (SELECT year_2014 FROM countries_ppp_values WHERE CountryCode = ? LIMIT 1)) * (SELECT year_2014 FROM countries_ppp_values WHERE CountryCode = ? LIMIT 1) AS ppp_calculation FROM countries_ppp LIMIT 1";
    //     $sourceCountry = $conn->query($query);
    //     $sourceCountry->execute([$targetCountry, $salary, $sourceCountry, $targetCountry]);
    //     $res = $sourceCountry->fetchAll(PDO::FETCH_ASSOC)[0];
    //     $year = 2014;
    // }
// }

// print_r($res);
// exit;

echo json_encode(array("currency" => $res[1]['currency_symbol'],
                      "nonRounded" => $ppp_calculation,
                      "rounded" => number_format($ppp_calculation, 2), 
                      "year" => $year));
// echo json_encode($res);
// exit;

?>