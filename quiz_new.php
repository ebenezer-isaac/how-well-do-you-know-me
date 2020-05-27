<?php

$loginuser = $_REQUEST['loginuser'];
$ans = $_REQUEST['ans'];
echo $loginuser . "<br>";
echo $ans;
$jsonAnswer = file_get_contents('answers.json');
$data = json_decode($jsonAnswer, true);
$data[$loginuser] = explode(",", $ans);
$newJsonAnswer = json_encode($data);
file_put_contents('answers.json', $newJsonAnswer);
$jsonAnswer = file_get_contents('results.json');
$data = json_decode($jsonAnswer, true);
$data[$loginuser]["asdf"] = "0";
$newJsonAnswer = json_encode($data);
file_put_contents('results.json', $newJsonAnswer);
echo "1";

