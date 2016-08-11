<?php

namespace App\Rules;

use Kata\Cart\Item;
use Kata\Cart\Rule;

class BulkDiscount extends Rule
{

	protected $bulkRules = [];
	protected $discountAmount = 0;

	public function __construct($productId = false)
	{
		parent::__construct($productId);
		$this->setup();
	}

	protected function setup()
	{
		$local = $this;
		$this->assert(
			function($rule, $item, $context) use ($local){

				$bulkRules = $local->getBulkRules();
				/**
				 * Sort the rules by quantity ascending to pick the lowest target quantity;
				 */
				usort($bulkRules, function($a, $b)
				{
					return $a['quantity'] < $b['quantity'] ? -1 : 1;
				});

				$minTarget = $bulkRules[0]['quantity'];

				$cnt = $local->getItemCount($rule, $item, $context);

				$rule->disable();
				return $cnt >= $minTarget;
			}
		);

		$this->success(
			function($rule, $item, $context) use ($local){
			
				$bulkRules = $local->getBulkRules();
				/**
				 * Sort the rules by quantity descending to be able to process biggest discounts first ;
				 */
				usort($bulkRules, function($a, $b)
				{
					return $a['quantity'] > $b['quantity'] ? -1 : 1;
				});

				$cnt = $local->getItemCount($rule, $item, $context);
				
				$totalDiscount = 0;
				/**
				 * loop through the bulk rules and apply the discount if it applies
				 */
				foreach($bulkRules as $rule){
					$numDiscount = floor($cnt / $rule['quantity'] );
					if($numDiscount > 0){
						$totalDiscount+= $numDiscount * $rule['discount'];
						$cnt -= ($numDiscount * $rule['quantity']);
					}
				}
				

				$discountItem = new Item();
				$discountItem->setProductDescription('Korting : ' . $item->description())
					->setQuantity(1)
					->setUnitDescription('stuk');
				$discountItem->setUnitPrice(-1 * $totalDiscount );

				$context->container()->cart()->addItem($discountItem);
			}
		);
	}

	protected function getItemCount($rule, $item, $context)
	{
		$items = $context->container()->cart()->items();
		$cnt = 0;
		foreach ($items as $cartitem)
		{
			if ($rule->applies($cartitem))
			{
				$cnt+= $cartitem->quantity();
			}
		}
		return $cnt;
	}

	/**
	 * 
	 * @param int $quantity
	 * @param double $discount
	 * @return BulkDiscount
	 */
	public function addBulkRule($quantity, $discount)
	{
		$this->bulkRules[] = ['quantity' => $quantity, 'discount' => $discount];
		return $this;
	}

	public function getBulkRules()
	{
		return $this->bulkRules;
	}

	public function getTotalDiscount()
	{
		return $this->discountAmount;
	}

}
