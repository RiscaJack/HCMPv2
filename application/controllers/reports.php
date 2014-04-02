<?php
/**
 * @author Kariuki
 */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Reports extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this -> load -> helper(array('form', 'url'));
		$this -> load -> library(array('hcmp_functions', 'form_validation'));
	}
	public function index() {
		$identifier = $this -> session -> userdata('user_indicator');
		switch ($identifier) {
			case moh :
				$data['content_view'] = "";
				$view = 'shared_files/template/dashboard_template_v';
				break;
			case facility_admin :
				$data['content_view'] = "";
				$view = 'shared_files/template/template';
				break;
			case district :
				$data['content_view'] = "";
				$view = 'shared_files/template/dashboard_template_v';
				break;
			case moh_user :
				$data['content_view'] = "";
				break;
			case facility :
				$data['content_view'] = "facility/facility_reports/reports_v";
				$view = 'shared_files/template/template';
				break;
			case district_tech :
				$data['content_view'] = "";
				break;
			case rtk_manager :
				$data['content_view'] = "";
				break;
			case super_admin :
				$data['content_view'] = "";
				break;
			case allocation_committee :
				$data['content_view'] = "";
				break;
		}

		$data['title'] = "Reports";
		$data['banner_text'] = "Reports";
		$this -> load -> view($view, $data);
	}
	/*
	 |--------------------------------------------------------------------------
	 | SHARED REPORTS
	 |--------------------------------------------------------------------------
	 */
	// get the commodity listing here
	public function commodity_listing() {
		$data['title'] = "Commodity Listing";
		$data['banner_text'] = "Commodity Listing";
		$data['content_view'] = "shared_files/commodities/commodity_list_v";
		$data['commodity_list'] = commodity_sub_category::get_all();
		$this -> load -> view('shared_files/template/template', $data);
	}
	/*
	 |--------------------------------------------------------------------------
	 | FACILITY REPORTS
	 |--------------------------------------------------------------------------
	 */
	 ///////////////////GET FACILITY STOCK DATA/////////////////////////////	 
	public function facility_stock_data() {
		$facility_code = $this -> session -> userdata('facility_id');
		$data['facility_stock_data'] = facility_stocks::get_distinct_stocks_for_this_facility($facility_code, 'batch_data');
		$data['title'] = "Facility Stock";
		$data['content_view'] = "facility/facility_reports/facility_stock_data_v";
		$data['banner_text'] = "Facility Stock";
		$this -> load -> view("shared_files/template/template", $data);
	}	
	// get the facility transaction data for ordering or quick analysis
	public function facility_transaction_data() {
		$facility_code = $this -> session -> userdata('facility_id');
		$data['facility_stock_data'] = facility_transaction_table::get_all($facility_code);
		$data['title'] = "Facility Stock Summary";
		$data['content_view'] = "facility/facility_reports/facility_transaction_data_v";
		$data['banner_text'] = "Facility Stock Summary";
		$this -> load -> view("shared_files/template/template", $data);
	}
	///////GET THE ITEMS A FACILITY HAS STOCKED OUT ON 
	public function facility_stocked_out_items(){
		$facility_code = $this -> session -> userdata('facility_id');
		$data['facility_stock_data'] =facility_stocks::get_items_that_have_stock_out_in_facility($facility_code);
		$data['title'] = "Facility Stock Out Summary";
		$data['content_view'] = "facility/facility_reports/facility_stocked_out_items_v";
		$data['banner_text'] = "Facility Stock Out Summary";
		$this -> load -> view("shared_files/template/template", $data);
	}
	 public function order_listing(){
		/*		
    	$data['order_counts']=Counties::get_county_order_details("","", $facility_c);
		$data['delivered']=Counties::get_county_received("","", $facility_c);
		$data['pending']=Counties::get_pending_county("","", $facility_c);
		$data['approved']=Counties::get_approved_county("","", $facility_c);
		$data['rejected']=Counties::get_rejected_county("","", $facility_c);
    	$data['content_view'] = "facility/facility_issues/facility_issues_service_points_v";
		$data['title'] = "Order Listing";
		$data['banner_text'] = "Order Listing";	*/
		$data['title'] = "Facility Orders";
		$data['banner_text'] =  "Facility Orders";
		$data['content_view'] = "facility/facility_orders/order_listing_v";
		$this -> load -> view('shared_files/template/template', $data);
    }

}
?>