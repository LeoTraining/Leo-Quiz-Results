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

			$dateStr = trim(explode('CourtSmart', $result['quiz_name'])[0]);
			$month = explode('/', $dateStr)[1];
			$year = explode('/', $dateStr)[0];
			$first = sprintf("%s-%s-01", $year, $month);
			$last = date('Y-m-t', strtotime($first));	
				
			$rows[] = (object) [
				'ptbid' => get_user_meta($result['user']->ID, 'ptbid', true),
				'first' => $result['user']->first_name,
				'last' => $result['user']->last_name,
				'dept' => $dept->post_title,
				'orgId' => get_post_meta($dept->ID, 'organization_id', true),
				'courseId' => $result['quiz_name'],				
				'classCode' => sprintf("CRTS%s", (new DateTime($last))->format('my')),
				'startDate' => $first,
				'endDate' => $last
			];
		}
				
		return $rows;
	}

	private function setColumnNames() {
		$this->excelObj->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'PTBID')
		            ->setCellValue('B1', 'First_Name')
		            ->setCellValue('C1', 'Last_Name')
		            ->setCellValue('D1', 'Agency_Name')
		            ->setCellValue('E1', 'Org_ID')
		            ->setCellValue('F1', 'Start_Date')
		            ->setCellValue('G1', 'End_Date')
		            ->setCellValue('H1', 'Course_ID')
		            ->setCellValue('I1', 'Class_Code')
		            ->setCellValue('J1', 'Credits');		           		           
	}

	private function addRow($i, $row) {
		$this->excelObj->setActiveSheetIndex(0)
						->setCellValue('A' . strval($i), $row->ptbid)
			            ->setCellValue('B' . strval($i), $row->first)
			            ->setCellValue('C' . strval($i), $row->last)
			            ->setCellValue('D' . strval($i), $row->dept)
			            ->setCellValue('E' . strval($i), $row->orgId)
			            ->setCellValue('F' . strval($i), $row->startDate)
			            ->setCellValue('G' . strval($i), $row->endDate)
			            ->setCellValue('H' . strval($i), $row->courseId)
			            ->setCellValue('I' . strval($i), $row->classCode)
			            ->setCellValue('J' . strval($i), '1');			            
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