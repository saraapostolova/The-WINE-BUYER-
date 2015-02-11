<?php
require_once("include/config.php");

include("include/basket.php");

$categoryID = $_GET['category'];
$sortBy = $_GET['sortBy'];

?>
<!DOCUMENT>
<html>
<head>
	<title>Почетна | Винарија</title>
    <?php include("include/htmlHeader.php"); ?>
</head>
<body>
	<?php include("include/nav.php"); ?>
    <div class="container content">
    	<div id="sidebar">
        	<ul>
            	<?php 
				$stmt = $mysqli->stmt_init();
				if ($stmt->prepare("SELECT ID,Name FROM Categories ORDER BY ID")){
					$stmt->execute();
					$stmt->bind_result($id,$name);
				}
				while($stmt -> fetch()){?>
            	<li <?php if($categoryID == $id){?>class="active"<?php } ?>><a href="index.php?category=<?php echo $id; ?>"><?php echo $name; ?></a></li>
                <?php }
                $stmt->close();
				?>
            </ul>
        </div>
        <div class="mainContent">
        	<form id="mainForm" name="mainForm" method="GET" action="index.php">
        	<div class="mainContentHeader">
            <?php 
				if(!empty($categoryID)){
					$stmt = $mysqli->stmt_init();
					if ($stmt->prepare("SELECT Name FROM Categories WHERE ID = ?")){
						$stmt->bind_param("s",$categoryID);
						$stmt->execute();
						$stmt->bind_result($CategoryName);
						$stmt -> fetch();
						$stmt->close();
					}
					echo '<h3><a href="index.php">Вина</a> / '.$CategoryName.'</h3>';
				}else{
					echo '<h3><a href="index.php">Вина</a></h3>';	
				}
				?>
                <div class="sortBy">
                	Сортирај&nbsp;  
                    <select onChange="document.mainForm.sortBy.value = this.value;document.mainForm.submit();">
                    	<option <?php if(empty($sortBy) || $sortBy == "1"){?>selected<?php } ?> value="1">Нормално</option>
                        <option <?php if($sortBy == "2"){?>selected<?php } ?> value="2">Цена (растечка)</option>
                        <option <?php if($sortBy == "3"){?>selected<?php } ?> value="3">Цена (опаѓачка)</option>
                        <option <?php if($sortBy == "4"){?>selected<?php } ?> value="4">Година на производство (растечка)</option>
                        <option <?php if($sortBy == "5"){?>selected<?php } ?> value="5">Година на производство (опаѓачка)</option>
                    </select>
                </div>
            </div>
        	<div>
            	<?php 
				$stmt = $mysqli->stmt_init();
				
				if(empty($categoryID)){
					
					if(empty($sortBy) || $sortBy == "1"){
						$orderBy = "Products.Category";	
					}else{
						$orderBy = returnOrderBy($sortBy);
					}
					
					$query = "SELECT Products.ID, Products.Name, Products.MadeBy, Products.Code, Products.Volume, Products.Harvest, Products.Alchohol, Products.Ancestry, Products.Price, Products.ImgURL, Categories.Name FROM Products JOIN Categories ON Products.Category = Categories.ID ORDER BY ".$orderBy."";
				}else{
					
					if(empty($sortBy) || $sortBy == "1"){
						$orderBy = "Products.ID";	
					}else{
						$orderBy = returnOrderBy($sortBy);
					}
					
					$query = "SELECT Products.ID, Products.Name, Products.MadeBy, Products.Code, Products.Volume, Products.Harvest, Products.Alchohol, Products.Ancestry, Products.Price, Products.ImgURL, Categories.Name FROM Products JOIN Categories ON Products.Category = Categories.ID WHERE Category = '".$categoryID."' ORDER BY ".$orderBy."";
				}
				if ($stmt->prepare($query)){
					$stmt->execute();
					$stmt->store_result();
					$count=$stmt->num_rows;
					$stmt->bind_result($id,$name, $madeBy, $code, $volume, $harvest, $alcho, $ancestry, $price, $img, $category);
				}
				?>
                <?php while($stmt -> fetch()){?>
                <div class="wine-box">
                	<a id="<?php echo 'product-'.$id; ?>"></a>
                	<img src="<?php echo $img; ?>" alt="..." />
                    <a href="product.php?item=<?php echo $id; ?>"><?php echo $name.' ('.$harvest.')'; ?></a>
                    <p><?php echo $price.' MKD'; ?></p>
                    <input type="button" class="btn-vine" value="Во кошничка" onClick="document.shopingCardForm.productID.value='<?php echo $id; ?>';document.shopingCardForm.productName.value='<?php echo $name; ?>';document.shopingCardForm.productPrice.value='<?php echo $price; ?>';document.shopingCardForm.productCode.value='<?php echo $code; ?>';document.shopingCardForm.submit();" />
                </div>
                <?php }
					$stmt->close();
				?>
        	</div>
            	<input type="hidden" id="category" name="category" value="<?php echo $categoryID; ?>" />
                <input type="hidden" id="sortBy" name="sortBy" value="" />
            </form>
            <form id="shopingCardForm" name="shopingCardForm" method="POST" action="index.php">
          		<input type="hidden" name="productID" id="productID" value="" />
                <input type="hidden" name="productCode" id="productCode" value="" />
                <input type="hidden" name="productName" id="productName" value="" />
                <input type="hidden" name="productPrice" id="productPrice" value="" />
            </form>
        </div> 
    </div>
</body>
</html>
<?php 
	function returnOrderBy($sortBy){
		switch($sortBy){
			case '2':
				return 'Products.Price ASC';
				break;
			case '3':
				return 'Products.Price DESC';
				break; 
			case '4':
				return 'Products.Harvest ASC';
				break; 
			case '5':
				return 'Products.Harvest DESC';
				break; 
			
		}	
	}
?>