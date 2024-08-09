<?php include_once('internal/db_connect.int.php'); 

if(isset($_GET['country']) && ($_GET['country'] != '')){ $countryVs = $getCountryDetails[0]["code3l"] ." vs ". $getCountryDetails[1]["code3l"]; }else{$countryVs = "No Ads 🚫"; }
// Title of the current page
$page_title = "💸 #1 PPP Exact Salary Calculator | ".$countryVs;
// Description of the current page which also appears in search engine
$page_content = "A advanced PPP calculator which helps job aspiants to negotiate their salaries 🤑 with HR according to living standards 🏠. Comparing between countries ".$countryVs;
// Open Graph also can be called giving content for Social media
$og_title = "#1 Accurate PPP Salary Calculator. Comapre your salary between countries ".$countryVs;

//require '../inc/header.inc.php'; 

?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
    <head>
        <link rel="stylesheet" href="<?=$BASE_URL?>style.css">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600;900&display=swap" rel="stylesheet">
</head>
<body class="primaryColorfiveopacity">

<?php //require '../inc/navbar.inc.php'; ?>
      
    <?php //include_once('internal/nav.int.php'); 
    // print_r(isset($_GET['country']));
     ?>

    


      <main class="calculator pt-0 animate_opacity" style="width: 100%;height: 100%;position:relative;opacity:0">

        
        <section class="heroContainer" id="heroContainerId">
            <div class="heading-subheading">
                <h1 class="primaryColor ppp-title">Salary Calculator (PPP)</h1>
                <div class="source-country-to-target-title">
                    <p><?=$getCountryDetails[0]["code3l"]?></p>
                    <img draggable="false" src="<?=$BASE_URL?>assets/images/ppp-coin.svg" alt="">
                    <p><?=$getCountryDetails[1]["code3l"]?></p>
                </div>
                <div class="subtitle-ppp">
                    <h2>Convert your salary from one currency to another using PPP Calculator</h2>
                </div>
            </div>
        </section>

        

        <section class="dynamicSizeControl">
            <div>
                <div class="languageDiv" style="position: relative;" onclick="openSourceCountry(this)">
                    <div class="languagechange searchCountry" data-lang="<?=$_GET['lang'] == '' ? 'eng' : $_GET['lang']?>" style="cursor:pointer;display:flex;flex-direction:row;justify-content:center;align-items:center;background:#4FB180;width:fit-content !important;border-radius:8px;height: 3rem;position: sticky;left: 100%;padding: 0rem 0.5rem;margin-bottom: 0.7rem;pointer-events:none;opacity:0.5;cursor:not-allowed">
                        <img src="<?=$BASE_URL?>assets/images/language.svg" width="30px" alt=""> 
                        <p style="color:white;margin: 0rem 0.3rem"><?= isset($_GET['lang']) ? $languages[$_GET['lang']]["name"] : "English" ?></p>
                        <img class="arrowHand" src="<?=$BASE_URL?>assets/images/arrow.svg" style="filter: invert(100%);" width="20px" alt="">
                    </div>
                    <div class="languages CountryList hidden" style="position: absolute;text-align: center;top: 80%;background: white;z-index: 9999;border-radius: 7px;width: 20%;max-width: 9rem;min-width: 7rem;">
                        <!-- <ul style="list-style-type: none;margin: 0;padding: 0;line-height: 1.8rem;">
                            <a href="es/"><li>Spanish</li></a>
                        </ul> -->
                    </div>
                </div>

            <div class="ppp-total-boxes">
            <div class="firstDiv primaryColorfiveopacity">
                <div style="display: flex;flex-direction: column;justify-content: center">
                    <p class="enteryoursalaryHeading" style="margin-top:0rem;font-size:large">Enter Your Salary</p>
                    <div class="inputSalaryBox"><span><?=$getCountryDetails[0]['currency_symbol']?> </span><input type="text" style="width:100%" placeholder="10,000" maxlength="27" oninput="restrictInput(this)" onfocusout="formatNumber(this)" onfocusin="removeformatNumber(this.value)" id="salaryInput" value="<?=$_GET['salary'] != null ? $_GET['salary'] : ''?>"></div>
                </div>
                <div class="justifyCenter chosecountryText"><p style="width: 80%;font-weight:500;">Choose your Country for Currency Conversion as per PPP below</p></div>
                <div class="origin-target-country-innerboxes">
                    <div class="innerDiv">
                        <div class="originCountry">
                            <label class="countryLabel">Origin Country</label>
                            <div class="countryList originCountry" style="position:relative;cursor:pointer;" onclick="openSourceCountry(this, document.querySelector('.CountryList'));">
                                <div style="opacity:0;position:absolute;height: 100%;z-index: -1;width: 100%;" class="searchBox">
                                    <input type="text" class="" style="position:relative;z-index: 99;width: 100%;height: 100%;font-size: medium;background: #f6fbf8;" onkeyup="filterCountrySearch(this)">
                                </div>
                                <div class="searchCountry selectedCountry" style="background-color:#F6FBF8;padding:12px;border-radius:8px;width:100%;display:flex;align-items:center;justify-content: space-between;width: 100%;">
                                    <div class="justifyCenter"><img class="countryImage" src="<?=$BASE_URL?>assets/countryflags/PNG-32/<?=$getCountryDetails[0]['code2l']?>-32.png" alt=""></div>
                                    <div>
                                        <p class="countryName" data-short="<?=$getCountryDetails[0]['code3l']?>" style="margin:0px;padding:0px;font-size:14px;font-weight:600 "><?=$getCountryDetails[0]['country_name']?></p>
                                    </div>
                                    <div class="justifyCenter arrowHand ">
                                        <img width="15px" height="18px" src="<?=$BASE_URL?>assets/images/arrow.svg" alt="">
                                    </div>
                                </div>
                                <div class="CountryList sourceCountry hidden">
                                    <ul>
                                        <?php $countries = getAllUsers(); // Fetch data from the database
                                        foreach ($countries as $country) { ?>
                                            <li data-short="<?=$country['code3l']?>" data-symbol="<?=$country['currency_symbol']?>" data-currencyname="<?=$country['currency_name']?>"><img loading="lazy" src="<?=$BASE_URL?>assets/countryflags/PNG-32/<?=$country['code2l']?>-32.png" alt=""> <span><?=$country['country_name']?></span></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="targetCountry">
                            <label class="countryLabel">Target Country</label>
                            <div class="countryList targetCountry" style="position:relative;cursor:pointer;" onclick="openSourceCountry(this, document.querySelector('.CountryList'))">
                                <div style="opacity:0;position:absolute;height: 100%;z-index: -1;width: 100%;" class="searchBox">
                                    <input type="text" class="" style="position:relative;z-index: 99;width: 100%;height: 100%;font-size: medium;background: #f6fbf8;"  onkeyup="filterCountrySearch(this)">
                                </div>
                                <div class="searchCountry selectedCountry" style="background-color:#F6FBF8;padding:12px;border-radius:8px;width:100%;display:flex;align-items:center;justify-content: space-between;width: 100%;">
                                    <div class="justifyCenter"><img class="countryImage" src="<?=$BASE_URL?>assets/countryflags/PNG-32/<?=$getCountryDetails[1]['code2l']?>-32.png" alt=""></div>
                                    <div>
                                        <p class="countryName" data-short="<?=$getCountryDetails[1]['code3l']?>" style="margin:0px;padding:0px;font-size:14px;font-weight:600 "><?=$getCountryDetails[1]['country_name']?></p>
                                    </div>
                                    <div class="justifyCenter arrowHand ">
                                        <img width="15px" height="18px" src="<?=$BASE_URL?>assets/images/arrow.svg" alt="">
                                    </div>
                                </div>
                                <div class="CountryList sourceCountry hidden">
                                    <ul>
                                        <?php 
                                            foreach ($countries as $country) { ?>
                                            <li data-short="<?=$country['code3l']?>" data-symbol="<?=$country['currency_symbol']?>" data-currencyname="<?=$country['currency_name']?>"><img loading="lazy" src="<?=$BASE_URL?>assets/countryflags/PNG-32/<?=$country['code2l']?>-32.png" alt=""> <span><?=$country['country_name']?></span></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top: calc(5%);">
                    <h3 class="currenyToCurrencyText">
                        <span class="currencywords_first"><?=$getCountryDetails[0]['currency_name']?></span> 
                        <span class="currencysymbol_first">(<?=$getCountryDetails[0]['currency_symbol']?>)</span> = <span class="currencywords_second"><?=$getCountryDetails[1]['currency_name']?></span> 
                        <span class="currencysymbol_second">(<?=$getCountryDetails[1]['currency_symbol']?>)</span>
                    </h3>
                </div>
                <div class="calculatePPPbtnDiv">
                    <button id="CalculatePPPBtn"
                            style="display: flex;
                            height: 6vh;
                            flex-direction: row;
                            align-items: center;
                            justify-content: center;
                            width: calc(100%);
                            background: #4FB180;
                            border: none;
                            border-radius: 4px;
                            cursor:pointer">
                        <div style="margin-right: 0.5rem">
                            <img src="<?=$BASE_URL?>assets/images/Salary.svg" height="20rem" height="20rem" alt="">
                        </div>
                        <div>
                            <p style="font-size: 18px;color:white;font-weight:bold;">Calculate PPP</p>
                        </div>
                    </button>
                </div>
            </div>
        
            <div class="secondDiv" style="position:relative">
                <div>
                    <img src="<?=$BASE_URL?>assets/images/money.svg" height="50px" width="50px" alt="">
                    <p class="resultHeader">You require a salary of</p>
                    <div style="display:flex;justify-content:center;flex-direction:column;align-items: center;">
                        <div class="pillAmountDesign"><span><span class="currencysymbol_second"><?=$getCountryDetails[1]['currency_symbol']?></span> 
                        <span class="fullAmt"><?=$getCountryDetails[0]["ppp_calculation"] == NULL ? number_format($getCountryDetails[1]["ppp_calculation"], 2) : number_format($getCountryDetails[0]["ppp_calculation"], 2)?></span></span>
                        <div style="display:inline;">
                            <img src="<?=$BASE_URL."/assets/images/info.svg"?>" class="openInfoBox" width="20px" height="20px" alt="info icon" style="cursor:pointer" >
                        <div class="infoBoxDescription">
                            <p style="color: black;
                                        font-size: 0.8rem;
                                        position: relative;
                                        text-align: justify;">We're comparing economic indicators between countries for the same year, even if it's not the latest year available. This ensures consistency, but if we want to use the latest data for both countries, you'll need to switch to an non-strict mode. <i>(feature coming soon)</i>.</p></div></div> </div>
                        <p class="resultText">in <span class="targetCountryTxt"><?=$getCountryDetails[1]['country_name']?></span> to live a similar quality of life as you would live with a salary of <span class="resultTextTargetcurrencyAmt"><strong><span class="currency"><?=$getCountryDetails[0]['currency_symbol']?></span><span class="txtAmt"><?=$_GET['salary'] == '' ? "10,000" : $_GET['salary']?></span></strong></span> in <span class="sourceCountryTxt"><?=$getCountryDetails[0]['country_name']?></span></p>
                    </div>
                </div>
                <div class="shortNote"><p><span><?=$getCountryDetails[0]['non_zero_year'] < $getCountryDetails[1]['non_zero_year'] ? $getCountryDetails[0]['non_zero_year'] : $getCountryDetails[1]['non_zero_year']?></span> PPP data from World Bank</p></div>
            </div>
        </div>
        </div>
        </section>





        <article style="margin-top:4rem;width:50%" class="blogContentDiv">
            <div>
                <h2 style="font-weight: 600;font-size:1.8rem;border-bottom:1px solid black">Introduction to PPP (Purchasing Power Parity)</h2>
            </div>
            <div style="text-align:left;margin-top:1rem">
                <h3 style="font-weight: 600;font-size:1.4rem">Purchasing Power Parity In layman language</h3>
                <div style="font-size:1.2rem;line-height:1.8rem">
                    <p style="font-weight:600;font-size:1.1rem">First of all, PPP is not perfect but it is a theoretical scaling instrument that helps us understand our salary difference using the exchange rates provided and cost of goods in the particular country.</p>
                    <p><i>In simpler terms, to calculate which things we can afford if our salaries remain the same in the same country where I want to buy things.</i></p><br/>
                    <p>Let me clear your confusion by presenting you with an example here how it goes, Imagine you have a <span class="salaryfield" style="font-weight:600;"><?=$getCountryDetails[0]['currency_symbol']?> 10,000</span> salary, you want to move abroad to the <span class="targetCountryTxt" style="font-weight:600;"><?=$getCountryDetails[1]['country_name']?></span>, should you directly convert your currency to check how much you should be paid ?</p>
                    <p>Well actually No, let's check with a burger, check if the price of 🍔<b style="font-weight:600">Burger</b> is the same in the <span class="targetCountryTxt"><?=$getCountryDetails[1]['country_name']?></span> as you have right now in <span class="sourceCountryTxt"><?=$getCountryDetails[0]['country_name']?></span>. Even if you have tried to convert the price of it, you will find it slightly expensive or affordable so are you convinced now why exchange rates are not correct to check your salary.</p>
                    <p>So instead of checking exchange rates use Purchasing power parity values provided by 🌐<a href="https://data.worldbank.org/indicator/PA.NUS.PPP" style="text-decoration:none;color:#4FB180;font-weight:600">World Bank</a>, this index will guide and keep you informed about keeping inflation, expensive places in mind to negotiate it with your hr.</p>
                    <p>PPP index is used in salary conversion, if you move to a different country to evaluate you get the same value as you used to get in your motherland/work country to the country you moved or from the previous company to your country which can be expensive or lower as per exchange rate but as I said earlier it only converts the currencies.</p><br/>
                </div>
            </div>

            <div style="text-align:left;margin-top:1rem">
                <div style="font-size:1.2rem;line-height:1.8rem">
                    <h3 style="font-weight: 600;font-size:1.4rem">Importance of PPP in economics</h3>
                    <p>PPP is important because it helps us compare how well-off people are in different countries. It's like a fair way to see who can buy more with their money, regardless of the currency they use. It helps economists understand the standard of living and economic productivity across the globe.</p>
                    <p>Importance of PPP is very significant in economics to help economists, investors, enthusiasts and a closer check for governments also to check their ppp as it helps them understand how much affordability is there in a country. It also helps them understand -</p>
                    <p><ul>
                        <li>Fair way of checking all the countries where who can buy more with their money regardless of currency they use.</li>
                        <li>Living standards across the nation.</li>
                        <li>Economic productivity across the globe.</li>
                    </ul></p>
                </div>
            </div>
            
            <div>
                <h2 style="font-weight: 600;font-size:1.8rem;border-bottom:1px solid black;margin-top:5rem">Understanding PPP Calculators</h2>
            </div>
            <div style="text-align:left;margin-top:1rem">
                <div style="font-size:1.2rem;line-height:1.8rem">
                    <h3 style="font-weight: 600;font-size:1.4rem">What is a PPP Calculator?</h3>
                    <p>A PPP (Purchasing Power Parity) Salary Calculator by (<a href="https://banksforyou.com" style="text-decoration:none;color:#4FB180;font-weight:600"><i>Banksforyou</i></a>) is a financial tool made to help job seekers, expatriates, employers, hr, economists, researchers and by IMF themselves to compare salaries or wages between different countries by taking into account the differences in the cost of living, simply to check if their current salary aligns with the local cost of living.</p>
                </div>
            </div>
            <div style="text-align:left;margin-top:1rem">
                <div style="font-size:1.2rem;line-height:1.8rem">
                    <h3 style="font-weight: 600;font-size:1.4rem">How PPP Salary Calculator Work?</h3>
                    <p>A PPP (Purchasing Power Parity) salary calculator works by adjusting salaries or wages from one country to another considering the differences in the <i>cost of living</i>. </p>
                    <p>Let us check the breakdown of this-</p>
                    <p><ol class="lineheightofOl">
                        <li><span class="weight600">Salary Input</span> - only numeric digits are allowed. Type the salary that you get in your current origin country i.e <span class="sourceCountryTxt"><?=$getCountryDetails[0]['country_name']?></span> </li>
                        <li><span class="weight600">Input Boxes</span> - one is the origin country and another is Target country. In your country, your current country you are living in and the salary you're expecting from <span class="sourceCountryTxt"><?=$getCountryDetails[0]['country_name']?></span> and the salary that should align with the <span class="targetCountryTxt"><?=$getCountryDetails[1]['country_name']?></span>. Select the countries from the boxes. </li>
                        <li><span class="weight600">Data Retrieval</span> - Data provided by World bank is stored into our databases then retrieved from it and calculates the ppp based on the selected origin and target country. The formulae goes by this - Select the countries from the boxes. </li>
                    </ol></p>
                </div>
            </div>
            <div style="text-align:left;margin-top:1rem">
                <div style="font-size:1.2rem;line-height:1.4rem">
                    <p>Salary you get in <span class="sourceCountryTxt"><?=$getCountryDetails[0]['country_name']?></span> (origin country) whole divided by total multiplication of (source country <span class="sourceCountryTxt"><?=$getCountryDetails[0]['country_name']?></span> index value multiplied by Target country <span class="targetCountryTxt"><?=$getCountryDetails[1]['code3l']?></span> index value provided by World Bank)</p>
                    <p>Salary (s)- <span class="salaryfield" style="font-weight:600;"><span style="direction: ltr;"> <?=$getCountryDetails[0]['currency_symbol']?></span> 10,000</span></p>
                    <p>Index Value of <?=$getCountryDetails[0]['code3l']?> (a)- <span class="weight600"><?=$getCountryDetails[0]['ppp_value']?></p>
                    <p>Index Value of <?=$getCountryDetails[1]['code3l']?> (b)- <span class="weight600"><?=$getCountryDetails[1]['ppp_value']?></p>
                    <p><span>(s) / ((a) * (b)) = </span><span class="pppValue weight600 fullAmount"> <?=$getCountryDetails[1]['currency_symbol'].number_format($getCountryDetails[1]['ppp_calculation'], 2)?></span></p>
                </div>
            </div>

            <div class="programmaticToCountry">
            <?php $countries = getAllUsers();
            // echo "<pre>";
            // print_r($getCountryDetails);
                foreach ($countries as $country) { if($country['country_name'] != $getCountryDetails[0]['country_name']){ ?>
                <a href="<?=$protocol.createURL($_GET['lang'] == '' ? 'eng' : $_GET['lang'], $getCountryDetails[0]['code3l'], $country['code3l'])?>"><div><?=$getCountryDetails[0]['country_name']?> vs <?=$country['country_name']?></div></a>
                <a href="<?=$protocol.createURL($_GET['lang'] == '' ? 'eng' : $_GET['lang'], $country['code3l'], $getCountryDetails[0]['code3l'])?>"><div><?=$country['country_name']?> vs <?=$getCountryDetails[0]['country_name']?></div></a>
            <?php }} ?>
            </div>

            <div>
                <h2 style="font-weight: 600;font-size:1.8rem;border-bottom:1px solid black;margin-top:4rem">Uses of PPP Calculators</h2>
            </div>
            <div style="text-align:left;margin-top:1rem">
                <div style="font-size:1.2rem;line-height:1.8rem">
                    <h3 style="font-weight: 600;font-size:1.4rem">Cost of Living Comparison</h3>
                    <p>This is how they can impact their cost of living standards by adjusting salaries based on the relative purchasing power of different currencies and locations.</p>
                    <p><ol class="">
                        <li>Standardizing Salary Comparisons</li>
                        <li>Ensuring Fair Compensation</li>
                        <li>Informing Relocation Decisions</li>
                        <li>Budgeting and Financial Planning</li>
                        <li>Negotiating Job Offers</li>
                    </ol></p>
                    <p>And for companies it gives them a relative index value so they are aware of their costs and the expenses and their product launch price is also affected. </p>
                </div>
            </div>

            <div style="text-align:left;margin-top:1rem">
                <div style="font-size:1.2rem;line-height:1.8rem">
                    <h3 style="font-weight: 600;font-size:1.4rem">Benefits of Using a PPP Salary Calculator</h3>
                    <p>A PPP (Purchasing Power Parity) salary calculator works by adjusting salaries or wages from one country to another considering the differences in the <i>cost of living</i>. </p>
                    <p>Let us check the breakdown of this-</p>
                    <p><ol class="">
                        <li class="weight600">Estimated Cost of Living Comparison -</li>
                        <p>PPP salary calculators help in understanding the difference and comparisons of salaries across different nations in context to your current country’s salary. Thus you can have an informed decision.</p>
                        <li class="weight600">Fair Compensation Practices -</li>
                        <p>It ensures that employers will provide a living standard salary to the employee.</p>
                        <li class="weight600">Effective Job Search - </li>
                        <p>It is an absolutely great tool to search for a job and based on your skills and market rate in your country you can search salary ranges in other countries as well with this tool.</p>
                    </ol></p>
                </div>
            </div>

            <div style="text-align:left;margin-top:1rem">
                <div style="font-size:1.2rem;line-height:1.8rem">
                    <h3 style="font-weight: 600;font-size:1.4rem">Disadvantages of using PPP Salary Calculator</h3>
                    <p><ol class="">
                        <li class="weight600">Accuracy Concerns -</li>
                        <p>As the data depends on the salary entered and the current market conditions like inflation, disaster or any unexpected incident also sometimes due to use of non-latest index value. The data is fetched from the World Bank so if they have not collected the new data it is not possible to update to the latest year from our side.</p>
                        <li class="weight600">Non-monetary benefits are not calculated -</li>
                        <p>Non monetary benefits such as training opportunities, career development courses or flexible work opportunities differ from person and places. </p>
                        <li class="weight600">Risk of Misinterpretation -</li>
                        <p>Misinterpretation of salary figures without considering crucial factors such as job satisfaction, work life balance or long term career growth.</p>
                    </ol></p>
                </div>
            </div>

            
        <div style="margin-top:5rem"></div>
        <h1>Frequently Asked Questions</h1>
        <div class="accordion-wrapper">
        <div class="accordion">
            <input type="radio" name="radio-a" id="check1" checked>
            <label class="accordion-label" for="check1">In simple Words, what is a PPP and why do we need a Calculator for that ?</label>
            <div class="accordion-content">
            <p>PPP in full form is Purchasing Power Parity is an Index which is prepared by the World Bank so as to check if the same item can be purchased with the same price or not across nations.</p>
            </div>
        </div>
        <div class="accordion">
            <input type="radio" name="radio-a" id="check2">
            <label class="accordion-label" for="check2">Distinguish GDP and PPP in terms of Salary Calculator</label>
            <div class="accordion-content">
            <p>GDP measures the total value of goods and services produced within a country, reflecting its economic output using market exchange rates. 
                PPP adjusted exchange rates to compare the purchasing power of different currencies, ensuring that identical goods and services have the same price when checked in a common currency.</p><br/>
            <p>Essentially, GDP focuses on the size and growth of an economy, while PPP provides a more accurate comparison of living standards and economic welfare between countries by considering differences in the cost of living and inflation rates such as production of the total number of burgers produced in <span class="sourceCountryTxt" style="font-weight:600;"><?=$getCountryDetails[0]['country_name']?></span> vs price of Burger in <span class="sourceCountryTxt" style="font-weight:600;"><?=$getCountryDetails[0]['country_name']?></span> Vs <span class="targetCountryTxt" style="font-weight:600;"><?=$getCountryDetails[1]['country_name']?></span></p>
            </div>
        </div>
        <div class="accordion">
            <input type="radio" name="radio-a" id="check3">
            <label class="accordion-label" for="check3">What is the relation between Per capita Income and PPP?</label>
            <div class="accordion-content">
            <p>Per capita Income is the average income earned by the citizens of <span class="sourceCountryTxt" style="font-weight:600;"><?=$getCountryDetails[0]['country_name']?></span> or <span class="targetCountryTxt" style="font-weight:600;"><?=$getCountryDetails[1]['country_name']?></span> it can be calculated either by Per capita GDP or GDP per capita PPP.</p>
            <p><ul>
                <li><span class="weight600">GDP per Capita</span> - Total GDP of a country / its population. It indicates the economic output per person in a country.</li>
                <li><span class="weight600">GDP per capita PPP</span> - It is almost similar to GDP per Capita but instead of using nominal GDP it used PPP adjusted GDP. It provides more accurate data for calculating cost of living standards.</li>
            </ul></p>
            </div>
        </div>
        </div>


        </article>


        


    </main>
      <script src="<?=$BASE_URL?>script.js"></script>

        <script type="application/ld+json">
            {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [{
                "@type": "Question",
                "name": "In simple Words, what is a PPP and why do we need a Calculator for that ?",
                "acceptedAnswer": {
                "@type": "Answer",
                "text": "PPP in full form is Purchasing Power Parity is an Index which is prepared by the World Bank so as to check if the same item can be purchased with the same price or not across nations."
                }
            },{
                "@type": "Question",
                "name": "Distinguish GDP and PPP in terms of Salary Calculator",
                "acceptedAnswer": {
                "@type": "Answer",
                "text": "GDP measures the total value of goods and services produced within a country, reflecting its economic output using market exchange rates. PPP adjusted exchange rates to compare the purchasing power of different currencies, ensuring that identical goods and services have the same price when checked in a common currency.
            Essentially, GDP focuses on the size and growth of an economy, while PPP provides a more accurate comparison of living standards and economic welfare between countries by considering differences in the cost of living and inflation rates such as production of the total number of burgers produced in India vs price of Burger in India Vs USA"
                }
            },{
                "@type": "Question",
                "name": "What is the relation between Per capita Income and PPP?",
                "acceptedAnswer": {
                "@type": "Answer",
                "text": "Per capita Income is the average income earned by the citizens of India or USA it can be calculated either by Per capita GDP or GDP per capita PPP.

            GDP per Capita - Total GDP of a country / its population. It indicates the economic output per person in a country.
            GDP per capita PPP - It is almost similar to GDP per Capita but instead of using nominal GDP it used PPP adjusted GDP. It provides more accurate data for calculating cost of living standards."
                }
            }]
            }
        </script>



        
      
</body>
</html>