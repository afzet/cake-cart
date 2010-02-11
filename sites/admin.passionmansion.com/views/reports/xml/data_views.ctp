<?
foreach ($data as $item) {
    $cat  .= "<category name='".$item['date']."' hoverText='".$item['date']."' />";
    $views .= "<set value='".$item['views']."' />";
    $uniques .= "<set value='".$item['unique']."' />";
}
?>

<graph caption='Views By Month' xAxisName='Views' yAxisName='Date' formatNumber='0' numberPrefix='' showNames='1'>
    <categories>   
        <?=$cat?>
    </categories>
    <dataset seriesName='Hits' color='AFD8F8'>    
        <?=$views?>
    </dataset>
    <dataset seriesName='Unique Hits' color='F6BD0F'>      
        <?=$uniques?>
    </dataset>
</graph>
