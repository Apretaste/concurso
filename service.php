<?php

class Service
{
	/**
	 * List of contests
	 *
	 * @author salvipascual
	 * @param Request $request
	 * @param Response $response
	 */
	public function _main(Request $request, Response $response)
	{
		// get the list of contests
		$contests = Connection::query("
			SELECT id, end_date, title, winner3, prize1, prize2, prize3 
			FROM _concurso 
			WHERE end_date >= NOW()
			ORDER BY end_date ASC
			LIMIT 10");

		// message for empty contests
		if (empty($contests)) {
			return $response->setTemplate('message.ejs', [
				"header"=>"No hay concursos",
				"icon"=>"sentiment_very_dissatisfied",
				"text" => "Lo sentimos, pero de momento no tenemos concursos disponibles. Estamos en búsqueda de nuevos concursos, por favor revise en unos días."
			]);
		}

		// send data to the view
		$response->setCache('day');
		$response->setTemplate("home.ejs", ["contests" => $contests]);
	}

	/**
	 * Check a contest
	 *
	 * @author salvipascual
	 * @param Request $request
	 * @param Response $response
	 */
	public function _ver(Request $request, Response $response)
	{
		// get the contest id
		$id = isset($request->input->data->id) ? $request->input->data->id : "";

		// get the contest
		$contest = Connection::query("SELECT * FROM _concurso WHERE id = $id");

		// message for empty contests
		if (empty($contest)) {
			return $response->setTemplate('message.ejs', [
				"header"=>"Concurso no encontrado",
				"icon"=>"sentiment_very_dissatisfied",
				"text" => "Lo sentimos, pero de momento este concurso no está disponible. Estamos en búsqueda de nuevos concursos, por favor revise en unos días."
			]);
		}

		// get the body
		$contest = $contest[0];
		$contest->body = base64_decode($contest->body);

		// get the winner 1 if exist
		if($contest->winner1) {
			$w = Connection::query("SELECT username FROM person WHERE email = '{$contest->winner1}'");
			$contest->winner1 = empty($w) ? "" : $w[0]->username;
		}

		// get the winner 2 if exist
		if($contest->winner2) {
			$w = Connection::query("SELECT username FROM person WHERE email = '{$contest->winner2}'");
			$contest->winner2 = empty($w) ? "" : $w[0]->username;
		}

		// get the winner 1 if exist
		if($contest->winner3) {
			$w = Connection::query("SELECT username FROM person WHERE email = '{$contest->winner3}'");
			$contest->winner3 = empty($w) ? "" : $w[0]->username;
		}

		// send data to the view
		$response->setCache();
		$response->setTemplate("contest.ejs", ["contest" => $contest]);
	}

	/**
	 * Check winners for a contest
	 *
	 * @author salvipascual
	 * @param Request $request
	 * @param Response $response
	 */
	public function _ganadores(Request $request, Response $response)
	{
		// get the winners list
		$cts = Connection::query("
			SELECT end_date, title, winner1, winner2, winner3, prize1, prize2, prize3 
			FROM _concurso 
			WHERE end_date <= NOW() 
			AND winner1 IS NOT NULL 
			ORDER BY end_date DESC
			LIMIT 10");

		// message for empty winners
		if(empty($cts)) {
			return $response->setTemplate('message.ejs', [
				"header"=>"No hay concursos",
				"icon"=>"sentiment_very_dissatisfied",
				"text" => "No tenemos los resultados de ningún concurso de momento. Si un concurso terminó y los resultados aún no aparecen, por favor comuníquese con el soporte."
			]);
		}

		// get list of winners 
		$contests = [];
		foreach ($cts as $contest){
			// get the winner 1 if exist
			if($contest->winner1) {
				$w = Connection::query("SELECT username FROM person WHERE email = '{$contest->winner1}'");
				$contest->winner1 = empty($w) ? "" : $w[0]->username;
			}

			// get the winner 2 if exist
			if($contest->winner2) {
				$w = Connection::query("SELECT username FROM person WHERE email = '{$contest->winner2}'");
				$contest->winner2 = empty($w) ? "" : $w[0]->username;
			}

			// get the winner 1 if exist
			if($contest->winner3) {
				$w = Connection::query("SELECT username FROM person WHERE email = '{$contest->winner3}'");
				$contest->winner3 = empty($w) ? "" : $w[0]->username;
			}

			$contests[] = $contest;
		}

		// send data to the view
		$response->setCache('week');
		$response->setTemplate('winners.ejs', ['contests' => $contests]);
	}
}
