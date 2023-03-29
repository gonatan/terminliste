<style>
	
	#terminInputForm {
		font-family: "Source Sans Pro", Arial, Verdana, sans-serif;
	} 	
	#terminInputForm label, #terminInputForm  #standardForm  label {
		display: inline-block;
		vertical-align: top;
		width: 200px;
		font-size: 14px;
	}

	#terminInputForm input.short {
		width: 40px;
	}
	
	#terminInputForm  #standardForm .checkboxfloats {
		float: none;
		display: inline-block;
		vertical-align: top;
		vertical-align: top;
		
	}
	
	#terminInputForm  #standardForm p.info {
		font-size: 12px;
	}
	
	#terminInputForm  div div p {
		font-size: 14px;
	}
	
	#terminInputForm #standardForm input[type=checkbox] { 
		display: inline-block; 
		margin-top: 5px;
		vertical-align: top; 
		width: 30px; }
	
</style>

<div id="terminInputForm">

	<form action="{$actionlink}" method="post" id="standardForm" name="MOD_TE_eintrag">
		
		<fieldset><legend>Grundddaten</legend>
		
		<p class="info">{$info_misc}</p>
		<label>{$label_venue}</label><input type="text" name="MOD_TE_ort" value="{$termin.4}" size=20 {$venuetype} />
		<select name="MOD_TE_ortsliste" size="1">';		
			{foreach from=$venuelist item=venue}		
			{if $venue == $termin.4 }
			<option selected value="{$venue}">{$venue}</option>
			{else}
			<option value="{$venue}">{$venue}</option>
			{/if}
			{/foreach}
		</select><br>
		<label>{$label_title}</label><input type="text" name="MOD_TE_titel" value="{$termin.6}" size=70/><br>
		<label class="nonmandatory">{$label_details}</label><input type="text" name="MOD_TE_ort_detail" value="{$termin.5}" size=27 /><br>
		<hr>
			<label>{$label_cycle}</label><select name="MOD_TE_cycle" size="1">';		
			{foreach from=$cyclelist key=key item=cycle}		
			{if $key == $termin.10 }
			<option selected value="{$key}">{$cycle}</option>
			{else}
			<option value="{$key}">{$cycle}</option>
			{/if}
			{/foreach}
			</select><br>

			<label>{$label_everyXdays}</label><input type="text" name="MOD_TE_xtag" value="{$termin.16}" size=3 class="short"><br>			
		<label>{$label_everyXweeknums}</label>{$cycle_weeknumselect}<br>			
		<label style="height: 50px;">{$label_everyXweekdays}</label><div style="display: inline-block;height: 50px;width: 50%;">{$cycle_weekdayselect}</div><br>
			<label>{$label_adddates}</label><input type="text" name="MOD_TE_zutermine" value="{$termin.22}" size=70/> {$label_formatyyyymmdd}<br>
			<label>{$label_removedates}</label><input type="text" name="MOD_TE_aliste" value="{$termin.17}" size=70/> {$label_formatyyyymmdd}<br>
		<label class="nonmandatory">{$label_teaser}</label><input type="checkbox" name="MOD_TE_teaser" value="yes" {$teaser_check} /><br>
		<label class="nonmandatory">{$label_highlight}</label><select name="MOD_TE_highlight" size="1">';
			{foreach from=$highlightlist key=key item=highlight}		
			{if $key == $termin.20 }
			<option selected value="{$key}">{$highlight}</option>
			{else}
			<option value="{$key}">{$highlight}</option>
			{/if}
			{/foreach}
			</select><br>
			
			
			<label class="nonmandatory">{$label_category}</label><div style="display: inline-block;width: 50%;">{$categoryselect}</div><br>
		<input type="submit" value="{$label_save}" class="send"><input type="hidden" name="MOD_TE_senden" value=1>
		</fieldset>
		
	</form>
	<br><br>
	<label>{$label_startdate}</label><div style="display: inline-block;">{$termin.startdate}</div><br>
	<label>{$label_enddate}</label><div style="display: inline-block;">{$termin.enddate}</div><br><br>
	<label>{$label_text}</label><div style="display: inline-block; width: 60%;">{$termin.text}</div><br><br>
	<label>{$label_link}</label><div style="display: inline-block;">{$termin.linkdesc}</div><br><br>	
	<label>{$label_image}</label><div style="display: inline-block;">{$termin.imageeditor}</div>
	{if $termin.imagesrc neq ""}<img src="{$termin.imagesrc}" style="display: inline-block;" />{/if}<br><br>	
</div>
