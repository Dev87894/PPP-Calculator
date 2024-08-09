<?php
error_reporting(0);

// Dynamically detect the base URL
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $protocol = "https://";
} else {
    $protocol = "http://";
}
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . $host . '/';

// Append the directory name if the application is not running at the document root
if($host == 'localhost'){
    $directory = 'ppp_calculator'; // Change this to match your directory name
}else{
    $directory = 'ppp'; // Change this to match your directory name
}

$base_url .= $directory . '/';

// Use the base URL for static file paths
$BASE_URL = $base_url;

//All the languages supported by the application
$languages = array(
    "eng" => array(
				"no" => 1, 
				"name" => "English"
			),
    "ben" => array(
				"no" => 1, 
				"name" => "Bengali"
			),
	"es" => array(
				"no" => 2, 
				"name" => "Spanish"
			),
	"ara" => array(
				"no" => 3, 
				"name" => "Arabic"
    ),
    "hin" => array(
                "no" => 4,
                "name" => "Hindi"
            ),
);

// Function to establish database connection
function connectToDatabase() {

    if($_SERVER['HTTP_HOST'] == 'localhost') {
        $host = 'localhost';
        $dbname = 'pppcalc';
        $username = 'root';
        $password = '';
    }else{
        $host = "localhost";
        $dbname = "pppcalc";
        $username = "bank_admin";
        $password = "banksforyou0Com";
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error connecting to database: " . $e->getMessage());
    }
}

// Function to fetch all users from the database
function getAllUsers($where = null) {
    $pdo = connectToDatabase();
    if($where == null) {
        $stmt = $pdo->query("SELECT countries_ppp.country_name, country.code3l, country.code2l, countries_ppp.currency_symbol, currency_names.currency_name FROM `countries_ppp` INNER JOIN country ON countries_ppp.country_code = country.code3l LEFT JOIN currency_names ON LOWER(TRIM(countries_ppp.country_name)) = LOWER(TRIM(currency_names.country_name)) ORDER by country_name ASC");
    } else {
        $querytoFetchCountries = "SELECT countries_ppp.country_name, country.code3l, country.code2l, countries_ppp.currency_symbol, currency_names.currency_name FROM `countries_ppp` INNER JOIN country ON countries_ppp.country_code = country.code3l LEFT JOIN currency_names ON LOWER(TRIM(countries_ppp.country_name)) = LOWER(TRIM(currency_names.country_name)) WHERE code3l != '".$where."' ORDER by country_name ASC";
        $stmt = $pdo->query($querytoFetchCountries);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function splitCountries($countryArray) {
    $pdo = connectToDatabase();
    $countries = explode('-vs-', $countryArray);

$sql = "SELECT 
countries_ppp.country_name, 
country.code3l, 
country.code2l, 
countries_ppp.currency_symbol, 
CASE 
        WHEN countries_ppp_values.year_2022 <> 0 THEN countries_ppp_values.year_2022
        WHEN countries_ppp_values.year_2021 <> 0 THEN countries_ppp_values.year_2021 
        WHEN countries_ppp_values.year_2014 <> 0 THEN countries_ppp_values.year_2014
        WHEN countries_ppp_values.year_2013 <> 0 THEN countries_ppp_values.year_2013
    END AS ppp_value,
currency_names.currency_name,
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

if(!isset($_GET['salary'])){
    $salary = 10000;
}else{
    $salary = str_replace(',', '', $_GET['salary']);
}

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$salary, $countries[0], $countries[1], $countries[0], $countries[1], $countries[0], $countries[1]]);    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function createUrl($lang, $sourceCountry3l, $targetCountry3l) {
     if($_GET['salary'] == '' && !isset($_GET['salary'])){ $salaryParam = ''; }else{ $salaryParam = '?salary=' . $_GET['salary'];}
     if($_SERVER['HTTP_HOST'] == 'localhost'){
        return $protocol . $_SERVER['HTTP_HOST']. '/ppp_calculator/' . $lang . '/' .  strtolower($sourceCountry3l) . '-vs-' . strtolower($targetCountry3l) .$salaryParam;
     }else{
        return $protocol . $_SERVER['HTTP_HOST']. '/ppp/' . $lang . '/' .  strtolower($sourceCountry3l) . '-vs-' . strtolower($targetCountry3l) .$salaryParam;
     }
    }


// print_r($_GET);
if(isset($_GET['country']) && $_GET['country'] != '') {
    $getCountryDetails = splitCountries($_GET['country']);
}else{
    $getCountryDetails = splitCountries('ind-vs-usa');
}

// SELECT 
//     countries_ppp.id, 
//     countries_ppp.country_name, 
//     countries_ppp.currency_symbol, 
//     countries_ppp.country_code, 
//     countries_ppp_values.year_1990,
//     countries_ppp_values.year_1991,
//     countries_ppp_values.year_1992,
//     countries_ppp_values.year_1993,
//     countries_ppp_values.year_1994,
//     countries_ppp_values.year_1995,
//     countries_ppp_values.year_1996,
//     countries_ppp_values.year_1997,
//     countries_ppp_values.year_1998,
//     countries_ppp_values.year_1999,
//     countries_ppp_values.year_2000,
//     countries_ppp_values.year_2001,
//     countries_ppp_values.year_2002,
//     countries_ppp_values.year_2003,
//     countries_ppp_values.year_2004,
//     countries_ppp_values.year_2005,
//     countries_ppp_values.year_2006,
//     countries_ppp_values.year_2007,
//     countries_ppp_values.year_2008,
//     countries_ppp_values.year_2009,
//     countries_ppp_values.year_2010,
//     countries_ppp_values.year_2011,
//     countries_ppp_values.year_2012,
//     countries_ppp_values.year_2013,
//     countries_ppp_values.year_2014,
//     countries_ppp_values.year_2015,
//     countries_ppp_values.year_2016,
//     countries_ppp_values.year_2017,
//     countries_ppp_values.year_2018,
//     countries_ppp_values.year_2019,
//     countries_ppp_values.year_2020,
//     countries_ppp_values.year_2021,
//     countries_ppp_values.year_2022
// FROM 
//     `countries_ppp` 
// INNER JOIN 
//     countries_ppp_values ON countries_ppp.country_code = countries_ppp_values.CountryCode
//     ORDER BY id ASC;
?>

