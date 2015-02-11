<?php

if(isset($_GET['msg'])){
	$msg=$_GET['msg'];
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	if(isset($_POST['contactName']) && !empty($_POST['contactName']) && isset($_POST['contactEmail']) && !empty($_POST['contactEmail']) && isset($_POST['contactMsg']) && !empty($_POST['contactMsg']) ){
		$name=$_POST['contactName'];
		$email=$_POST['contactEmail'];
		$message=$_POST['contactMsg'];
	
		//$to="arapova_15@hotmail.com";
		$to="nikola_garvanliev@hotmail.com";
		$subject = "Винарија \"Симка\"";
		$header='From: '.$name."\r\n";
		$header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
		$message="Од: ".$name."\r\nЕмаил: ".$email."\r\nПорака:\r\n".$message;

		if(mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header)){
			$msg='1';
		}else{
			$msg='2';	
		}

	}else{
		$msg="3";
	}
	header("Location: http://www.ng-development.com/simka/contact.php?msg=".$msg);
}
?>
<!DOCUMENT>
<html>
<head>
	<title>Контакт | Винарија</title>
    <?php include("include/htmlHeader.php"); ?>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript">
	  function initialize() {
		var mapCanvas = document.getElementById('map');
		var myLatlng = new google.maps.LatLng(41.4308672,22.6483443);
		var mapOptions = {
		  center: myLatlng,
		  zoom: 8,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		var map = new google.maps.Map(mapCanvas, mapOptions);
		 var marker = new google.maps.Marker({
		  position: myLatlng,
		  map: map,
	  });
		
	  }
	   google.maps.event.addDomListener(window, 'load', initialize);
	</script>
</head>
<body>
	<?php include("include/nav.php"); ?>
	<div class="container content">
    	<div id="map"></div>
    	<div class="contactFormCntr">
            <form id="contactForm" name="contactForm" acttion="contact.php" method="POST">
                <?php if(!empty($msg)){?>
                    <h3 class="info"><?php
						switch($msg){
							case '1':
								echo 'Пораката е успешно пратена. Врати се на <a href="index.php">почетна страна?</a>';
								break;
							case '2':
								echo 'Пораката не е успешно пратена. <a href="contact.php">Обидете се повторно?</a>';
								break;
							case '3':
								echo 'Сите полиња се задолжителни!!!';
								break;
								
						}
					 ?>
                     </h3>
                <?php }
				
				if(empty($msg) || $msg=="3"){ ?>
                <div class="form-row">
                    <label>Име и Презиме *</label>
                    <input type="text" id="contactName" name="contactName"  />
                </div>
                <div class="form-row">
                    <label>Емаил *</label>
                    <input type="text" id="contactEmail" name="contactEmail"  />
                </div>
                <div class="form-row">
                    <label>Порака *</label>
                    <textarea rows="8"  id="contactMsg" name="contactMsg"></textarea>
                </div
                ><div class="form-row">
                    <input type="submit" class="btn-vine" value="Прати" />
                </div>
                <?php } ?>
            </form>
        </div>
	</div>
    <?php include("include/footer.php"); ?>
</body>
</html>