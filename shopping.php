<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$mydbname = "crud";

$conn = new mysqli($servername,$username,$password,$mydbname);
if (isset($_POST['add_to_cart'])) {

    if(isset($_SESSION['table'])) {

        $session_array_id = array_column($_SESSION['table'], "id");
       
        if(!in_array($_GET['id'], $session_array_id)) {
         
            $session_array = array(
                'id' => $_GET['id'],
                "name" => $_POST['name'],
                "price" => $_POST['price'],
                "quantity"  => $_POST['quantity']
            );
    
            $_SESSION['table'][] = $session_array; 
        }


    }else{

        $session_array = array(
            'id' => $_GET['id'],
            "name" => $_POST['name'],
            "price" => $_POST['price'],
            "quantity"  => $_POST['quantity']
        );

        $_SESSION['table'][] = $session_array;
    }
}
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Shoppig cart</title>
            <link rel = "stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        </head>
        <body>


        <div class="container-fluid">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="text-center">Shopping cart data</h2>
                        <div class="col-md-12">
                            <div class="row">

                        
                            </html>
                            
                        <?php
                        $query = "SELECT * FROM shopping" ;
                        $result = mysqli_query($conn,$query) or die(mysqli_error($conn));






                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                        {?>
                       <div class="col-md-4">  
                        <form method ="post" action="shopping.php?id=<?=$row['id'] ?>">
                            <img src="<?= $row['image'] ?>" style='height: 50px ;'>
                            <h5 ><?= $row['product_name']; ?></h5>
                            <h5>N<?= number_format($row['price'],2); ?></h5><br>
                            <input type="hidden" name="name" value="<?= $row['product_name'] ?>">
                            <input type="hidden" name="price" value="<?= $row['price'] ?>">
                            <input type="number" name="quantity" value="1" class="form-control">
                            <input type="submit" name="add_to_cart"  class= "btn btn-warning btn block my-2" value="Add to cart">


                        </form>
                     </div>  



                        <?php } 


                        ?>
                        
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                        <h1 class="text-center" class="color-blue">Item Selected</h1>

                        <?php
                            
                         $total =0;

                       $output = "";

                       $output .= "
                       <table class='table table-bordered table-striped'>
                       <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Item price</th>
                        <th>Item quantity</th>
                        <th>Total price</th>
                        <th>Action</th>
                       </tr>
                 ";
                  

                 if(!empty($_SESSION['table'])) {


                    foreach ($_SESSION['table'] as $key => $value) {

                        $output .="
                        <tr>
                          <td>".$value['id']."</td>
                          <td>".$value['name']."</td>
                          <td>".$value['price']."</td>
                          <td>".$value['quantity']."</td>
                          <td>N".number_format($value['price'] * $value['quantity'])."</td>
                          <td>
                             <a href='shopping.php?action=remove&id=".$value['id']."'>
                             <button class='btn btn-danger btn-block'>Remove</button>
                             </a>
                          </td>
                        </tr>

                        ";

                        $total = $total + $value['quantity'] * $value['price'];
                    }

                    $output .= "
                      <tr>
                       <td colspan='3'></td>
                       <td></b>Total price</b></td>
                       <td>".number_format($total,2)."</td>
                       <td>
                       <a href= 'shopping.php?action=clearall'>
                       <button class='btn btn-warning'>Clear</button>
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

            <?php
            
            if(isset($_GET['action'])) {


                if ($_GET['action'] == "clearall") {
                    unset($_SESSION['table']);
                }


                if($_GET['action'] == "remove") {


                    foreach ($_SESSION['table'] as $key => $value) {

                        if($value['id'] == $_GET['id']) {
                            unset($_SESSION['table'][$key]);
                        }
                    }
                }
            

            }
                    
                        ?>

        </div>
        </body>
    </html>