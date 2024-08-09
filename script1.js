var dataShort;
var i = 0;
var Anyerror = false;
var salaryInput = document.querySelector('.inputSalaryBox input');
let targetCountryTxt = document.querySelector('.targetCountryTxt');
let sourceCountryTxt = document.querySelector('.sourceCountryTxt');
let cssJson = [];
let BASE_URL;


if (window.location.hostname === 'localhost') 
{BASE_URL = 'http://localhost/ppp_calculator/';} 
else {BASE_URL = 'https://tools.banksforyou.com/ppp/';}

// cssJson['fullAmt'] = document.querySelector('.fullAmt').computedStyleMap().get('font-size');
cssJson['fullAmt'] = getComputedStyle(document.querySelector('.fullAmt')).getPropertyValue('font-size');

// cssJson['pillAmountDesign'] = document.querySelector('.pillAmountDesign').computedStyleMap().get('font-size');
cssJson['pillAmountDesign'] = getComputedStyle(document.querySelector('.pillAmountDesign')).getPropertyValue('font-size');

let cssJson2 = ['fullAmt', 'pillAmountDesign'];

let openSourceCountry = (button, dropdown) => {
    dropdown.classList.toggle('hidden');
    button.querySelector('.arrowHand').classList.add('rotate180');
    searchBoxFieldOpen(button.parentElement.querySelector('.searchBox'));
}

function searchBoxFieldOpen(that){
    that.classList.add('animate_opacity');
    that.style.zIndex = '9';
    that.querySelector('input').focus();
}

function searchBoxFieldClose(that){
    // that.addEventListener('transitionend', () => {
        // if(that.classList.contains('searchBox') != false){
            console.log(that)
            if(that == 'undefined'){
                that.style.zIndex = '-1';
                that.classList.remove('animate_opacity');
            }else{
                that.style.zIndex = '-1';
                that.classList.remove('animate_opacity');
                return true;
            }
            // if(that.classList[0]){
            //     that.style.zIndex = '-1';
            //     that.classList.remove('animate_opacity');
            // }
        // }
        // console.log(that)
    // }, { once: true });
}

let filterCountrySearch = (that) => {
    // var filter, ul, li, a, i, txtValue;
    let getSearchValue = that.value.toLowerCase();
    // getLi = that.querySelector('.CountryList ul li');
    let getLi = that.parentElement.parentElement.querySelectorAll('.CountryList ul li');
    let getULAttribute = that.parentElement.parentElement.querySelector('.CountryList ul').getAttribute('data-short');

    // console.log(getUL)
    getLi.forEach((li) => {
        let txtValue = li.textContent || li.innerText;
        if (txtValue.toUpperCase().indexOf(that.value.toUpperCase()) > -1) {
            li.style.display = "";
        } else{
        // else if(li.getAttribute('data-short').toUpperCase().indexOf(that.value.toUpperCase()) > -1){
        //     li.style.display = "";
        // }
        // console.log(li.getAttribute('data-short').toUpperCase().indexOf(that.value.toUpperCase()) > -1)
            li.style.display = "none";
        }
    })
    // filter = getUL.value.toUpperCase();

}


document.addEventListener('click', function(event) {
    var buttons = document.querySelectorAll('.searchCountry');
    var dropdowns = document.querySelectorAll('.CountryList');
    var searchBox = document.querySelectorAll('.searchBox');
    // var selectedCountry = document.querySelectorAll('.selectedCountry');
    
    buttons.forEach((button, index) => {
        var dropdown = dropdowns[index];
        var searchBoxes = searchBox[index];
        var isClickInsideButton = button.contains(event.target);

        if (!isClickInsideButton) {
            dropdown.classList.add('hidden');
            button.querySelector('.arrowHand').classList.remove('rotate180');
            // openInfoBox(document.querySelector('.infoBox'));
            searchBoxFieldClose(searchBoxes);
            // console.log()
            // searchBoxField(button.parentElement);
        } else {
            // searchBoxField(button.parentElement);
            openSourceCountry(button, dropdown);
            // searchBoxField(button);
            // searchBox.classList.remove('animate_opacity');
            // searchBoxField(button);
        }
    });
});

