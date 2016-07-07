<h3 class="quiz-dropdown-heading">Filter By Quiz</h3>

<select onchange="quizDropdownOnChange()" id="quiz-dropdown">
	<option value="all">All Quizzes</option>
	<?php foreach($quizzes as $quiz) : ?>
	<option value="<?php echo $quiz->id; ?>" <?php if($quiz->id == $quiz_filter_id) echo 'selected="selected"'; ?>>
		<?php echo $quiz->name; ?>
	</option>
	<?php endforeach; ?>
</select>

<?php if($departments == null) : ?>
	<h3>Sorry, you are not a department head and do not have access to view quiz results.</h3>
<?php else: ?>

<?php foreach($departments as $dept) : 
$results = $qr->get_results_for_department($dept); 
if($has_filter) {
	$filtered_results = [];
	foreach($results as $result) {
		if($result['form_id'] == $quiz_filter_id) {
			array_push($filtered_results, $result);
		}
	}
	$results = $filtered_results;
} ?>
<section class="leo-quiz-results">

<?php if(count($results) == 0 && !$has_filter) : ?>
	<h3>Sorry, no quiz results for <?php echo $dept['name'];?> yet. Check back later.</h3>
<?php elseif(count($results) == 0 && $has_filter) : ?>
	<h3>Sorry, no <span class="quiz-name"><?php echo $quiz_name; ?></span> results for <?php echo $dept['name'];?> yet. Check back later.</h3>
<?php else : ?>

	<?php if($has_filter) : ?>
	<h3><span class="quiz-name"><?php echo $quiz_name; ?></span> Results for <?php echo $dept['name'];?></h3>	
	<?php else: ?>
	<h3>Quiz Results for <?php echo $dept['name'];?></h3>	
	<?php endif; ?>

	<table>
		<thead>
			<th>Quiz Name</th>
			<th>User</th>			
			<th>Score</th>
			<th>Time</th>
			<th>Attempt</th>
			<th>Pass/Fail</th>			
		</thead>
		<tbody>
			<?php foreach(array_reverse($results) as $r) :
				if(!$has_filter || ($has_filter && $r['form_id'] == $quiz_filter_id)) : ?>
			<tr>
				<td><?php echo $r['quiz_name']; ?></td>
				<td><?php echo $r['user']->display_name; ?> (<?php echo $r['user']->user_email; ?>)</td>
				<td><a data-fire="qabox-<?php echo $r['result']->id; ?>"><?php echo $r['score']; ?></a></td>
				<td><?php echo $r['timestamp']; ?> (UTC)</td>
				<td><?php echo $r['attempt']; ?></td>
				<td class="<?php echo $r['pass_fail']; ?>"><?php echo ucwords($r['pass_fail']); ?></td>				
			</tr>

			<?php include __DIR__ . '/leo-quiz-results-answers-view.php'; ?>

			<?php endif; endforeach; ?>
		</tbody>
	</table>

	<?php endif; ?>
</section>

<?php endforeach; endif;?>
