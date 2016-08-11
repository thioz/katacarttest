<?php

use Kata\Cart\Store\Memory;

class CartTestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';
		
	protected $products;
	protected $app;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
			if(!$this->app){
					$app = require __DIR__.'/../bootstrap/app.php';

					$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
				$this->app = $app;
			}

        return $this->app;
    }
		
		protected function setUp()
		{
			$app = $this->createApplication();
			$this->products = config('products');
			parent::setUp();
			
		}
		
		public function getProductById($id){
			foreach($this->products as $product){
				if($product['id'] == $id){
					return $product;
				}
			}
		}
		
	
	/**
	 * 
	 * @return \Kata\Cart
	 */
	public function createCart(){
		$app = $this->createApplication();
		return $app->make('\Kata\Cart',[ new Memory() ]);

	}
}
