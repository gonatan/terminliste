<style>
	
	.events {
		border-top: 1px solid #cccccc;
	}	
	
	.event {
		border-bottom: 1px solid #cccccc;
		padding-bottom: 20px;
	}
	
	.event h2 {
		font-size: 18px;
		padding: 20px 0;
	}
	
	.event .termintext {
		padding: 10px 0;
	}
	
</style>


{if $MOD.display}

	{if $errors} 
	
	<ul class="errors">
		
		{foreach from=$errors item=error}
		<li class="error">{$error}</li>
		
		{/foreach}
	</ul>
	{/if}
	
	{if $success} 
	
	<ul class="success">
		
		{foreach from=$success item=sucmsg}
		<li class="success">{$sucmsg}</li>
		
		{/foreach}
	</ul>
	{/if}
	
	<div class="events">
	
		{assign var="prevmonth" value=""}
		
		{foreach from=$termine item=termin}
		
		
		
		<div class="event {$termin.highlight}">
		
		{if $prevmonth neq $termin.date1_monthfull}
			<div class="">{$termin.date1_monthfull} {$termin.date1_yearfull}</div>
		{/if}
		
		{assign var="prevmonth" value=$termin.date1_monthfull}
		
			<h2>{$termin.titel}</h2>
			{if !($termin.groupeddate)} 
			<p>[Datum/Date] {$termin.date1_lang}{if $termin.zeit1 neq "00:00:00"}, {$termin.zeit1|date_format:"%H:%M"} Uhr{/if}
				{if !($termin.oneday or $termin.datum2 eq "")} - {$termin.date2_lang}{if $termin.zeit2 neq "00:00:00"}, {$termin.zeit2|date_format:"%H:%M"} Uhr{/if} {/if}
			</p>
			{/if}
			{if $termin.image != "" }
			<img src="{$termin.imagefullpath}" alt="{$termin.description}" title="{$termin.medianame}">
			{/if}
			
			<p>[Ort/Venue] {$termin.ort}</p>
			
			<div class="termintext">{$termin.text}</div>
			{if $termin.linktype eq "external"}
			{assign var="thislink" value="{$termin.linkexternal}"}
			{elseif $termin.linktype eq "internal"}
			{assign var="thislink" value="front_content.php?idart={$termin.linkinternal}"}
			{elseif $termin.linktype eq "file"}
			{assign var="thislink" value="{$termin.linkfilename}"}
			{/if}			
			
			<p><a href="{$thislink}" target="{if $termin.linkframe eq "true"}blank{/if}" class="">{$termin.linkdesc}</a></p>
		</div>
		{/foreach}
		
	</div>
	
	{else}
	<p>Module deactivated.</p>

{/if}