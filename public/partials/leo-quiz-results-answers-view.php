<?php $qas = $qr->get_answer_view_model($r); ?>

<div class="modal" id="qabox-<?php echo $r['result']->id; ?>">
	<div class="modal-box">
		<section class="answers">
			<h4><?php echo $r['quiz_name']; ?> - <?php echo $r['user']->display_name; ?> (<?php echo $r['user']->user_email; ?>)</h4>
			<em>Score: <?php echo $r['score']; ?><br />Time: <?php echo $r['timestamp']; ?> (UTC)<br />Attempt: <?php echo $r['attempt']; ?></em>

			<?php foreach($qas as $qa) : ?>
			<article class="qa">	
				<strong>Question:</strong>
				<?php echo $qa['question_text']; ?> <br />		
				
				<div class="user-answers">
					<strong>Answer(s):</strong>
				 	<?php foreach($qa['user_answers'] as $ua) : ?>
				 	<span class="answer <?php if($ua['score'] != '0') echo 'correct'; ?>"><?php echo $ua['text']; ?> (Score: <?php echo $ua['score']; ?>)</span>
				 	<?php endforeach; ?>
			 	</div>

			 	<div class="correct-answers">
				 	<strong>Correct Answer(s):</strong>
				 	<?php foreach($qa['correct_answers'] as $ca) : ?>
				 	<span class="answer"><?php echo $ca['label']; ?> (Score: <?php echo $ca['score']; ?>)</span><br />
				 	<?php endforeach; ?>
			 	</div>
			</article>
			<?php endforeach; ?>	
		</section>
	</div>
</div>