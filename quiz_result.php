<?php

$user = $_REQUEST['user'];
$loginuser = $_REQUEST['loginuser'];
$jsonResult = file_get_contents('results.json');
$data = json_decode($jsonResult, true);
if (isset($_REQUEST['score'])) {
    $score = $_REQUEST['score'];
    $data[$user][$loginuser] = $score;
    $newJsonResult = json_encode($data);
    file_put_contents('results.json', $newJsonResult);
}
echo "<div align='center' class='table-responsive-xl' ><table width='70%' width = '70%' class='table table-bordered' style='text-align:center;'><thead class='thead-dark'><tr><th>Username</th><th>Score</th></tr></thead><tbody>";
$count = 0;
foreach ($data[$user] as $key => $value) {
    if ($key != "asdf") {
        $count = $count + 1;
        $highligter = "<tr>";
        if ($key == $loginuser) {
            $highligter = "<tr class = 'table-success'>";
        }
        echo $highligter . "<td><href='https://www.instagram.com/" . $key . "'>" . $key . "</a></td><td>" . $value . "</td></tr>";
    }
}
if ($count == 0) {
    echo "<tr><td colspan=2 >No Participants Yet</td></tr>";
}
echo "</tbody></table>";
