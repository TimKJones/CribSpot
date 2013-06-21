 <?php 
        $counter = 0;
        foreach($orderItems as $orderItem){
            echo $this->element('ShoppingCart/featured-listing', array("fl"=>$orderItem, "price"=>$orderItem->price, "id"=>$counter++));
        }
        ?>