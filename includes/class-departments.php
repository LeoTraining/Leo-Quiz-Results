<?php
class Departments {
	/*
     *  Get array of current departments user is head of
	 */	
	public static function get_departments_current_user_is_head_of(){
		$departments = get_option('leo_department_manager_departments');
		$department_arr = array();		
		$user = wp_get_current_user();
		foreach($departments as $dept) {
			if(count($dept['departmentHeads']) == 0) continue;
			if(in_array(strval($user->ID), $dept['departmentHeads'])) {
				array_push($department_arr, $dept);
			}
		}
		if(count($department_arr) == 0) {
			return false;			
		}		
		return $department_arr;
	}

	/*
     *  Get all departments
	 */
	public static function get_departments() {
		return get_option('leo_department_manager_departments');
	}

	/*
     *  Get department by id
	 */	
	public static function get_department_by_id($id) {	
		$departments = get_option('leo_department_manager_departments');		
		foreach($departments as $dept) {		
			if($dept['id'] == $id) {
				return $dept;
			}
		}
		return false;
	}
}