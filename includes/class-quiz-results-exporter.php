<?php

class QuizResultsExporter {
	const TITLE_PREFIX = 'LeoQuizResultsExport';

	private $quizResults;
	private $excelObj;
	private $title;

	public function __construct($results) {

		$this->quizResults = $results;
		$this->excelObj = new PHPExcel();
		$this->setColumnNames();
	}

	public function export() {
		$rows = $this->getRows();		

		foreach($rows as $i => $row) {								
			$this->addRow($i + 2, $row);			
		}

		$this->title = sprintf(
			"%s-%s", 
			self::TITLE_PREFIX, 
			date('m-d-Y')
		);

		$this->excelObj
				->getActiveSheet()
				->setTitle($this->title);	

		$this->printAndExit();
	}

	private function getRows() {
		$rows = [];

		foreach($this->quizResults as $result) {			
			$dept = get_post(
				get_user_meta($result['user']->ID, '_department', true)
			);
						
			$rows[] = (object) [
				'ptbid' => get_user_meta($result['user']->ID, 'ptbid', true),
				'name' => $result['user']->first_name . ' '.  $result['user']->last_name,
				'dept' => $dept->post_title,
				'courseId' => $result['quiz_name'],
				'score' => $result['score']
			];
		}
		echo '<pre>'; var_dump($rows); echo '</pre>'; exit();
		return $rows;
	}

	private function setColumnNames() {
		$this->excelObj->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'PTBID')
		            ->setCellValue('B1', 'Name')
		            ->setCellValue('C1', 'Department')
		            ->setCellValue('D1', 'Organization ID #')
		            ->setCellValue('E1', 'Date of completion')
		            ->setCellValue('F1', 'Course ID')
		            ->setCellValue('G1', 'Score');		           
	}

	private function addRow($i, $row) {

		$this->excelObj->setActiveSheetIndex(0)
						->setCellValue('A' . strval($i), $row->ptbid)
			            ->setCellValue('B' . strval($i), $row->name)
			            ->setCellValue('C' . strval($i), $row->dept)
			            ->setCellValue('D' . strval($i), $row->orgId)
			            ->setCellValue('E' . strval($i), $row->date)
			            ->setCellValue('F' . strval($i), $row->courseId)
			            ->setCellValue('G' . strval($i), $row->score);			            
	}

	private function setHeaders() {
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $this->title .'.xlsx"');
		header('Cache-Control: max-age=0');		
		header('Cache-Control: max-age=1');		
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: cache, must-revalidate');
		header('Pragma: public');
	}

	private function printAndExit() {
		$this->setHeaders();

		$writer = PHPExcel_IOFactory::createWriter(
			$this->excelObj, 
			'Excel2007'
		);

		$writer->save('php://output');				
		exit;
	}
}