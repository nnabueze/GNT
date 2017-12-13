

<!DOCTYPE html>
<html>
<head>
<title>
	CentralPay
</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
body {
	margin-top: 20px;
}
</script>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-8">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="panel-title">
							<div class="row">
								<div class="col-xs-6">
									<h5><span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart</h5>
								</div>
								<div class="col-xs-6">
									<button type="button" class="btn btn-primary btn-sm btn-block">
										<span class="glyphicon glyphicon-share-alt"></span> Continue shopping
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-body">

						<form name="cpay" action="/pay">
						<input type="hidden" name="merchant_id" value="NIBSS0000000045" />
						<input type="hidden" name="product_id" value="123" />
						<input type="hidden" name="product_description" value="Gucci Men Shoe" />
						
						<input type="hidden" name="currency" value="566" />
						<input type="hidden" name="name" value="eze" />
						<input type="hidden" name="phone" value="08035400839" />



						<div class="row">
							<div class="col-xs-2"><img class="img-responsive" src="http://placehold.it/100x70">
							</div>
							<div class="col-xs-4">
								<h4 class="product-name"><strong>Product name</strong></h4><h4><small>Product description</small></h4>
							</div>
							<div class="col-xs-6">
								<div class="col-xs-6 text-right">
									<input type="text" name="transaction_id" value="" placeholder='transaction id' />
								</div>
								<div class="col-xs-4">
									<input type="text" name="amount" value="" placeholder='amount' />
								</div>

							</div>
						</div>

						<hr>
					<div class="panel-footer">
						<div class="row text-center">
							<div class="col-xs-9">
							</div>
							<div class="col-xs-3">
								<input type="submit" name="submit" class="btn btn-success btn-block" value="Checkout" />
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>