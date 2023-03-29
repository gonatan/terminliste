<script type="text/javascript">

	$(document).ready(function() {
		$("legend.showmore").each(function() {
				var tis = jQuery(this), state = false, answer = tis.next("div").hide().css("height","auto").slideUp();
				tis.click(function() {
				faqimg = tis.find("span").first();
				state = !state;
				answer.slideToggle(state);
				tis.toggleClass("active",state);
				if ( state )  { faqimg.attr("class","morebutton2"); faqimg.html("&equiv;"); }
				if ( !state ) {faqimg.attr("class","morebutton"); faqimg.html("&raquo;");   }
		});
		});
	});
	
         try {
            function fncUpdateSel(sSelectBox, sStorage)
            {
               var sSelection = "";
               var oSelectBox = document.getElementsByName(sSelectBox)[0];
               var oStorage   = document.getElementsByName(sStorage)[0];
               
               if (oSelectBox && oStorage)
               {
                  for (i = 0; i < oSelectBox.length; i++)
                  {
                     if(oSelectBox.options[i].selected == true)
                     {
                        if (sSelection != "")
                           sSelection = sSelection + ",";
                        sSelection = sSelection + oSelectBox.options[i].value;
                     }
                  }
                  oStorage.value = sSelection;
               }
            }
         } catch (e) { }
		
</script>