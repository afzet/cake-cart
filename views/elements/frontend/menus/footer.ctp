<?php
echo $html->link('Press', array('controller' => 'press', 'action' => 'index', 'plugin' => false)).' | ';
echo $html->link('Advertising', array('controller' => 'docs', 'action' => 'advertise')).' | ';
echo $html->link('Sitemap', array('controller' => 'docs', 'action' => 'sitemap')).' | ';
echo $html->link('Aknowledgements', array('controller' => 'docs', 'action' => 'thanks'));
?>