<?php
/*
 * Template Name: Giftcard
 * Description: Buy the spason giftcard...
 */

	if($_POST){
		$errors = array();
		$show_thank_you = false;
		$sanitized_post = array();
		foreach($_POST as $key => $val){
			$sanitized_post[$key] = filter_var($val, FILTER_SANITIZE_STRING);
		}

		if(empty($sanitized_post['gc_name'])) array_push($errors,"Namn måste anges");
		if(empty($sanitized_post['gc_email'])) array_push($errors,"E-post måste anges");
		if($sanitized_post['gc_count'] < 1) array_push($errors,"Antal måste vara större än 1");
		if($sanitized_post['gc_value'] == false){
			if($sanitized_post['gc_custom_value'] < 300) array_push($errors,"Värdet måste vara minst 300 SEK");
		}
		if($sanitized_post['gc_print'] == "printed") {
			if(empty($sanitized_post['gc_address'])) array_push($errors,"Adress måste anges");
			if(empty($sanitized_post['gc_zip'])) array_push($errors,"Postnummer måste anges");
			if(empty($sanitized_post['gc_city'])) array_push($errors,"Ort måste anges");
		}		
		if(count($errors) == 0){
			$mail_content = "";
			$mail_content .= "<h1>Presentkort</h1>";
			$mail_content .= "<b>Name:</b> {$sanitized_post[gc_name]} <br>";
			$mail_content .= "<b>E-post:</b> {$sanitized_post[gc_email]} <br>";
			$mail_content .= "<b>Adress:</b> {$sanitized_post[gc_address]} <br>";
			$mail_content .= "<b>Postnummer:</b> {$sanitized_post[gc_zip]} <br>";
			$mail_content .= "<b>Ort:</b> {$sanitized_post[gc_city]} <br>";
			$mail_content .= "<hr>";
			$mail_content .= "<b>Digital/print*:</b> {$sanitized_post[gc_print]} <br>";
			$mail_content .= "*OBS! Om digital kopia ska 30 SEK rabatt / kort ges kunden på betalningen. <br>";
			$mail_content .= "<b>Antal presentkort:</b> {$sanitized_post[gc_count]} <br>";
			if($sanitized_post['gc_value'] == false){
				$mail_content .= "<b>Värde:</b> {$sanitized_post[gc_custom_value]} <br>";
			}else{
				$mail_content .= "<b>Värde:</b> {$sanitized_post[gc_value]} <br>";
			}
			if($sanitized_post[gc_print] == "digital") {
				$mail_content .= "<b>Totalt pris:</b> ".$sanitized_post[gc_count]*($sanitized_post[gc_value]-30)."<br>";
			}else{
				$mail_content .= "<b>Totalt pris:</b> ".$sanitized_post[gc_count]*($sanitized_post[gc_value])."<br>";
			}
			
			$headers = "From: {$sanitized_post[gc_email]}" . "\r\n" .
			$headers  .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$to = "bokning@spason.se";
			$subject = "Presenkort " . $sanitized_post[gc_name];
			if (mail($to, $subject, $mail_content, $headers)){
				header("Location: " . home_url('/presentkort/order/'));
			}
		}
	}
	get_header();
?>
<?php 
while(have_posts()) : the_post();
	global $post; 
?>
<div class="container">
	<div id="giftcard">
		<div class="col-sm-7 info">
			<h1><?php the_title() ?></h1>
			<?php the_content(); ?>
		</div>

		<form id="form-giftcard" method="post" class="col-sm-5">
		<h2 class="heading">Beställ</h3>
			<?php if(count($errors) > 0) { ?>
			<div class="errors alert alert-danger">
				<p><strong>Formuläret innehåller <?php echo count($errors)?> fel:</strong></p>
				<ul>
					<?php foreach($errors as $error) {?>
					<li><?php echo $error; ?></li>
					<?php } ?>
				</ul>
			</div>
			<?php } // if(count($errors) > 0)?>
			<div class="form-group">
				<label for="gc_name">Beställarens Namn</label>
				<input class="form-control" type="text" name="gc_name" id="gc_name" value="<?php echo $_POST['gc_name'] ?>">
			</div>
			<div class="form-group">
				<label for="gc_email">Beställarens E-post</label>
				<input class="form-control" type="text" name="gc_email" id="gc_email" value="<?php echo $_POST['gc_email'] ?>">
			</div>
			<div class="form-group">
				<label for="gc_count">Antal Presentkort</label>
				<input class="form-control" type="number" name="gc_count" id="gc_count" value="<?php echo ($_POST['gc_count']>0)?$_POST['gc_count']: 1; ?>">
			</div>
			<div class="form-group">
				<label for="gc_value">Värde</label>
				<select class="form-control" type="text" name="gc_value" id="gc_value" value="<?php echo $_POST['gc_value'] ?>">
					<?php 
					$values = array(300,500,1000,1500,2000,2500,3000);
					foreach($values as $value){?>
						<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
					<?php } ?>
					<option value="false">Eget värde</option>
				</select>
			</div>
			<div class="form-group custom-value">
				<label for="gc_custom_value">Eget värde</label>
				<input class="form-control" type="number" name="gc_custom_value" id="gc_custom_value" value="<?php echo $_POST['gc_custom_value'] ?>" placeholder="Lägsta belopp 300 SEK">
			</div>
			<div class="radio">
			  <label>
			    <input type="radio" name="gc_print" id="gc_print_op1" value="digital" checked>
			   	Jag vill ha ett digitalt presentkort skickat via e-post och skriva ut själv <span class="color-angry-red">(-30 SEK)</span>
			  </label>
			</div>
			<div class="radio">
			  <label>
			    <input type="radio" name="gc_print" id="gc_print_op2" value="printed">
			   	Jag vill ha ett printat presentkort skickat via snigel-post
			  </label>
			</div>
			<div class="print-info">
				<div class="form-group">
					<label for="gc_address">Beställarens Adress</label>
					<input class="form-control" type="text" name="gc_address" id="gc_address" value="<?php echo $_POST['gc_address'] ?>">
				</div>
				<div class="form-group">
					<label for="gc_zip">Beställarens Postnummer</label>
					<input class="form-control" type="text" name="gc_zip" id="gc_zip" value="<?php echo $_POST['gc_zip'] ?>">
				</div>
				<div class="form-group">
					<label for="gc_city">Beställarens Ort</label>
					<input class="form-control" type="text" name="gc_city" id="gc_city" value="<?php echo $_POST['gc_city'] ?>">
				</div>
			</div>
			<h3 class="total-price"></h3>
			<button type="submit" class="btn btn-primary btn-lg btn-block">Beställ</button>
			<div class="payment-info">När du beställt skickas en faktura och en digital version av presenkortet till din e-post. När fakturan är betald aktiveras kortet. Om du beställt en printad kopia skickas denna när fakturan är betald.</div>
		</form>
	</div>
</div>
<?php endwhile; 
get_footer();
?>