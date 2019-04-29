<?php 
session_start();
if(empty($_SESSION['login_user']))
{
  header("Location: login/sign.php");
}
include 'config.php';
include 'classes/Pickup_Order_Selection.class.php';
$order = new Pickup_Order_Selection();
$order->getPickupMenu();
unset($_SESSION['reservationID']);
  unset($_SESSION['tutorID']);
  unset($_SESSION['cateringID']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pickup Order</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="navcss.css">
</head>
<body>
  <?php if($_SESSION['login_type'] == 'Admin') include 'headers/admin.php';
  else if($_SESSION['login_type'] == 'Student')  include 'headers/student.php';
  else if($_SESSION['login_type'] == 'Faculty')  include 'headers/faculty.php';
  ?>
  <div class = 'container'>
    <div class = 'row'>
      <div class="col-md-6"><?php echo $order->printPickupMenu(); ?></div>
      <div class="col-md-6">
        <h4>Order</h4>
        <form  class = 'runningorder' action="enter/enterPickupOrder.php" method="get" style = 'display:none;'>
          <br>
          <label>Enter a Pick-up Date:</label>
          <input type="date" id="pickupdate" name="pickupdate" required>
          <br><br>
          <label>Enter a Pick-up Time:</label>
          <input type="time" id="pickuptime" min='07:30:00'  max ='15:30:00' name="pickuptime" required>
          <br><br>
          <p>Total Price: $<span class = "totaltext"></span></p>
          <button class = 'total' name ='total' value = 5 type='submit'>Enter Order</button>
        </form>
      </div>
    </div>
  </div>
</body>
<script>
  var now = new Date();

  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
  $('#pickupdate').val(today);
  $('#pickupdate').attr("min",today);



$(".addItem").click(function(){
  

  var currentitem = $(this).attr("data-name");
  var currentquantity = $(this).prev().val();
  var currentitemid = $(this).attr("data-id");
  var currentitemprice = $(this).attr("data-price");
  $(this).parent().parent().parent().parent().prev().css("display","none");
  $('.runningorder').prepend("<div class='card'><div class='card-header'><strong>"+currentitem+" </strong></div> <div class='card-body'> Quantity: <input type='number' class = 'pickupitem' name='"+currentitemid+"' value = "+currentquantity+" min='0'><br><p>Price Per Item: $<span>"+currentitemprice+"<span></p><p>Subtotal for Item: $<span class = 'sub'>"+(currentitemprice*currentquantity).toFixed(2)+"</span><br></div></div>");
  $('.runningorder').css("display", "inline");
  var total = 0;
  $.each($('.sub'), function (index, value) {
    total = total + parseFloat($(value).text());
   });
   $(".total").val(total.toFixed(2));
   $(".totaltext").text(total.toFixed(2));
});

$(".runningorder").on( "change", ".pickupitem", function(){
  $(this).next().next().next().children("span").text(($(this).val()*$(this).next().next().children("span").text()).toFixed(2));
  var total = 0;
  $.each($('.sub'), function (index, value) {
    total = total + parseFloat($(value).text());
   });
   $(".total").val(total.toFixed(2));
   $(".totaltext").text(total.toFixed(2));
});



</script>
</html>