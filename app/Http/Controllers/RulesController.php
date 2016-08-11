<?php
namespace App\Http\Controllers;
/* 
 * Basic controller to test the cart functionality
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Routing\Controller;
use Kata\Cart\Item;
use Kata\Cart\Rule\Container;
use Kata\Cart\Store\Memory;

class RulesController extends Controller
{
	/**
	 * Init cart and add some items;
	 */
	function index(){
		
		
		$cart = app()->make('\Kata\Cart',[ new Memory() ]);
		$rulesContainer = new Container($cart);

		/**
		 * Discounts that cannot be combined with other discounts should go on top so they can cancel the rule chain
		 */
		
		// 1 kilo of strooigoed and 3 chocolate letters gives you a free box of chocolates ... how nice of dear old sinterklaas 
		$rulesContainer->add( 
			( new \App\Rules\CombinationDiscount(5) )
			->assert( (new \App\Rules\Asserts\MinimumQuantity() )->setRules(5, 3) )
			->assert( (new \App\Rules\Asserts\MinimumQuantity() )->setRules(3, 1000) )
			->addGiveAWay(4,'Gratis doos bonbons')
		);
		
		// 6 chocolate letters and 3 boxes of chocolate milk gets you a 8.5 discount ... and probably type 2 diabetis
		$rulesContainer->add( 
			( new \App\Rules\CombinationDiscount(5) )
			->assert( (new \App\Rules\Asserts\MinimumQuantity() )->setRules(5, 6) )
			->assert( (new \App\Rules\Asserts\MinimumQuantity() )->setRules(7, 3) )
			->addGiveAWay(0,'Winter aanbieding korting',-8.5)
		);
		
		$rulesContainer->add( (new \App\Rules\DiscountQuantity(2))->setTargetQuantity(3)->setDiscountQuantity(1) );
		$rulesContainer->add( (new \App\Rules\DiscountQuantity(5))->setTargetQuantity(3)->setDiscountAmount(2) );
		$rulesContainer->add( (new \App\Rules\BulkDiscount(6))->addBulkRule(12, 6)->addBulkRule(6, 2) );
		
		
		/**
		 * Simple array to build the items
		 * format [productId, productdescription, unit price, unit, unitdescription,  quatity]
		 */
		
		$items = [
			[1,'Chocolade',2.0,100,'g',80],
			[2,'Reep',2.5,1,'stuk',5],
			[3,'Strooigoed', 4, 1000, 'g', 800],
			[4,'Bonbons',5, 1,['doos','dozen'],2],
			[5,'Chocolade letter',3, 1,['stuk','stuks'], 6],
			[6,'Chocolade donuts',1, 1,['stuk','stuks'],19],
			[7,'Chocolade melk',1, 1,['pak','pakken'], 3],
			
		];
		
		foreach($items as $itemconfig){
			$cartitem = new Item();
			$cartitem->setProductId($itemconfig[0])
				->setProductDescription($itemconfig[1])
				->setUnitPrice($itemconfig[2])
				->setUnit($itemconfig[3])
				->setUnitDescription($itemconfig[4])
				->setQuantity($itemconfig[5]);
			
			// add the item to the cart
			$cart->addItem($cartitem);
		}
		
		$rulesContainer->process();
		
		/**
		 * Display the cart ... usually some sort of mechanism should be implemented to decorate te amounts and stuff like that
		 * this could be done in this case with a sort of CartStoreDecorator or Formatter 
		 */
		return view()->make('cart.index',['items' => $cart->items(),'cart' => $cart]);
		
		
	}
}

