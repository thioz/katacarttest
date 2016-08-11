<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BasicCartTest extends CartTestCase
{

	protected $examples = [
		['products' => [2 => 1, 5 => 1], 'total' => 7.5],
		['products' => [2 => 6, 5 => 3], 'total' => 30],
		['products' => [2 => 5, 3 => 1400], 'total' => 18.10],
		['products' => [6 => 7, 3 => 3000], 'total' => 19],
		['products' => [2 => 4, 3 => 1400,4=>1], 'total' => 20.60],
		['products' => [2 => 10, 3 => 4500,4=>2,5=>15,7=>5], 'total' => 133],
	];

	/**
	 * Simple ex
	 *
	 * @return void
	 */
	public function testBasicCartAmounts()
	{
		foreach ($this->examples as $example)
		{
			$cart = $this->createCart();
			$items = $example['products'];
			$targetPrice = $example['total'];

			foreach ($items as $productId => $quantity)
			{

				$product = $this->getProductById($productId);


				$cart->addItem(
					(new \Kata\Cart\Item())->setProduct($productId, $product['description'])
						->setQuantity($quantity)
						->setUnit($product['unit'])
						->setUnitPrice($product['unitprice'])
						->setUnitDescription($product['unitdescription'])
				);
			}

			$this->assertEquals(round($cart->totalamount(),2), $targetPrice);
		}
	}

}
