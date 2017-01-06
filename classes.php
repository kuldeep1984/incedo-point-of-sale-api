<?php

/**
 * Product Class
 * - To manage the individual Products
 */
class Product {

    //declaring product properties
    public $name;
    public $price_per_unit;
    public $group_units;
    public $group_price;

    //Initializing the product properties
    public function __construct($name, $price_per_unit, $group_units, $group_price) {
        $this->name = $name;
        $this->price_per_unit = $price_per_unit;
        $this->group_units = $group_units;
        $this->group_price = $group_price;
    }

}

/**
 * ProductCart Class
 * - To manage the cart
 */
class ProductCart {

    //declaring the cart Properties
    public $cart_product;
    public $product_count;

    //initializing the cart properties
    public function __construct(Product $product) {

        $this->cart_product = $product; //adding product into cart
        $this->product_count = 1; //default value would be 1
    }

}

/**
 * Terminal Class
 * - The main class to manage the Point Of Sale Api
 */
class Terminal {

    //declaring the Terminal Properties
    private $product_list; //Contain Available Product List
    private $cart_list; //Contain Cart Products
    private $cart_total; //Contain total amount of Current Cart

    //Initializing the Terminal Properties

    public function __construct() {

        $this->cart_list = array();
        $this->product_list = array();
        $this->cart_total = 0;
    }

    /**
     *  Function set_pricing :- To set the product pricing
     * @param type $product_code
     * @param type $price_per_unit
     * @param type $group_units
     * @param type $group_price
     */
    public function set_pricing($product_code, $price_per_unit, $group_units = 1, $group_price = 1) {

        if (array_key_exists($product_code, $this->product_list)) { //if product pricing already set
            $product_object = $this->product_list[$productCode];
            $product_object->price_per_unit = $price_per_unit;
            $product_object->group_units = $group_units;
            $product_object->group_price = $group_price;
        } else {
            //If it is new Product
            $this->product_list[$product_code] = new Product($product_code, $price_per_unit, $group_units, $group_price);
        }
    }

    /**
     * - Function scan :- Adding the Available Products into Cart
     * @param type $products
     */
    public function scan($products) {

        for ($i = 0; $i < strlen($products); $i++) {//iterating through the given product list
            $product_code = $products[$i];
            if (array_key_exists($product_code, $this->cart_list)) { //if product already in CART

                $product_cart_obj = $this->cart_list[$product_code]; //fetching the Cart Product
                $product_cart_obj->product_count++; // Incrementing the Cart Product Frequnecy
                
            } elseif (isset($this->product_list[$product_code])) { //if product new to Cart

                $this->cart_list[$product_code] = new ProductCart($this->product_list[$product_code]);
            }
        }
    }

    /**
     * - Function :- Calculating the total amount of Cart Products
     * @return type
     */
    public function total() {

        if (!empty($this->cart_list)) { //if Cart is not empty
            foreach ($this->cart_list as $cart_product_obj) {
                
                $cart_product = $cart_product_obj->cart_product;

                $total = 0;
                if ($cart_product->group_units > 1 && $cart_product_obj->product_count > 1) {//if grouped price is given and product count is greater than one
                    $total = (intval($cart_product_obj->product_count % $cart_product->group_units) * $cart_product->price_per_unit) + (intval($cart_product_obj->product_count / $cart_product->group_units) * $cart_product->group_price);
                } else {
                    $total = ($cart_product->price_per_unit * $cart_product_obj->product_count);
                }

                $this->cart_total += $total;
            }
        }

        return number_format($this->cart_total,2);
    }

}
