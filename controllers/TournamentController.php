<?php

namespace app\controllers;

use app\models\Game;
use app\models\Team;
use Yii;
use app\models\User;
use yii\web\Controller;
/**
* @property Team [] $teams
*/

class TournamentController extends Controller
{
    public function actionFour ($controller = null, $step = null, $basket = false) {
        $data = $this->f($controller, $step, 4, false, false, false, $basket);
        if ($data != null) {
            $game = $data['game'];
            $groups = $data['groups'];
            $controller = $data['controller'];
            $step = $data['step'];
            $basket = $data['basket'];
            $teams = $data['teams'];
            $play_off = $data['play_off'];
            $results = $data['results'];
            return $this->render('four', [
                'groups' =>$groups,
                'step' =>$step,
                'teams' =>$teams,
                'basket' =>$basket,
                'play_off'=>$play_off,
                'results'=>$results,
                'controller' =>$controller
            ]);
        } else return $this->redirect(['site/index', 'message' =>'У вас недостаточно команд']);

    }

    public function actionEight($controller = null, $step = null, $semifinal = false, $final = false, $basket = false) {
        $data = $this->f($controller, $step, 8, $semifinal, $final, false, $basket);
        if ($data!=null) {
            $game = $data['game'];
            $groups = $data['groups'];
            $teams = $data['teams'];
            $controller = $data['controller'];
            $step = $data['step'];
            $play_off = $data['play_off'];
            $results = $data['results'];
            $basket = $data['basket'];
            $finalists = $data['finalists'];

            return $this->render('eight', [
                'groups' => $groups,
                'step' => $step,
                'teams' => $teams,
                'play_off' => $play_off,
                'results' => $results,
                'basket' =>$basket,
                'finalists' => $finalists,
                'controller' => $controller
            ]);
        } else return $this->redirect(['site/index']);
    }

    public function actionSixteenth ($controller = null, $step = null, $semifinal = false, $final = false,$qouterFinal = true, $basket = false) {
        $data = $this->f($controller, $step, 16, $semifinal, $final,$qouterFinal, $basket);
        if ($data!=null) {
            $game = $data['game'];
            $groups = $data['groups'];
            $teams = $data['teams'];
            $controller = $data['controller'];
            $step = $data['step'];
            $basket = $data['basket'];
            $play_off = $data['play_off'];
            $results = $data['results'];
            $finalists = $data['finalists'];
            $semifinalists = $data['semifinalists'];

            return $this->render('sixteenth', [
                'groups' => $groups,
                'step' => $step,
                'teams' => $teams,
                'play_off' => $play_off,
                'results' => $results,
                'basket' =>$basket,
                'semifinalists' =>$semifinalists,
                'finalists' => $finalists,
                'controller' => $controller
            ]);
        } else return $this->redirect(['site/index']);
    }

