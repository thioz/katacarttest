<?php

namespace App\Rules;

use Kata\Cart\Item;
use Kata\Cart\Rule;

class CombinationDiscount extends Rule
{

	protected $combinationRules = [];
	protected $giveaways = [];

	public function __construct($productId = false)
	{
		parent::__construct($productId);
		$this->setup();
	}

	protected function setup()
	{
		$local = $this;

		$this->success(
			
			function($rule, $item, $context) use ($local){
				$context->cancel();
				
				$giveAways = $local->getGiveAways();
				
				foreach($giveAways as $giveAway){
					$discountItem = new Item();
					$discountItem->setProductDescription($giveAway['description'])
						->setQuantity($giveAway['quantity'])
						->setUnitDescription('stuk');
					$discountItem->setUnitPrice( $giveAway['price'] );

					$context->container()->cart()->addItem($discountItem);
				}
			}
		);
	}
 
	/**
	 * 
	 * @param int $productId
	 * @param int $quantity
	 * @return CombinationDiscount
	 */
	public function addGiveAway($productId, $description,$price = 0, $quantity = 1)
	{
		$this->giveaways[] = [
			'product_id' => $productId,
			'price' => $price,
			'description' => $description, 
			'quantity' => $quantity,
		];
		return $this;
	}

	public function getGiveAways()
	{
		return $this->giveaways;
	}


}
