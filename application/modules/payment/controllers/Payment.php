<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payment extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));
		
		$group = array('admin', 'tutor', 'student','institute');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}
	
	/** Displays the Index Page**/
	function index()
	{
		if(isset($_POST['Submit']))
		{
			$this->form_validation->set_rules('package_id', get_languageword('package'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('gateway_id', get_languageword('Payment gateway'), 'trim|required|xss_clean');			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');			
			if ($this->form_validation->run() == TRUE)
			{
				$package_id = $this->input->post('package_id');
				$gateway_id = $this->input->post('gateway_id');
				
				$gateway_details = $this->base_model->get_payment_gateways(' AND st2.type_id = '.$gateway_id);
				
				$package_info 	= $this->db->get_where('packages',array('id' => $package_id))->result();
				if(count($gateway_details) > 0 && count($package_info) > 0)
				{
					$field_values = $this->db->get_where('system_settings_fields',array('type_id' => $gateway_id))->result();
					$total_amount 	= $package_info[0]->package_cost;
					if(isset($package_info[0]->discount) && ($package_info[0]->discount != 0))
					{
						if($package_info[0]->discount_type == 'Value')
						{
							$total_amount = $package_info[0]->package_cost - $package_info[0]->discount;				
						}
						else
						{
							$discount = ($package_info[0]->discount/100)*$package_info[0]->package_cost;						
							$total_amount = $package_info[0]->package_cost - $discount;
						}
					}
					$package_name = $package_info[0]->package_name;
					$this->session->set_userdata('is_valid_request', 1);
					if($gateway_details[0]->type_id == 28) //Paypal Settings
					{
						$config['return'] 				= base_url().'payment/paypal_success';
						$config['cancel_return'] 		= base_url().'payment/paypal_cancel';
						$config['production'] 	= true;
						$config['currency_code'] 		= 'USD';
						$config['custom'] = 'user_id='.$this->ion_auth->get_user_id().'&package_id='.$package_id;						
						foreach($field_values as $value) {
							if($value->field_key == 'Paypal_Email') {
								$config['business'] = $value->field_output_value;
							}
							if($value->field_key == 'Account_Type' && $value->field_output_value == 'sandbox') {
								$config['production'] = false;
							}
							if($value->field_key == 'Currency_Code') {
								$config['currency_code'] = $value->field_output_value;
							}
							if($value->field_key == 'Header_Logo') {
								$config['cpp_header_image'] = URL_PUBLIC_UPLOADS2.'settings/thumbs/'.$value->field_output_value;
							}
						}						

						$this->load->library('paypal', $config);
						$this->paypal->__initialize($config);
						$this->paypal->add($package_name, $total_amount);
						$this->paypal->pay(); /*Process the payment*/
					}
					elseif($gateway_details[0]->type_id == 27) //Payu Settings
					{
						$payuparams = array();
						$MERCHANT_KEY = $SALT = $account_type = '';
						$PAYU_BASE_URL = 'https://test.payu.in';
						foreach($field_values as $value) {
							if($value->field_key == 'Account_TypeLIveSandbox') {
								$account_type = $value->field_output_value;
							}
						}
						foreach($field_values as $value) {						
							
							if($account_type == 'Sandbox')
							{
								if($value->field_key == 'Sandbox_Merchant_Key') {
									$payuparams['key'] = $value->field_output_value;
								}
								if($value->field_key == 'Sandbox_Salt') {
									$payuparams['salt'] = $value->field_output_value;
								}
								if($value->field_key == 'Test_URL') {
									$payuparams['action'] = $value->field_output_value;
								}								
							}
							else
							{
								if($value->field_key == 'Live_Merchant_Key') {
									$payuparams['key'] = $value->field_output_value;
								}
								if($value->field_key == 'Live_Salt') {
									$payuparams['salt'] = $value->field_output_value;
								}
								if($value->field_key == 'Live_URL') {
									$payuparams['action'] = $value->field_output_value;
								}
							}
						}
						$payuparams['surl'] = base_url() . 'payment/payu_success';
						$payuparams['furl'] = base_url() . 'payment/payu_cancel';
						
						$payuparams['udf1'] = $this->ion_auth->get_user_id();
						$payuparams['udf2'] = $package_id;
						
						$payuparams['service_provider'] = 'payu_paisa';
						$payuparams['productinfo'] = $package_name;
						$payuparams['amount'] = $total_amount;
						
						$user_details = $this->base_model->fetch_records_from('users', array('id' => $this->ion_auth->get_user_id()));
						if(!empty($user_details))
						{
							$payuparams['firstname'] = $user_details[0]->first_name;
							$payuparams['lastname'] = $user_details[0]->last_name;
							$payuparams['email'] = $user_details[0]->email;
							$payuparams['phone'] = $user_details[0]->phone;
						}						
						$this->load->helper('payu');					
						echo call_payu( $payuparams );
						die();
					}
				}
				else
				{
					$this->safe_redirect('', 'There are no packages. Please contact administrator');
				}
			}
			else
			{
				$this->prepare_flashmessage(validation_errors(), 1);
				$user_type = getUserType();
				if($user_type == "student")
					$redirect_path = URL_STUDENT_LIST_PACKAGES;
				else if($user_type == "tutor")
					$redirect_path = URL_TUTOR_LIST_PACKAGES;
				else if($user_type == "institute")
					$redirect_path = URL_INSTITUTE_LIST_PACKAGES;
				redirect($redirect_path);
			}
		}
		else
		{
			$this->safe_redirect();
		}
	}
	
	function issetCheck($post,$key)
	{
		if(isset($post[$key])){
		$return=$post[$key];
		}
		else{
		$return='';
		}
		return $return;
	}

	function paypal_success()
	{
		if($this->input->post() && $this->session->userdata('is_valid_request'))
		{
			$custom = array();
			parse_str($this->input->post("custom"), $custom);
			$user_id = $custom['user_id'];
			$package_id = $custom['package_id'];
			if($user_id != '' && $package_id != '')
			{
				$user_details = $this->base_model->get_user_details( $user_id );
				$package_details = $this->base_model->fetch_records_from('packages', array('id' => $package_id));
				if(!empty($user_details) && !empty($package_details))
				{
					$user_info = $user_details[0];
					$subscription_info['user_id'] = $user_id;
					$subscription_info['user_name'] = $user_info->first_name.' '.$user_info->last_name;
					$subscription_info['user_type'] = $user_info->group_name;
					$subscription_info['user_group_id'] = $user_info->group_id;
					
					$subscription_details 	= $package_details[0];
					$subscription_info['package_id'] = $package_id;
					$subscription_info['package_name'] = $subscription_details->package_name;
					$subscription_info['package_cost'] = $subscription_details->package_cost;

					$subscription_info['discount_type'] = $subscription_details->discount_type;
					$subscription_info['discount_value'] = $subscription_details->discount;
					$discount_amount 	= 0;
					if(isset($subscription_details->discount) && ($subscription_details->discount != 0))
					{
						if($subscription_details->discount_type == 'Value')
						{
							$discount_amount = $subscription_details->discount;				
						}
						else
						{
							$discount_amount = ($subscription_details->package_cost/$subscription_details->discount)/100;
						}
					}
					$subscription_info['discount_amount'] = $discount_amount;
					
					$subscription_info['amount_paid'] = $this->input->post('mc_gross');
					$subscription_info['credits'] 	  = $subscription_details->credits;
					$subscription_info['payment_type'] 		= "paypal";
					$subscription_info['transaction_no']   	= $this->input->post("txn_id");
					$subscription_info['payment_received'] 	= "1";					
					$subscription_info['payer_id'] 			= $this->input->post("payer_id");
					$subscription_info['payer_email'] 		= $this->input->post("payer_email");
					$subscription_info['payer_name'] 		= $this->input->post("first_name") . " " . 
					$this->input->post("last_name");
					$subscription_info['subscribe_date'] 	= date('Y-m-d H:i:s');
					$ref 	= $this->base_model->insert_operation_id($subscription_info, 'subscriptions');
					if($ref > 0)
					{
						$user_data['subscription_id'] 		= $ref;
						$this->base_model->update_operation($user_data, 'users', array('id' => $user_id));

						//Log Credits transaction data & update user net credits - Start
						$log_data = array(
							'user_id' => $user_id,
							'credits' => $subscription_details->credits,
							'per_credit_value' => get_system_settings('per_credit_value'),
							'action'  => 'credited',
							'purpose' => 'Package "'.$subscription_details->package_name.'" subscription',
							'date_of_action	' => date('Y-m-d H:i:s'),
							'reference_table' => 'subscriptions',
							'reference_id' => $ref,
						);
						log_user_credits_transaction($log_data);

						update_user_credits($user_id, $subscription_details->credits, 'credit');
						//Log Credits transaction data & update user net credits - End
					}
					$this->prepare_flashmessage(get_languageword('Payment success Transaction Id '). ": <strong>" . 
					$subscription_info['transaction_no'] . "</strong>", 0);
					$this->session->unset_userdata('is_valid_request');
					$user_type = getUserType($user_id);
					if($user_type == "institute")
						redirect(URL_INSTITUTE_SUBSCRIPTIONS, 'refresh');
					else if($user_type == "tutor")
						redirect(URL_TUTOR_SUBSCRIPTIONS, 'refresh');
					else if($user_type == "student")
						redirect(URL_STUDENT_SUBSCRIPTIONS, 'refresh');
					else
						redirect(URL_AUTH_INDEX);
				}
			}
		}
		else
		{
			$this->safe_redirect('', get_languageword('Wrong operation'), FALSE);
		}
	}
	
	function paypal_cancel()
	{
		$this->safe_redirect('', get_languageword('You have cancelled your transaction'), FALSE);
	}
	
	function payu_success()
	{

		if($this->input->post() && $this->session->userdata('is_valid_request'))
		{
			$status_message = '';
			$status=$_POST["status"];
			$firstname=$_POST["firstname"];
			$amount=$_POST["amount"];
			$txnid=$_POST["txnid"];
			$posted_hash=$_POST["hash"];
			$key=$_POST["key"];
			$productinfo=$_POST["productinfo"];
			$email=$_POST["email"];
			
			$user_id = $_POST['udf1'];
			$package_id = $_POST['udf2'];
			
			$salt = '';
			$field_values = $this->db->get_where('system_settings_fields',array('type_id' => 27))->result();
			$account_type = '';
			foreach($field_values as $value) {
				if($value->field_key == 'Account_TypeLIveSandbox') {
					$account_type = $value->field_output_value;
				}
			}
			foreach($field_values as $index => $value) {
				if($account_type == 'Sandbox')
				{
					if($value->field_key == 'Sandbox_Salt') {
						$salt = $value->field_output_value;
					}
				}
				else
				{
					if($value->field_key == 'Live_Salt') {
						$salt = $value->field_output_value;
					}
				}				
			}
			
			if (isset($_POST["additionalCharges"])) {
			$additionalCharges=$_POST["additionalCharges"];
			$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||'.$package_id.'|'.$user_id.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

			}
			else {
			$retHashSeq = $salt.'|'.$status.'|||||||||'.$package_id.'|'.$user_id.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
			}
			$hash = hash("sha512", $retHashSeq);

			if ($hash != $posted_hash) {
			   $status_message = get_languageword("Invalid Transaction Please try again");
			} 
			else
			{
				if($status == 'success')
				{
					$user_details = $this->base_model->get_user_details( $user_id );
					$user_info = $user_details[0];
					$subscription_info['user_id'] = $user_id;
					$subscription_info['user_name'] = $user_info->first_name.' '.$user_info->last_name;
					$subscription_info['user_type'] = $user_info->group_name;
					$subscription_info['user_group_id'] = $user_info->group_id;
					
					$package_details = $this->base_model->fetch_records_from('packages', array('id' => $package_id));
					$subscription_details 	= $package_details[0];
					$subscription_info['package_id'] = $package_id;
					$subscription_info['package_name'] = $subscription_details->package_name;
					$subscription_info['package_cost'] = $subscription_details->package_cost;

					$subscription_info['discount_type'] = $subscription_details->discount_type;
					$subscription_info['discount_value'] = $subscription_details->discount;
					$discount_amount 	= 0;
					if(isset($subscription_details->discount) && ($subscription_details->discount != 0))
					{
						if($subscription_details->discount_type == 'Value')
						{
							$discount_amount = $subscription_details->discount;				
						}
						else
						{
							$discount_amount = ($subscription_details->package_cost/$subscription_details->discount)/100;
						}
					}
					$subscription_info['discount_amount'] = $discount_amount;
					
					$subscription_info['amount_paid'] = $this->input->post('amount');
					$subscription_info['credits'] 	  = $subscription_details->credits;					
					$subscription_info['payment_type'] 		= "payu";
					$subscription_info['transaction_no']   	= $this->input->post("txnid");
					$subscription_info['payment_received'] 	= "1";					
					//$subscription_info['payer_id'] 			= $this->input->post("payer_id");
					$subscription_info['payer_email'] 		= $this->input->post("email");
					$subscription_info['payer_name'] 		= $this->input->post("firstname") . " " . 
					$this->input->post("lastname");
					$subscription_info['subscribe_date'] 	= date('Y-m-d H:i:s');

					$ref 	= $this->base_model->insert_operation_id($subscription_info, 'subscriptions');
					if($ref > 0)
					{
						$user_data['subscription_id'] 		= $ref;
						$this->base_model->update_operation($user_data, 'users', array('id' => $user_id));

						//Log Credits transaction data & update user net credits - Start
						$log_data = array(
							'user_id' => $user_id,
							'credits' => $subscription_details->credits,
							'per_credit_value' => get_system_settings('per_credit_value'),
							'action'  => 'credited',
							'purpose' => 'Package "'.$subscription_details->package_name.'" subscription',
							'date_of_action	' => date('Y-m-d H:i:s'),
							'reference_table' => 'subscriptions',
							'reference_id' => $ref,
						);
						log_user_credits_transaction($log_data);

						update_user_credits($user_id, $subscription_details->credits, 'credit');
						//Log Credits transaction data & update user net credits - End
					}

					$this->prepare_flashmessage(get_languageword('Payment success Transaction Id '). ": <strong>" . 
					$subscription_info['transaction_no'] . "</strong>", 0);
					$this->session->unset_userdata('is_valid_request');
					$user_type = getUserType($user_id);
					if($user_type == "institute")
						redirect(URL_INSTITUTE_SUBSCRIPTIONS, 'refresh');
					else if($user_type == "tutor")
						redirect(URL_TUTOR_SUBSCRIPTIONS, 'refresh');
					else if($user_type == "student")
						redirect(URL_STUDENT_SUBSCRIPTIONS, 'refresh');
					else
						redirect(URL_AUTH_INDEX);
				}
				else
				{
					$this->safe_redirect('', get_languageword('Transaction Failed Please try again'), FALSE);
				}
			}	
		}
		else
		{
			$this->safe_redirect('', get_languageword('Wrong operation'), FALSE);
		}
	}
	
	function payu_cancel()
	{
		$this->safe_redirect('', get_languageword('You have cancelled your transaction'), FALSE);
	}
}