// Add click event listener to dropdown items
document.querySelectorAll('.CountryList li').forEach(item => {
    item.addEventListener('click', function() {
        let thisContext = this.parentElement.parentElement.parentElement;
        var selectedText = this.textContent.trim();
        var selectedCountryName = thisContext.querySelector('.selectedCountry * .countryName');
        var selectedCountryImg = thisContext.querySelector('.selectedCountry * .countryImage');
        var firstNameDiv = document.querySelector('.source-country-to-target-title p:first-child');
        var secondNameDiv = document.querySelector('.source-country-to-target-title p:last-child');

        dataShort = this.getAttribute('data-short');
        // console.log(this)

        // let sourceCurrency = document.querySelectorAll('.inputSalaryBox span, .currenyToCurrencyText .currencysymbol_first, span.currency');
        // let targetCurrency = document.querySelectorAll('.currencysymbol_second');
        // shortNameDiv.textContent = this.getAttribute('data-short');
        var symbolCountryName = this.getAttribute('data-symbol');
        var CurrencyWordName = this.getAttribute('data-currencyname');
        
        if(thisContext.classList.contains('originCountry')){
            firstNameDiv.textContent = selectedText.length > 6 ? dataShort : selectedText;
            // sourceCurrency.forEach((e) => {
            //     e.textContent = symbolCountryName;
            // })
            document.querySelector('.currencywords_first').textContent = CurrencyWordName
            document.querySelector('.currencysymbol_first').textContent = symbolCountryName
            document.querySelector('.inputSalaryBox span').textContent = symbolCountryName
            // sourceCountryTxt.textContent = this.querySelector('span').textContent.trim()
        }else{
            // targetCurrency.forEach((e) => {
            //     e.textContent = symbolCountryName;
            // });
            // secondNameDiv.textContent = selectedText;
            secondNameDiv.textContent = selectedText.length > 6 ? dataShort : selectedText;
            // console.log(dataShort.length)
            // console.log(selectedText.length)
            document.querySelector('.currencywords_second').textContent = CurrencyWordName
            document.querySelector('.currencysymbol_second').textContent = symbolCountryName
            if(document.querySelector('.targetCountry').classList.contains('error')){
                document.querySelector('.targetCountry').classList.remove('error');
                Anyerror = false;
            }
        }
        // console.log(this.querySelector('span').textContent.trim())
        selectedCountryName.textContent = selectedText;
        selectedCountryName.setAttribute('data-short', dataShort);
        // console.log(this)
        selectedCountryImg.src = this.querySelector('img').src;
    });
});

