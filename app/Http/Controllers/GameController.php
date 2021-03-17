<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function generateNumber() {
        $str = '0123456789';
        $number = '';
        $arr = array_fill(0, 10, 0);
        for ($i = 1; $i <= 4; $i++) {
            $rand = rand(0, strlen($str)-1);
            $number .= $str[$rand];
            $arr[$str[$rand]] = $i;
            $str = str_replace($str[$rand], '', $str);
        }
        session(['number' => $number, 'arr' => $arr]);
        echo session('number');
        print_r(session('arr'));
        session()->save();
        return true;
    }

    public function checkNumber($guess) {
        $number = session('number');
        $arr = session('arr');
        if (!$number || !$arr) {
            return response("Няма започната игра!", 400);
        }
        if ($number == $guess) {
            return response('win');
        }
        $bulls = 0;
        $cows = 0;
        $guessArr = array_fill(0, 10, 0);
        for ($i = 0; $i < 4; $i++) {
            $guessArr[$guess[$i]]++;
            if ($guessArr[$guess[$i]] > 1) {
                return response('Има дублиращи се цифри!', 400);
            }
            if ($arr[$guess[$i]] > 0) {
                if ($arr[$guess[$i]] == $i+1) {
                    $bulls++;
                }
                else {
                    $cows++;
                }
            }
        }
        return response(['bulls' => $bulls, 'cows' => $cows]);
    }
}
