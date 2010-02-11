<?
foreach ($data as $item) {
    $cat  .= "<category name='".$item['category']."' hoverText='".$item['category']."' />";
    $views .= "<set value='".$item['views']."' />";
}
?>

<graph caption='Category Views' xAxisName='Views' yAxisName='Categories' formatNumber='0' numberPrefix='' showNames='1' rotateNames="1">
    <categories>   
        <?=$cat?>
    </categories>
    <dataset seriesName='Hits' color='AFD8F8'>    
        <?=$views?>
    </dataset>
</graph>
