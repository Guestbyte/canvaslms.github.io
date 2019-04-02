<?php
 /*
  Simple Canvas LMS API library

  Note: This library does NOT validate inputs to ensure they comply with
    the Canvas API.

  Copyright 2014 David Lippman, Lumen Learning

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
<<<<<<< HEAD
// */
// $servername = "localhost";
// $username = "mathe046_dw";
// $password = "dw_mathema";
// $dbname = "mathe046_dw";

//$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
//if ($conn->connect_error) {
//die("Falha de conexÃ£o: " . $conn->connect_error);
//} else {
//echo "Connected successfully";
//}

//require_once 'meekrodb.2.3.class.php';
//DB::$user = 'mathe046_dw';
//DB::$password = 'dw_mathema';
//DB::$dbName = 'mathe046_dw';
=======
*/
$servername = "localhost";
$username = "mathe046_dw";
$password = "dw_mathema";
$dbname = "mathe046_dw";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Falha de conexÃ£o: " . $conn->connect_error);
} else {
	//echo "Connected successfully";
}

require_once 'meekrodb.2.3.class.php';
DB::$user = 'mathe046_dw';
DB::$password = 'dw_mathema';
DB::$dbName = 'mathe046_dw';
>>>>>>> 2daf22a6a552e19e18c0e06a7167eabda5735a03

//include 'class.simple_mail.php';

$token    = '10884~h94y7IovYRZcuQvEjIlB4oBaF4DYvvt8LlG7nHz86lLF3VyxuqE6TpAudkNlCO24';
$domain   = 'mathema.instructure.com';

class CanvasLMS
{
	private $token;
	private $domain;
	public function CanvasLMS($t, $d)
	{
		$this->token = $t;
		$this->domain = $d;
	}

