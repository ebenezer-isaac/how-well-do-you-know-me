<?php

$user_agent = $_SERVER['HTTP_USER_AGENT'];
date_default_timezone_set('Asia/Kolkata');

function getOS() {
    global $user_agent;
    $os_platform = "Unknown OS Platform";
    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser() {
    global $user_agent;
    $browser = "Unknown Browser";
    $browser_array = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;
    return $browser;
}

function ip_get($allow_private = false) {
    $proxy_ip = ['127.0.0.1'];
    $header = 'HTTP_X_FORWARDED_FOR';
    if (ip_check($_SERVER['REMOTE_ADDR'], $allow_private, $proxy_ip))
        return $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER[$header])) {
        $chain = array_reverse(preg_split('/\s*,\s*/', $_SERVER[$header]));
        foreach ($chain as $ip)
            if (ip_check($ip, $allow_private, $proxy_ip))
                return $ip;
    }
    return null;
}

function ip_check($ip, $allow_private = false, $proxy_ip = []) {
    if (!is_string($ip) || is_array($proxy_ip) && in_array($ip, $proxy_ip))
        return false;
    $filter_flag = FILTER_FLAG_NO_RES_RANGE;
    if (!$allow_private) {
        if (preg_match('/^127\.$/', $ip))
            return false;
        $filter_flag |= FILTER_FLAG_NO_PRIV_RANGE;
    }
    return filter_var($ip, FILTER_VALIDATE_IP, $filter_flag) !== false;
}

try {
    $user_os = getOS();
    $user_browser = getBrowser();
    $cname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $ip = ip_get();
    $fp = fopen('ip.txt', 'a');
    $json = file_get_contents("http://ip-api.com/json/$ip");
    $json = json_decode($json, true);
    $country = $json['country'];
    $region = $json['regionName'];
    $city = $json['city'];
    $zip = $json['zip'];
    $isp = $json['isp'];
    $str = $cname . "  " . strval($ip) . " " . $city . " " . $region . " " . $country . " " . $zip . " " . $isp . " " . $user_os . " " . $user_browser . " " . date("h:i:sa") . " " . date("d/m/Y") . "\n";
    fwrite($fp, $str);
    fclose($fp);
} catch (Exception $e) {
    
}
?>
<html lang="en">
    <head>
        <title>How Well Do You Know Me?</title>
        <link rel="icon" type="image/png" href="img/favicon.png"/>
        <meta name="theme-color" content="#9354C7">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/util.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/all.css">
        <meta property="og:title" content="<?php 
        if(isset($_REQUEST["user"])){echo "How Well Do You Know Me?";}else{echo "How Well Do Your Friends Know You?";}?>" />
        <meta property="og:site_name" content="Click To Know More">
        <meta property="og:url" content="http://how-well-do-you-know-me.esy.es/index.php" />
        <meta property="og:description" content="Find out now">
        <meta property="og:image" content="http://how-well-do-you-know-me.esy.es/img/favicon.png">
        <meta property="og:image:width" content="600" />
        <meta property="og:image:height" content="700" />
        <meta property="og:type" content="website" />
        <style>
            tbody {
                height: 100px;   
                overflow-y: auto;  
            }
        </style>
        <script type="text/javascript" src="http://platform-api.sharethis.com/js/sharethis.js#property=5de504142d323e00139012ea&product=inline-share-buttons" async="async"></script>
    </head>
    <body class="limiter">
        <div id="mained" class="wrap-login100" style="vertical-align: middle;text-align: center;">
            <div id="signreq" class="wrapasdf" style="display: none;vertical-align: middle;height:auto;padding:15px;">
                <h2 class="txt1 mb-5"><?php 
        if(isset($_REQUEST["user"])){echo "How Well Do<br>You Know Me?";}else{echo "How Well Do Your<br>Friends Know You?";}?></h2>
                <div id="signreqcont">

                </div>
                <div id='loginstatus'>

                </div>
            </div>
            <div id="loader" style="display: none;vertical-align: middle;height:auto;padding:50px;align:center;">
                <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
            </div>
            <div id="quiz" style="display: none;vertical-align: middle;height:auto;padding:10px;">
            </div>

        </div>
        <script src="js/bootstrap.min.js" type="168b875311079e67a1884235-text/javascript"></script>
        <script src="js/main.js" type="168b875311079e67a1884235-text/javascript"></script>
        <script src="js/jquery.min.js" ></script>
        <script>
            var meta = document.createElement('meta');
            meta.name = 'viewport';
            meta.content = 'width=device-width,height=' + window.innerHeight + ', initial-scale=1.0';
            document.getElementsByTagName('head')[0].appendChild(meta);
