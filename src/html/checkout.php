<?php 
	session_start();
	if(!$_SESSION['username']) {
		header("Location: index.php");
		die();
	}
	$ref = $_SERVER['HTTP_REFERER'];
  if($ref !== 'https://localhost:1337/shoppingcart.php') {
	die("No permission");
  }
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="bootstrapPaper.css">
  </head>
  <body>

    <?php
    include __DIR__ . '/navbar.php'
     ?>

  <div class="col-md-1">
  </div>
  <div class="col-md-10">
<h2> Checkout </h2>

<?php
  require_once __DIR__ . "/../inc/DatabaseHelper.php";
  $db = new DatabaseHelper();
  
  if(array_key_exists('shopping_cart', $_SESSION)) {
    $cart = $_SESSION['shopping_cart'];
  }
  #Check if cart contains zero items
  if (count($cart) == 0){
    $cart = array();
	header("Location: productlist.php");
    die;
  }
	#CSRF token protection
	$_SESSION['ctoken'] = uniqid();
	$ctoken = $_SESSION['ctoken'];

  $products = $db->get_products_with_id_numbers(array_keys($cart));
  $sum = 0;

  foreach($products as $thisproduct){
    $price = $thisproduct->price();
    $sum += $cart[$thisproduct->id()]*$price;
  }

	$username = $_SESSION['username'];

  echo "<div class='col-md-2'></div><div class='col-md-2'><h5>Username: </h5></div><div class='col-md-2'><h5>". $username . "</h5></div><div class='col-md-6'></div>";
  echo "<div class='col-md-12'></div>";
  echo "<div class='col-md-2'></div><div class='col-md-2'><h5>Total sum: </h5></div><div class='col-md-2'><h5>". $sum . " kr</h5></div><div class='col-md-6'></div>";

 ?>
<div class='col-md-12' style="height:30px;"></div>
<div class='col-md-2'></div>
<div class="col-md-6">
		<form name="payform" action="paymentcheck.php" onsubmit="return validateForm()" method="POST" class="form-horizontal">
  <fieldset>
    <legend>Enter Valid Payment information</legend>
    <div class="form-group" >
      <label for="inputCardNumber" class="col-lg-2 control-label">Card Number</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputCardNumber" placeholder="0000 0000 0000 0000">
	  </div>
    </div>
    <div class="form-group" >
      <label for="inputCVC" class="col-lg-2 control-label">CVC code</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputCVC" placeholder="XXX">
      </div>
    </div>
    <div class="form-group">
      <label for="inputName" class="col-lg-2 control-label">Full name</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputName" placeholder="Full name">
      </div>
    </div>
	<div class="form-group">
      <label for="inputName" class="col-lg-2 control-label">Password</label>
      <div class="col-lg-10">
        <input type="password" class="form-control" name="pwd" placeholder="******">
      </div>
    </div>
	<input type="hidden" class="form-control" name="ctoken" value="<?php echo $ctoken;?>" />
    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <button type="reset" class="btn btn-default">Reset fields</button>
        <button type="submit" class="btn btn-primary">Pay</button>
      </div>
    </div>
  </fieldset>
</form>
</div>

</div>
  </body>
<script>
function validateForm() {
    var card = document.forms["payform"]["inputCardNumber"].value;
	var cvc = document.forms["payform"]["inputCVC"].value;
	var name = document.forms["payform"]["inputName"].value;
	card = card.replace(/\s+/g, '');
	cvc = cvc.replace(/\s+/g, '');
	name = name.replace(/\s+/g, '');
    if (card.length != 16 || cvc.length != 3 || name.length < 1) {
        alert("Please enter the information in the correct format");
        return false;
    }
}
</script>
</html>
