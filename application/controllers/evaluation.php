<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Evaluation extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this -> load -> helper(array('form', 'url'));
		$this -> load -> library(array('hcmp_functions', 'form_validation'));

		// $this->index();
	}

	 public function calculate_percentage($val1,$val2){
            $percentage = ($val2/$val1)*100;
            return @round($percentage);
        }
     public function index(){
     	$data['district_data'] = districts::getDistrict(1);
		$data['banner_text'] = "Facility Training Evaluation Results";
		$data['title'] = "Facility Training Evaluation Results";
		$data['report_view']  = "facility/facility_reports/analysis_home";
		$data['sidebar'] = "shared_files/report_templates/side_bar_sub_county_v";
		$data['content_view'] = "facility/facility_reports/reports_v";
		$data['report_view'] = "facility/facility_reports/analysis_home";
		$this -> load -> view('shared_files/template/template', $data);
     	
     }
	public function analysis($district=null) {
		$county_id = isset($district) ? null : 1;
		$district_id = isset($district) ? $district :null;
	
		/*if(isset($district_id)){
		$district_id =	$district;
		}else{
		$county_id = 1;	
		//	$county_id = $this -> session -> userdata('county_id');
		}*/
		
		$graph_data =$data= array();
	

		$data['county_id'] = $county_id;

		//creating the first pie chart

		$facility_evaluation = evaluation_data::get_facility_type($county_id,$district_id);	    
		
		$total_facilities_evaluated = $facility_evaluation[2]['total'] + $facility_evaluation[1]['total'] + $facility_evaluation[0]['total'];
		$fbo = $this -> calculate_percentage($total_facilities_evaluated,$facility_evaluation[0]['total']);
		$other_public_institutions = $this -> calculate_percentage($total_facilities_evaluated,$facility_evaluation[2]['total']);
		$gok = $this -> calculate_percentage($total_facilities_evaluated,$facility_evaluation[1]['total']);
		
		$graph_data = array_merge($graph_data, array("graph_id" => 'chart_1'));
		$graph_data = array_merge($graph_data, array("graph_title" => 'Facility Type Evaluated in %'));
		$graph_data = array_merge($graph_data, array("graph_type" => 'pie'));
		$graph_data = array_merge($graph_data, array("graph_yaxis_title" => 'Total Facilities'));
		$graph_data = array_merge($graph_data, array("graph_categories" => array('FBO', 'Other Public Institutions', 'GOK')));
		$graph_data = array_merge($graph_data, array("series_data" => array("Facility Type" =>
		 array(array('FBO',(int)$fbo),
		 array('Other Public Institutions',(int)$other_public_institutions),
		 array('GOK',(int)$gok)))));		
        
        $data['facility_evaluation']=$this->hcmp_functions->create_high_chart_graph($graph_data);
		// end of chart data
        $county_id = 1;	
		//creating the first bar chart
        $training_resource = evaluation_data::get_training_resource($county_id, $district_id);

		$graph_data = array_merge($graph_data, array("graph_id" => 'chart_3'));
		$graph_data = array_merge($graph_data, array("graph_title" => 'Appropriate Resources During Training'));
		$graph_data = array_merge($graph_data, array("graph_type" => 'bar'));
		$graph_data = array_merge($graph_data, array("graph_yaxis_title" => 'Appropriate Resources in %'));
		$graph_data = array_merge($graph_data, array("graph_categories" => array('Computers', 'Modems', 'Bundles','Manuals')));
		
		$graph_data = array_merge($graph_data, array("series_data" => array("Resources" =>
		 array(array('Computers',(int)$training_resource[0]['comp']),
		 array('Modems',(int)$training_resource[0]['modem']),
		 array('Bundles',(int)$training_resource[0]['bundles']),
		 array('Manuals',(int)$training_resource[0]['manual'])))));		
 
        $data['training_resource']=$this->hcmp_functions->create_high_chart_graph($graph_data);
        // end of chart data
        

//creating the second pie chart
		$trained_personel = evaluation_data::get_personel_trained($county_id, $district_id);


		// $total_facilities_evaluated = $facility_evaluation[2]['total'] + $facility_evaluation[1]['total'] + $facility_evaluation[0]['total'];
		// $fbo = $this -> calculate_percentage($total_facilities_evaluated,$facility_evaluation[0]['total']);
		// $other_public_institutions = $this -> calculate_percentage($total_facilities_evaluated,$facility_evaluation[2]['total']);
		// $gok = $this -> calculate_percentage($total_facilities_evaluated,$facility_evaluation[1]['total']);

		$graph_data = array_merge($graph_data, array("graph_id" => 'chart_2'));
		$graph_data = array_merge($graph_data, array("graph_title" => 'Personel Trained'));
		$graph_data = array_merge($graph_data, array("graph_type" => 'pie'));
		$graph_data = array_merge($graph_data, array("graph_yaxis_title" => 'Total personel'));
		$graph_data = array_merge($graph_data, array("graph_categories" => array('Facility Deputy','Nurse','Facility Head','Pharmacy Technologist','Store Manager')));
		$graph_data = array_merge($graph_data, array("series_data" => array("Personel Trained" =>
		 array(array('Facility Deputy',(int)$trained_personel[0]['fdep']),
		 array('Nurse',(int)$trained_personel[0]['nurse']),
		 array('Facility Head',(int)$trained_personel[0]['fhead']),
		 array('Pharmacy Technologist',(int)$trained_personel[0]['ptech']),
		 array('Store Manager',(int)$trained_personel[0]['sman'])))));
		
        $data['trained_personel']=$this->hcmp_functions->create_high_chart_graph($graph_data);
        // end of chart data
		
		$frequency_of_use  = evaluation_data::get_use_freq($county_id, $district_id);
		$satisfaction = evaluation_data::get_use_freq($county_id, $district_id);
		$county_id = 1;
		
		// fifth chart
		$frequency_of_use  = evaluation_data::get_use_freq($county_id, $district_id);

		$graph_data = array_merge($graph_data, array("graph_id" => 'chart_5'));
		$graph_data = array_merge($graph_data, array("graph_title" => 'Frequency of Use'));
		$graph_data = array_merge($graph_data, array("graph_type" => 'bar'));
		$graph_data = array_merge($graph_data, array("graph_yaxis_title" => 'Total Answers'));
		$graph_data = array_merge($graph_data, array("graph_categories" => array('Daily', 'Once a week', 'Once every 2 weeks')));
		$graph_data = array_merge($graph_data, array("series_data" => array("Frequency of Use" =>
		 array(array('Daily',(int)$frequency_of_use[0]['level']),
		 array('Once a week',(int)$frequency_of_use[1]['level']),
		 array('Once every 2 weeks',(int)$frequency_of_use[2]['level'])
		 ))));	

		 $data['frequency_of_use']=$this->hcmp_functions->create_high_chart_graph($graph_data);

	// end of chart data	

		


		// echo "<pre>";
		// print_r($comfort_level[0]);
		// echo "</pre>";exit;
	// sixth chart
		$comfort_level = evaluation_data::level_of_comfort($county_id, $district_id);

		$graph_data = array_merge($graph_data, array("graph_id" => 'chart_6'));
		$graph_data = array_merge($graph_data, array("graph_title" => 'Level of Comfort'));
		$graph_data = array_merge($graph_data, array("graph_type" => 'bar'));
		$graph_data = array_merge($graph_data, array("graph_yaxis_title" => 'Total Answers'));
		$graph_data = array_merge($graph_data, array("graph_categories" => array('Issue Commodities','Order Commodities','Update Order','Generate Reports')));
		$graph_data = array_merge($graph_data, array("series_data" => array("Frequency of Use" =>
		 array(array('Issue Commodities',(int)$comfort_level[0]['comp']),
		 array('Order Commodities',(int)$comfort_level[0]['modem']),
		 array('Update Order',(int)$comfort_level[0]['bundles']),
		 array('Generate Reports',(int)$comfort_level[0]['manual'])
		 ))));	
	// end of sixth chart data	


	

        $data['comfort_level']=$this->hcmp_functions->create_high_chart_graph($graph_data);
		// end of fifth chart data

		$data['get_personel_trained'] = evaluation_data::get_personel_trained($county_id, $district_id);
		$data['get_training_resource'] = evaluation_data::get_training_resource($county_id,$district_id);

		$data['scheduled_training'] = evaluation_data::get_sheduled_training($county_id, $district_id);
		$data['facility_total'] = evaluation_data::get_sheduled_training($county_id,$district_id);
		$data['feedback_training'] = evaluation_data::get_feedback_training($county_id, $district_id);
		$data['pharm_supervision'] = evaluation_data::get_pharm_supervision($county_id, $district_id);
		$data['coord_supervision'] = evaluation_data::get_coord_supervision($county_id, $district_id);

		// handled
		$data['req_id'] = evaluation_data::get_req_id($county_id, $district_id);
		$data['req_addr'] = evaluation_data::get_req_addr($county_id, $district_id);
		$data['retrain'] = evaluation_data::get_retrain($county_id, $district_id);
		$data['train_recommend'] = evaluation_data::get_train_recommend($county_id,$district);
		// handled

		$data['get_use_freq'] = evaluation_data::get_use_freq($county_id, $district_id);
		$data['improvement'] = evaluation_data::get_improvement($county_id, $district_id);
		$data['ease_of_use'] = evaluation_data::get_ease_of_use($county_id, $district_id);
		$data['meet_expect'] = evaluation_data::get_meet_expect($county_id, $district_id);
		$data['train_useful'] = evaluation_data::get_train_useful($county_id, $district_id);
		$data['coverage_data'] = evaluation_data::get_district_coverage_data($county_id, $district_id);
		$data['show_req'] = evaluation_data::show_req_id($county_id, $district_id);
		$data['show_suggest'] = evaluation_data::show_expect_suggest($county_id, $district_id);

		 $views = 'facility/facility_reports/analysis_new';

		return $this -> load -> view('facility/facility_reports/analysis_new', $data);

	}

}

// public function get_county_evaluation_form_results() {
// 	$county_id = $this -> session -> userdata('district_id');
// 	$data['district_id'] = $county_id;
// 	$data['content_view'] = "facility/facility_reports/reports_v";
// 	$data['banner_text'] = "Facility Training Evaluation Results";
// 	$data['title'] = "Facility Training Evaluation Results";

// 	$data['sheduled_training'] = evaluation_data::get_sheduled_training($county_id);
// 	$data['feedback_training'] = evaluation_data::get_feedback_training($county_id);
// 	$data['pharm_supervision'] = evaluation_data::get_pharm_supervision($county_id);
// 	$data['coord_supervision'] = evaluation_data::get_coord_supervision($county_id);
// }
?>