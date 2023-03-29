<style>
	
	#standardForm fieldset {
		border: 1px solid #cccccc;
		padding: 10px;
		margin-bottom: 30px;
	}
	
	#standardForm fieldset legend {
		font-size: 14px;
	}
	
	#standardForm label, #standardForm input {
		display: inline-block;
		vertical-align: top;
	}
	
	#standardForm label {
		width: 80px;
		margin-right: 20px;
	}
	
	#standardForm h2 {
		padding: 15px 0 0 0 ;
	}
	
	#standardForm input.send {
		margin-top: 20px;
	}
	
	#standardForm div, #standardForm h2 {
		clear: both;
	}
	
</style>	

<script>
	$(document).ready(function(){        
		$('.date-pick').datePicker();
	});
</script>

<div>
	
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
	
	<form action="{$formaction}" method="post" name="MOD_TS_select" id="standardForm">
		
		<fieldset><legend>{$label.dateframe}</legend>
			<div>
			<label class="nonmandatory" style="float:left;">{$label.datefrom}</label>
			<input class="date-pick" type="text" name="MOD_TS_datum_von" value="{$datefrom}" />
			</div>
			<div>
			<label class="nonmandatory" style="float:left;">{$label.dateuntil}</label>
			<input class="date-pick" type="text" name="MOD_TS_datum_bis" value="{$dateuntil}" />
			</div>

		
		{if $MOD.show_timeopt}
		<h2>{$label.timeoption}</h2>
		<table>
			<tr>
				<td><input type="radio" name="MOD_TS_timeopt" value="keine" {$MOD.timeopt_none} />{$label.today}</td>
				<td><input type="radio" name="MOD_TS_timeopt" value="woche" {$MOD.timeopt_week} />{$label.thisweek}</td>
				<td><input type="radio" name="MOD_TS_timeopt" value="monat" {$MOD.timeopt_month} />{$label.thismonth}</td>
				<td><input type="radio" name="MOD_TS_timeopt" value="jahr" {$MOD.timeopt_year} />{$label.thisyear}</td>
			</tr>
		</table>
		{/if}

		{if $MOD.show_cat && $MOD.categories_sel.1 != ""}
		<h2>{$label.categoryoption}</h2>
		<input type="hidden" name="MOD_TS_cat_check" value="yes">
		{if $MOD.catform == "dropdown"}<p>{$categories_dropdown}<p>{/if}
		{if $MOD.catform == "checkbox"}<p>{$categories_checkbox}<p>{/if}
			{else}
			<input type="hidden" name="MOD_TS_cat_check" value="no">
		{/if}
					
			<label class="nonmandatory">&nbsp;</label><input type="submit" class="send" name="MOD_TS_suchen" value="{$label.send}">
		
		</fieldset>
		
		
		
	</form>
</div>