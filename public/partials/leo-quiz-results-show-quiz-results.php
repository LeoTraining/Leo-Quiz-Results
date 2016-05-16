<?php if($departments == null) : ?>
	<h3>Sorry, you are not a department head and do not have access to view quiz results.</h3>
<?php else: ?>

<?php foreach($departments as $dept) : $results = $qr->get_results_for_department($dept); ?>
<section class="leo-quiz-results">

<?php if(count($results) == 0) : ?>
	<h3>Sorry, no quiz results for <?php echo $dept['name'];?> yet. Check back later.</h3>
<?php else : ?>
	<h3>Quiz Results for <?php echo $dept['name'];?></h3>		
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
				<td><?php echo $r['quiz_name']; ?></td>
				<td><?php echo $r['user']->display_name; ?> (<?php echo $r['user']->user_email; ?>)</td>
				<td><a data-fire="qabox-<?php echo $r['result']->id; ?>"><?php echo $r['score']; ?></a></td>
				<td><?php echo $r['timestamp']; ?> (UTC)</td>
				<td><?php echo $r['attempt']; ?></td>
				<td class="<?php echo $r['pass_fail']; ?>"><?php echo ucwords($r['pass_fail']); ?></td>				
			</tr>

			<?php include __DIR__ . '/leo-quiz-results-answers-view.php'; ?>

			<?php endforeach; ?>
		</tbody>
	</table>

	<?php endif; ?>
</section>

<?php endforeach; endif;?>
