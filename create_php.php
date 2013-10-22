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
	if (isset($user_login->partner)) {
		$partner = $user_login->partner;
		$partner_id = $partner->partner_id;
		
		//change this data with new user data
		$user_data = array(
	        'name' => 'idk_new_user',
	        'mail' => 'idk_new_user@gmail.com',
	        'pass' => '1234',
	        'pass_conf' => '1234',
	        'name_first' => 'idk_new_user',
	        'name_last' => 'user',
	    );
		
		$store_data = array(
	        'name_alias' => 'idk_new_user_store',
	        'name' => 'idk_new_user_store',
	        'category' => array ( 		//for store category data, use this API : http://beta1.indokatalog.com/api/1/utilities/category
	            '988',
	            '989',
	            '997',
	            '994',
	            '1000',
	       	),
	        'address' => 'idk_new_user_store',
	        'phone' => '123456',
	        'province' => '897',		//for store province data, use this API : http://beta1.indokatalog.com/api/1/utilities/province
	        'city' => '898',			//for store city, add province tid to province API : 'http://beta1.indokatalog.com/api/1/utilities/province/{tid} example: http://beta1.indokatalog.com/api/1/utilities/province/897
	        'zipcode' => '12345',		
	        'latitude' => '12',
	        'longitude' => '34',
	        'description' => 'idk_new_user_store',
	        'site' => 'idk_new_user_store.com',
	        'partner_id' => $partner_id,
	    );

	    $path = 'http://beta1.indokatalog.com/api/1/user/create';
		$return = clone call($path, 'POST', $user_data);
		print '<br/>user:<br/>';
		print_r($return);
		//if user create is success, then continue to next step to create store which is related to previous user data
		if ($return->status) {
			$user = $return->data;
			print '---';
			print_r($user->user_id);
			print '---';
			$store_data['user_id'] = $user->user_id; 	//use recently created user uid as post parameter in store data  
			
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

			$path = 'http://beta1.indokatalog.com/api/1/store/create';
			$return = clone call($path, 'POST', $store_data);
			print '<br/>store<br/>';
			print_r($return);
		}

	} else {
		print 'user tidak terdaftar sebagai partner!';
	}