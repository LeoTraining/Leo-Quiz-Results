<h1>Quiz Results By Department</h1>
<hr />
<section>
	<label>Department&nbsp;&nbsp;&nbsp;</label>
	<select data-departments>
		<option value="">Please Select a Department</option>
		<?php foreach($departments as $dept) : ?>
		<option value="<?php echo $dept['id']; ?>"
			<?php if($department['id'] == $dept['id']) : ?>
			selected="selected"
			<?php endif; ?> >
			<?php echo $dept['name']; ?></option>
		<?php endforeach; ?>		
	</select>	
</section>

<hr />

<section class="leo-quiz-results">
	<?php if(count($results) == 0)  : ?>
		<?php if(isset($_GET['leo_dept'])) : ?>
			<h3>Sorry, no quiz results for <?php echo $department['name'];?> yet. Check back later.</h3>
		<?php endif; ?>
	<?php else : ?>
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
			<?php foreach(array_reverse($results) as $r) : ?>
			<tr>
				<td><a href="<?php echo $r['quiz_link']; ?>" target="_blank"><?php echo $r['quiz_name']; ?></a></td>
				<td><a href="<?php echo $r['user_link']; ?>" target="_blank"><?php echo $r['user']->display_name; ?> (<?php echo $r['user']->user_email; ?>)</a></td>
				<td><a href="<?php echo $r['link']; ?>" target="_blank"><?php echo $r['score']; ?></a></td>
				<td><?php echo $r['timestamp']; ?> (UTC)</td>
				<td><?php echo $r['attempt']; ?></td>
				<td class="<?php echo $r['pass_fail']; ?>"><?php echo ucwords($r['pass_fail']); ?></td>
				
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php endif; ?>
</section>


<script type="text/javascript">
(function($){
	function updateDeptartment() {
		window.location.search += '&leo_dept=' + $(this).val();
	};

	$('[data-departments]').on('change', updateDeptartment);
})(jQuery);
</script>