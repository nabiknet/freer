#  Virtual Freer
#  http://freer.ir/virtual
#
#  Copyright (c) 2011 Mohammad Hossein Beyram, freer.ir
#
#  This program is free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License v3 (http://www.gnu.org/licenses/gpl-3.0.html)
#  as published by the Free Software Foundation.

DROP TABLE IF EXISTS card;
CREATE TABLE `card` (
  `card_id` int(11) NOT NULL auto_increment,
  `card_product` int(11) NOT NULL,
  `card_first_field` varbinary(256) default NULL,
  `card_second_field` varbinary(256) default NULL,
  `card_third_field` varbinary(256) default NULL,
  `card_time` varchar(30) NOT NULL,
  `card_res_user` varchar(256) NOT NULL,
  `card_res_time` varchar(30) NOT NULL,
  `card_customer_email` varchar(128) NOT NULL,
  `card_customer_mobile` varchar(32) NOT NULL,
  `card_payment_id` int(11) default NULL,
  `card_payment_res_num` varchar(64) NOT NULL,
  `card_payment_ref_num` varchar(64) NOT NULL,
  `card_payment_gateway` varchar(128) NOT NULL,
  `card_payment_time` varchar(30) NOT NULL,
  `card_status` varchar(2) NOT NULL default 1,
  `card_show` varchar(2) NOT NULL default 1,
  PRIMARY KEY  (`card_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS category;
CREATE TABLE `category` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_parent_id` int(11) NOT NULL,
  `category_title` varchar(256) NOT NULL,
  `category_image` varchar(128) default NULL,
  `category_order` int(11) NOT NULL,
  `category_creator` int(11) NOT NULL,
  `category_time` varchar(30) NOT NULL,
  `category_ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS config;
CREATE TABLE `config` (
  `config_id` int(11) NOT NULL auto_increment,
  `config_site_title` varchar(256) NOT NULL,
  `config_site_description` varchar(256) NOT NULL,
  `config_site_keyword` text NOT NULL,
  `config_operator_description` text NOT NULL,
  `config_description` text NOT NULL,
  `config_admin_email` varchar(128) NOT NULL,
  `config_admin_yahoo_id` varchar(128) NOT NULL,
  `config_admin_username` varchar(128) NOT NULL,
  `config_admin_password` varchar(128) NOT NULL,
  `config_input_validate` varchar(2) NOT NULL default 1,
  PRIMARY KEY  (`config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS payment;
CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL auto_increment,
  `payment_user` varchar(128) NOT NULL,
  `payment_email` varchar(128) NOT NULL,
  `payment_mobile` varchar(30) NOT NULL,
  `payment_amount` int(11) NOT NULL,
  `payment_gateway` varchar(128) NOT NULL,
  `payment_status` varchar(2) NOT NULL default 1,
  `payment_rand` varchar(64) NOT NULL,
  `payment_time` varchar(30) NOT NULL,
  `payment_ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`payment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS plugin;
CREATE TABLE `plugin` (
  `plugin_id` int(11) NOT NULL auto_increment,
  `plugin_uniq` varchar(128) collate latin1_general_ci NOT NULL,
  `plugin_name` varchar(128) character set utf8 NOT NULL,
  `plugin_type` varchar(128) collate latin1_general_ci NOT NULL,
  `plugin_status` varchar(2) collate latin1_general_ci NOT NULL,
  `plugin_time` varchar(30) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`plugin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS plugindata;
CREATE TABLE `plugindata` (
  `plugindata_id` int(11) NOT NULL auto_increment,
  `plugindata_uniq` varchar(128) NOT NULL,
  `plugindata_field_name` varchar(256) NOT NULL,
  `plugindata_field_value` text NOT NULL,
  PRIMARY KEY  (`plugindata_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS product;
CREATE TABLE `product` (
  `product_id` int(11) NOT NULL auto_increment,
  `product_title` varchar(256) NOT NULL,
  `product_body` text,
  `product_first_field_title` varchar(128) default NULL,
  `product_second_field_title` varchar(128) default NULL,
  `product_third_field_title` varchar(128) default NULL,
  `product_price` int(11) NOT NULL,
  `product_image` varchar(128) default NULL,
  `product_category` int(11) NOT NULL,
  `product_creator` int(11) NOT NULL,
  `product_time` varchar(30) NOT NULL,
  `product_ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
