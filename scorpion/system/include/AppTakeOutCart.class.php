<?php
	/**
 	* 功能说明：<购物车类>
 	* ============================================================================
 	* 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：蒲公英技术部
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class AppTakeOutCart {
		private static $instance = null;
		private $items = array();
        private $payItems=array();
		final protected function __construct()
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

		// 把购物车的单例对象放到session里
		public static function getCart()
		{
			if(!isset($_SESSION["lglp_shop_web_cart"]) || !($_SESSION["lglp_shop_web_cart"] instanceof self))
			{
				$_SESSION["lglp_shop_web_cart"] = self::getInstance();
			}

			return $_SESSION["lglp_shop_web_cart"];
		}

		/*
         * 功能：判断某商品是否存在
		 */
		public function hasItem($attrid)
		{

			foreach($this->items as $key=>$value)
			{
               if($value->attrid==$attrid)
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
                        $value->comid == $model->dto->comid &&
						$value->goodscode == $model->dto->goodscode &&
						$value->attrid == $model->dto->attrid
					  )
					{
						$value->goods_number += $model->dto->goods_number;
						//$value->goods_total_integral = $value->goods_number * $value->goods_integral;
						$value->goods_total_price = $value->goods_number * $value->goods_price;
						$this->items[$key]->goods_number = $value->goods_number;
						// $this->items[$key]->goods_total_integral = $value->goods_total_integral;
						$this->items[$key]->goods_total_price = $value->goods_total_price;

						return true;
						break;
					}
				}
			}

			return false;
		}

		/*
		 * 功能：修改购笺车中的商品数量
		 * 参数：
		 * $cartid 购物车id
		 * $number 修改数量  $goodsid==>$attrid
		 */
		public function modifyNumber($attrid,$number = 1)
		{
			   //var_dump($cartid);
			   //var_dump($attrid);
			  $key=$this->hasItem($attrid);
			  //echo $key;exit;
			  
			  if($key !== false)
			  {
			  	  $this->items[$key]->goods_number = $number;
			     // $this->items[$key]->goods_total_integral = $number * $this->items[$key]->goods_integral;
			      $this->items[$key]->goods_total_price = $number * $this->items[$key]->goods_price;
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
			//外卖商品属性表id
	        $item->attrid=$model->dto->attrid;
	        //外卖口味
	        $item->tastename=$model->dto->tastename;
			//商品id
	        $item->goodsid=$model->dto->goodsid;
			//校区id
			$item->uniid= $model->dto->uniid;
			//商家id
			$item->comid= $model->dto->comid;
			//商家名称
			$item->comname=$model->dto->comname;
			//单价
			$item->saleprice=$model->dto->saleprice;
			//商品货号
			$item->goodscode = $model->dto->goodscode;
			//商品名称 			
			$item->goodsname = $model->dto->goodsname;
            //商品数量
            $item->goods_number = $model->dto->goods_number;


			// 满的钱数
			$item->full=$model->dto->full;
			// 减的钱数
			$item->minus=$model->dto->minus;


			//商家免运费下限金额
			$item->freefreight = $model->dto->freefreight;
			//运费
			$item->freight=$model->dto->freight;
			//运费提醒
			$item->freightclock=$model->dto->freightclock;

			//商品单价		
			// $item->goods_price =sprintf("%.1f",substr(sprintf("%.2f", $model->dto->saleprice), 0, -1)); // $model->goods_price;
            $item->goods_price = $model->dto->saleprice;
			//商品价格小计
			// $item->goods_total_price=sprintf("%.1f",substr(sprintf("%.2f",  $model->dto->goods_total_price), 0, -1));// $model->goods_total_price;
			$item->goods_total_price=$model->dto->goods_total_price;
			//打折节省金额总数	
			// $item->goods_discount_total=sprintf("%.1f",substr(sprintf("%.2f",  $model->dto->goods_discount_total), 0, -1));
            $item->goods_discount_total=$model->dto->goods_discount_total;
			//购物类型,例1团购,2.特卖	
			//$item->cart_type= $model->cart_type;
		
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
		public function delItem($attrid)
		{
            
            $key=$this->hasItem($attrid);

			if($key!==false)
			{
				unset($this->items[$key]);			
			}

		}

		//商品数量加1
		public function incNumber($attrid)
		{
			$key=$this->hasItem($attrid);
			if($key)
			{
				$this->items[$key]->goods_number += 1;
				//积分小计
				//$this->items[$key]->goods_total_integral = $this->items[$key]->goods_number * $this->items[$key]->goods_integral;
				//价格小计
				$this->items[$key]->goods_total_price = $this->items[$key]->goods_number * $this->items[$key]->goods_price;
			}
		}

		//商品数量减1
		public function decNum($attrid)
		{
			//echo $attrid;
			$key=$this->hasItem($attrid);
			//Tool::check($key);
			// if($this->hasItem($cart_id,$attrid))
			if($key !== false)
			{
				$this->items[$key]->goods_number -= 1;
				//$this->items[$key]->goods_total_integral = $this->items[$key]->goods_number * $this->items[$key]->goods_integral;
				$this->items[$key]->goods_total_price = $this->items[$key]->goods_number * $this->items[$key]->goods_price;

				if($this->items[$key]->goods_number < 1)
				{
					$this->items[$key]->goods_number = 0;
					//$this->items[$key]->goods_total_integral = $this->items[$key]->goods_integral;
					$this->items[$key]->goods_total_price = $this->items[$key]->goods_price;
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
			return count($this->items);
		}

		//获取商品总数
		public function getTotalNumber()
		{
			if($this->items)
			{
				
				$sum = 0;
				foreach($this->items as $value)
				{
					$sum += $value->goods_number;
				}

				return $sum;
			}
			return 0;
		}

		//获取单条价格小计
		public function getItemTotalPrice($attrid)
		{
			$key=$this->hasItem($attrid);

			if($key!==false)
			{
				return $this->items[$key]->goods_total_price;
			}
			return 0;
		}



		//获取总金额
		public function getTotalPrice()
		{
			if($this->items)
			{
				$total = 0.0;
				foreach ($this->items as $key => $value) 
				{
					$total += $value->goods_price * $value->goods_number;
				}
			    return $total;
				//return sprintf("%.1f",substr(sprintf("%.2f", $total), 0, -1));				
			}

			return 0;
		}


		//获取节省的总折扣的金额  
		public function getTotalDiscount()
		{

			if($this->items)
			{
				$total = 0.0;
				foreach ($this->items as $key => $value)
				{	
                    if( $value->goods_price * $value->goods_number >= $value->goods_price )
                    {
                    	$total += $value->goods_price * $value->goods_number - $value->minus;
                    }
                    else
                    {
                    	$total = 0.0;
                    }				     
					    				
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

		//添加需要结算的商品  并返回
		public function getPayCart($idstr='')
		{
		   if($idstr)   //$idStr=5,27$1,16$1,3$    商家及商品id
		   {
		   	   //每次要重新向这个数组里边放要结算的商品
		   	   unset($this->payItems);
	           $arr=explode('$',$idstr);

	           foreach($arr as $idStr)
	           {
	           	  if($idStr)
	           	  {
	           	  	  $idArr=explode(',',$idStr);

	           	  	  //Tool::check( $idArr);//exit;

		       	  	  //$cartid=$idArr[0];
		           	  $attrid=$idArr[1];

		          	  $key=$this->hasItem($attrid);
		       
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
		   return false;
		   
           //return $this->payItems;
		}


        //把同一商家的要结算的商品放在一个数组   
        public function getPayGoodsCateByComid()
        {
			if($this->payItems)
			{
				 
				   $cartArr=array();  
				   $cartComId=array();
				   foreach($this->payItems as $goods)
				   {
					   if($goods)
					   {
						  $comid=$goods->comid;
						  $cartArr[$comid][]=$goods; 
						  $cartComId[]=$goods->comid;
					   }        
				   }
				   return $cartArr;
			}
            return 0;
        }

        //获取要结算的商品的总价
		public function getTotalPayPrice($cartid='')
		{
			
            //获取要结算的某一商家的商品的总价
            if($this->payItems)
			{
				if($cartid)
				{
                      
		                $cartArr=$this->getPayGoodsCateByComid();

		                $total = 0.0;
						foreach ($cartArr[$cartid] as $key => $value) 
						{
							$total += $value->goods_price * $value->goods_number;
						}
					    return $total;
				       
				}
				else
				{
					$total = 0.0;
					foreach ($this->payItems as $key => $value) 
					{
						$total += $value->goods_price * $value->goods_number;
					}
				    return $total;
				}
			}
			
			return 0;
		}



		//  获取满的钱数
		public function getFull()
		{
			if($this->payItems)
			{
				$full = 0.0;
				foreach ($this->payItems as $key => $value) 
				{
					$full=$value->full;	
				}

			    return $full;				
			}

			return 0;
		}


		//  获取减的钱数
		public function getMinus()
		{
			if($this->payItems)
			{
				$minus = 0.0;
				foreach ($this->payItems as $key => $value) 
				{
					$minus=$value->minus;	
				}

			    return $minus;				
			}

			return 0;
		}

		//  获取运费
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
	

/********************************** 外卖购物车 商品口味 ******************************/
	
		
	}

?>