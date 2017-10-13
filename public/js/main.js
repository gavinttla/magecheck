/**
 * Created by Feng Wang on 5/21/17.
 */

const BASE_URL = "/";

const TYPE_SECURITY = "security";
const TYPE_SEO = "seo";

const TOTAL_AJAX_CALL = 17;
const MAX_ITEM_NUM = 200;
const VALIDATE_URL = BASE_URL + "magento/process";

const MAGENTO_VERSION = "Magento Version";
const VERSION_CONTENT = "This shows your current Magento version. Magento releases security fixes periodically in all newer versions, after 1.4.0 (Community) and 1.10 (Enterprise).";



/*
 * One element in this array contains a whole record of one item
 * I assume that there are at most 100 different items for security and seo respectively
 * first 100 belongs to security
 * second 100 belongs to seo
 */
var htmlArr = new Array(MAX_ITEM_NUM);

var ajaxCallCount = 0;
var intervalHandler = null;

var domain = "";

var securityItemNameArray = ["version", "item1", "item2", "item4", "item5",
    "item6", "item7", "item8", "item9", "item10",
    "checkadminurl", "checkssl"];

var seoItemNameArray = ["keywords", "backlinks", "refdomains", "organic_competitors", "mixed_content"];

// clear the search bar
$("#clear").on("click", function() {
    $(".domain-input").val("");
    $(".container.result .container-security").empty();
    $(".container.result .container-seo").empty();
    $(".container.result").hide();
    $(".container.subscribe").hide();
    $("#myProgress").hide();
    $("#myProgressValue").hide();
    $("#error-image").hide();
});

function search() {
    // when user click scan button, clear previous results and hide result block
    // reset ajaxCallCount and input domain to 0 and clear the html array
    $(".container.result .container-security").empty();
    $(".container.result .container-seo").empty();
    $(".container.result").hide();
    $("#myProgress").hide();
    $("#myProgressValue").hide();
    $("#error-image").hide();
    $(".container.subscribe").hide();
    ajaxCallCount = 0;
    htmlArr = new Array(MAX_ITEM_NUM);

    // check required input domain
    if (!$("#input-domain").val()) {
        alert("Please input the base URL...");
        return;
    }

    domain = $("#input-domain").val();

    $("#myProgress").show();
    $("#myProgressValue").show();
    move();

    // validate domain
    $.ajax({
        url : VALIDATE_URL,
        method : "GET",
        data : {
            "domain" : domain,
            "item" : "version"
        },
        dataType : "JSON",
        success : function(data) {
            if (!data) {
                alert("Domain Validation Failed! Please try again later...");
                window.location.reload();
            } else {
                // firstly, check if we can access those input domain
                if (data.status == "fail") {
                    alert("The domain you typed in is invalid!");
                    $("#myProgress").hide();
                    $("#myProgressValue").hide();
                    return;
                } else {
                    var version = "";
                    // if framework is magento, start complete security scan
                    if (data.framework > 0) {
                        version = data.version;
                        // put version in the first row
                        setVersionData(version, 0);
                        ajaxCallCount++;

                        // scan other security items
                        for (var i = 1; i < securityItemNameArray.length; i++) {
                            scan(TYPE_SECURITY, securityItemNameArray[i], version, data.framework, i);
                        }
                        // start seo scan
                        for (var i = 0; i < seoItemNameArray.length; i++) {
                            scan(TYPE_SEO, seoItemNameArray[i], version, data.framework, 100 + i);
                        }
                    } else {
                        $("#myProgress").hide();
                        $("#myProgressValue").hide();
                        // if it is not magento framework, show the error image
                        $("#error-image").show();
                        return;
                    }
                }
            }
        },
        failure : function() {
            alert("Domain Validation Failed! Please try again later...");
            window.location.reload();
        }
    });

    // set interval handler to check whether all ajax calls are finished every second
    intervalHandler = setInterval(function() {
        if (ajaxCallCount == TOTAL_AJAX_CALL) {
            // display result
            displayResult();
            clearInterval(intervalHandler);
        }
    }, 100);
}

$("#search").on("click", search);

$("#input-domain").on("keypress", function(event) {
    var code = event.keyCode ? event.keyCode : event.which;
    if (code == 13) {
        search();
    }
});

$("#input-email").on("keypress", function(event) {
    var code = event.keyCode ? event.keyCode : event.which;
    if (code == 13) {
        subscribe();
    }
});

function scan(type, item, version, framework, index) {
    var url = BASE_URL + type + "/process";
    $.ajax({
        url : url,
        method : "GET",
        data : {
            "domain" : domain,
            "version" : version,
            "framework" : framework,
            "item" : item
        },
        dataType : "JSON",
        success : function(data) {
            if (data && data.status == "success") {
                if (type == TYPE_SECURITY) {
                    setSecurityData(data, index);
                } else {
                    setSeoData(data, index);
                }
            }
        },
        failure : function() {
            alert("Scan Failed!");
            window.location.reload();
        }
    }).done(function() {
        ajaxCallCount++;
    });
}

/**
 * Set data for version row
 * @param version current magento version
 * @param index order, should be 0
 */
