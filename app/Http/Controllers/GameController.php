<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function newGame(Request $request) {
        $name = $request->has('name') ? $request->input('name') : session('name');
        if (!$name) {
            return response('Играчът няма име!', 400);
        }
        session(['name' => $name]);
        $this->generateNumber();
        echo session('number');
        print_r(session('arr'));
        return true;
    }

    private function generateNumber() {
        $str = '0123456789';
        $number = '';
        $arr = array_fill(0, 10, 0);
        for ($i = 1; $i <= 4; $i++) {
            $rand = rand(0, strlen($str)-1);
            $number .= $str[$rand];
            $arr[$str[$rand]] = $i;
            $str = str_replace($str[$rand], '', $str);
        }
        $this->applyCustomRules($number, $arr);
        session(['number' => $number, 'arr' => $arr, 'tries' => 0]);
    }

    private function applyCustomRules(&$number, &$arr) {
        $problems = 0;
        $oneEight = false;
        $four = false;
        $five = false;
        if ($arr[1] && $arr[8] && abs($arr[1] - $arr[8]) > 1) {
            $problems++;
            $oneEight = true;
        }
        if ($arr[4] && $arr[4] % 2 == 0) {
            $problems++;
            $four = true;
        }
        if ($arr[5] && $arr[5] % 2 == 0) {
            $problems++;
            $five = true;
        }
        if ($problems > 2) {
            $this->generateNumber();
        }
        if ($oneEight) {
            $index8 = $arr[8] - 1;
            $index1 = $arr[1] - 1;
            $newIndex = $arr[1] < $arr[8] ? $index1 + 1 : $index1 - 1;
            $temp = $number[$newIndex];
            $number[$newIndex] = $number[$index8];
            $number[$index8] = $temp;
            $arr[8] = $newIndex + 1;
            $arr[$temp] = $index8 + 1;
        }
        if ($four) {
            $index4 = $arr[4] - 1;
            $newIndex = (!$oneEight || ($number[$index4-1] != 1 && $number[$index4-1] != 8)) ?
                $index4 - 1 : (($index4 - 3 > 0) ? $index4 - 3 : $index4 + 1);
            $temp = $number[$newIndex];
            $number[$newIndex] = $number[$index4];
            $number[$index4] = $temp;
            $arr[4] = $newIndex + 1;
            $arr[$temp] = $index4 + 1;
        }
        if ($five) {
            $index5 = $arr[5] - 1;
            $newIndex = (!$oneEight || ($number[$index5-1] != 1 && $number[$index5-1] != 8)) ?
                $index5 - 1 : (($index5 - 3 > 0) ? $index5 - 3 : $index5 + 1);
            $temp = $number[$newIndex];
            $number[$newIndex] = $number[$index5];
            $number[$index5] = $temp;
            $arr[5] = $newIndex + 1;
            $arr[$temp] = $index5 + 1;
        }
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
            if ($_GET['time']) {
                $record->time = $_GET['time'];
            }
            $this->addTop10($record);
            return response(['win' => true, 'tries' => session('tries'), 'time' => $_GET['time']]);
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

    private function addTop10($current) {
        if ($current->tries) {
            $this->generateTop10('top-tries.json', 'tries', 'time', $current);
        }
        if ($current->time) {
            $this->generateTop10('top-times.json', 'time', 'tries', $current);
        }
    }

    private function generateTop10($filename, $keyname1, $keyname2, $current) {
        $json = Storage::get($filename);
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
            elseif ($data[$lastIndex]->$keyname1 > $current->$keyname1 ||
                ($data[$lastIndex]->$keyname1 == $current->$keyname1 && $data[$lastIndex]->$keyname2 > $current->$keyname2)) {
                $data[$lastIndex] = $current;
            }
            else {
                return;
            }
            for ($i = $lastIndex - 1; $i >= 0; $i--) {
                if ($data[$i]->$keyname1 > $current->$keyname1 ||
                    ($data[$i]->$keyname1 == $current->$keyname1 && $data[$i]->$keyname2 > $current->$keyname2)) {
                    $temp = $data[$i];
                    $data[$i] = $current;
                    $data[$i + 1] = $temp;
                }
            }
        }
        $json = json_encode($data);
        Storage::put($filename, $json);
    }

    public function getTop($category) {
        switch ($category) {
            case 'tries':
                $json = Storage::get('top-tries.json');
                break;
            case 'times':
                $json = Storage::get('top-times.json');
                break;
            default:
                $json = '';
        }
        return $json;
    }
}
