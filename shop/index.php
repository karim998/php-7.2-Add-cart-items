<?php

session_start();

$connect=mysqli_connect("localhost","root","","shop");

if(isset($_POST['add_to_cart'])){

    if(isset($_SESSION['cart'])){

        $session_array_id=array_column($_SESSION['cart'],"id");
        if(! in_array($_GET['id'], $session_array_id)){

            $session_array= array(
                'id' => $_GET['id'],
                "name" => $_POST['name'],
                "price" => $_POST['price'],
                "quantity" => $_POST['quantity']
        
            );
            $_SESSION['cart'][]= $session_array;
        }

}else{

    $session_array=array(
        'id'=> $_GET['id'],
        "name"=> $_POST['name'],
        "price"=> $_POST['price'],
        "quantity"=> $_POST['quantity']

    );
    $_SESSION['cart'][]= $session_array;

}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>

    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
            <div class="col-md-6">
            <h2 class="text-center">Shopping Cart Date</h2> 
            <div class="col-md-12">
                <div class="row">

            <?php
            
            $query="SELECT * FROM cart_item";
            $result=mysqli_query($connect,$query);

            while($row=mysqli_fetch_array($result)) { ?>
             <div class="col-md-4">
                <form method="post" action="index.php?id=<?=$row['id'] ?>">
                       <img src="img/<?= $row['img'] ?>" style='height: 150px;'>
                       <h5 class="text-center"><?= $row['name']; ?></h5>
                       <h5 class="text-center">$<?= number_format($row['price'],2); ?></h5>
                       <input type="hidden" name="name" value="<?= $row['name'] ?>">
                       <input type="hidden" name="price" value="<?= $row['price'] ?>">
                       <input type="number" name="quantity" value="1" class="form-control">
                       <input type="submit" name="add_to_cart" class="btn btn-warning btn-block my-2" value="Add To Cart"> 

                </form>
                </div>
         <?php 
          }


            ?>

         </div>
         </div>
        </div>
        <div class="col-md-6">
            <h2 class="text-center">Item Selected</h2>


            <?php

            $total=0;

            $output="";
            $output.="
            <table class='table table-bordered table-striped'>
            <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Item Price</th>
            <th>Item Quantity</th>
            <th>Total Price</th>
            <th>Action</th>
          
            </tr>
            ";
            if(!empty($_SESSION['cart'])){

                foreach($_SESSION['cart'] as $key => $value) {

                    $output .= "
                    <tr>
                    <td>".$value['id']."</td>
                    <td>".$value['name']."</td>
                    <td>".$value['price']."</td>
                    <td>".$value['quantity']."</td>
                    <td>$".number_format($value['price'] * $value['quantity'],2)."</td>
                    <td>
                    <a href='index.php?action=remove&id=".$value['id']."'>
                    <button class='btn btn-danger btn-block'>Remove</button>
                    </a>
                    </td>
                    
                    ";

                    $total=$total+$value['quantity'] * $value['price'];

                }

                 
                //for total column
                $output .="
                <tr>
                <td colspan='3'></td>
                <td></b>Total Price</b></td>
                <td>".number_format($total,2)."</td>
                   
                
                <td>
                     <a href='index.php?action=clearall'>
                     <button class='btn btn-warning btn-block'>Clear</button>  
                    </a>
                
                </td>
                
                </tr>
                ";


            }


            echo $output;
            ?>

         </div>
         </div>
         </div>
         </div>
               
         <?php

           //for clear button
         if(isset($_GET['action'])){

            if($_GET['action']=="clearall"){
                unset($_SESSION['cart']);

            }

              //for remove button
            if($_GET['action']=="remove"){
                foreach($_SESSION['cart'] as $key=> $value){
                    if($value['id']== $_GET['id']){
                        unset($_SESSION['cart'][$key]);
                    }
                }

         }
        }


         ?>

    
</body>
</html>