function setVersionData(version, index) {
    htmlArr[index] = '<tr class="score-high">';
    htmlArr[index] += '<td class="col-sm-2 item">';
    htmlArr[index] += MAGENTO_VERSION;
    htmlArr[index] += '</td>';
    htmlArr[index] += '<td class="col-sm-8 content">';
    htmlArr[index] += VERSION_CONTENT;
    htmlArr[index] += '</td>';
    htmlArr[index] += '<td class="col-sm-2 score">';
    htmlArr[index] += version;
    htmlArr[index] += '</td>';
    htmlArr[index] += '</tr>';
}

/**
 * Set data for security panel
 * @param data json data from ajax call
 * @param index html order
 */
function setSecurityData(data, index) {
    if (data.detail.score == "unknown") {
        htmlArr[index] = '<tr class="score-unknown">';
    } else if (data.detail.score == "safe") {
        htmlArr[index] = '<tr class="score-high">';
    } else {
        htmlArr[index] = '<tr class="score-low">';
    }
    htmlArr[index] += '<td class="col-sm-2 item">';
    htmlArr[index] += data.detail.item;
    htmlArr[index] += '</td>';
    htmlArr[index] += '<td class="col-sm-8 content">';
    htmlArr[index] += data.detail.content;
    htmlArr[index] += '</td>';
    htmlArr[index] += '<td class="col-sm-2 score">';
    htmlArr[index] += data.detail.score;
    htmlArr[index] += '</td>';
    htmlArr[index] += '</tr>';
}

/**
 * Set data for seo panel
 * @param data json data from ajax call
 * @param index html order
 */
function setSeoData(data, index) {
    var hasScore = true;
    if (data.detail.score == "unknown") {
        htmlArr[index] = '<tr class="score-unknown">';
    } else if (data.detail.score == "high") {
        htmlArr[index] = '<tr class="score-high">';
    } else if (data.detail.score == "low") {
        htmlArr[index] = '<tr class="score-low">';
    } else if (data.detail.score == "medium") {
        htmlArr[index] = '<tr class="score-medium">';
    } else {
        htmlArr[index] = index % 2 == 0 ? '<tr class="score-odd">' : '<tr class="score-even">';
        hasScore = false;
    }
    htmlArr[index] += '<td class="col-sm-2 item">';
    htmlArr[index] += data.detail.item;
    htmlArr[index] += '</td>';
    htmlArr[index] += '<td class="col-sm-8 content">';
    htmlArr[index] += data.detail.content;
    htmlArr[index] += '</td>';
    htmlArr[index] += '<td class="col-sm-2 score">';
    htmlArr[index] += hasScore ? data.detail.score : "Yes";
    htmlArr[index] += '</td>';
    htmlArr[index] += '</tr>';
}

function displayResult() {
    var finalSecurityHtml = "";
    var finalSeoHtml = "";

    finalSecurityHtml += '<h2>SECURITY</h2>';
    finalSecurityHtml += '<table class="table table-bordered">';
    finalSecurityHtml += '<tbody>';
    for (var i = 0; i < securityItemNameArray.length; i++) {
        finalSecurityHtml += htmlArr[i] ? htmlArr[i] : "";
    }
    finalSecurityHtml += '</tbody>';
    finalSecurityHtml += '</table>';
    $(".container.result .container-security").html(finalSecurityHtml);

    finalSeoHtml += '<h2>SEO</h2>';
    finalSeoHtml += '<table class="table table-bordered">';
    finalSeoHtml += '<tbody>';
    for (var i = 0; i < seoItemNameArray.length; i++) {
        finalSeoHtml += htmlArr[100 + i] ? htmlArr[100 + i] : "";
    }
    finalSeoHtml += '</tbody>';
    finalSeoHtml += '</table>';
    $(".container.result .container-seo").html(finalSeoHtml);

    // hide spinner
    // $(".spinner").hide();
    $("#myProgress").hide();
    $("#myProgressValue").hide();
    // display scan result
    $(".container.subscribe").show();
    $(".container.result").show();
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}



function subscribe() {
    var email = $("#input-email").val();
    if (!validateEmail(email)) {
        alert("Please input correct Email Address");
        return;
    }

    var html = '<!DOCTYPE html><html><head><title>MageAudit</title>';
    html += '<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
    html += '<style>table,tr,td{border:1px solid black;border-collapse:collapse;}td{padding:10px;}</style></head><body>';
    html += $(".container.result").html();
    html += '<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>';
    html += '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>';
    html += '</body></html>';

    var url = BASE_URL + "security/savedata";
    $.ajax({
        url : url,
        method : "POST",
        data : {
            "email" : email,
            "domain" : domain
        },
        dataType : "JSON",
        success : function(data) {
            if (data && data.status == "success") {
                $("#html").val(html);
                $("#domain").val(domain);
                $("#pdfForm").submit();
            } else {
                alert("Create Report Failed!");
                window.location.reload();
            }
        },
        failure : function() {
            alert("Create Report Failed!");
            window.location.reload();
        }
    });
}

$("#subscribe").on("click", subscribe);

function move() {
    var elem = document.getElementById("myBar");
    var id = setInterval(frame, 10);
    function frame() {
        if (ajaxCallCount == TOTAL_AJAX_CALL) {
            clearInterval(id);
        } else {
            elem.style.width = ajaxCallCount * 100 / TOTAL_AJAX_CALL + '%';
        }
        $("#myProgressValue").html(Math.round(ajaxCallCount * 100 / TOTAL_AJAX_CALL) + '%');
    }
}