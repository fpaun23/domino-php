<?php

namespace App\Controller;

use App\Conf\Conf;
use App\Game\GameInterface;
use App\Helpers\OutputInterface;
use App\Validators\RequestParametersValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    private $parametersValidator;
    protected $conf;
    protected $output;
    protected $game;

    public function __construct(
        RequestParametersValidator $parametersValidator,
        Conf $conf,
        OutputInterface $output,
        GameInterface $game
    )
    {
        $this->parametersValidator = $parametersValidator;
        $this->output = $output;
        $this->conf = $conf;
        $this->game = $game;
    }

    /**
     * @Route("/play", name="app_play", methods={"POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function play(Request $request): JsonResponse
    {
        $plays = $request->query->all('players');

        try {
            $this->parametersValidator->validate(['players' => $plays], $this->conf);
        } catch (InvalidArgumentException $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $this->game->start($plays);
        $gameResults = $this->game->getResult();
        $formattedOutput = $this->output->output($gameResults);

        return new JsonResponse($formattedOutput);
    }


}