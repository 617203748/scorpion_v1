<?php
	/**
 	* 功能说明：<购物车类>
 	* ============================================================================
 	* 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：蒲公英技术部
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class AppCart {
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
		public function hasItem($cartid,$picid)
		{

			foreach($this->items as $key=>$value)
			{
               if($value->comid==$cartid && $value->picid==$picid)
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
                        $value->picid == $model->dto->picid && 
						$value->goodsid == $model->dto->goodsid &&
						$value->goodscode == $model->dto->goodscode &&
						$value->colorname == $model->dto->colorname && 
						$value->modelname == $model->dto->modelname &&
						$value->unitname == $model->dto->unitname &&
						$value->brandname == $model->dto->brandname)
					{
						$value->goods_number += $model->dto->goods_number;
						$value->goods_total_integral = $value->goods_number * $value->goods_integral;  
						$value->goods_total_price = $value->goods_number * $value->goods_price; 
						$value->goods_discount_total = $value->goods_number * $value->goods_discount;


						$this->items[$key]->goods_number = $value->goods_number;
						$this->items[$key]->goods_total_integral = $value->goods_total_integral;
						$this->items[$key]->goods_total_price = $value->goods_total_price;
						$this->items[$key]->goods_discount_total = $value->goods_discount_total;

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
		 * $number 修改数量
		 */
		public function modifyNumber($cartid,$picid,$number = 1)
		{
			  $key=$this->hasItem($cartid,$picid);   
			  if($key!==false)
			  {
			  	  $this->items[$key]->goods_number = $number;
			      $this->items[$key]->goods_total_integral = $number * $this->items[$key]->goods_integral;
			      $this->items[$key]->goods_total_price = $number * $this->items[$key]->goods_price;
			      $this->items[$key]->goods_discount_total = $number * $this->items[$key]->goods_discount;     
			  }			 
			  return $this->items;
		}

		/*
		 * 功能：添加商品
		 * 参数：购物车类
		 */
		public function addItem($model, $key = null)
		{
			// 如果存在，累加数量
			if($this->hasSameItem($model))
			{
				return;
			}

			$item = null;
			//校区id
			$item->uniid= $model->dto->uniid;
			//商家id
			$item->comid= $model->dto->comid;
			//商家名称
			$item->comname=$model->dto->comname;
			//图片表id
			$item->picid=$model->dto->picid;
			//商品顶级分类的id
			$item->topid =$model->dto->topid;
			//商品id
			$item->goodsid= $model->dto->goodsid;
			//商品货号
			$item->goodscode = $model->dto->goodscode;
			//商品名称 			
			$item->goodsname = $model->dto->goodsname;
			//商品颜色			
			$item->colorname = $model->dto->colorname;
            //商品型号
            $item->modelname = $model->dto->modelname;
            //商品单位
            $item->unitname = $model->dto->unitname;
            //商品品牌
            $item->brandname = $model->dto->brandname;
            
            //单个商品打折
			$item->tempdiscount = $model->dto->tempdiscount;
			//单个商品打折开始年月日
			$item->tempstartday=$model->dto->tempstartday;
			//单个商品打折开始时间
			$item->tempstarttime=$model->dto->tempstarttime;
			//单个商品打折结束年月日
			$item->tempendday=$model->dto->tempendday;
			//单个商品打折结束时间
			$item->tempendtime=$model->dto->tempendtime;

			//商家打折
			$item->comtempdiscount = $model->dto->comtempdiscount;
			//商家打折开始年月日
			$item->comtempstartday=$model->dto->comtempstartday;
			//商家打折开始时间
			$item->comtempstarttime=$model->dto->comtempstarttime;
			//商家打折结束年月日
			$item->comtempendday=$model->dto->comtempendday;
			//商家打折结束时间
			$item->comtempendtime=$model->dto->comtempendtime;


			//商家免运费下限金额
			$item->freefreight = $model->dto->freefreight;
			//运费
			$item->freight=$model->dto->freight;
			//运费提醒
			$item->freightclock=$model->dto->freightclock;
			// 一个商品折扣金额
			$item->goods_discount=$model->dto->goods_discount;


			//商品单价		
			$item->goods_price = $model->dto->saleprice; // $model->goods_price;
			//商品积分
			$item->goods_integral = $model->dto->goodsinte;
			//商品略缩图		
			//$item->smallpic= $model->dto->smallpic;

			//商品app图		
			$item->apppic= $model->dto->apppic;

			//商品数量	
			$item->goods_number = $model->dto->goods_number;
			//商品积分小计
			$item->goods_total_integral= $model->dto->goods_total_integral;
			//商品价格小计	
			$item->goods_total_price=$model->dto->goods_total_price;// $model->goods_total_price;
			// 商品打折节省金额小计
			$item->goods_discount_total = $model->dto->goods_discount_total;

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
		public function delItem($cartid,$picid)
		{
            
            $key=$this->hasItem($cartid,$picid);

			if($key!==false)
			{
				unset($this->items[$key]);			
			}

		}

		//商品数量加1
		public function incNumber($cart_id)
		{
			if($this->hasItem($cart_id))
			{
				$this->items[$cart_id]->goods_number += 1;
				//积分小计
				$this->items[$cart_id]->goods_total_integral = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_integral;
				//价格小计
				$this->items[$cart_id]->goods_total_price = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_price;
				//折扣小计
				$this->items[$cart_id]->goods_discount_total = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_discount;
			}
		}

		//商品数量减1
		public function decNumber($cart_id)
		{
			if($this->hasItem($cart_id))
			{
				$this->items[$cart_id]->goods_number -= 1;
				$this->items[$cart_id]->goods_total_integral = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_integral;
				$this->items[$cart_id]->goods_total_price = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_price;
				//折扣小计
				$this->items[$cart_id]->goods_discount_total = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_discount;

				if($this->items[$cart_id]->goods_number < 1)
				{
					$this->items[$cart_id]->goods_number = 1;
					$this->items[$cart_id]->goods_total_integral = $this->items[$cart_id]->goods_integral;
					$this->items[$cart_id]->goods_total_price = $this->items[$cart_id]->goods_price;
					//折扣小计
				   $this->items[$cart_id]->goods_discount_total = $this->items[$cart_id]->goods_discount;
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
		public function getItemTotalPrice($cartid,$picid)
		{
			$key=$this->hasItem($cartid,$picid);
			if($key!==false)
			{
				return $this->items[$key]->goods_total_price;
			}
			return 0;
		}

		//获取单条积分小计
		public function getItemTotalIntegral($cartid,$picid)
		{
			$key=$this->hasItem($cartid,$picid);
			if($key!==false)
			{
				return $this->items[$key]->goods_total_integral;
			}
			return 0;
		}


		//获取单折扣小计
		public function getItemTotalDiscount($cartid,$picid)
		{
			$key=$this->hasItem($cartid,$picid);
			if($key!==false)
			{
				return $this->items[$key]->goods_discount_total;
			}
			return 0;
		}




		//获取总金额 = 商品金额 - 节省金额
		public function getTotalPrice()
		{

			if($this->items)
			{
				$total = 0.0;
				foreach ($this->items as $key => $value) 
				{
					$total += $value->goods_total_price - $value->goods_discount_total;
				}
			    return $total;
				//return sprintf("%.1f",substr(sprintf("%.2f", $total), 0, -1)); 
				
			}

			return 0;
		}


		// 获取折扣总金额  
		public function getTotalDiscount()
		{

			if($this->items)
			{
				$total = 0.0;
				foreach ($this->items as $key => $value) 
				{
					$total += $value->goods_discount_total;
				}
			    return $total;
				//return sprintf("%.1f",substr(sprintf("%.2f", $total), 0, -1)); 
				
			}

			return 0;
		}


		//获取总积分
		public function getTotalIntegral()
		{
			if($this->items)
			{
				$total = 0.0;
				foreach ($this->items as $key => $value) 
				{
					$total += $value->goods_integral * $value->goods_number;
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

		       	  	  $cartid=$idArr[0];
		           	  $picid=$idArr[1];

		          	  $key=$this->hasItem($cartid,$picid);
		       
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

		
		//获取要结算的商品的总积分
		public function getTotalPayInte($cartid='')
		{
			 if($this->payItems)
			 {
			 	if($cartid)
			 	{
			 		$cartArr=$this->getPayGoodsCateByComid();
                    //要结算的商品中某商家商品总的积分
                    $total = 0.0;
					foreach ($cartArr[$cartid] as $key => $value) 
					{
						$total += $value->goods_integral * $value->goods_number;
					}
					return $total;
			 	}
			 	else
			 	{
		 		    $total = 0.0;
					foreach ($this->payItems as $key => $value) 
					{
						$total += $value->goods_integral * $value->goods_number;
					}
					return $total;
			 	}
				
			 }
			 return 0;
		}



		//获取要结算的商品的总积分
		public function getTotalPayDiscount($cartid='')
		{
			 if($this->payItems)
			 {
			 	if($cartid)
			 	{
			 		$cartArr=$this->getPayGoodsCateByComid();
                    //要结算的商品中某商家商品总的积分
                    $total = 0.0;
					foreach ($cartArr[$cartid] as $key => $value) 
					{
						$total += $value->goods_discount * $value->goods_number;
					}
					return $total;
			 	}
			 	else
			 	{
		 		    $total = 0.0;
					foreach ($this->payItems as $key => $value) 
					{
						$total += $value->goods_discount * $value->goods_number;
					}
					return $total;
			 	}
				
			 }
			 return 0;
		}
	}

?>