let CalculatePPPBttn = document.querySelector('#CalculatePPPBtn');
// let sourceCountryCode = document.querySelector('.originCountry div.selectedCountry div p').getAttribute('data-short');
// let targetCountryCode = document.querySelector('.targetCountry div.selectedCountry div p').getAttribute('data-short');
CalculatePPPBttn.addEventListener('click', () => {
    
    // alert(window.screen < 769)
    if(window.screen.width < 769){
        window.scroll({
            top: 560, 
            left: 0, 
            behavior: 'smooth'
        });
    }

    

    let xhrAjax = new XMLHttpRequest();
    xhrAjax.open('POST', BASE_URL + '/ajax/createContent.ajax.php', true);
    xhrAjax.setRequestHeader('Content-Type', 'application/json');
    document.querySelector('.programmaticToCountry').innerHTML = '';
    xhrAjax.onreadystatechange = function() {
        if (xhrAjax.readyState === XMLHttpRequest.DONE) {
            if (xhrAjax.status === 200) {
                let responseAjax = JSON.parse(xhrAjax.responseText);
                // console.log(responseAjax);
                document.querySelector('.programmaticToCountry').innerHTML = responseAjax;

                var Headtitle = document.querySelector("head > title");
                var HeadMetadescription = document.querySelector("head > meta[name='description']");
                var ogtitle = document.querySelector("head > meta[property='og:title']");
                let arrCountriesShort = [];
                // console.log(selectedCountryName)
                document.querySelectorAll('p:nth-child(1).countryName').forEach((e) => {
                    arrCountriesShort.push(e.getAttribute('data-short'));
                });
                Headtitle.textContent = `💸 #1 PPP Exact Salary Calculator | ${arrCountriesShort.join(' Vs ')}`;
                HeadMetadescription.setAttribute('content', `A advanced PPP calculator which helps job aspiants to negotiate their salaries 🤑 with HR according to living standards 🏠. Comparing between countries ${arrCountriesShort.join(' Vs ')}`);
                ogtitle.setAttribute('content', `💸 #1 PPP Exact Salary Calculator | ${arrCountriesShort.join(' Vs ')}`);
                // Handle the response here
            } else {
                console.error('Error: ' + xhrAjax.status);
                // Handle errors here
            }
        }
    };

    let jsonDataAjax = JSON.stringify({
        selectedCountryShort: document.querySelector('p:nth-child(1).countryName').getAttribute('data-short'),
        selectedCountryName: document.querySelector('p:nth-child(1).countryName').textContent,
        language: document.querySelector('.languagechange').getAttribute('data-lang')
    });
        
    // Send the request with the JSON data in the body
    xhrAjax.send(jsonDataAjax);
    

    if(document.querySelector('.targetCountry div.selectedCountry div p').getAttribute('data-short') == document.querySelector('.originCountry div.selectedCountry div p').getAttribute('data-short')){
        document.querySelector('.targetCountry').classList.add('error');
        scrollTo(0, 0);
        Anyerror = true;
        return false;
    }else{
        document.querySelector('.targetCountry').classList.remove('error');
        Anyerror = false;
    }

    // alert('clicked')
    if(salaryInput.value == ''){
        document.querySelector('#salaryInput').classList.add('error');
        scrollTo(0, 0);
        Anyerror = true;
        return false;
    }else{
        document.querySelector('#salaryInput').classList.remove('error');
        Anyerror = false;
    }        

    if(removeformatNumber(salaryInput.value).length < 10){
        document.querySelectorAll('.fullAmt, .pillAmountDesign').forEach((e) => {
            // e.style.fontSize = cssJson[`${cssJson2[i]}`].value+"px";
            e.style.fontSize = cssJson[`${cssJson2[i]}`];
            i++;
        })
        i = 0;
    }else{
        document.querySelectorAll('.fullAmt, .pillAmountDesign').forEach((e) => {
            e.style.fontSize = '20px';
        })
        i = 0;
    }

    if(Anyerror == false){
        let xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL+'/ajax/calculatePPP.ajax.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                
    // createblocks();
                // console.log(this.responseText)
                let response = JSON.parse(this.responseText);
                let pillAmountDesignSpan = document.querySelector('.pillAmountDesign span');
                pillAmountDesignSpan.classList.add('animate_up'); // Add 'animate_up' class to trigger animation
                document.querySelectorAll('.targetCountryTxt, .sourceCountryTxt').forEach((e) => {
                    e.classList.add('animate_up');
                })
                document.querySelector('.resultTextTargetcurrencyAmt strong span.currency').textContent = document.querySelector('.inputSalaryBox span').textContent
                // document.querySelectorAll('.resultTextTargetcurrencyAmt strong .currency, .resultTextTargetcurrencyAmt strong .txtAmt').forEach((e) => {
                //     // e.textContent = document.querySelector('.inputSalaryBox span').textContent
                //     // e.textContent
                    // console.log(document.querySelector('.inputSalaryBox span').textContent)
                    // console.log(salaryInput.value)
                // })

                document.querySelector('.shortNote span').textContent = response.year;

                //create url
                createUrl(document.querySelector('.targetCountry div.selectedCountry div p').getAttribute('data-short'), document.querySelector('.originCountry div.selectedCountry div p').getAttribute('data-short'));

                // Listen for the 'animationend' event on the '.pillAmountDesign span' element
                pillAmountDesignSpan.addEventListener('animationend', () => {
                    // Update the content based on the response
                    document.querySelector('.pillAmountDesign span .currencysymbol_second').textContent = response.currency;
                    // document.querySelector('.pillAmountDesign span .fullAmt').textContent = response.rounded;
                    document.querySelectorAll('.pillAmountDesign span .fullAmt, .fullAmount').forEach((e) => {
                        e.textContent = response.rounded;
                    })
                    document.querySelectorAll('.sourceCountryTxt').forEach((e) => {
                        e.textContent = document.querySelector('.originCountry div.selectedCountry div p').textContent;
                    })
                    
                    document.querySelectorAll('.targetCountryTxt').forEach((e) => {
                        e.textContent = document.querySelector('.targetCountry div.selectedCountry div p').textContent;
                    })
                    // document.querySelector('.originCountry div.selectedCountry div p').textContent
                    // document.querySelector('.targetCountryTxt').textContent = document.querySelector('.targetCountry div.selectedCountry div p').textContent
                    // document.querySelector('.resultTextTargetcurrencyAmt strong span:nth-child(2)').textContent = salaryInput.value;
                    document.querySelector('.resultTextTargetcurrencyAmt strong span:nth-child(2)').textContent = salaryInput.value;
                    document.querySelectorAll('.salaryfield').forEach((e) => {
                        e.textContent = `${document.querySelector('.inputSalaryBox span').textContent} ${salaryInput.value}`;
                    })

                    // Remove 'animate_up' class and add 'animate_down' class after animation ends
                    pillAmountDesignSpan.classList.remove('animate_up');
                    document.querySelector('.resultTextTargetcurrencyAmt').classList.remove('animate_up')
                    pillAmountDesignSpan.classList.add('animate_down');
                    document.querySelector('.resultTextTargetcurrencyAmt').classList.add('animate_down')
                    document.querySelectorAll('.targetCountryTxt, .sourceCountryTxt').forEach((e) => {
                        e.classList.remove('animate_up');
                        e.classList.add('animate_down');
                    })
                    setTimeout(() => {
                        pillAmountDesignSpan.classList.remove('animate_down');
                        document.querySelectorAll('.targetCountryTxt, .sourceCountryTxt').forEach((e) => {
                            e.classList.remove('animate_down');
                        })
                        document.querySelector('.resultTextTargetcurrencyAmt').classList.remove('animate_down')
                    }, 1000);
                }, { once: true });


            }
        }


        let jsonData = JSON.stringify({
            shortName: dataShort, 
            salary: removeformatNumber(salaryInput.value), 
            year: 2022,
            targetCountry: document.querySelector('.targetCountry div.selectedCountry div p').getAttribute('data-short'), 
            sourceCountry: document.querySelector('.originCountry div.selectedCountry div p').getAttribute('data-short')
        });

        xhr.send(jsonData);
    }

    
});

