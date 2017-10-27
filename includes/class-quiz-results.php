<?php 

class Quiz_Results {
	private $all_results;
	private $quizzes;

	public function __construct($department_id) {
		global $wpdb;
		
		$users = get_users(array(
			'meta_key'     => '_department',
			'meta_value'   => intval($department_id),
			'meta_compare' => '='
		));
		
		$in = '';

		foreach($users as $u) {
			if($in == '') {
				$in .= $u->ID;
			} else {
				$in .= (',' . $u->ID);
			}
		}
		$sql = 'select * from wp_fsq_data where user_id IN(' . $in . ')';
		
		$this->all_results = $wpdb->get_results($sql , OBJECT );
		$this->quizzes = $wpdb->get_results( 'select * from wp_fsq_form', OBJECT );
	}

	/*
	 *  Get all results
	 */
	public function get_results() {
		return $this->get_view_models($this->all_results);
	}

	public function get_all_quizzes() {
		return $this->quizzes;
	}

	public function get_results_for_department($department) {
		$results_arr = array();

		foreach($this->all_results as $result) {				
			$u_d = get_user_meta($result->user_id, '_department', true);				
			if(intval($u_d) == intval($department->ID)) {
				array_push($results_arr, $result);
			}
		}

		return $this->get_view_models($results_arr);
	}

	/*
	 *  Get view models
	 */
	private function get_view_models($results) {
		$view_results = array();

		foreach($results as $r) {
			$score = $r->score . '/' .  $r->max_score;
			$percent = round($r->score/$r->max_score * 100, 0);			
			$quiz = $this->filter_array_by_prop($this->quizzes, 'id', $r->form_id)[0];
			$user = get_user_by( 'email', $r->email);

			array_push($view_results, array(
				'quiz_link' => get_site_url() . '/wp-admin/admin.php?page=ipt_fsqm_view_all_submissions&form_id=' . $r->form_id,
				'id' => $r->id,
				'quiz_name' => $quiz->name,
				'user' => $user,
				'form_id' => $r->form_id,
				'user_link' => get_site_url() . '/wp-admin/user-edit.php?user_id=' . $user->ID,
				'score' => $score . ' (' . strval($percent) . '%)',
				'pass_fail' => $percent < 70 ? 'fail' : 'pass',
				'timestamp' => date('l, n/j/Y g:i A', strtotime($r->date)),
				'attempt' => $this->get_attempts($user, $r),
				'link' => get_site_url() . '/wp-admin/admin.php?page=ipt_fsqm_view_submission&id=' . $r->id,
				'result' => $r
				));
		}

		return $view_results;
	}

	private function filter_array_by_prop($arr, $prop, $value) {
		$return_arr = array();
		foreach($arr as $item) {
			if(gettype($item) == 'array') {
				if($item[$prop] == $value) {
					array_push($return_arr, $item);
				}
			}
			if(gettype($item) == 'object') {
				if($item->$prop == $value) {
					array_push($return_arr, $item);
				}
			}
		}
		return $return_arr;
	}

	private function get_attempts($user, $result) {		
		$all_attempts = array();
		$this_result_index = array_search($result, $this->all_results);

		foreach($this->all_results as $key => $value) {
			if($value->user_id == $user->ID && $result->form_id == $value->form_id) {
				array_push($all_attempts, $value);
			}
		}	

		return array_search($result, $all_attempts) + 1 . ' of ' . count($all_attempts);
	}

	public function get_answer_view_model($view_result) {		
		$form_id = $view_result['result']->form_id;
		$questions = unserialize($view_result['result']->mcq);
		$quiz = $this->filter_array_by_prop($this->quizzes, 'id', $form_id)[0];
		$qa = array();

		foreach($questions as $key => $value) {
			$qd = unserialize($quiz->mcq)[$key];			
			$correct_answers = $this->get_correct_answers($qd['settings']['options']);
			$user_answers = $this->get_user_answers($value['options'], $qd['settings']['options']);
			$correct = false;

			foreach($user_answers as $a) {
				if($a['score'] != '0') {
					$correct = true;
				}
			}

			array_push($qa, array(
				'question_text' => $qd['title'],
				'correct_answers' => $correct_answers,
				'user_answers' => $user_answers,
				'correct' => $correct,
				));	
		}
		
		return $qa;
	}

	private function get_user_answers($answer_options, $form_options) {
		$user_answers = array();
		foreach($answer_options as $key => $value) {			
			array_push($user_answers, array(
				'text' => $form_options[intval($value)]['label'],
				'score' => $form_options[intval($value)]['score']
				));	
		}

		return $user_answers;
	}

	private function get_correct_answers($options) {
		$return_arr = array();

		foreach($options as $opt) {
			if($opt['score'] != '0') {
				array_push($return_arr, $opt);
			}
		}

		return $return_arr;
	}
}	