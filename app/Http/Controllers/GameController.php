<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function generateNumber(Request $request) {
        $name = $request->has('name') ? $request->input('name') : session('name');
        if (!$name) {
            return response('Играчът няма име!', 400);
        }
        session(['name' => $name]);
        $str = '0123456789';
        $number = '';
        $arr = array_fill(0, 10, 0);
        for ($i = 1; $i <= 4; $i++) {
            $rand = rand(0, strlen($str)-1);
            $number .= $str[$rand];
            $arr[$str[$rand]] = $i;
            $str = str_replace($str[$rand], '', $str);
        }
        session(['number' => $number, 'arr' => $arr, 'tries' => 0]);
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
        if (!is_numeric($guess) || strlen($guess) !== 4) {
            return response("Невалидно число!", 400);
        }
        session()->increment('tries');
        if ($number == $guess) {
            $record = new \stdClass();
            $record->name = session('name');
            $record->tries = session('tries');
//            $record = ['name' => session('name'), 'tries' => session('tries')];
            $this->generateTop10($record);
            return response(['win' => true, 'tries' => session('tries')]);
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
        return response(['bulls' => $bulls, 'cows' => $cows, 'tries' => session('tries')]);
    }

    public function giveUp() {
        $number = session('number');
        session()->forget(['number', 'arr']);
        return $number;
    }

    public function editName($name) {
        if (trim($name)) {
            session(['name' => $name]);
            return session('name');
        }
        return response('Не може да оставите името празно!', 400);
    }

    private function generateTop10($current) {
        $json = file_get_contents('top-tries.json');
        $data = json_decode($json);
        if (!$data) {
            $data = [];
        }
        if (count($data) == 0) {
            $data[] = $current;
        }
        else {
            $lastIndex = count($data) - 1;
            if (count($data) < 10) {
                $data[] = $current;
                $lastIndex++;
            }
            elseif ($data[$lastIndex]->tries > $current->tries) {
                $data[$lastIndex] = $current;
            }
            else {
                return;
            }
            for ($i = $lastIndex - 1; $i >= 0; $i--) {
                if ($data[$i]->tries > $current->tries) {
                    $temp = $data[$i];
                    $data[$i] = $current;
                    $data[$i + 1] = $temp;
                }
            }
        }
        $json = json_encode($data);
        file_put_contents('top-tries.json', $json);
    }

    public function getTop($category) {
        switch ($category) {
            case 'tries':
                $json = file_get_contents('top-tries.json');
                break;
            default:
                $json = '';
        }
        return $json;
    }
}
