<?php
namespace App\Http\Controllers;
/* 
 * Basic controller to test the cart functionality
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Routing\Controller;
use Kata\Cart\Store\Memory;

class CartController extends Controller
{
	/**
	 * Init cart and add some items;
	 */
	function index(){
		
		
		$cart = app()->make('\Kata\Cart',[ new Memory() ]);
		
		$item = new \Kata\Cart\Item();
		/**
		 * Simple array to build the items
		 * format [productId, productdescription, unit price, unit, unitdescription,  number]
		 */
		$items = [
			[1,'Chocolade',2.0,100,'g',80],
			[2,'Reep',2.5,1,'stuk',3],
		];
		foreach($items as $itemconfig){
			$cartitem = new \Kata\Cart\Item();
			$cartitem->setProductId($itemconfig[0])
				->setProductDescription($itemconfig[1])
				->setUnitPrice($itemconfig[2])
				->setUnit($itemconfig[3])
				->setUnitDescription($itemconfig[4])
				->setNumber($itemconfig[5]);
			
			// add the item to the cart
			$cart->addItem($cartitem);
		}

		
		/**
		 * Display the cart
		 */
		return view()->make('cart.index',['items' => $cart->items()]);
		
		
	}
}

