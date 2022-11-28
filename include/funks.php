<?
/*
  Virtual Freer
  http://freer.ir/virtual

  Copyright (c) 2011 Mohammad Hossein Beyram, freer.ir

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v3 (http://www.gnu.org/licenses/gpl-3.0.html)
  as published by the Free Software Foundation.
*/
//---------------------- Clean Input vars Function------------------
function cleaner($input)
{
	if($input)
	{
		if(is_array($input))
		{
			foreach ($input as $key => $value)
			{
				$output[$key] = cleaner($value);
			}
		}
		else
		{
			$output = htmlspecialchars($input,ENT_QUOTES);
		}
		return $output;
	}
	else
	{
		return '';
	}
}

//---------------------- Check Login Function------------------
function check_login()
{
	global $session;
	if ($session[admin] == 1)
		return true;
	else
		return false;
}

//------------------------------- Check if category exist or not
function check_category_exist($id){
	global $db;
	$result = $db->retrieve('category_id','category','category_id',$id);
	if($result)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//------------------------------- Check if product exist or not
function check_product_exist($id){
	global $db;
	$result = $db->retrieve('product_id','product','product_id',$id);
	if($result)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//------------------------------- Check if card exist or not
function check_card_exist($id){
	global $db;
	$result = $db->retrieve('card_id','card','card_id',$id);
	if($result)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//---------------------- Create random chars Function------------------
function get_rand_id($length)
{
  if($length>0) 
  { 
	$rand_id="";
	for($i=1; $i<=$length; $i++)
	{
		mt_srand((double)microtime() * 1000000);
		$num = mt_rand(1,9);
		$rand_id .= $num;
	}
  }
	return $rand_id;
} 

//---------------------- Send mail Function------------------
function send_mail($from_email,$from_name,$to_email,$to_name,$subject,$body,$signature,$attachment=null) {
	require_once('libs/class.phpmailer.php');
	if ($signature)
		$signature = '
			<tr>
				<td style="background-color:#3a3a3a; padding:5px; direction:rtl; text-align:right; font-size: 10px; font-family:tahoma; color:#E0E0E0">'.$signature.'</td>
			</tr>';
	
	$mail_body = '
		<table style="margin-left:auto; margin-right:auto; width:80%; border:0px;">
			<tr>
				<td style="background-color:#3a3a3a; padding:5px; direction:rtl; text-align:right; font-size: 12px; font-family:tahoma; font-weight:bold; color:#E0E0E0">'.$from_name.'</td>
			</tr>
			<tr>
				<td style="background-color:#f5f5f5; padding:25px; border: 1px solid #c6c6c6; direction:rtl; text-align:right; font-size: 12px; font-family:tahoma; color:#3a3a3a">'.$body.'</td>
			</tr>'.$signature.'
		</table>';
	$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
	try {
	  $mail->AddReplyTo($from_email, $from_name);
	  $mail->SetFrom($from_email, $from_name);
	  $mail->AddAddress($to_email, $to_name);
	  $mail->CharSet = 'UTF-8';
	  $mail->Subject = $subject;
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	  $mail->MsgHTML($mail_body);
	  if ($attachment)
	  	$mail->AddAttachment($attachment);
	  $mail->Send();
	  return 1;
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}
}

//---------------------- xml2array Function------------------
function xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();
        
        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;
                    
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    
                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }
    
    return($xml_array);
}