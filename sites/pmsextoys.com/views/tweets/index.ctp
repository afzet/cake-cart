<div style="background-color: #FDD0E2; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					News &amp; Updates
				</div>				
			</td>
		</tr>
	</table>
</div>

<br />

<div style="padding: 10px;">	
	<ul style="list-style: none; width: 90">
		<?php
		$source = 'source';
		$tweet = $tweets[2];
		foreach ($tweets as $tweet):
			$date = substr($tweet['created_at'], 0, 10);
			$msg = str_replace(array('adult movie lesbian ', ' #ihave #forsale'), '', $tweet['text']);
			if ($tweet['source'] == '<a href="http://ioffer.com" rel="nofollow">iOffer</a>'):
				$parts = explode(' ', $msg); 
				$pieces = count($parts);
				$part_number = $parts[$pieces - 2] .' '. $parts[$pieces - 1];
				$part = strtoupper(str_replace(' ', '-', $part_number));
				$link = $html->link($part, array('controller'=>'product','action'=>'search','mainkeyword:'.$part));
				$msg = str_replace($part_number, $link, $msg);
				$msg = str_replace($parts[$pieces - 3], '-', $msg);
				$msg = 'Recent Purchase: ' . $msg;
			endif;
			echo '<li style="margin-bottom:10px">';
			echo $text->autoLinkUrls($date .' - '. $msg, array('target' => 'new'));
			echo '</li>';
		endforeach;
		
		?>
	</ul>
</div>