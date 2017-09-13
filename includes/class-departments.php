<?php
class Departments {
	/*
     *  Get array of current departments user is head of
	 */	
	public static function get_departments_current_user_is_head_of(){
		$user = wp_get_current_user();
		$department_arr[] = get_post(get_user_meta($user->ID, '_department', true));
		return $department_arr;
	}

	/*
     *  Get all departments
	 */
	public static function get_departments() {
		$q = new WP_Query(array(
			'post_type' => 'department',
			'posts_per_page' => -1
		));

		return $q->posts;
	}

	/*
     *  Get department by id
	 */	
	public static function get_department_by_id($id) {	
		return get_post($id);
	}
}