<?php

use Apretaste\Request;
use Apretaste\Response;
use Framework\Database;
use Apretaste\Challenges;

class Service
{
	/**
	 * List of contests
	 *
	 * @param Request $request
	 * @param Response $response
	 *
	 * @throws \Framework\Alert
	 * @author salvipascual
	 */
	public function _main(Request $request, Response &$response)
	{
		// get the list of contests
		$contests = Database::query('
			SELECT id, end_date, title, prize1, prize2, prize3 
			FROM _concurso 
			WHERE end_date >= NOW()
			ORDER BY end_date ASC
			LIMIT 10');

		// message for empty contests
		if (empty($contests)) {
			$response->setTemplate('message.ejs', [
				'header' => 'No hay concursos',
				'icon' => 'sentiment_very_dissatisfied',
				'text' => 'Lo sentimos, pero de momento no tenemos concursos disponibles. Estamos en búsqueda de nuevos concursos, por favor revise en unos días.'
			]);
			return;
		}

		// send data to the view
		$response->setCache('day');
		$response->setTemplate('home.ejs', ['contests' => $contests]);

		Challenges::complete('view-current-contests', $request->person->id);
	}

	/**
	 * Get contest by id
	 *
	 * @param $id
	 *
	 * @return mixed|null
	 * @throws \Framework\Alert
	 */
	public static function getContest($id)
	{
		$contest = Database::query("
			SELECT *,
       			(SELECT username FROM person WHERE person.id = _concurso.winner_1) as username1,
       			(SELECT username FROM person WHERE person.id = _concurso.winner_2) as username2,
       			(SELECT username FROM person WHERE person.id = _concurso.winner_3) as username3
       		FROM _concurso WHERE id = $id");

		if (isset($contest[0])) {
			$contest = $contest[0];
			$contest->winner1 = $contest->username1;
			$contest->winner2 = $contest->username2;
			$contest->winner3 = $contest->username3;
			return $contest;
		}

		return null;
	}

	/**
	 * Check a contest
	 *
	 * @param Request $request
	 * @param Response $response
	 *
	 * @throws \Framework\Alert
	 * @author salvipascual
	 */
	public function _ver(Request $request, Response &$response)
	{
		// get the contest id
		$id = isset($request->input->data->id) ? $request->input->data->id : '';

		// get the contest
		$contest = self::getContest($id);

		// message for empty contests
		if ($contest === null) {
			$response->setTemplate('message.ejs', [
				'header' => 'Concurso no encontrado',
				'icon' => 'sentiment_very_dissatisfied',
				'text' => 'Lo sentimos, pero de momento este concurso no está disponible. Estamos en búsqueda de nuevos concursos, por favor revise en unos días.'
			]);
		}

		// get the body
		$contest->body = base64_decode($contest->body);

		// send data to the view
		$response->setCache();
		$response->setTemplate('contest.ejs', ['contest' => $contest]);
	}

	/**
	 * Check winners for a contest
	 *
	 * @param Request $request
	 * @param Response $response
	 *
	 * @throws \Framework\Alert
	 * @author salvipascual
	 */
	public function _ganadores(Request $request, Response &$response)
	{
		for ($i = 1; $i < 4; $i++) {
			Database::query("UPDATE _concurso SET winner{$i} = null WHERE winner{$i} = ''");
			Database::query("UPDATE _concurso SET winner_{$i} = null WHERE winner_{$i} = 250378");
			Database::query("UPDATE _concurso SET winner{$i} = (SELECT email FROM person WHERE person.id = winner_{$i}) WHERE winner{$i} is null");
			Database::query("UPDATE _concurso SET winner_{$i} = (SELECT id FROM person WHERE person.email = winner{$i}) WHERE winner_{$i} is null");
		}

		// get the winners list
		$contests = Database::query("
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
			AND (winner1 IS NOT NULL || winner1 <> '') 
			ORDER BY end_date DESC
			LIMIT 10");

		// message for empty winners
		if (empty($contests)) {
			$response->setTemplate('message.ejs', [
				'header' => 'No hay concursos',
				'icon' => 'sentiment_very_dissatisfied',
				'text' => 'No tenemos los resultados de ningún concurso de momento. Si un concurso terminó y los resultados aún no aparecen, por favor comuníquese con el soporte.'
			]);
			return;
		}

		// send data to the view
		$response->setCache('week');
		$response->setTemplate('winners.ejs', ['contests' => $contests]);
	}
}
