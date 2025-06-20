<?php
/* <!--  START 
===============================================
Version :- V.2 		Author : ''    23-July-2020
=============================================== 
-->
*/
defined('BASEPATH') or exit('No direct script access allowed');

class Payslip extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model(['workspace_model', 'payslip_model', 'notifications_model', 'projects_model', 'users_model', 'payments_model']);
		$this->load->library(['ion_auth', 'form_validation', 'Pdf']);
		$this->load->helper(['url', 'language']);
		$this->load->library('session');

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	public function index()
	{
		if (!check_permissions("payslips", "read", "", true)) {
			return redirect(base_url(), 'refresh');
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

			$product_ids = explode(',', $user->workspace_id);

			$section = array_map('trim', $product_ids);

			$product_ids = $section;

			$data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
			if (!empty($workspace)) {
				if (!$this->session->has_userdata('workspace_id')) {
					$this->session->set_userdata('workspace_id', $workspace[0]->id);
				}
			}
			$workspace_id = $this->session->userdata('workspace_id');
			if (!empty($workspace_id)) {
				$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
				$data['projects'] = $projects;
				$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
				$this->load->view('payslip', $data);
			} else {
				redirect('home', 'refresh');
				return false;
				exit();
			}
		}
	}

	public function create_payslip()
	{
		if (!check_permissions("payslips", "create", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

			$product_ids = explode(',', $user->workspace_id);

			$section = array_map('trim', $product_ids);

			$product_ids = $section;

			$data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
			if (!empty($workspace)) {
				if (!$this->session->has_userdata('workspace_id')) {
					$this->session->set_userdata('workspace_id', $workspace[0]->id);
				}
			}
			$current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
			$user_ids = explode(',', $current_workspace_id[0]->user_id);
			$section = array_map('trim', $user_ids);
			$user_ids = $section;
			$data['currency'] = get_currency_symbol();
			$data['all_user'] = $this->users_model->get_user($user_ids);
			$workspace_id = $this->session->userdata('workspace_id');
			$data['allowances'] = $this->payslip_model->get_allowance($workspace[0]->id);
			$data['deductions'] = $this->payslip_model->get_deduction($workspace[0]->id);
			$data['payment_modes'] = $this->payments_model->get_payment_modes($workspace_id);
			$workspace_id = $this->session->userdata('workspace_id');
			if (!empty($workspace_id)) {
				$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
				$data['projects'] = $projects;
				$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
				$this->load->view('create-payslip', $data);
			} else {
				redirect('home', 'refresh');
			}
		}
	}

	public function create_allowance()
	{
		if (!check_permissions("payslips", "create", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		$this->form_validation->set_rules('allowance_name', str_replace(':', '', 'Name is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('amount', str_replace(':', '', 'Amount is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {

			$data = array(
				'name' => strip_tags($this->input->post('allowance_name', true)),
				'amount' => strip_tags($this->input->post('amount', true)),
				'workspace_id' => $this->session->userdata('workspace_id')
			);
			$allowance_id = $this->payslip_model->add_allowance($data);
			if ($allowance_id != false) {
				$response['error'] = false;
				$response['allowance_id'] = $allowance_id;
				$response['message'] = 'Allowance Added Successfully.';
				$this->session->set_flashdata('message', 'Allowance added successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$response['error'] = true;
				$response['message'] = 'Allowance could not added! Try again!';
				$this->session->set_flashdata('message', 'Allowance could not added! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
		} else {
			$response['error'] = true;
			$response['message'] = validation_errors();
		}
		$response['csrfName'] = $this->security->get_csrf_token_name();
		$response['csrfHash'] = $this->security->get_csrf_hash();
		echo json_encode($response);
	}
	public function create_deduction()
	{
		if (!check_permissions("payslips", "create", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		$this->form_validation->set_rules('deduction_name', str_replace(':', '', 'Name is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('deduction_type', str_replace(':', '', 'deduction_type is empty.'), 'trim|required|xss_clean');
		if ($this->form_validation->run() === TRUE) {

			$data = array(
				'name' => strip_tags($this->input->post('deduction_name', true)),
				'deduction_type' => strip_tags($this->input->post('deduction_type', true)),
				'amount' => strip_tags($this->input->post('deduction_amount', true)),
				'percentage' => strip_tags($this->input->post('deduction_percentage', true)),
				'workspace_id' => $this->session->userdata('workspace_id')
			);
			$deduction_id = $this->payslip_model->add_deduction($data);
			if ($deduction_id != false) {
				$response['error'] = false;
				$response['deduction_id'] = $deduction_id;
				$response['message'] = 'Deduction Added Successfully.';
				$this->session->set_flashdata('message', 'Deduction added successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$response['error'] = true;
				$response['message'] = 'Deduction could not added! Try again!';
				$this->session->set_flashdata('message', 'Deduction could not added! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
		} else {
			$response['error'] = true;
			$response['message'] = validation_errors();
		}
		$response['csrfName'] = $this->security->get_csrf_token_name();
		$response['csrfHash'] = $this->security->get_csrf_hash();
		echo json_encode($response);
	}

	public function get_lop()
	{
		$startDate = date('Y-m-01');
		$endDate = date('Y-m-t');
		$query = $this->db->select_sum('leave_days', 'total_leave_days')
			->from('leaves')
			->where('status', 1)
			->where('user_id', $this->input->post('user_id', true))
			->where('leave_from >=', $startDate)
			->where('leave_to <=', $endDate)
			->get();
		$result = $query->row();
		$totalLeaveDays = $result->total_leave_days;
		$response['csrfName'] = $this->security->get_csrf_token_name();
		$response['csrfHash'] = $this->security->get_csrf_hash();
		$response['leaves'] = $totalLeaveDays ? $totalLeaveDays : 0;
		echo json_encode($response);
	}

	public function create()
	{
		if (!check_permissions("payslips", "create", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		$working_days = $this->input->post('working_days') ? $this->input->post('working_days') : 0;
		$lop_days = $this->input->post('lop_days') ? $this->input->post('lop_days') : 0;
		$paid_days = $working_days - $lop_days;
		$basic_salary = $this->input->post('basic_salary') ? round(floatval($this->input->post('basic_salary')), 2) : 0;
		$leave_deduction = $basic_salary / $working_days * $lop_days;
		$total_basic_salary_deduction = $basic_salary - $leave_deduction;
		$ot_hours = $this->input->post('ot_hours') ? $this->input->post('ot_hours') : 0;
		$ot_rate = $this->input->post('ot_rate') ? $this->input->post('ot_rate') : 0;

		if (is_numeric($ot_hours) && is_numeric($ot_rate)) {
			$ot_payment = $ot_hours * $ot_rate;
		} else {
			$ot_payment = 0;
		}
		$total_allowance = $this->input->post('total_allowance') ? $this->input->post('total_allowance') : 0;
		$total_allowance = round(floatval($total_allowance), 2);
		if (is_numeric($total_allowance)) {
			$gross_salary = $total_basic_salary_deduction + $total_allowance;
		} else {
			$gross_salary = 0;
		}

		$bonus = $this->input->post('bonus') ? $this->input->post('bonus') : 0;
		$incentives = $this->input->post('incentives') ? $this->input->post('incentives') : 0;

		$ot_payment = round(floatval($ot_payment), 2);
		$bonus = round(floatval($bonus), 2);
		$incentives = round(floatval($incentives), 2);
		$other_earning = $ot_payment + $bonus + $incentives;
		$total_earnings = $gross_salary + $other_earning;
		$total_deduction = $this->input->post('total_deduction') ? round(floatval($this->input->post('total_deduction')), 2)  : 0;
		$net_pay = $total_earnings - $total_deduction;
		$this->form_validation->set_rules('user_id', str_replace(':', '', 'Username is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('basic_salary', str_replace(':', '', 'Basic salary is empty.'), 'trim|required|xss_clean');
		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'payslip_month' => strip_tags($this->input->post('payslip_month', true)),
				'working_days' => $working_days,
				'lop_days' => $lop_days,
				'paid_days' => $paid_days,
				'basic_salary' => $basic_salary,
				'leave_deduction' => $leave_deduction,
				'ot_hours' => $ot_hours,
				'ot_rate' => $ot_rate,
				'ot_payment' => $ot_payment,
				'total_allowance' => $total_allowance,
				'incentives' => $incentives,
				'bonus' => $bonus,
				'total_earnings' => $total_earnings,
				'total_deductions' => $total_deduction,
				'net_pay' => $net_pay,
				'payment_date' => strip_tags($this->input->post('payment_date', true)),
				'payment_method' => strip_tags($this->input->post('payment_method', true)),
				'status' => strip_tags($this->input->post('status', true)),
				'user_id' => $this->input->post('user_id'),
				'workspace_id' => $this->session->userdata('workspace_id'),
			);

			$payslip_id = $this->payslip_model->create_payslip($data);
			$allowance_names = $this->input->post("allowance_name");
			$allowance_amounts = $this->input->post("amount");
			$allowance_ids = $this->input->post("allowance");

			if (!empty($allowance_names) && !empty($payslip_id)) {
				$payslip_allowance_ids = [];
				for ($i = 0; $i < count($allowance_names); $i++) {
					if (isset($allowance_ids[$i])) {
						$allowance_data = array(
							'payslip_id' => $payslip_id,
							'allowance_id' => $allowance_ids[$i],
							'allowance_name' => $allowance_names[$i],
							'amount' => $allowance_amounts[$i],
							'workspace_id' => $this->session->userdata('workspace_id')
						);
						$payslip_allowance_id = $this->payslip_model->add_payslip_allowance($allowance_data);

						if ($payslip_allowance_id != false) {
							array_push($payslip_allowance_ids, $payslip_allowance_id);
						}
					}
				}
				$allowance_item_ids = implode(",", $payslip_allowance_ids);
				$data = array(
					'allowance_item_ids' => $allowance_item_ids
				);
				$this->payslip_model->update_payslip($data, $payslip_id);
				$this->session->set_flashdata('message', 'Payslip Created successfully.');
				$this->session->set_flashdata('message_type', 'success');
			}
			$deduction_names = $this->input->post("deduction_name");
			$deduction_type = $this->input->post("deduction_type");
			$deduction_amounts = $this->input->post("deduction_amount");
			$deduction_percentages = $this->input->post("deduction_percentage");
			$deduction_ids = $this->input->post("deduction");

			if (!empty($deduction_names) && !empty($payslip_id)) {
				$payslip_deduction_ids = [];

				for ($i = 0; $i < count($deduction_names); $i++) {
					if (isset($deduction_ids[$i])) {
						$deduction_data = array(
							'payslip_id' => $payslip_id,
							'deduction_type' => $deduction_type[$i],
							'percentage' => $deduction_percentages[$i],
							'deduction_id' => $deduction_ids[$i],
							'deduction_name' => $deduction_names[$i],
							'amount' => $deduction_amounts[$i],
							'workspace_id' => $this->session->userdata('workspace_id')
						);
						if ($deduction_data['deduction_type'] == "percentage") {
							$deduction_percentage = $deduction_percentages[$i];
							$deduction_amount = ($deduction_percentage / 100) * $basic_salary;
						} elseif ($deduction_data['deduction_type'] == "amount") {
							$deduction_amount = $deduction_data['amount'];
						} else {
							$deduction_amount = 0;
						}

						$deduction_data['amount'] = $deduction_amount;
						$payslip_deduction_id = $this->payslip_model->add_payslip_deduction($deduction_data);
						if ($payslip_deduction_id != false) {
							array_push($payslip_deduction_ids, $payslip_deduction_id);
						}
					}
				}
				$deduction_item_ids = implode(",", $payslip_deduction_ids);
				$data = array(
					'deduction_item_ids' => $deduction_item_ids
				);
				$this->payslip_model->update_payslip($data, $payslip_id);
				$this->session->set_flashdata('message', 'Payslip Created successfully.');
				$this->session->set_flashdata('message_type', 'success');
			}
			$response['error'] = false;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Successful';
			echo json_encode($response);
		} else {
			$response['error'] = true;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = validation_errors();
			echo json_encode($response);
		}
	}
	public function edit()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		if (!check_permissions("payslips", "update", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		$working_days = $this->input->post('working_days') ? $this->input->post('working_days') : 0;
		$lop_days = $this->input->post('lop_days') ? $this->input->post('lop_days') : 0;
		$paid_days = $working_days - $lop_days;
		$basic_salary = $this->input->post('basic_salary') ? $this->input->post('basic_salary') : 0;
		$basic_salary = round(floatval($basic_salary), 2);
		$working_days = $this->input->post('working_days') ? $this->input->post('working_days') : 0;
		$lop_days = $this->input->post('lop_days') ? $this->input->post('lop_days') : 0;
		$paid_days = $working_days - $lop_days;
		$basic_salary = $this->input->post('basic_salary') ? $this->input->post('basic_salary') : 0;
		$basic_salary = round(floatval($basic_salary), 2);
		$leave_deduction = $basic_salary / $working_days * $lop_days;
		$total_basic_salary_deduction = $basic_salary - $leave_deduction;
		$ot_hours = $this->input->post('ot_hours') ? $this->input->post('ot_hours') : 0;
		$ot_rate = $this->input->post('ot_rate') ? $this->input->post('ot_rate') : 0;

		if (is_numeric($ot_hours) && is_numeric($ot_rate)) {
			$ot_payment = $ot_hours * $ot_rate;
		} else {
			$ot_payment = 0;
		}
		$total_allowance = $this->input->post('total_allowance') ? $this->input->post('total_allowance') : 0;
		$total_allowance = round(floatval($total_allowance), 2);
		if (is_numeric($total_allowance)) {
			$gross_salary = $total_basic_salary_deduction + $total_allowance;
		} else {
			$gross_salary = 0;
		}

		$bonus = $this->input->post('bonus') ? $this->input->post('bonus') : 0;
		$incentives = $this->input->post('incentives') ? $this->input->post('incentives') : 0;

		$ot_payment = round(floatval($ot_payment), 2);
		$bonus = round(floatval($bonus), 2);
		$incentives = round(floatval($incentives), 2);

		$other_earning = $ot_payment + $bonus + $incentives;
		$total_earnings = $gross_salary + $other_earning;
		$total_deduction = round(floatval($this->input->post('total_deduction')), 2) ? $this->input->post('total_deduction') : 0;
		$net_pay = $total_earnings - $total_deduction;

		$data = array(
			'payslip_month' => $this->input->post('payslip_month'),
			'working_days' => $working_days,
			'lop_days' => $lop_days,
			'paid_days' => $paid_days,
			'basic_salary' => $basic_salary,
			'leave_deduction' => $leave_deduction,
			'ot_hours' => $ot_hours,
			'ot_rate' => $ot_rate,
			'ot_payment' => $ot_payment,
			'total_allowance' => $total_allowance,
			'incentives' => $incentives,
			'bonus' => $bonus,
			'total_earnings' => $total_earnings,
			'total_deductions' => $total_deduction,
			'net_pay' => $net_pay,
			'payment_date' => $this->input->post('payment_date'),
			'payment_method' => $this->input->post('payment_method'),
			'status' => $this->input->post('status'),
			'workspace_id' => $this->session->userdata('workspace_id'),
		);
		$allowance_names = $this->input->post("allowance_name");
		$allowance_amounts = $this->input->post("amount");
		$allowance_id = $this->input->post("allowance");
		$allowance_ids = $this->input->post("allowance_item_ids");
		$payslip_id = $this->input->post('id');

		$deduction_names = $this->input->post("deduction_name");
		$deduction_type = $this->input->post("deduction_type");
		$deduction_amounts = $this->input->post("deduction_amount");
		$deduction_percentages = $this->input->post("deduction_percentage");
		$deduction_id = $this->input->post("deduction");
		$deduction_item_ids = $this->input->post("deduction_item_ids");
		$allowance_item_ids_array = [];
		$deduction_item_ids_array = [];
		if ($this->payslip_model->update_payslip($data, $payslip_id)) {
			if (is_array($allowance_names)) {
				for ($i = 0; $i < count($allowance_names); $i++) {
					$allowdata = array(
						'payslip_id' => strip_tags($payslip_id, true),
						'allowance_id' => isset($allowance_id[$i]) ? $allowance_id[$i] : null,
						'allowance_name' => isset($allowance_names[$i]) ? $allowance_names[$i] : null,
						'amount' => isset($allowance_amounts[$i]) ? $allowance_amounts[$i] : null,
						'workspace_id' => $this->session->userdata('workspace_id')
					);
					if ($allowdata['allowance_id'] !== null) {
						if (!empty($allowance_ids[$i])) {
							$this->payslip_model->update_payslip_allowance_item($allowdata, $allowance_ids[$i]);
							array_push($allowance_item_ids_array, $allowance_ids[$i]);
						} else {
							$allowance_item_id = $this->payslip_model->add_payslip_allowance($allowdata);
							if ($allowance_item_id != false) {
								array_push($allowance_item_ids_array, $allowance_item_id);
							}
						}
					}
				}
				$allowance_item_ids = implode(",", $allowance_item_ids_array);
				$data = array(
					'allowance_item_ids' => $allowance_item_ids
				);
				$this->payslip_model->update_payslip($data, $payslip_id);
				if (isset($_POST['deleted_allowance_item_ids'])) {
					$deleted_allowance_item_ids = explode(",", $_POST['deleted_allowance_item_ids']);
					foreach ($deleted_allowance_item_ids as $allowance_item_id) {
						$this->payslip_model->delete_payslip_allowance_item($allowance_item_id);
					}
				}
			} else {
				$allowance_item_ids = implode(",", $allowance_item_ids_array);
				$data = array(
					'allowance_item_ids' => $allowance_item_ids
				);
				$this->payslip_model->update_payslip($data, $payslip_id);
				if (isset($_POST['deleted_allowance_item_ids'])) {
					$deleted_allowance_item_ids = explode(",", $_POST['deleted_allowance_item_ids']);
					foreach ($deleted_allowance_item_ids as $allowance_item_id) {
						$this->payslip_model->delete_payslip_allowance_item($allowance_item_id);
					}
				}
			}

			if (is_array($deduction_names)) {
				for ($i = 0; $i < count($deduction_names); $i++) {
					$deduction_data = array(
						'payslip_id' => $payslip_id,
						'deduction_type' => $deduction_type[$i],
						'percentage' => $deduction_percentages[$i],
						'deduction_id' => $deduction_id[$i],
						'deduction_name' => $deduction_names[$i],
						'amount' => $deduction_amounts[$i],
						'workspace_id' => $this->session->userdata('workspace_id')
					);
					if ($deduction_data['deduction_type'] == "percentage") {
						$basic_salary = $basic_salary;
						$deduction_percentage = $deduction_percentages[$i];
						$deduction_amount = ($deduction_percentage / 100) * $basic_salary;
					} elseif ($deduction_data['deduction_type'] == "amount") {
						$deduction_amount = $deduction_data['amount'];
					} else {
						$deduction_amount = 0;
					}
					$deduction_data['amount'] = $deduction_amount;
					if (!empty($deduction_item_ids[$i])) {
						$this->payslip_model->update_payslip_deduction_item($deduction_data, $deduction_item_ids[$i]);
						array_push($deduction_item_ids_array, $deduction_item_ids[$i]);
					} else {
						$deduction_item_ids = $this->payslip_model->add_payslip_deduction($deduction_data);
						if ($deduction_item_ids != false) {
							array_push($deduction_item_ids_array, $deduction_item_ids);
						}
					}
				}
				$deduction_item_ids = implode(",", $deduction_item_ids_array);
				$data = array(
					'deduction_item_ids' => $deduction_item_ids
				);
				if (isset($_POST['deleted_deduction_item_ids'])) {
					$deleted_deductions_item_ids = explode(",", $_POST['deleted_deduction_item_ids']);
					foreach ($deleted_deductions_item_ids as $deduction_item_id) {
						$this->payslip_model->delete_payslip_deductions_item($deduction_item_id);
					}
				}
				$this->payslip_model->update_payslip($data, $payslip_id);
			} else {
				$deduction_item_ids = implode(",", $deduction_item_ids_array);
				$data = array(
					'deduction_item_ids' => $deduction_item_ids
				);
				if (isset($_POST['deleted_deduction_item_ids'])) {
					$deleted_deductions_item_ids = explode(",", $_POST['deleted_deduction_item_ids']);
					foreach ($deleted_deductions_item_ids as $deduction_item_id) {
						$this->payslip_model->delete_payslip_deductions_item($deduction_item_id);
					}
				}
				$this->payslip_model->update_payslip($data, $payslip_id);
			}

			$this->session->set_flashdata('message', 'Payslip updated successfully.');
			$this->session->set_flashdata('message_type', 'success');
			$response['error'] = false;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Successful';
			echo json_encode($response);
		} else {
			$this->session->set_flashdata('message', 'Payslip could not update! Try again!');
			$this->session->set_flashdata('message_type', 'error');

			$response['error'] = true;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = validation_errors();
			echo json_encode($response);
		}
	}

	public function get_deduction()
	{
		if (!check_permissions("payslips", "read", "", true)) {
			return redirect(base_url(), 'refresh');
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$workspace_id = $this->session->userdata('workspace_id');
			$data =  $this->payslip_model->get_deduction($workspace_id);
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['data'] = $data;
			print_r(json_encode($response));
		}
	}

	public function get_deduction_by_id($id = '')
	{
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
			$data = $this->payslip_model->get_deduction_by_id($id);
			if (!empty($data)) {
				$data[0]['csrfName'] = $this->security->get_csrf_token_name();
				$data[0]['csrfHash'] = $this->security->get_csrf_hash();
				echo json_encode($data[0]);
			} else {
				$data[0]['csrfName'] = $this->security->get_csrf_token_name();
				$data[0]['csrfHash'] = $this->security->get_csrf_hash();
				echo json_encode($data[0]);
			}
		} else {
			$data[0]['csrfName'] = $this->security->get_csrf_token_name();
			$data[0]['csrfHash'] = $this->security->get_csrf_hash();
			echo json_encode($data[0]);
		}
	}

	public function get_allowance()
	{
		if (!check_permissions("payslips", "read", "", true)) {
			return redirect(base_url(), 'refresh');
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$workspace_id = $this->session->userdata('workspace_id');
			$data =  $this->payslip_model->get_allowance($workspace_id);
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['data'] = $data;
			print_r(json_encode($response));
		}
	}

	public function get_allowance_by_id($id = '')
	{
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
			$data = $this->payslip_model->get_allowance_by_id($id);
			if (!empty($data)) {
				$data[0]['csrfName'] = $this->security->get_csrf_token_name();
				$data[0]['csrfHash'] = $this->security->get_csrf_hash();
				echo json_encode($data[0]);
			} else {
				$data[0]['csrfName'] = $this->security->get_csrf_token_name();
				$data[0]['csrfHash'] = $this->security->get_csrf_hash();
				echo json_encode($data[0]);
			}
		} else {
			$data[0]['csrfName'] = $this->security->get_csrf_token_name();
			$data[0]['csrfHash'] = $this->security->get_csrf_hash();
			echo json_encode($data[0]);
		}
	}

	public function get_payslip_list()
	{
		if (!check_permissions("payslips", "read", "", true)) {
			return redirect(base_url(), 'refresh');
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$workspace_id = $this->session->userdata('workspace_id');
			return $this->payslip_model->get_payslip_list($workspace_id);
		}
	}

	public function get_payslip_by_id()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {

			$payslip_id = $this->input->post('id');

			if (empty($payslip_id) || !is_numeric($payslip_id)) {
				redirect('payslip', 'refresh');
				return false;
				exit(0);
			}
			$data = $this->payslip_model->get_payslip_by_id($payslip_id);
			$data[0]['csrfName'] = $this->security->get_csrf_token_name();
			$data[0]['csrfHash'] = $this->security->get_csrf_hash();

			echo json_encode($data[0]);
		}
	}

	public function edit_payslip()
	{
		if (!check_permissions("payslips", "update", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$payslip_id = $this->uri->segment(3);

			if (empty($payslip_id) || !is_numeric($payslip_id) || $payslip_id < 1) {
				redirect('payslip', 'refresh');
				return false;
				exit(0);
			}
			$data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

			$product_ids = explode(',', $user->workspace_id);

			$section = array_map('trim', $product_ids);

			$product_ids = $section;

			$data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
			if (!empty($workspace)) {
				if (!$this->session->has_userdata('workspace_id')) {
					$this->session->set_userdata('workspace_id', $workspace[0]->id);
				}
			}
			$current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
			$user_ids = explode(',', $current_workspace_id[0]->user_id);
			$section = array_map('trim', $user_ids);
			$user_ids = $section;
			$data['currency'] = get_currency_symbol();
			$projects = $this->projects_model->get_projects($workspace[0]->id);
			$data['projects'] = $projects;
			$data['all_user'] = $this->users_model->get_user($user_ids);
			$payslip = $this->payslip_model->get_payslip_by_id($payslip_id);
			$data['payslip'] = $payslip[0];
			$data['allowances'] = $this->payslip_model->get_allowance($workspace[0]->id);
			$data['deductions'] = $this->payslip_model->get_deduction($workspace[0]->id);
			$data['allowance_items'] = $this->payslip_model->get_allowance_items($payslip_id);
			$data['deductions_items'] = $this->payslip_model->get_deductions_items($payslip_id);
			$data['payment_modes'] = $this->payments_model->get_payment_modes($workspace[0]->id);
			$workspace_id = $this->session->userdata('workspace_id');
			if (!empty($workspace_id)) {
				$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
				$data['projects'] = $projects;
				$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
				$this->load->view('edit-payslip', $data);
			} else {
				redirect('home', 'refresh');
			}
		}
	}

	public function view_payslip()
	{
		if (!check_permissions("payslips", "read", "", true)) {
			return redirect(base_url(), 'refresh');
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$payslip_id = $this->uri->segment(3);

			if (empty($payslip_id) || !is_numeric($payslip_id) || $payslip_id < 1) {
				redirect('payslip', 'refresh');
				return false;
				exit(0);
			}
			$data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

			$product_ids = explode(',', $user->workspace_id);

			$section = array_map('trim', $product_ids);

			$product_ids = $section;

			$data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
			if (!empty($workspace)) {
				if (!$this->session->has_userdata('workspace_id')) {
					$this->session->set_userdata('workspace_id', $workspace[0]->id);
				}
			}
			$current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
			$user_ids = explode(',', $current_workspace_id[0]->user_id);
			$section = array_map('trim', $user_ids);
			$user_ids = $section;
			$payslip = $this->payslip_model->get_payslip_by_id($payslip_id);
			$data['payslip'] = $payslip[0];
			$data['allowance_items'] = $this->payslip_model->get_allowance_items($payslip_id);
			$data['deductions_items'] = $this->payslip_model->get_deductions_items($payslip_id);
			$data['all_user'] = $this->users_model->get_user($user_ids);
			$data['my_fonts'] = file_get_contents("assets/fonts/my-fonts.json");
			$workspace_id = $this->session->userdata('workspace_id');
			if (!empty($workspace_id)) {
				$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
				$data['projects'] = $projects;
				$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
				$this->load->view('view-payslip', $data);
			} else {
				redirect('home', 'refresh');
			}
		}
	}

	public function delete()
	{
		if (!check_permissions("payslips", "delete", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		$id = $this->uri->segment(3);
		if (!empty($id) && is_numeric($id)  || $id < 1) {
			if ($this->payslip_model->delete_payslip($id)) {
				$this->session->set_flashdata('message', 'Payslip deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Payslip could not be deleted! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
		}
		redirect('payslip', 'refresh');
	}

	public function allowances()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (!check_permissions("payslips", "read", "", true)) {
				return redirect(base_url(), 'refresh');
			}

			$data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
			$product_ids = explode(',', $user->workspace_id);
			$section = array_map('trim', $product_ids);
			$product_ids = $section;

			$data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
			$workspace_id = $this->session->userdata('workspace_id');

			if (!empty($workspace_id)) {
				$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
				$data['projects'] = $projects;
				$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
				$this->load->view('allowances-list', $data);
			} else {
				redirect('home', 'refresh');
			}
		}
	}

	public function deductions()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (!check_permissions("payslips", "read", "", true)) {
				return redirect(base_url(), 'refresh');
			}

			$data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
			$product_ids = explode(',', $user->workspace_id);
			$section = array_map('trim', $product_ids);
			$product_ids = $section;

			$data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
			$workspace_id = $this->session->userdata('workspace_id');

			if (!empty($workspace_id)) {
				$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
				$data['projects'] = $projects;
				$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
				$this->load->view('deduction-list', $data);
			} else {
				redirect('home', 'refresh');
			}
		}
	}
	public function get_allowances_list()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (!check_permissions("payslips", "read", "", true)) {
				return redirect(base_url(), 'refresh');
			}
			return $this->payslip_model->get_allowances_list();
		}
	}

	public function get_deductions_list()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (!check_permissions("payslips", "read", "", true)) {
				return redirect(base_url(), 'refresh');
			}
			return $this->payslip_model->get_deductions_list();
		}
	}
	public function edit_allowance()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
			$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
			$this->session->set_flashdata('message_type', 'error');
			redirect('payslip', 'refresh');
			return false;
			exit();
		}
		if (!check_permissions("payslips", "update", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}

		$this->form_validation->set_rules('allowance_name', str_replace(':', '', 'Name is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('amount', str_replace(':', '', 'Amount is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {

			$data = array(
				'name' => strip_tags($this->input->post('allowance_name', true)),
				'amount' => strip_tags($this->input->post('amount', true)),
				'workspace_id' => $this->session->userdata('workspace_id')
			);
			if ($this->payslip_model->edit_allowance($data, $this->input->post('id'))) {
				$this->session->set_flashdata('message', 'allowance Updated successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'allowance could not Updated! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}

			$response['error'] = false;

			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Successful';
			echo json_encode($response);
		} else {
			$response['error'] = true;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = validation_errors();
			echo json_encode($response);
		}
	}

	public function edit_deduction()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
			$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
			$this->session->set_flashdata('message_type', 'error');
			redirect('payslip', 'refresh');
			return false;
			exit();
		}
		if (!check_permissions("payslips", "update", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}

		$this->form_validation->set_rules('deduction_name', str_replace(':', '', 'Name is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('deduction_type', str_replace(':', '', 'deduction_type is empty.'), 'trim|required|xss_clean');
		if ($this->form_validation->run() === TRUE) {

			$data = array(
				'name' => strip_tags($this->input->post('deduction_name', true)),
				'deduction_type' => strip_tags($this->input->post('deduction_type', true)),
				'amount' => strip_tags($this->input->post('deduction_amount', true)),
				'percentage' => strip_tags($this->input->post('deduction_percentage', true)),
				'workspace_id' => $this->session->userdata('workspace_id')
			);
			if ($this->payslip_model->edit_deduction($data, $this->input->post('id'))) {
				$this->session->set_flashdata('message', 'Deduction Updated successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Deduction could not Updated! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}

			$response['error'] = false;

			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Successful';
			echo json_encode($response);
		} else {
			$response['error'] = true;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = validation_errors();
			echo json_encode($response);
		}
	}

	public function delete_allowance()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		if (!check_permissions("payslips", "delete", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		$id = $this->uri->segment(3);

		if (!empty($id) && is_numeric($id)  || $id < 1) {
			if ($this->payslip_model->delete_allowance($id)) {
				$this->session->set_flashdata('message', 'Allowance deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Allowance could not be deleted! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
			redirect('payslip/allowance-list', 'refresh');
		}
	}

	public function delete_deductions()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		if (!check_permissions("payslips", "delete", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		$id = $this->uri->segment(3);

		if (!empty($id) && is_numeric($id)  || $id < 1) {
			if ($this->payslip_model->delete_deductions($id)) {
				$this->session->set_flashdata('message', 'Deductions deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Deductions could not be deleted! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
			redirect('payslip/deduction-list', 'refresh');
		}
	}
	public function deduction()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (!check_permissions("payslips", "read", "", true)) {
				return redirect(base_url(), 'refresh');
			}

			$data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
			$product_ids = explode(',', $user->workspace_id);
			$section = array_map('trim', $product_ids);
			$product_ids = $section;

			$data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
			$workspace_id = $this->session->userdata('workspace_id');

			if (!empty($workspace_id)) {
				$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
				$data['projects'] = $projects;
				$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
				$this->load->view('deduction-list', $data);
			} else {
				redirect('home', 'refresh');
			}
		}
	}
	public function duplicate_data()
	{
		if (!check_permissions("payslips", "read", "", true)) {
			return redirect(base_url(), 'refresh');
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$data = duplicate_row("payslips", $_POST['id']);
			$response['data'] = $data;
			$response['error'] = false;

			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Successful';
			echo json_encode($response);
		}
	}
	public function create_payslips_sign()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
				$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
				$this->session->set_flashdata('message_type', 'error');
				redirect('payslips', 'refresh');
				return false;
				exit();
			}
			if (!check_permissions("payslips", "create", "", true)) {
				return response(PERMISSION_ERROR_MESSAGE);
			}
			$payslips_id = fetch_details('payslips', ['id' => $_POST['id']], '*');
			$imagedata = $this->input->post('signatureImage');
			$decoded_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagedata));
			$target_path = "assets/sign/";

			if (!is_dir($target_path)) {
				mkdir($target_path, 0777, TRUE);
			}

			$file_name =  "Sign" . time() . "-" . rand(100, 999) . '.png';
			file_put_contents($target_path . $file_name, $decoded_image);

			$data = array(
				'signature' => $file_name,

			);
			if ($this->payslip_model->edit_payslips($data, $this->input->post('id'))) {
				$this->session->set_flashdata('message', 'payslips Sign Created successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'payslips Sign could not be created! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}

			// Prepare the JSON response
			$response['error'] = false;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Successful';
			echo json_encode($response);
		}
	}

	public function delete_sign_payslips()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message_type', 'error');
                redirect('payslips', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("payslips", "delete", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            $id = $this->uri->segment(3);
            if (!empty($id) && is_numeric($id) && $id > 0) {
                if ($this->payslip_model->delete_sign_payslips($id)) {
                    $this->session->set_flashdata('message', 'Payslips sign deleted successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                } else {
                    $this->session->set_flashdata('message', 'Payslips sign could not be deleted! Try again!');
                    $this->session->set_flashdata('message_type', 'error');
                }
            }
            redirect('payslips', 'refresh');
        }
    }
}
/* 
// END
// ===============================================
// Version : V.2 		Author : ''    23-July-2020
// =============================================== 
*/
