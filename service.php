<?php

class Service
{
	/**
	 * List of contests
	 *
	 * @param Request $request
	 * @param Response $response
	 *
	 * @return \Response
	 * @throws \Exception
	 * @author salvipascual
	 */
	public function _main(Request $request, Response $response)
	{
		// get the list of contests
		$contests = Connection::query("
			SELECT id, end_date, title, prize1, prize2, prize3 
			FROM _concurso 
			WHERE end_date >= NOW()
			ORDER BY end_date ASC
			LIMIT 10");

		// message for empty contests
		if (empty($contests)) {
			return $response->setTemplate('message.ejs', [
				"header" => "No hay concursos",
				"icon" => "sentiment_very_dissatisfied",
				"text" => "Lo sentimos, pero de momento no tenemos concursos disponibles. Estamos en búsqueda de nuevos concursos, por favor revise en unos días."
			]);
		}

		// send data to the view
		$response->setCache('day');
		$response->setTemplate("home.ejs", ["contests" => $contests]);

		Challenges::complete("view-current-contests", $request->person->id);
	}

	/**
	 * Check a contest
	 *
	 * @param Request $request
	 * @param Response $response
	 * @author salvipascual
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
				"header" => "Concurso no encontrado",
				"icon" => "sentiment_very_dissatisfied",
				"text" => "Lo sentimos, pero de momento este concurso no está disponible. Estamos en búsqueda de nuevos concursos, por favor revise en unos días."
			]);
		}

		// get the body
		$contest = $contest[0];
		$contest->body = base64_decode($contest->body);

		// get the winner 1 if exist
		if ($contest->winner1) {
			$w = Connection::query("SELECT username FROM person WHERE email = '{$contest->winner1}'");
			$contest->winner1 = empty($w) ? "" : $w[0]->username;
		}

		// get the winner 2 if exist
		if ($contest->winner2) {
			$w = Connection::query("SELECT username FROM person WHERE email = '{$contest->winner2}'");
			$contest->winner2 = empty($w) ? "" : $w[0]->username;
		}

		// get the winner 1 if exist
		if ($contest->winner3) {
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
	 * @param Request $request
	 * @param Response $response
	 * @author salvipascual
	 */
	public function _ganadores(Request $request, Response $response)
	{
		// get the winners list
		$contests = Connection::query("
			SELECT 
				end_date, title, prize1, prize2, prize3,
				(SELECT username FROM person WHERE email = winner1) AS winner1,
				(SELECT avatar FROM person WHERE email = winner1) AS winner1avatar,
				(SELECT avatarcolor FROM person WHERE email = winner1) AS winner1aColor,
				(SELECT username FROM person WHERE email = winner2) AS winner2,
				(SELECT avatar FROM person WHERE email = winner2) AS winner2avatar,
				(SELECT avatarColor FROM person WHERE email = winner2) AS winner2aColor,
				(SELECT username FROM person WHERE email = winner3) AS winner3,
				(SELECT avatar FROM person WHERE email = winner3) AS winner3avatar,
				(SELECT avatarcolor FROM person WHERE email = winner3) AS winner3aColor
			FROM _concurso
			WHERE end_date <= NOW() 
			AND winner1 IS NOT NULL 
			ORDER BY end_date DESC
			LIMIT 10");

		// message for empty winners
		if (empty($contests)) {
			return $response->setTemplate('message.ejs', [
				"header" => "No hay concursos",
				"icon" => "sentiment_very_dissatisfied",
				"text" => "No tenemos los resultados de ningún concurso de momento. Si un concurso terminó y los resultados aún no aparecen, por favor comuníquese con el soporte."
			]);
		}

		$images = [];
		$pathToService = Utils::getPathToService($response->serviceName);
		foreach ($contests as $contest) {
			if (empty($contest->winner1avatar)) $contest->winner1avatar = "hombre";
			if (empty($contest->winner2avatar)) $contest->winner2avatar = "hombre";
			if (empty($contest->winner3avatar)) $contest->winner3avatar = "hombre";

			$images[] = "$pathToService/images/{$contest->winner1avatar}.png";
			$images[] = "$pathToService/images/{$contest->winner2avatar}.png";
			$images[] = "$pathToService/images/{$contest->winner3avatar}.png";
		}

		// send data to the view
		$response->setCache('week');
		$response->setTemplate('winners.ejs', ['contests' => $contests], $images);
	}
}
