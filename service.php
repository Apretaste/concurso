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
	 * @author salvipascual
	 * @param Request $request
	 * @param Response $response
	 */
	public function _main(Request $request, Response $response)
	{
		// get the list of contests
		$contests = Database::query('
			SELECT id, end_date, title, prize1, prize2, prize3, is_internal
			FROM _concurso 
			WHERE end_date >= NOW()
			ORDER BY end_date ASC');

		// message for empty contests
		if (empty($contests)) {
			return $response->setTemplate('message.ejs', [
				'header' => 'No hay concursos',
				'icon' => 'sentiment_very_dissatisfied',
				'text' => 'Lo sentimos, pero de momento no tenemos concursos disponibles. Estamos en búsqueda de nuevos concursos, por favor revise en unos días.'
			]);
		}

		// complete challenge
		Challenges::complete('view-current-contests', $request->person->id);

		// send data to the view
		$response->setCache('day');
		$response->setTemplate('home.ejs', ['contests' => $contests]);
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
		$id = isset($request->input->data->id) ? $request->input->data->id : '';

		// get contest from the database
		$contest = Database::queryFirst("SELECT * FROM _concurso WHERE id = $id");

		// message for empty contests
		if (empty($contest)) {
			return $response->setTemplate('message.ejs', [
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
	 * @author salvipascual
	 */
	public function _ganadores(Request $request, Response $response)
	{
		// get the winners list
		$contests = Database::query("
			SELECT 
				end_date, title, prize1, prize2, prize3,
				(SELECT username FROM person WHERE id = winner_1) AS winner_1,
				(SELECT avatar FROM person WHERE id = winner_1) AS winner_1avatar,
				(SELECT avatarcolor FROM person WHERE id = winner_1) AS winner_1aColor,
				(SELECT username FROM person WHERE id = winner_2) AS winner_2,
				(SELECT avatar FROM person WHERE id = winner_2) AS winner_2avatar,
				(SELECT avatarColor FROM person WHERE id = winner_2) AS winner_2aColor,
				(SELECT username FROM person WHERE id = winner_3) AS winner_3,
				(SELECT avatar FROM person WHERE id = winner_3) AS winner_3avatar,
				(SELECT avatarcolor FROM person WHERE id = winner_3) AS winner_3aColor
			FROM _concurso
			WHERE end_date <= NOW()
			AND is_internal = 1
			AND (winner_1 IS NOT NULL || winner_1 <> '') 
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