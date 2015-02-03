<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" type="text/javascript"></script>
<script type="text/javascript">

	$(document).ready(function() {
		$.cookie.json = true;
		if($.cookie('spason_nl')) {
			var spason_nl = $.cookie('spason_nl');
			if(spason_nl.shown !== true) {
				spason_nl.pageCount = spason_nl.pageCount + 1;
				if(spason_nl.pageCount > 2) {
					$('#newsletterPopup').modal('show');
					spason_nl.shown = true;
					dataLayer.push({'event': 'newsletterPopup'}) 
					$.cookie('spason_nl', spason_nl, { expires: 30 });
				} else {
					$.cookie('spason_nl', spason_nl, { expires: 30 });
				}
			}else{
				$.cookie('spason_nl', spason_nl, { expires: 30 });
			}
		}else{
			var spason_nl = {
				shown: false,
				pageCount: 1
			}
			$.cookie('spason_nl', spason_nl, { expires: 30 });
		}
	});
</script>

<div class="modal fade" id="newsletterPopup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body" style="width: 400px; max-width: 100%; margin: 20px auto;">
      	<h3>30-70% rabatt på Sveriges bästa spahotell.</h3>
  	 	<p>Fyll i ditt namn och email för att få erbjudanden före alla andra!</p>
		<form action="//spason.us7.list-manage.com/subscribe/post?u=77a87f42537c515abef985ef2&amp;id=50c11f4942" method="post" id="mc-newsletter-popup" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			<div class="mc-field-group form-group">
				<label for="mce-FNAME">Namn </label>
				<input type="text" value="" name="FNAME" class="form-control" id="mce-FNAME">
			</div>
			<div class="mc-field-group form-group">
				<label for="mce-EMAIL">E-post</label>
				<input type="email" value="" name="EMAIL" class="required email form-control" id="mce-EMAIL">
			</div>
			<div id="mce-responses" class="clear">
				<div class="response" id="mce-error-response" style="display:none"></div>
				<div class="response" id="mce-success-response" style="display:none"></div>
			</div>    
			<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
		    <div style="position: absolute; left: -5000px;"><input type="text" name="b_77a87f42537c515abef985ef2_50c11f4942" tabindex="-1" value=""></div>
		    <input type="submit" value="Få erbjudanden" name="subscribe" id="mc-embedded-subscribe" class="btn btn-primary">
		</form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->