let createUrl = (targetCountryShort, sourceCountryShort) => {
    let newURL = `${BASE_URL}${document.querySelector('.languagechange').getAttribute('data-lang')}/${sourceCountryShort.toLowerCase()}-vs-${targetCountryShort.toLowerCase()}${"?salary="+salaryInput.value}`; // Concatenate the new URL
    history.replaceState({}, null, newURL);
}

let formatNumber = (inputField) => {
    // Get the input value
    let value = inputField.value;
            
    // Remove formatting (commas, dots, and spaces)
    value = value.replace(/[^\d.]/g, '');

    const formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 10
    });
    
    // Format the value using Intl.NumberFormat
    value = formatter.format(value);
    
    // Update the input field value
    if(value == 0){
        inputField.value = '';
    }else{
        inputField.value = value;
    }
    // console.log(value)
}

let removeformatNumber = (inputField) => {
    if(document.querySelector('#salaryInput').classList.remove('error')){
        document.querySelector('#salaryInput').classList.remove('error');
        // href= '#heroContainerId'
    }
    // Get the current value of the input field
    let formattedNumber = document.getElementById('salaryInput').value || '';
    // inputField = document.getElementById('salaryInput').value;
    // Remove commas from the formatted number string
    const numberWithoutCommas = formattedNumber.replace(/,/g, '');
    // return inputField;

    // Remove any non-numeric characters except the dot (.)
    const cleanedNumber = numberWithoutCommas.replace(/[^\d.]/g, '');

    // If the cleaned number has a decimal part, remove leading zeros
    const cleanedNumberWithoutLeadingZeros = cleanedNumber.replace(/^0+/, '');

    // Return the cleaned number
    return cleanedNumberWithoutLeadingZeros;
}



function restrictInput(that) {
    // const input = event.target;
    const currentValue = that.value;

    // return currentValue.length > 27 ? false : true;
    
    // Remove non-numeric characters except for the first decimal point
    const newValue = currentValue.replace(/[^0-9.]/g, '').replace(/^(\d*\.\d*).*$/, '$1');

    // Update the input value
    that.value = newValue;
}

// FAQ scripts
const accordion = document.querySelector('.faq-accordion-container');
const items     = accordion.querySelectorAll('li');
const questions = accordion.querySelectorAll('.faq-question');

function toggleFaqAccordion() {
  const thisItem = this.parentNode;

  items.forEach(item => {
    if (thisItem == item) {
      thisItem.classList.toggle('default-faq-open');
      return;
    }

    item.classList.remove('default-faq-open');
  });
}

questions.forEach(question => question.addEventListener('click', toggleFaqAccordion));
// FAQ scripts ends


// function createblocks(){
//     alert('clicked')
    

// }