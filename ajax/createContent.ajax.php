<?php
require '../internal/db_connect.int.php';


if(!str_contains($BASE_URL, 'localhost') && !str_contains($BASE_URL, 'tools.banksforyou.com')){
    header('Location: '.$BASE_URL);
    exit;
}

$_POST = json_decode(file_get_contents("php://input"));

// print_r("SELECT countries_ppp.country_name, country.code3l, country.code2l, countries_ppp.currency_symbol, currency_names.currency_name FROM `countries_ppp` INNER JOIN country ON countries_ppp.country_code = country.code3l LEFT JOIN currency_names ON LOWER(TRIM(countries_ppp.country_name)) = LOWER(TRIM(currency_names.country_name)) WHERE 3l != '".strtolower(htmlspecialchars($_POST['selectedCountryShort']))."' ORDER by country_name ASC");
// print_r("SELECT countries_ppp.country_name, country.code3l, country.code2l, countries_ppp.currency_symbol, currency_names.currency_name FROM `countries_ppp` INNER JOIN country ON countries_ppp.country_code = country.code3l LEFT JOIN currency_names ON LOWER(TRIM(countries_ppp.country_name)) = LOWER(TRIM(currency_names.country_name)) WHERE 3l != '".strtolower(htmlspecialchars($_POST->selectedCountryShort))."' ORDER by country_name ASC");
// exit;

// if(isset($_GET['country']) && $_GET['country'] != '') {
//     $getCountryDetails = splitCountries($_GET['country']);
// }else{
//     $getCountryDetails = splitCountries('ind-vs-usa');
// }
// print_r($_POST);

$arr;
 $countries = getAllUsers(strtolower(htmlspecialchars($_POST->selectedCountryShort)));
            // echo "<pre>";
            // print_r($getCountryDetails);
            // exit;
                foreach ($countries as $country) { 
                    $arr .= '<a href="https://' . createURL($_POST->language == '' ? 'eng' : $_POST->language, $_POST->selectedCountryShort, $country['code3l']) . '"><div>' . $_POST->selectedCountryName . ' vs ' . $country['country_name'] . '</div></a>';
                    $arr .= '<a href="https://' . createURL($_POST->language == '' ? 'eng' : $_POST->language, $country['code3l'], $_POST->selectedCountryShort) . '"><div>' . $country['country_name'] . ' vs ' . $_POST->selectedCountryName . '</div></a>';
                }

echo json_encode($arr);


 ?>