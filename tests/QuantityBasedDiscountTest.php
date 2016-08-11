<?php

use App\Rules\BulkDiscount;
use App\Rules\DiscountQuantity;
use Kata\Cart\Item;
use Kata\Cart\Rule\Container;

class QuantityBasedDiscountTest extends CartTestCase
{

	protected $basicexamples = [
		['products' => [2 => 1, 5 => 1], 'total' => 7.5],
		['products' => [2 => 6, 5 => 3], 'total' => 23.0],
		['products' => [2 => 5, 3 => 1000], 'total' => 14],
		['products' => [6 => 7, 3 => 3000], 'total' => 17],
		['products' => [2 => 4, 3 => 1400, 4 => 1], 'total' => 18.1],
		['products' => [2 => 10, 3 => 4500, 4 => 2, 5 => 15, 7 => 5], 'total' => 115.5],
		['products' => [6 => 18], 'total' => 10],
		['products' => [6 => 20], 'total' => 12],
	];
	
	protected $breakexamples = [
		['products' => [2 => 1, 5 => 1], 'total' => 7.5],
		['products' => [2 => 6, 5 => 3], 'total' => 23.0],
		['products' => [2 => 5, 3 => 1000], 'total' => 14],
		['products' => [6 => 7, 3 => 3000], 'total' => 17],
		['products' => [2 => 4, 3 => 1400, 4 => 1], 'total' => 18.1],
		['products' => [2 => 10, 3 => 4500, 4 => 2, 5 => 15, 7 => 5], 'total' => 133],
		['products' => [6 => 18], 'total' => 10],
		['products' => [6 => 20], 'total' => 12],
	];

	/**
	 * Simple ex
	 *
	 * @return void

	 * 	 */
	public function testBasicCartRules()
	{
		foreach ($this->basicexamples as $example)
		{
			$cart = $this->createCart();
			$rulesContainer = new Container($cart);

			$rulesContainer->add((new DiscountQuantity(2))->setTargetQuantity(3)->setDiscountQuantity(1));
			$rulesContainer->add((new DiscountQuantity(5))->setTargetQuantity(3)->setDiscountAmount(2));
			$rulesContainer->add((new BulkDiscount(6))->addBulkRule(12, 6)->addBulkRule(6, 2));

			$items = $example['products'];
			$targetPrice = $example['total'];

			foreach ($items as $productId => $quantity)
			{

				$product = $this->getProductById($productId);


				$cart->addItem(
					(new Item())->setProduct($productId, $product['description'])
						->setQuantity($quantity)
						->setUnit($product['unit'])
						->setUnitPrice($product['unitprice'])
						->setUnitDescription($product['unitdescription'])
				);
			}

			$rulesContainer->process();
			$this->assertEquals(round($cart->totalamount(), 2), $targetPrice);
		}
	}
	
	public function testChainCancel()
	{
		foreach ($this->breakexamples as $example)
		{
			$cart = $this->createCart();
			$rulesContainer = new Container($cart);
			
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

			$rulesContainer->add((new DiscountQuantity(2))->setTargetQuantity(3)->setDiscountQuantity(1));
			$rulesContainer->add((new DiscountQuantity(5))->setTargetQuantity(3)->setDiscountAmount(2));
			$rulesContainer->add((new BulkDiscount(6))->addBulkRule(12, 6)->addBulkRule(6, 2));

			$items = $example['products'];
			$targetPrice = $example['total'];

			foreach ($items as $productId => $quantity)
			{

				$product = $this->getProductById($productId);


				$cart->addItem(
					(new Item())->setProduct($productId, $product['description'])
						->setQuantity($quantity)
						->setUnit($product['unit'])
						->setUnitPrice($product['unitprice'])
						->setUnitDescription($product['unitdescription'])
				);
			}

			$rulesContainer->process();
			$this->assertEquals(round($cart->totalamount(), 2), $targetPrice);
		}
	}

}
