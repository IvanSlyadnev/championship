<?php


namespace app\models;


use yii\base\Model;

class Game extends Model
{
    public $play_off = [];
    public function play($team1, $team2, $group, $final = false, $qouterFinal = false) {
        //var_dump($_SESSION['play_off'][0][0]->name);
        $s1 = 0;$t1 = null;
        $s2 = 0;$t2 = null;

        $c = rand(1, 10);
        for ($i = 0; $i < $c; $i++) {
            $t1 = rand(0, $team1->power);
            $t2 = rand(0, $team2->power);
            if ($t1 > $t2) {
                $s1+=1;
            } else {
                $s2+=1;
            }
        }
        if ($group) {
            $team1->goals_scored +=$s1;
            $team2->goals_scored +=$s2;
            $team1->goals_missed +=$s2;
            $team2->goals_missed +=$s1;
            $team1->played+=1;
            $team2->played+=1;
            if ($s1 > $s2) {
                $team1->score += 3;
            } else if ($s1 == $s2) {
                $team1->score+=1;
                $team2->score+=1;
            } else {
                $team2->score += 3;
            }
            $team1->save();
            $team2->save();
            if ($_SESSION['info'] == null) $_SESSION['info'] = [];
            array_push($_SESSION['info'],
                $team1->name.'  '.$s1.' - '.$s2.'  '.$team2->name
            );
        } else {

            while ($s1 == $s2) {
                $t1 = rand(0, $team1->power);
                $t2 = rand(0, $team2->power);
                if ($t1 > $t2) {
                    $s1 += 1;
                } else {
                    $s2 += 1;
                }
            }
            if($final) {
                $team1->final_goals = $s1;
                $team2->final_goals = $s2;
            } else if ($qouterFinal) {
                $team1->semifinal_goals = $s1;
                $team2->semifinal_goals = $s2;
            } else {
                $team1->match_goals = $s1;
                $team2->match_goals = $s2;
            }
            $res = [
                'winner'=>($s1>$s2) ? $team1 : $team2,
                'loser'=>($s1<$s2) ? $team1 : $team2
            ];
            return $res;

        }
    }

    public function getTeamForIndex($teams, $index) {
        foreach ($teams as $team) {
            if ($team->index_group == $index) {
                return $team;
            }
        }
    }

    public function getTeams($teams,$step, $i) {
        if ($step==1){
            if ($i==0){
                return [
                    'first' =>$this->getTeamForIndex($teams, 0),
                    'second'=>$this->getTeamForIndex($teams, 1)
                ];
            } else if ($i==1) {
                return [
                    'first'=>$this->getTeamForIndex($teams, 2),
                    'second'=>$this->getTeamForIndex($teams, 3)
                ];
            }
        } else if ($step==2){
            if ($i==0) {
                return  [
                    'first'=>$this->getTeamForIndex($teams, 0),
                    'second'=>$this->getTeamForIndex($teams, 2)
                ];
            } else if ($i==1) {
                return [
                    'first'=>$this->getTeamForIndex($teams, 1),
                    'second'=>$this->getTeamForIndex($teams, 3)
                ];
            }
        } else if ($step == 3) {
            if ($i == 0) {
                return [
                    'first' =>$this->getTeamForIndex($teams, 0),
                    'second'=>$this->getTeamForIndex($teams, 3)
                ];
            } else if ($i==1){
                return [
                    'first'=>$this->getTeamForIndex($teams, 1),
                    'second'=>$this->getTeamForIndex($teams, 2)
                ];
            }
        }
    }

    public function sortGroups($groups) {
        for($i=0;$i < count($groups); $i++) {
            usort($groups[$i], array($this, "st"));
        }
        return $groups;
    }

    public function st($t1, $t2) {
        if ($t1->score == $t2->score) {;
            return ($t1->goals_scored-$t1->goals_missed) < ($t2->goals_scored-$t2->goals_missed);
        }
        else {
            return $t1->score < $t2->score;
        }
    }

    public function sg($t1, $t2) {
        return $t1->sort < $t2->sort;
    }

    public function getGroups($teams, $c, $s, $basket = false) {;
        if ($basket == null) $basket = false;
        if ($basket) {
            $baskets = [];
            for($i = 0; $i < $c/2; $i++) {
                array_push($baskets,Team::find()->where(['basket_id'=>
                    Basket::find()->where(['number'=>$i+1])->one()->id])->all());

            }
            if (count($baskets)>0) {
                for($i = 1; $i < count($baskets); $i++) {
                    if (count($baskets[0]) !== count($baskets[$i])) {
                        var_dump("A");
                        return null;
                    }
                }
            }
            if ($s) {
                foreach ($baskets as $basket) {
                    foreach ($basket as $team) {
                        $team->sort = rand(0,1000);
                        $team->save();
                    }
                }
            }

            foreach ($baskets as $basket) {
                foreach ($basket as $team) {
                    $team->sort = rand(0,1000);
                    $team->save();
                }
            }

            $B = [];
            foreach ($baskets as $basket) {
                usort($basket, array($this, "sg"));
                array_push($B,$basket);
            }
            $baskets = $B;

            $groups = [];
            $group = [];
            $count = count($baskets[0]);
            $j = 0;

            for ($i = 1; $i <= $c; $i++) {
                $j = ($i-1)/count($baskets);
                if ($i%4==0) {
                    array_push($group, $baskets[$i%($c/$count)][$j]);
                    array_push($groups, $group);
                    $group = [];
                }
                else array_push($group, $baskets[$i%($c/$count)][$j]);

               // var_dump($i);var_dump(($i%($c/2)));var_dump($baskets[$i%$c/2][$j]->name);
            }

            return $groups;

        }
        if ($s) {
            foreach ($teams as $team) {
                $team->sort = rand(0,1000);
                $team->save();
            }
        }
        usort($teams, array($this, "sg"));

        $groups = [];
        $group = [];
        for ($i = 1; $i <= $c; $i++) {
            if ($i%4==0) {
                array_push($group, $teams[$i-1]);
                array_push($groups, $group);
                $group = [];
            } else
            array_push($group, $teams[$i-1]);
        }
        return $groups;
    }

}