	//These functions return a list of items as an associative array: id=>name
	public function getEnrollList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/enrollments", 'id', $max);
	}

	public function getCourseList($max = -1)
	{
		return $this->getlist("/api/v1/courses", 'id', 'name', $max);
	}

	public function getAssignmentList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/assignments", 'id', 'name', $max);
	}

	public function getFileList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/files", 'id', 'display_name', $max);
	}

	public function getQuizList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/quizzes", 'id', 'title', $max);
	}

	public function getPageList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/pages", 'url', 'title', $max);
	}

	public function getDiscussionTopicList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/discussion_topics", 'id', 'title', $max);
	}

	public function getItemList($courseid, $type, $max = -1)
	{
		switch ($type) {
			case 'assignments':
				return $this->getAssignmentList($courseid, $max);
				break;
			case 'files':
				return $this->getFileList($courseid, $max);
				break;
			case 'quizzes':
				return $this->getQuizList($courseid, $max);
				break;
			case 'pages':
				return $this->getPageList($courseid, $max);
				break;
			case 'discuss':
				return $this->getDiscussionTopicList($courseid, $max);
				break;
			default:
				echo 'error in item type';
				exit;
		}
	}

	//These functions return the full list results of the API list call
	// as an associative array:  id=>dataObject
	public function getFullCourseList($max = -1)
	{
		return $this->getlist("/api/v1/courses", 'id', '', $max);
	}

	public function getFullAssignmentList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/assignments", 'id', '', $max);
	}

	public function getFullFileList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/files", 'id', '', $max);
	}

	public function getFullQuizList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/quizzes", 'id', '', $max);
	}

	public function getFullPageList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/pages", 'url', '', $max);
	}

	public function getFullDiscussionTopicList($courseid, $max = -1)
	{
		return $this->getlist("/api/v1/courses/$courseid/discussion_topics", 'id', '', $max);
	}

	//These functions return the detailed data on one specific item
	public function getCourseData($courseid, $item = '')
	{
		return $this->getdata("/api/v1/courses/$courseid", $item);
	}

	public function getAssignmentData($courseid, $assignmentid, $item = '')
	{
		return $this->getdata("/api/v1/courses/$courseid/assignments/$assignmentid", $item);
	}

	public function getFileData($fileid, $item = '')
	{
		return $this->getdata("/api/v1/files/$fileid", $item);
	}

	public function getQuizData($courseid, $quizid, $item = '')
	{
		return $this->getdata("/api/v1/courses/$courseid/quizzes/$quizid", $item);
	}

	public function getPageData($courseid, $pageid, $item = '')
	{
		return $this->getdata("/api/v1/courses/$courseid/pages/" . urlencode($pageid), $item);
	}

	public function getDiscussionTopicData($courseid, $discid, $item = '')
	{
		return $this->getdata("/api/v1/courses/$courseid/discussion_topics/$discid", $item);
	}

	public function getItemData($courseid, $type, $typeid, $item = '')
	{
		switch ($type) {
			case 'assignments':
				return $this->getAssignmentData($courseid, $typeid, $item = '');
				break;
			case 'files':
				return $this->getFileData($courseid, $typeid, $item = '');
				break;
			case 'quizzes':
				return $this->getQuizData($courseid, $typeid, $item = '');
				break;
			case 'pages':
				return $this->getPageData($courseid, $typeid, $item = '');
				break;
			case 'discuss':
				return $this->getDiscussionTopicData($courseid, $typeid, $item = '');
				break;
			default:
				echo 'error in item type';
				exit;
		}
	}

	//These functions update an item.  The val array should be an associative
	// array of the form key=>value.  Consult the Canvas API for valid keys.
	// For items like Wiki Pages, use the keys that would be reported in the
	// item details, not the update parameters.  For example, use "body" for
	// the key, not "wiki_page[body]".
	public function updateAssignment($courseid, $assignmentid, $valarray)
	{
		$paramarray = array();
		foreach ($valarray as $p => $v) {
			$paramarray[] = "assignment[$p]=" . urlencode($v);
		}
		return $this->update("/api/v1/courses/$courseid/assignments/$assignmentid", implode('&', $paramarray));
	}

	public function updateEnrollment($courseid, $valarray)
	{
		$paramarray = array();
		foreach ($valarray as $p => $v) {
			$paramarray[] = "enrollment[$p]=" . urlencode($v);
		}
		//  echo "/api/v1/courses/$courseid/enrollments". implode('&', $paramarray)."<br>";
		return $this->update("/api/v1/courses/$courseid/enrollments", implode('&', $paramarray));
	}

	public function updateFile($courseid, $fileid, $valarray)
	{
		$paramarray = array();
		foreach ($valarray as $p => $v) {
			$paramarray[] = "$p=" . urlencode($v);
		}
		return $this->update("/api/v1/files/$fileid", implode('&', $paramarray));
	}

	public function updateQuiz($courseid, $quizid, $valarray)
	{
		$paramarray = array();
		foreach ($valarray as $p => $v) {
			$paramarray[] = "quiz[$p]=" . urlencode($v);
		}

		return $this->update("/api/v1/courses/$courseid/quizzes/$quizid", implode('&', $paramarray));
	}

	public function updatePage($courseid, $pageid, $valarray)
	{
		$paramarray = array();
		foreach ($valarray as $p => $v) {
			$paramarray[] = "wiki_page[$p]=" . urlencode($v);
		}
		return $this->update("/api/v1/courses/$courseid/pages/" . urlencode($pageid), implode('&', $paramarray));
	}

	public function updateDiscussionTopic($courseid, $discid, $valarray)
	{
		$paramarray = array();
		foreach ($valarray as $p => $v) {
			$paramarray[] = "$p=" . urlencode($v);
		}
		return $this->update("/api/v1/courses/$courseid/discussion_topics/$discid", implode('&', $paramarray));
	}

	public function updateItem($courseid, $type, $typeid, $valarray)
	{
		switch ($type) {
			case 'assignments':
				return $this->updateAssignment($courseid, $typeid, $valarray);
				break;
			case 'files':
				return $this->updateFile($courseid, $typeid, $valarray);
				break;
			case 'quizzes':
				return $this->updateQuiz($courseid, $typeid, $valarray);
				break;
			case 'pages':
				return $this->updatePage($courseid, $typeid, $valarray);
				break;
			case 'discuss':
				return $this->updateDiscussionTopic($courseid, $typeid, $valarray);
				break;
			default:
				echo 'error in item type';
				exit;
		}
	}

<<<<<<< HEAD
=======




