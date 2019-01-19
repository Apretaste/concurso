<?php
class Service
{
	/**
	 * Function executed when the service is called
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function _main(Request $request, Response $response){
		$contests = Connection::query("SELECT * from _concurso WHERE end_date >= now()");

		if(empty($contests)){
			$response->setTemplate('no-contests.ejs');
			return;
		}

		foreach($contests as $contest){
			unset($contest->body);
			$contest->end_date = date_format((new DateTime($contest->end_date)),'d/m/Y - h:i a');
		}
		
		$winner1 = Connection::query("SELECT * FROM _concurso WHERE end_date <= now() AND winner1 IS NOT NULL ORDER BY end_date DESC;");;
		$response->setCache('day');
		$response->setTemplate("basic.ejs",["contests" => $contests,"winners" => isset($winner1[0])]);
	}

	public function _ver(Request $request, Response $response){
		$id = (isset($request->input->data->id))?$request->input->data->id:false;

		if(!$id){
			$response->setTemplate('no-contests.ejs');
			return;
		}

		$contest = Connection::query("SELECT *, (end_date >= now()) as is_open from _concurso WHERE id = $id");

		if(empty($contest)){
			$response->setTemplate('no-contests.ejs');
			return;
		}

		$contest = $contest[0];
		$contest->body = base64_decode($contest->body);
		$contest->end_date = date_format((new DateTime($contest->end_date)),'d/m/Y - h:i a');

		$winner1 = Utils::getPerson($contest->winner1);
		$winner2 = Utils::getPerson($contest->winner2);
		$winner3 = Utils::getPerson($contest->winner3);

		if($winner1) $contest->winner1 = $winner1->username;
		if($winner2) $contest->winner2 = $winner2->username;
		if($winner3) $contest->winner3 = $winner3->username;

		$response->setCache();
		$response->setTemplate("contest.ejs", ["contest" => $contest]);
	}

	public function _ganadores(Request $request, Response $response){
		$cts = Connection::query("SELECT * FROM _concurso WHERE end_date <= now() AND winner1 IS NOT NULL ORDER BY end_date DESC;");;
		if(empty($cts)){
			$response->setTemplate('no-winners.ejs');
			return;
		}
		$contests = [];

		foreach ($cts as $contest){
			unset($contest->body);

			$winner1 = Utils::getPerson($contest->winner1);
			$winner2 = Utils::getPerson($contest->winner2);
			$winner3 = Utils::getPerson($contest->winner3);

			if($winner1) $contest->winner1 = $winner1->username;
			if($winner2) $contest->winner2 = $winner2->username;
			if($winner3) $contest->winner3 = $winner3->username;

			$contest->end_date = date_format((new DateTime($contest->end_date)),'d/m/Y - h:i a');
			$contests[] = $contest;
		}

		$response->setTemplate('winners.ejs', ['contests' => $contests]);
		return;
	}
}
