<?php
class Concurso extends Service
{
    /**
     * Function executed when the service is called
     *
     * @param Request $request
     * @return Response
     */
    public function _main(Request $request)
    {
        $connection = new Connection();

        $sql = "SELECT * from _concurso WHERE end_date >= now();";

        $r = $connection->query($sql);

        if (isset($r[0]))
        {
            foreach($r as $contest)
            {
                $contest->body = base64_decode($contest->body);
                $contest->teaser = substr(strip_tags($contest->body),0,200);
                $contest->end_date = substr($contest->end_date,0,strlen($contest->end_date)-3);
            }

            $responseContent = [
                "contests" => $r
            ];

            $response = new Response();
            $response->setResponseSubject("Concursos");
            $response->createFromTemplate("basic.tpl", $responseContent);
            return $response;
        }

        return new Response();
    }

    public function _ver(Request $request)
    {
        $connection = new Connection();

        $id = intval(trim($request->query));
        $sql = "SELECT *, (end_date <= now()) as is_open from _concurso WHERE id = $id;";

        $r = $connection->query($sql);

        if (isset($r[0]))
        {
            $contest = $r[0];
            $contest->body = base64_decode($contest->body);
            $contest->teaser = substr(strip_tags($contest->body),0,200);
            $contest->end_date = substr($contest->end_date,0,strlen($contest->end_date)-3);

            $contest->winners = false;

            $winner1 = $this->utils->getPerson($contest->winner1);
            $winner2 = $this->utils->getPerson($contest->winner2);
            $winner3 = $this->utils->getPerson($contest->winner3);

            $contest->winner1 = $winner1;
            $contest->winner2 = $winner2;
            $contest->winner3 = $winner3;

            if ($winner1 !== false || $winner2 !== false || $winner2 !== false)
                $contest->winners = [];

            if ($winner1 !== false) $contest->winners[] = $winner1;
            if ($winner2 !== false) $contest->winners[] = $winner2;
            if ($winner3 !== false) $contest->winners[] = $winner3;

            $images = $this->getContestImages($contest->id);
			
			$imageList = [];
			foreach ($images as $img)
				$imageList[] = $wwwroot."/public/contestsImages/$id/{$img['filename']}";
				
            $responseContent = ["contest" => $contest];

            $response = new Response();
            $response->setResponseSubject("Concurso");
            $response->createFromTemplate("contest.tpl", $responseContent, $imageList);
            return $response;
        }

        return new Response();
    }

    public function _ganadores(Request $request)
    {
        $connection = new Connection();
        $r = $connection->query("SELECT * FROM _concurso WHERE end_date <= now() AND winner1 is not null and winner1 <> '' order by end_date DESC limit 50;");
        if (isset($r[0]))
        {
            $contests = [];
            foreach ($r as $contest)
            {
                $contest->winner1 = $this->utils->getPerson($contest->winner1);
                $contest->winner2 = $this->utils->getPerson($contest->winner2);
                $contest->winner3 = $this->utils->getPerson($contest->winner3);
                $contest->end_date = substr($contest->end_date,0,strlen($contest->end_date)-3);

                if ($contest->winner1 !== false || $contest->winner2 !== false || $contest->winner2 !== false)
                    $contests[] = $contest;
            }

            if (isset($contests[0]))
            {
                $response = new Response();
                $response->setResponseSubject("Ganadores en concursos");
                $response->createFromTemplate("winners.tpl", [
                    "contests" => $contests
                ]);
                return $response;
            }
        }

        return new Response();
    }

    private function getContestImages($id)
    {
        $connection = new Connection();
        $r = $connection->query("SELECT * FROM _concurso_images WHERE contest = '$id';");

        $di = \Phalcon\DI\FactoryDefault::getDefault();
        $wwwroot = $di->get('path')['root'];

        $images = [];

        if ($r !== false)
        {
            foreach ($r as $row)
            {   $imageContent = file_get_contents($wwwroot."/public/contestsImages/$id/{$row->filename}");
                $images[$row->filename] = ['filename' => $row->filename, 'type' => $row->mime_type, 'content' => base64_encode($imageContent)];
            }
        }

        return $images;
    }
}