<div id="mod_te">
	{$IMG}
	<h1>{$label_title}</h1>
	<h2>{$termin.6}</h2>
	
	<p>{$termin.date0_dayfull}, {$termin.1date|date_format:"%d.%m.%Y"} {$termin.1time|date_format:"%H:%M:%S"} - {$termin.date2_dayfull}, {$termin.2date|date_format:"%d.%m.%Y"} {$termin.2time|date_format:"%H:%M:%S"} </p>
	
	<h2>{$label_venue}</h2>
	<p>{$termin.4}</p>
	<p>{$termin.5}</p>
	
	<p>{$termin.text}</p>
	<p><a href="{$termin.link}" target="{$termin.linkframe}" class="">{$termin.linkdesc}</a></p>
	<p><a href="JavaScript:history.back(1)">{$label_back}</a></p>
	
</div>
