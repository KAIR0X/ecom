<? require_once("config.php");?>

<?php


    if(isset($_GET['add'])){
        

        $query =query("SELECT * FROM products WHERE product_id=" .escape_string($_GET['add'])." ");
        confirm($query);

        while($row = fetch_array($query)){

            if($row['product_quantity'] != $_SESSION['product_' . $_GET['add']]){

                $_SESSION['product_' . $_GET['add']]+=1;
                redirect("../public/checkout.php");
            
            }else{
                set_message("We only have " . $row['product_quantity'] . " {$row['product_title']}" ." available");
                redirect("../public/checkout.php");
            }
            
        }

        // confirm($query);

        // $_SESSION['product_' . $_GET['add']] +=1;

        // redirect("index.php");

    }

    if(isset($_GET['remove'])){

        $_SESSION['product_' . $_GET['remove']]--;
        if($_SESSION['product_' . $_GET['remove']] < 1){

            unset($_SESSION['item_total']);
            unset($_SESSION['item_quantity']);

            redirect("../public/checkout.php");
        
        }else{

        redirect("../public/checkout.php");
        
        }
    
    }

    if(isset($_GET['delete'])){

        $_SESSION['product_' . $_GET['delete']] = '0';

        unset($_SESSION['item_total']);
        unset($_SESSION['item_quantity']);

        redirect("../public/checkout.php");
        
    }

function cart(){

    $total = 0;
    $item_quantity = 0;


    //paypal variables
    $item_name = 1;
    $item_number = 1;
    $amount = 1;
    $quantity =1;

    foreach ($_SESSION as $name => $value) {

        if($value > 0 ) {

            if(substr($name, 0, 8 ) == "product_"){

                $length = strlen($name); // find the length of the product name //Donot use -8
                $id = substr($name, 8 , $length); //id of the individual session

                $query = query("SELECT * FROM products WHERE product_id = ". escape_string($id) . "");
                confirm($query);
     

                while($row = fetch_array($query)) {

                    $sub = $row['product_price']*$value;
                    $item_quantity += $value;

                    $product_image = display_image($row['product_image']);

                    $product = <<<DELIMETER
                    <tr>
                        <td>{$row['product_title']}<br>

                        <img width='100' src='../resources/{$product_image}'>

                        </td>
                        <td>&#36;{$row['product_price']}</td>
                        <td>{$value}</td>
                        <td>&#36;{$sub}</td>

                        <td><a class='btn btn-warning' href="../resources/cart.php?remove={$row['product_id']}"><span class='glyphicon glyphicon-minus'></span></a>     <a class='btn btn-success'href="../resources/cart.php?add={$row['product_id']}"><span class='glyphicon glyphicon-plus'></span></a>      <a class='btn btn-danger'href="../resources/cart.php?delete={$row['product_id']}"><span class='glyphicon glyphicon-trash'></span></a></td>
                    </tr>

                       <INPUT TYPE="hidden" NAME="item_name_{$item_name}" VALUE="{$row['product_title']}">
                       <INPUT TYPE="hidden" NAME="item_number_{$item_number}" VALUE="{$row['product_id']}">
                       <INPUT TYPE="hidden" NAME="amount_{$amount}" VALUE="{$row['product_price']}">
                       <INPUT TYPE="hidden" NAME="quantity_{$quantity}" VALUE="{$value}">

                    DELIMETER;

                    echo $product;

                        $item_name++;
                        $item_number++;;
                        $amount++;
                        $quantity++;

                $_SESSION['item_total'] = $total += $sub; 
                $_SESSION['item_quantity'] = $item_quantity;

                }
            }

        }
       
    }

}


function  show_paypal(){

    if(isset($_SESSION['item_quantity']) && ($_SESSION['item_quantity'] >= 1)){

    $paypal_button = <<<DELIMETER
    <INPUT TYPE="image" NAME="upload" SRC="https://imgssl.constantcontact.com/kb/ThirdParty/paypal-check-out-button.png" BORDER="0" ALT="Make payments with PayPal - it's fast, free and secure!">
    DELIMETER;

    return $paypal_button;
 
    }
} 


//FUNCTION TO SHOW ORDER REPORT FOR ADMIN

function process_transaction(){

    if(isset($_GET['tx'])){

    $amount = $_GET['amt'];
    $currency = $_GET['cc'];
    $transaction = $_GET['tx'];
    $status = $_GET['st'];
    $total = 0;
    $item_quantity = 0;

    foreach ($_SESSION as $name => $value) {

        if($value > 0 ) {

            if(substr($name, 0, 8 ) == "product_"){

                $length = strlen($name); // find the length of the product name
                $id = substr($name, 8 , $length); //id of the individual session

                //INSERTS INTO ORDER TABLE IN DATABASE
                $send_order = query("INSERT INTO orders (order_amount, order_transaction, order_status, order_currency) VALUES('{$amount}','{$transaction}','{$status}','{$currency}')");
                $last_id = last_id();
                confirm($send_order);

                //GET INDIVIDUAL PRODUCT DETAILS BY USING PRODUCT ID
                $query = query("SELECT * FROM products WHERE product_id = ". escape_string($id) . "");
                confirm($query);
     

                while($row = fetch_array($query)) {
                    $product_price = $row['product_price'];
                    $product_title = $row['product_title']; 
                    $sub = $row['product_price']*$value;
                    $item_quantity += $value;
                    

                
                   //INSERTS INTO ORDER TABLE IN DATABASE
                    $insert_report= query("INSERT INTO reports (product_id, order_id, product_title, product_price, product_quantity) VALUES('{$id}','{$last_id}','{$product_title}','{$product_price}','{$value}')");
                    confirm($insert_report);

                }

                $total += $sub; 
                echo $item_quantity;

            }

        }
       
    }

    session_destroy();
    }else{
        redirect("index.php");
        }
}

?>