>>>>>>> 2daf22a6a552e19e18c0e06a7167eabda5735a03
	//These are the internal functions that do the calls.
	//	private function getlist($base,$itemident,$nameident,$max=-1) {
	public function getlist($base, $itemident, $nameident, $max = -1)
	{

		$pagecnt = 1;
		$itemcnt = 0;
		$itemassoc = array();
		while (1) {
<<<<<<< HEAD

			try {
				$f = file_get_contents('https://' . $this->domain . $base . 'per_page=50&page=' . $pagecnt . '&access_token=' . $this->token);
				echo "sdf: " . $f;
			} catch (Exception $e) {
				echo 'Exceção capturada: ',  $e->getMessage(), "\n";
			}


=======
			$f = @file_get_contents('https://' . $this->domain . $base . 'per_page=50&page=' . $pagecnt . '&access_token=' . $this->token);
>>>>>>> 2daf22a6a552e19e18c0e06a7167eabda5735a03
			$pagecnt++;
			if (trim($f) == '[]' || $pagecnt > 30 || $f === false) {
				break; //stop if we run out, or if something went wrong and $pagecnt is over 30
			} else {
				$itemlist = json_decode($f);
				for ($i = 0; $i < count($itemlist); $i++) {
					if ($nameident != '') {
						$itemassoc[$itemlist[$i]->$itemident] = $itemlist[$i]->$nameident;
						//	$itemassoc[$i] = $itemlist[$i]->$nameident;
					} else {
						$itemassoc[$itemlist[$i]->$itemident] = $itemlist[$i];
						//	$itemassoc[$i] = $itemlist[$i];
					}
					$itemcnt++;
					if ($max != -1 && $itemcnt >= $max) {
						break;
					}
				}
				if (count($itemlist) < 50) { //not going to be another page
					break;
				}
			}
		}
		return $itemassoc;
	}

	public function getdata($base, $item = '')
	{
		$page = json_decode(file_get_contents('https://' . $this->domain . $base . '?access_token=' . $this->token));
		if ($item == '') {
			return $page;
		} else {
			return $page->$item;
		}
	}

	private function update($item, $vals)
	{
		$ch = curl_init('https://' . $this->domain . $item . '?access_token=' . $this->token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $vals);
		$response = curl_exec($ch);
		return ($response !== false);
	}
}

function startsWith($haystack, $needle)
{
	// search backwards starting from haystack length characters from the end
	return $needle === ''
		|| strrpos($haystack, $needle, -strlen($haystack)) !== false;
}
function endsWith($haystack, $needle)
{
	// search forward starting from end minus needle length characters
	if ($needle === '') {
		return true;
	}
	$diff = \strlen($haystack) - \strlen($needle);
	return $diff >= 0 && strpos($haystack, $needle, $diff) !== false;
}

function formatPeriod($endtime, $starttime)
{

	$duration = $endtime - $starttime;

	$hours = (int)($duration / 60 / 60);

	$minutes = (int)($duration / 60) - $hours * 60;

	$seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;

	return ($hours == 0 ? "00" : $hours) . ":" . ($minutes == 0 ? "00" : ($minutes < 10 ? "0" . $minutes : $minutes)) . ":" . ($seconds == 0 ? "00" : ($seconds < 10 ? "0" . $seconds : $seconds));
}

function build_table($array)
{

	$html = '<table id="example" class="display compact nowrap" cellspacing="0" style="width: max-content;margin-bottom: 20px;">';
	// header row
	// $html .= '<tr>';
	// foreach($array[TOPO] as $key=>$value){
	//         $html .= '<th>' . $value . '</th>';
	//     }
	// $html .= '</tr>';

	// data rows
	foreach ($array as $key => $value) {
		//echo $key . ' ' .$value;
		//if (is_numeric($key)) {
		$html .= '<tr>';
		$html .= '<td>' . $key . '</td>';
		foreach ($value as $key2 => $value2) {
			$html .= '<td>' . $value2 . '</td>';
		}
		$html .= '</tr>';
		//}
	}

	// finish table and return it

	$html .= '</table>';
	return $html;
}

function verAprovacao($tarefasAluno)
{
	$notaTotal = $notaAluno = 0;
	foreach ($tarefasAluno as $tarefa) {
		$isQuizz = $tarefa->assignment_id !== null;
		if ($isQuizz) {
			$notaAluno = $notaAluno + $tarefa->submission->score;
			$notaTotal = $notaTotal + $tarefa->points_possible;
		}
	}
	$notaGeral = @round($notaAluno / $notaTotal * 10);
	$exigencia = 0.7;
	$notaCorte = $notaTotal * $exigencia;
	if ($notaGeral >= $notaCorte and $notaGeral > 0) {
		return array(true, $notaGeral, $notaAluno, $notaTotal);
	} else {
		return array(false, $notaGeral, $notaAluno, $notaTotal);
	}
}

function verAvanco($modules)
{
	$feito = $total = 0;
	foreach ($modules as $module) {
		foreach ($module->items as $aula) {
			$total = $total + 1;
			if ($aula->completion_requirement->completed == "1") {
				$feito = $feito + 1;
			}
		}
	} //foreach ($module
	$progresso = @round($feito / $total * 100);
	//echo 'Status:'.$feito.' '.$total.' '.@round($feito / $total *100).'%<br>';
	return array($progresso, $feito, $total);
}