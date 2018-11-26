<?php
	/**
 	* 功能说明：<洗衣购物车类>
 	* ============================================================================
 	* 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：蒲公英技术部
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class AppWashCart {
		private static $instance = null;
		private $items = array();
        private $payItems=array();
		final public function __construct()
		{

		}

		final protected function __clone()
		{

		}

		// 获取实例
		protected static function getInstance()
		{
			
			if(!(self::$instance instanceof self))
			{
				self::$instance = new self();
			}

			return self::$instance;
		}

		//把购物车的单例对象放到session里
		public static function getCart()
		{
			if(!isset($_SESSION["wash_web_cart"]) || !($_SESSION["wash_web_cart"] instanceof self))
			{
				$_SESSION["wash_web_cart"] = self::getInstance();
			}

			return $_SESSION["wash_web_cart"];

		}

		/*
         * 功能：判断某水果是否存在
		 */
		public function hasItem($washid)
		{
			foreach($this->items as $key=>$value)
			{
               if($value->washid==$washid)
               {
                  return $key;
               }          
			}
			return false;
		}

		/*
         * 功能：判断某商品是否重复
		 */
		public function hasSameItem($model)
		{

			if($this->items)
			{

				foreach($this->items as $key=>$value)
				{

					if(
	                    $value->uniid == $model->dto->uniid && 
	                    $value->washid == $model->dto->washid )
					{
						$value->wash_number += $model->dto->wash_number;
						$value->wash_total_price = $value->wash_number * $value->wash_price;

						return true;
						break;
					}
				}
			}

			return false;
		}


		/*
		 * 功能：修改购物车中的商品数量
		 * 参数：
		 * $cartid 购物车id
		 * $wash_number 修改数量
		 */
		public function modifyNumber($washid,$goods_number = 1)
		{
              //Tool::check($goods_number); 
			  $key=$this->hasItem($washid);   
			  if($key!==false)
			  {
			  	  $this->items[$key]->wash_number = $goods_number;
			      $this->items[$key]->wash_total_price = $goods_number * $this->items[$key]->wash_price;  
			        
			  }			 
			  return $this->items;
		}


		/*
		 * 功能：添加商品
		 * 参数：购物车类
		 */
		public function addItem($model, $key = null)
		{
			//如果存在，累加数量
			if($this->hasSameItem($model))
			{
				return;
			}
           
			$item = null;
						//校区id
			$item->uniid=Tool::getCookie('sc_id');
			//洗衣id
			$item->washid=$model->dto->washid;
			//洗衣名称 			
			$item->washname = $model->dto->washname;
			//洗衣分类
			$item->catename = $model->dto->catename;
			//商家免运费下限金额
			$item->freefreight = $model->dto->freefreight;
			//运费
			$item->freight=$model->dto->freight;
			//运费提醒
			$item->freightclock=$model->dto->freightclock;
			//商品单价		
			//$item->fruit_price =sprintf("%.1f",substr(sprintf("%.2f", $model->dto->fruit_price), 0, -1)); // $model->fruit_price;
			$item->wash_price =sprintf("%.2f", $model->dto->wash_price); // $model->fruit_price;
			// Tool::check($item->wash_price);
			//洗衣数量	
			$item->wash_number = $model->dto->wash_number;
			//洗衣价格小计
			//$item->fruit_total_price=sprintf("%.1f",substr(sprintf("%.2f",  $model->dto->fruit_total_price), 0, -1));// $model->fruit_total_price;
			$item->wash_total_price=sprintf("%.2f",  $model->dto->wash_total_price);
            
			if($key)
			{
				$this->items[$key] = $item;
			}
			else
			{
				$this->items[] = $item;
			}

		}

		//删除一条
		public function delItem($washid)
		{
            
            $key=$this->hasItem($washid);

			if($key!==false)
			{
				unset($this->items[$key]);			
			}

		}

		//商品数量加1
		public function incNumber($washid)
		{
            $key=$this->hasItem($washid);
			
			$this->items[$key]->wash_number += 1;
			//价格小计
			$this->items[$key]->wash_total_price = $this->items[$key]->wash_number * $this->items[$key]->wash_price;
			
		}

		//商品数量减1
		public function decNum($washid)
		{
			$key=$this->hasItem($washid);

			if($key!==false)
			{
				$this->items[$key]->wash_number -= 1;
				$this->items[$key]->wash_total_price = $this->items[$key]->wash_number * $this->items[$key]->wash_price;

				if($this->items[$key]->wash_number < 1)
				{
					$this->items[$key]->wash_number = 1;
					$this->items[$key]->wash_total_price = $this->items[$key]->wash_price;
				}
			}
		}

		/*
    	 * 功能：清空购物车
		 */
		public function clear()
		{
			$this->items = array();
		}
		
		
		//获取购物车条目数
		public function getTotalCartItem()
		{
			return count($this->item);
		}


		//获取商品总数
		public function getTotalNumber()
		{
			//Tool::check($this->items); 
			if($this->items)
			{
				$sum = 0;
				foreach($this->items as $value)
				{
					//Tool::check($value->wash_number);
					$sum += $value->wash_number;
				}
				return $sum;
			}
			return 0;
		}


		//获取单条价格小计
		public function getItemTotalPrice($washid)
		{
			$key=$this->hasItem($washid);
			if($key!==false)
			{
				return $this->items[$key]->wash_total_price;
			}
			return 0;
		}


		//获取总金额
		public function getTotalPrice()
		{
	        //Tool::check($this->items);
			if($this->items)
			{
				$total = 0.0;
				foreach ($this->items as $key => $value) 
				{
					$total+=$value->wash_total_price;	
				}
			    return $total;			
			}

			return 0;
		}



		//获取购物车所有商品
		public function getAllCart()
		{

			return $this->items;
		}

	

	    //获取需要结算的商品  并返回
		public function getPayCart($idstr='')
		{
		   if($idstr)   //$idStr=5,27$1,16$1,3$    水果及水果id
		   {
		   	   //每次要重新向这个数组里边放要结算的商品
		   	   unset($this->payItems);

	           $arr=explode('$',$idstr);
               $this->payItems='';
	           foreach($arr as $idStr)
	           {	
	           	  if($idStr)
	           	  {
	           	  	  $idArr=explode(',',$idStr);

		       	  	  $washid=$idArr[0];
		       	  	  $key=$this->hasItem($washid);
					  if($key!==false)
					  {
						 $this->payItems[$key]=$this->items[$key];
					  }
	           	   }
	           }
		   }
		   
		   if(isset($this->payItems))//加这个判断是：   下单成功跳到会员中心  再点返回的话$this->payItems就没有值   所以要判断一下
		   {
		   	   return $this->payItems;
		   }
           //return $this->payItems;
		}

        //获取运费
		public function getFreight()
		{
			if($this->payItems)
			{
				$freight = 0.0;
				foreach ($this->payItems as $key => $value) 
				{
					$freight=$value->freight;	
				}

			    return $freight;
			}

			return 0;
		}

		//  获取免运费下限金额
		public function getFreeFreight()
		{
			if($this->payItems)
			{
				$freefreight = 0.0;
				foreach ($this->payItems as $key => $value) 
				{
					$freefreight=$value->freefreight;	
				}

			    return $freefreight;				
			}

			return 0;
		}

		 //获取要结算的商品的总价
		public function getTotalPayPrice($cart_id='')
		{
			
            //获取要结算的洗衣的总价
            if($this->payItems)
			{
				if($cart_id)
				{
 
	                $total = 0.0;
					foreach ($this->payItems as $key => $value) 
					{
						$total+=$value->wash_total_price;	
					}
				    return $total;
				       
				}
				else
				{
					$total = 0.0;
					foreach ($this->payItems as $key => $value) 
					{
						$total += $value->wash_price * $value->wash_number;
					}
				    return $total;
				}
			}
			
			return 0;
		}

}

?>		