    public function f($controller, $step, $count, $semifinal, $final, $qouterFinal = false, $basket) {
        $game = new Game;
        if ($controller == null) {
            $controller = $_SESSION['controller'];
            if ($controller == null)
                $controller = 0;
        }
        if ($step == null) {
            $step = $_SESSION['step'];
            if ($step == null)
                $step = 0;
        }
        $results = [];
        $semifinalists = [];
        $finalists = [];
        if ($controller == 0 && $step == 0) $_SESSION['info'] = [];
        if (empty($play_off))$play_off = [];
        $_SESSION['controller'] = $controller;
        $_SESSION['step'] = $step;
        //$step = 0;
        $user = User::findOne(Yii::$app->user->identity->id);

        if (count($user->teams) < $count) {
            return null;
        }
        $teams = array_slice($user->teams, 0, $count);
        if ($step == 0) {
            $groups = $game->getGroups($teams, $count, true, $basket);
            foreach ($groups as $group) {
                for ($i = 0; $i < count($group); $i++) {
                    $group[$i]->match_goals = 0;
                    $group[$i]->played = 0;
                    $group[$i]->score = 0;
                    $group[$i]->goals_scored = 0;
                    $group[$i]->goals_missed = 0;
                    $group[$i]->index_group = $i;
                    $group[$i]->setOder();
                    $group[$i]->save();
                }
            }
        }
        $groups = $game->getGroups($teams, $count, false, $basket);

        if ($step == 1) {
            for ($i=0; $i< count($groups);$i++) {
                $this->game_($groups[$i], $step, $game);
            }
        }
        if($step == 2) {
            for ($i=0; $i< count($groups);$i++) {
                $this->game_($groups[$i], $step, $game);
            }
        }
        if($step == 3) {
            for ($i=0; $i< count($groups);$i++) {
                $this->game_($groups[$i], $step, $game);
            }
            $groups = $game->sortGroups($groups);

            if ($semifinal) {
                $game->play_off[0][0] = $groups[0][0];
                $game->play_off[0][1] = $groups[1][1];
                $game->play_off[1][0] = $groups[1][0];
                $game->play_off[1][1] = $groups[0][1];
            } else if ($qouterFinal) {
                $game->play_off[0][0] = $groups[0][0];
                $game->play_off[0][1] = $groups[1][1];
                $game->play_off[1][0] = $groups[1][0];
                $game->play_off[1][1] = $groups[0][1];
                $game->play_off[2][0] = $groups[2][0];
                $game->play_off[2][1] = $groups[3][1];
                $game->play_off[3][0] = $groups[3][0];
                $game->play_off[3][1] = $groups[2][1];
            } else {
                for($i = 0; $i < $count/4; $i++) {
                    array_push($game->play_off, [$groups[$i][0], $groups[$i][1]]);
                }
            }
            $_SESSION['play_off'] = $game->play_off;
        }
        if ($step == 4) {
            $game->play_off = $_SESSION['play_off'];
            for($i = 0; $i < count($game->play_off); $i++) {
                array_push($results, $game->play($game->play_off[$i][0], $game->play_off[$i][1], false));
            }
            if ($qouterFinal) {
                $semifinalists = [
                    [$results[0]['winner'],$results[1]['winner']],
                    [$results[2]['winner'],$results[3]['winner']]
                ];
            }
            $finalists = [$results[0]['winner'], $results[1]['winner']];
            $_SESSION['semifinalists'] = $semifinalists;
            $_SESSION['finalists'] = $finalists;
        }
        if ($step == 5)
        {
            $game->play_off = $_SESSION['play_off'];
            if ($final){
                $results = [
                    $game->play($_SESSION['finalists'][0], $_SESSION['finalists'][1], false, true)
                ];
            }
            if ($qouterFinal) {
                $results = [
                    $game->play($_SESSION['semifinalists'][0][0], $_SESSION['semifinalists'][0][1], false, false, true),
                    $game->play($_SESSION['semifinalists'][1][0], $_SESSION['semifinalists'][1][1], false, false, true)
                ];
                $finalists = [
                    $results[0]['winner'], $results[1]['winner']
                ];
                $_SESSION['finalists'] = $finalists;
            } else $finalists = $_SESSION['finalists'];
            $semifinalists = $_SESSION['semifinalists'];
        }
        if ($step == 6) {
            $game->play_off = $_SESSION['play_off'];
            $semifinalists = $_SESSION['semifinalists'];
            $finalists = $_SESSION['finalists'];
            $game->play($finalists[0], $finalists[1], false, true, false);
        }
        $groups = $game->sortGroups($groups);
        return [
            'teams' =>$teams,
            'groups' =>$groups,
            'game'=>$game,
            'basket' => $basket,
            'controller'=>$controller,
            'step'=>$step,
            'play_off' => $game->play_off,
            'results' =>$results,
            'semifinalists' =>$semifinalists,
            'finalists'=>$finalists
         ];
    }

    public function game_($teams, $step, $game) {
        for ($i = 0; $i < 2; $i++) {
            $res = $game->getTeams($teams, $step ,$i);
            if ($res['first']->played==$step-1 && $res['second']->played==$step-1){
                $game->play($res['first'], $res['second'], true);
            }
        }
    }
}