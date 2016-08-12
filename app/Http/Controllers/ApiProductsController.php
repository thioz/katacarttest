<?php

namespace App\Http\Controllers;

/*
 * Basic controller to test the cart functionality
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kata\Cart\Item;
use Kata\Cart\Rule\Container;
use Kata\Cart\Store\Memory;

class ApiProductsController extends Controller
{
	/**
	 * Get all products
	 */
	function index()
	{
		$products = config('products');

		return response()->json($products);
	}

	function rules()
	{
		$data = [
			[
				'id' => 1,
				'description' => 'Sinterklaar actie'
			],
			[
				'id' => 2,
				'description' => 'Winteractie'
			],
			[
				'id' => 3,
				'description' => 'Chocolade repen: € 2,50 per reep, 3 voor de prijs van 2. '
			],
			[
				'id' => 4,
				'description' => 'Chocolade letters: € 3,- euro per letter, 3 letters voor € 7,- euro. '
			],
			[
				'id' => 5,
				'description' => 'Chocolade donuts: € 6,- voor een dozijn, € 4,- voor een half dozijn en € 1,- euro per stuk'
			],
		];

		return response()->json($data);
	}

	function update(Request $request)
	{
		$items = $request->input('items', []);
		$rules = $request->input('rules', []);
		$products = config('products');

		$cart = app()->make('\Kata\Cart', [ new Memory()]);
		$rulesContainer = new Container($cart);

		foreach ($rules as $id => $val)
		{
			if ($val == true)
			{
				switch ($id)
				{
					case 1:
						// 1 kilo of strooigoed and 3 chocolate letters gives you a free box of chocolates ... how nice of dear old sinterklaas 
						$rulesContainer->add(
							( new \App\Rules\CombinationDiscount(5))
								->assert((new \App\Rules\Asserts\MinimumQuantity())->setRules(5, 3))
								->assert((new \App\Rules\Asserts\MinimumQuantity())->setRules(3, 1000))
								->addGiveAWay(4, 'Gratis doos bonbons')
						);
						break;
					case 2:
						// 6 chocolate letters and 3 boxes of chocolate milk gets you a 8.5 discount ... and probably type 2 diabetis
						$rulesContainer->add(
							( new \App\Rules\CombinationDiscount(5))
								->assert((new \App\Rules\Asserts\MinimumQuantity())->setRules(5, 6))
								->assert((new \App\Rules\Asserts\MinimumQuantity())->setRules(7, 3))
								->addGiveAWay(0, 'Winter aanbieding korting', -8.5)
						);
						break;
					case 3:
						$rulesContainer->add((new \App\Rules\DiscountQuantity(2))->setTargetQuantity(3)->setDiscountQuantity(1));
						break;
					case 4:
						$rulesContainer->add((new \App\Rules\DiscountQuantity(5))->setTargetQuantity(3)->setDiscountAmount(2));
						break;
					case 5:
						$rulesContainer->add((new \App\Rules\BulkDiscount(6))->addBulkRule(12, 6)->addBulkRule(6, 2));
						break;
				}
			}
		}


		foreach ($items as $item)
		{
			$cartitem = new \Kata\Cart\Item();
			$cartitem->setProductId($item['id'])
				->setProductDescription($item['description'])
				->setUnitPrice($item['unitprice'])
				->setUnit($item['unit'])
				->setUnitDescription($item['unitdescription'])
				->setQuantity($item['quantity']);

			// add the item to the cart
			$cart->addItem($cartitem);
		}
		
		$rulesContainer->process();
		
		$response = ['items' => [], 'total' => 0];
		foreach ($cart->items() as $i => $item)
		{
			$row = [
				'rownum' => $i,
				'description' => $item->description(),
				'quantity' => $item->quantity(),
				'unit' => $item->unit(),
				'unitprice' => $item->price(),
				'unitdescription' => $item->unitdescription(),
				'id' => $item->productId(),
				'amount' => $item->totalamount(),
			];
			$response['items'][] = $row;
		}
		
		
		$response['total'] = $cart->totalamount();
		return response()->json($response);
	}

}
