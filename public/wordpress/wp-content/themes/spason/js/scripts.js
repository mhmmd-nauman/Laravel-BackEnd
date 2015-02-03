$(document).ready(function(){
	var visibleMenu = false;

	$(".show-menu").click(function(e) {
		if(visibleMenu === false){
			e.preventDefault();
			$(".site-menu").animate({right: "0px"});
			visibleMenu = true;
			// lock scroll position, but retain settings for later
			var scrollPosition = [
			self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
			self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
			];
			var html = jQuery('html'); // it would make more sense to apply this to body, but IE7 won't have that
			html.data('scroll-position', scrollPosition);
			html.data('previous-overflow', html.css('overflow'));
			html.css('overflow', 'hidden');
			window.scrollTo(scrollPosition[0], scrollPosition[1]);
			var overlay = jQuery('<div id="overlay"> </div>');
			overlay.appendTo(document.body);
			overlay.click(function(e) {
				e.preventDefault();
				hideMenu();
			});
		}
	});

	$(".hide-menu").click(function(e) {
		e.preventDefault();
		hideMenu();
	});


	$(window).bind("touchstart, scroll",function() {
		hideMenu();
	});

	secondaryHeaderAffix();
	

	  	function hideMenu() {
	  		if(visibleMenu === true){
				$("#overlay").remove();
				$(".site-menu").animate({right: "-200px"});
				visibleMenu = false;
				// un-lock scroll position
				var html = jQuery('html');
				var scrollPosition = html.data('scroll-position');
				html.css('overflow', html.data('previous-overflow'));
				window.scrollTo(scrollPosition[0], scrollPosition[1]);
			}
	  	}

	$('#description-more').hide();

	$("#toggle-description-more").click(function(e) {
		console.log("hej");
		e.preventDefault();
		$('#description-more').toggle();
		if($('#description-more').is(':visible')) {
			$(this).text("Visa mindre");
		}else{
			$(this).text("Visa mer");
		}
	});

	giftcardInteractive();

});

function giftcardInteractive() {
	$("#form-giftcard .print-info").hide();
	$("#form-giftcard .custom-value").hide();
	var printGiftcard = false;
	function updatePrice() {
		var price = null;
		if($("#form-giftcard #gc_value").attr('value') == 'false'){
			price = $("#form-giftcard #gc_custom_value").attr('value');
		} else {
			price = $("#form-giftcard #gc_value").attr('value');
		}

		if(isNaN(price) || price <= 0){
			$(".total-price").text('');
		}else{
			if(!printGiftcard){
				price = price - 30;
			};
			price = $("#form-giftcard #gc_count").val() * price;
			$(".total-price").text("Totalt pris: " + price + " SEK");
		}
	}

	updatePrice();

	$("#form-giftcard #gc_value, #form-giftcard #gc_custom_value").change(function(){
		updatePrice();
	});

	$("#form-giftcard #gc_count").change(function(){
		if($(this).attr('value')<=0) {
			$(this).val(1);
		}
		updatePrice();
	})

	$("#form-giftcard input[name=gc_print]:radio").change(function () {
		if($(this).attr('value') == "printed") {
			$("#form-giftcard .print-info").show();
			printGiftcard = true;
		}else{
			$("#form-giftcard .print-info").hide();
			printGiftcard = false;
		}
		updatePrice();
	});
	$("#form-giftcard #gc_value").change(function () {
		if($(this).attr('value') == "false") {
			$("#form-giftcard .custom-value").show();
		}else{
			$("#form-giftcard .custom-value").hide();
		}
	});	


}

function secondaryHeaderAffix() {
	if($('#secondary-header').length) {
		$('#secondary-header').affix({
		    offset: {
		      top: $('#secondary-header').offset().top
		    }
		});
	}
}

