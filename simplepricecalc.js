(function($) {

  $.fn.SimplePriceCalc= function () {      
		
// Initialize Variables

	var total=0;
	var child=this.children(); 
	this.addClass("simple-price-calc");	
	InitialUpdate();	
	

	$(".simple-price-calc input[type=checkbox]").change( function() {
	
		if($(this).is(':checked')) {
			var checkboxval= $(this).val();
			if($.isNumeric(checkboxval)) {				
				$(this).attr('data-total', checkboxval);				
			}
			else {
				$(this).attr('data-total', 0);
			}
		}					
        else {
			$(this).attr('data-total', 0);
		}		
		
		UpdateTotal();
	
	});
	
		$(".simple-price-calc input[type=radio]").change( function() {
			$(".simple-price-calc input[type=radio]").each( function() {
				if($(this).is(':checked')) {
					var radioval= $(this).val();
					if($.isNumeric(radioval)) {				
						$(this).attr('data-total', radioval);
					}
					else {
						$(this).attr('data-total', 0);
					}
				}					
				else {
					$(this).attr('data-total', 0);
				}		
			});	
		UpdateTotal();
	
		});
	
	
	
	//Initialize all fields if data is there
	
	function InitialUpdate() {				
	
		$(".simple-price-calc input[type=checkbox]").each( function() {
	
			if($(this).is(':checked')) {
				var checkboxval= $(this).val();
				if($.isNumeric(checkboxval)) {				
					$(this).attr('data-total', checkboxval);
				}
				else {
					$(this).attr('data-total', 0);
				}
			}					
			else {
				$(this).attr('data-total', 0);
			}					
	
		});
	
		
			$(".simple-price-calc input[type=radio]").each( function() {
				if($(this).is(':checked')) {
					var radioval= $(this).val();
					if($.isNumeric(radioval)) {				
						$(this).attr('data-total', radioval);
					}
					else {
						$(this).attr('data-total', 0);
					}
				}					
				else {
					$(this).attr('data-total', 0);
				}		
			});		
			UpdateTotal();
	
	}
	
		//Change value of total field by adding all data totals in form
	
	function UpdateTotal() {
		
		total=0;		
		
		child.each(function () {
			itemcost= 	$(this).attr("data-total") || 0;
			total += parseInt(itemcost);
		});			
			    
		$(".simple-price-calc #simple-price-total label").html("$"+$.number(total,2));		
						
		
	}	
		
	 this.append('<div id="sidebar"><div id="simple-price-total"><h3 style="margin:0;"> Total: </h3><label id="simple-price-total-num"> $' + $.number(total,2) + ' </label></div> </div>');	
	 
	return this;
   
 };  // End of plugin

}(jQuery));