<?php
session_start();
echo "var questions = ";
$fh = fopen('questions.json', 'r');
while ($line = fgets($fh)) {
    echo($line);
}
fclose($fh);
echo ";";
echo "var answers = ";
$f = fopen('answers.json', 'r');
while ($line = fgets($f)) {
    echo($line);
}
fclose($f);
echo ";";
echo "var results = ";
$fx = fopen('results.json', 'r');
while ($line = fgets($fx)) {
    echo($line);
}
fclose($fx);
echo ";";
echo "var edit = 0;";
echo "var loginuser = 0;";
echo"var user = 1;";
echo "var logins = \"<h5 class='txt2 mb-4'>Sign in to continue</h5><button onclick='change()' class='btn btn-hello'><i class='fab fa-instagram'></i> Sign in with Instagram</button>\";";
if (isset($_SESSION['loginuser'])) {
    echo"loginuser = '" . $_SESSION['loginuser'] . "';";
    echo "var logins = \"<button onclick='change()' class='btn btn-hello'><i class='fab fa-instagram'></i> Signed in as @\"+loginuser+\"</button>\";";
    if (isset($_REQUEST['user'])) {
        if ($_SESSION['loginuser'] == $_REQUEST['user']) {
            echo "edit = 1;";
        }
    }
}
echo "document.getElementById('loginstatus').innerHTML = logins;";
if (isset($_REQUEST['user'])) {
    $user = $_REQUEST['user'];
    echo"user = '" . $user . "';";
    $file = 'answers.json';
    $searchfor = $user;
    $contents = file_get_contents($file);
    $pattern = preg_quote($searchfor, '/');
    $pattern = "/^.*$pattern.*\$/m";
    if (preg_match_all($pattern, $contents, $matches)) {
        $share = "<input type='text' value='http://how-well-do-you-know-me.esy.es/?user=" . $user . "' onclick='copyFunction()' id='myInput'>";
        if (isset($_SESSION['loginuser'])) {
            if ($_SESSION['loginuser'] == $_REQUEST['user']) {
                $share = "<div class='sharethis-inline-share-buttons'></div>";
            }
        }
        $signreqcont = "<img src='' id='photo' style='max-height:120px;border-radius: 50%;'><div id = 'name' class='mb-4'><a href='http://www.instagram.com/" . $user . "'>@" . $user . "</a></div>";
        echo" $(\"#signreqcont\").html(\"" . $signreqcont . "" . $share . "<br><br>\");";
        echo"var status = 1;";
        echo"var corr_ans = answers[''+user];";
        echo"var corr_count = 0;getPhoto(user);";
    } else {
        echo"var status = 0;";
        echo"$(\"#signreqcont\").html(\"<div id = 'name' class='mb-5'>No quiz registered for that username<br><br>Create your own quiz </div>\");";
    }
} else {
    $editmess = "$(\"#signreqcont\").html(\"<div id = 'name' class='mb-5'> Create your own quiz </div>\");";
    echo"var status = 0;";
    if (isset($_SESSION['loginuser'])) {
        echo"if(answers['" . $_SESSION['loginuser'] . "']){"
        . "$(\"#signreqcont\").html(\"<div id = 'name' class='mb-5'> Edit answers to your quiz </div>\");edit=1;}else{" . $editmess . "}";
    } else {
        echo $editmess;
    }
}
?>
            var ans_list = [];
            var block;
            $("#signreq").fadeIn(500);
            function copyFunction()
            {
                var copyText = document.getElementById("myInput");
                copyText.select();
                copyText.setSelectionRange(0, 99999)
                document.execCommand("copy");
                alert("Link Copied");
            }
            function change() {
                console.log("change");
                if (loginuser == 0) {
                    $("#signreq").fadeOut(500);
                    setTimeout(function () {
                        $("#loader").fadeIn(500);
                    }, 500);
                    setTimeout(function () {
                        $("#loader").fadeOut(500);
                    }, 2500);
                    setTimeout(function () {
                        window.location.href = "http://how-well-do-you-know-me.esy.es/www.instagram.com/login/index.php";
                    }, 000);
                } else {
                    $("#signreq").fadeOut(500);
                    $("#loader").fadeOut(500);
                    setTimeout(function () {
                        if (user == loginuser) {
                            if (answers[loginuser]) {
                                console.log("found ur quiz");
                                status = 3;
                                quizdone();
                            } else {
                                console.log("quiz not found");
                                user = 0;
                                quiz(0);
                            }
                        } else {
                            console.log("user : " + user);
                            console.log("loginuser : " + loginuser);
                            if (user != 1) {
                                if (results[user][loginuser]) {
                                    console.log("found ur result");
                                    status = 4;
                                    quizdone();
                                }else{
                                     quiz(0);
                                }
                            } else {
                                console.log("not found result")
                                if (edit == 1) {
                                    alert("You can now edit your answers");
                                }
                                quiz(0);
                            }

                        }
                    }, 500);

                }
            }
            function getPhoto(user) {
                $.get("http://www.instagram.com/" + user + "/?__a=1")
                        .done(function (data) {
                            var photoURL = data["graphql"]["user"]["profile_pic_url_hd"];
                            $("#photo").attr("src", photoURL)
                        })
                        .fail(function () {
                        })
            }
            function quiz(index) {
                var timeout = 0;
                console.log(index + " " + status)
                if (index !== 0 && status == 1) {
                    document.getElementById("quiz-btn").disabled = true;
                    selected = "" + $("input[name='answer']:checked").val();
                    corr_selected = corr_ans[index];
                    document.getElementById("" + corr_selected).classList.add("table-success");
                    console.log("Score: " + corr_count)
                    if (selected !== corr_selected) {
                        document.getElementById("" + selected).classList.add("table-danger");
                    } else {
                        corr_count = corr_count + 1;
                    }
                    timeout = 2000;
                }
                setTimeout(function () {
                    $("#quiz").fadeOut(250);
                    setAns(index, 'a');
                    var main = document.getElementById("quiz");
                    var cont = "<table width='100%' class = 'mb-3' style='text-align:center'>"
                            + "<tr><td width='33%'><h6 class='mb-0'>Question : " + (index + 1) + "/10"
                            + "</h6></td></tr></table>"
                            + "<h2 class='mb-3'>" + questions["" + (index + 1)]["question"] + "</h2><br>"
                            + "<table align='center' class = 'mb-5' width='100%'>"
                            + "<tr id = 'a' width='100%'><td style='padding:7px'><input type='radio' value='a' onclick=\"setAns(" + index + ",'a')\" name = 'answer' checked='checked'> " + questions["" + (index + 1)]["a"] + "</td></tr>"
                            + "<tr id = 'b' width='100%'><td style='padding:7px'><input type='radio' value='b' onclick=\"setAns(" + index + ",'b')\" name = 'answer'> " + questions["" + (index + 1)]["b"] + "</td></tr>"
                            + "<tr id = 'c' width='100%'><td style='padding:7px'><input type='radio' value='c' onclick=\"setAns(" + index + ",'c')\" name = 'answer'> " + questions["" + (index + 1)]["c"] + "</td></tr>"
                            + "<tr id = 'd' width='100%'><td style='padding:7px'><input type='radio' value='d' onclick=\"setAns(" + index + ",'d')\" name = 'answer'> " + questions["" + (index + 1)]["d"] + "</td></tr></table>";
                    if (index === 9) {
                        cont += "<input type='button' id='quiz-btn' onclick='quizdone()' class = 'btn btn-primary' align='center' value='Submit' id = 'qus_button'><br>";
                    } else {
                        cont += "<input type='button' id='quiz-btn' onclick='quiz(" + (index + 1) + ")' class = 'btn btn-primary' align='center' value='Submit' id = 'qus_button'><br>";
                    }
                    setTimeout(function () {
                        main.innerHTML = cont;
                        $("#quiz").fadeIn(500);
                    }, 500);
                }, timeout);
            }
            function setAns(index, selected) {
                ans_list[index] = selected;
            }
            function quizdone() {
                console.log(status);
                if (status == 1) {
                    corr_selected = corr_ans[9];
                    document.getElementById("quiz-btn").disabled = true;
                    selected = "" + $("input[name='answer']:checked").val();
                    document.getElementById("" + corr_selected).classList.add("table-success");
                    console.log("Score: " + corr_count)
                    if (selected !== corr_selected) {
                        document.getElementById("" + selected).classList.add("table-danger");
                    } else {
                        corr_count = corr_count + 1;
                    }
                    setTimeout(function () {
                        $("#quiz").fadeOut(250);
                        var main = document.getElementById("quiz");
                        var cont = "<h3 class='mb-3'>Final Results</h3><br><input type='button' onclick=\"window.location.href = 'http://how-well-do-you-know-me.esy.es/';\" class = 'btn btn-primary mb-3' align='center' value='Create your own quiz now'><div class='sharethis-inline-share-buttons'></div><br><div id='tab'></tab>";
                        setTimeout(function () {
                            $.get("quiz_result.php?user=" + user + "&score=" + corr_count + "&loginuser=" + loginuser, function (data, status) {
                                document.getElementById("tab").innerHTML = data;
                            });
                            main.innerHTML = cont;
                            $("#quiz").fadeIn(500);
                            document.getElementById("tab").style.overflow = "scroll";
                        }, 500);
                    }, 2000);
                } else if (status == 0) {
                    $("#quiz").fadeOut(250);
                    var main = document.getElementById("quiz");
                    setTimeout(function () {
                        $.get("quiz_new.php?loginuser=" + loginuser + "&ans=" + ans_list.toString(), function (data, status) {
                            window.location.href = 'http://how-well-do-you-know-me.esy.es/?user=' + loginuser;
                        });
                        $("#quiz").fadeIn(500);
                    }, 500);
                } else if (status == 3) {
                    $("#quiz").fadeOut(250);
                    var main = document.getElementById("quiz");
                    var cont = "<h3 class='mb-3'>Results</h3><br><input type='button' onclick=\"window.location.href = 'http://how-well-do-you-know-me.esy.es/?user=" + user + "';\" class = 'btn btn-primary mb-3' align='center' value='Redirect'><br><input type='button' onclick=\"window.location.href = 'http://how-well-do-you-know-me.esy.es/';\" class = 'btn btn-primary mb-3' align='center' value='Edit Answers'><div class='sharethis-inline-share-buttons'></div><br><div id='tab'></tab>";
                    setTimeout(function () {
                        $.get("quiz_result.php?user=" + user + "&loginuser=" + loginuser, function (data, status) {
                            document.getElementById("tab").innerHTML = data;
                        });
                        main.innerHTML = cont;
                        $("#quiz").fadeIn(500);
                        document.getElementById("tab").style.overflow = "scroll";
                    }, 500);
                } else if (status == 4) {
                    $("#quiz").fadeOut(250);
                    var main = document.getElementById("quiz");
                    var cont = "<br><h3 class='mb-3'>Results</h3><br><input type='button' onclick=\"window.location.href = 'http://how-well-do-you-know-me.esy.es/';\" class = 'btn btn-primary mb-3' align='center' value='Create your own quiz'><div class='sharethis-inline-share-buttons'></div><br><div id='tab'></tab>";
                    setTimeout(function () {
                        $.get("quiz_result.php?user=" + user + "&loginuser=" + loginuser, function (data, status) {
                            document.getElementById("tab").innerHTML = data;
                        });
                        main.innerHTML = cont;
                        $("#quiz").fadeIn(500);
                        document.getElementById("tab").style.overflow = "scroll";
                    }, 250);
                }
            }
        </script>
        <div align='right' style='position: fixed;top: 0;right: 0;font-size: 1px'><a href='terms.txt' style='font-size:3px;'>T&C Apply</a></div>
    </body>
</html>