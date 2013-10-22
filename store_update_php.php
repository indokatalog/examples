<?php 
	require_once('call_api.php');
	//change this data with your login data as partner
	$data = array(
		'name' => 'jagoanstore',
		'pass' => '1234',
	);
	$path = 'http://beta1.indokatalog.com/api/1/user/login';
	$return = clone call($path, 'POST', $data);
	$user_login = $return->data;

	$store_id = 42;

	if (isset($user_login->partner)) {
		$partner = $user_login->partner;
		$partner_id = $partner->partner_id;
		
		$store_data = array(
	        'name_alias' => 'idkatalog_store_update',
	        'name' => 'idkatalog_store_update',
	        'category' => array ( 		//for store category data, use this API : http://beta1.indokatalog.com.local.skyshi.com/api/1/utilities/category
	            '988',
	            '989',
	            '997',
	            '994',
	            '1000',
	       	),
	        'address' => 'idkatalog_store_address_update',
	        'phone' => '12345621',
	        'province' => '897',		//for store province data, use this API : http://beta1.indokatalog.com.local.skyshi.com/api/1/utilities/province
	        'city' => '898',			//for store city, add province tid to province API : 'http://beta1.indokatalog.com.local.skyshi.com/api/1/utilities/province/{tid} example: http://beta1.indokatalog.com.local.skyshi.com/api/1/utilities/province/897
	        'zipcode' => '55555',		
	        'latitude' => '44',
	        'longitude' => '17',
	        'description' => 'idkatalog store desc update',
	        'site' => 'idk_new_user_store_update.com',
	        //'partner_id' => $partner_id,
	    );

		$user = $return->data->user;
		if (isset($_FILES['logo'])) {
			$tmpfile = $_FILES['logo']['tmp_name'];
			$filename = $_FILES['logo']['name'];
			$type = $_FILES['logo']['type'];
			$name = $_FILES['logo']['name'];

			//bind with '@' to save $_FILES data with CURL
			$store_data['logo'] = '@'.$tmpfile.';filename='.$filename. ';type='. $type. ';name='. $name;
		}
		//convert multi dimension array data to string with ';' as glue
		$store_data['category'] = implode(';', $store_data['category']);

		$path = 'http://beta1.indokatalog.com/api/1/store/update/'. $store_id;
		$return = clone call($path, 'POST', $store_data);
		print '<br/>store';
		print_r($return);
	} else {
		print 'user tidak terdaftar sebagai partner!';
	}