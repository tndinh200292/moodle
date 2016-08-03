<?php
/**
 * Created by PhpStorm.
 * User: tndinh
 * Date: 27/07/2016
 * Time: 11:26
 */
$htmlBreadcump = "<div>";
//var_dump($breadData);
foreach ($breadData as $link) {
    //echo $link['label'];
    $temp = "<a href='".$link['href']."'>".$link['label']."</a>";
    $htmlBreadcump = $htmlBreadcump.$temp." / ";
}
$htmlBreadcump = $htmlBreadcump."</div>";
echo $htmlBreadcump;