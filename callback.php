<?
/*
  Virtual Freer
  http://freer.ir/virtual

  Copyright (c) 2011 Mohammad Hossein Beyram, freer.ir

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v3 (http://www.gnu.org/licenses/gpl-3.0.html)
  as published by the Free Software Foundation.
*/
//------------------ Load Configuration
include 'include/configuration.php';
//------------------ Start Smarty
include 'include/startSmarty.php';

	if ($request[gateway])
	{
		//-- اطلاعات لازم از پلاگین
		require_once('plugins/'.$request[gateway].'.php');
		$sql			= "SELECT * FROM `plugindata` WHERE `plugindata_uniq` = '$request[gateway]';";
		$plugindatas	= $db->fetchAll($sql);
		if ($plugindatas)
			foreach($plugindatas as $plugindata)
			{
				$data[$plugindata[plugindata_field_name]] = $plugindata[plugindata_field_value];
			}
		$output = call_user_func('callback__'.$request[gateway],$data);
		
		//-- پرداخت موفقیت آمیز بود
		if($output[status] == 1)
		{
			//-- اطلاعات کارت ها
			$sql		= "SELECT *,DECODE(card_first_field,'".$config[databaseInfo][salt]."') as card_first_field ,DECODE(card_second_field,'".$config[databaseInfo][salt]."') as card_second_field ,DECODE(card_third_field,'".$config[databaseInfo][salt]."') as card_third_field FROM `card` WHERE `card_payment_id` = '$output[payment_id]';";
			$cards		= $db->fetchAll($sql);
			
			//-- اطلاعات محصول
			$sql		= "SELECT * FROM `product` WHERE `product_id` = '".$cards[0][card_product]."';";
			$product	= $db->fetch($sql);
			
			//-- اطلاعات فکتور پرداخت
			$sql		= "SELECT * FROM `payment` WHERE `payment_id` = '$output[payment_id]';";
			$payment	= $db->fetch($sql);
			
			//-- به‌روز رسانی و نمایش کارت
			$update[payment_status]	= 2;
			$sql = $db->queryUpdate('payment', $update, "WHERE `payment_id` = '$output[payment_id]' LIMIT 1;");
			$db->execute($sql);
			unset($update);
			
			$update[card_customer_email]	= $payment[payment_email];
			$update[card_customer_mobile]	= $payment[payment_mobile];
			$update[card_payment_res_num]	= $output[res_num];
			$update[card_payment_ref_num]	= $output[ref_num];
			$update[card_payment_gateway]	= $request[gateway];
			$update[card_payment_time]		= $now;
			$update[card_status]			= 2;
			$sql = $db->queryUpdate('card', $update, "WHERE `card_payment_id` = '$output[payment_id]';");
			$db->execute($sql);
			
			//-- پلاگین‌های اطلاع رسانی
			$sql		= "SELECT * FROM `plugin` WHERE `plugin_type` = 'notify' AND `plugin_status` = '1';";
			$plugins	= $db->fetchAll($sql);
			if($plugins)
				foreach($plugins as $plugin)
				{
					require_once('plugins/'.$plugin[plugin_uniq].'.php');
					
					$sql			= "SELECT * FROM `plugindata` WHERE `plugindata_uniq` = '$plugin[plugin_uniq]';";
					$plugindatas	= $db->fetchAll($sql);
					if ($plugindatas)
						foreach($plugindatas as $plugindata)
						{
							$data[$plugindata[plugindata_field_name]] = $plugindata[plugindata_field_value];
						}
					call_user_func('notify__'.$plugin[plugin_uniq],$data,$output,$payment,$product,$cards);
					unset($data);
				}
			//-- ارسال اطلاعات به اسمارتس
			$query	= "SELECT * FROM `config` WHERE `config_id` = '1' LIMIT 1";
			$config	= $db->fetch($query);

			$smarty->assign('config', $config);
			$smarty->assign('cards', $cards);
			$smarty->assign('product', $product);
			$smarty->assign('output', $output);
			$smarty->display('callback.tpl');
		}
		else
		{
			//-- نمایش پیغام خطا
			$data[title] = 'خطای سیستم';
			$data[message] = '<font color="red">در بازگشت از بانک مشکلی به وجود آمد٬ لطفا دوباره سعی کنید.</font><br /><a href="index.php" class="button">بازگشت</a>';
			$smarty->assign('data', $data);
			$smarty->display('message.tpl');
			exit;
		}
	}
	else
	{
			//-- نمایش پیغام خطا
			$data[title] = 'خطای سیستم';
			$data[message] = '<font color="red">اطلاعات پرداخت ناقص است.</font><br /><a href="index.php" class="button">بازگشت</a>';
			$smarty->assign('data', $data);
			$smarty->display('message.tpl');
			exit;
	}
	