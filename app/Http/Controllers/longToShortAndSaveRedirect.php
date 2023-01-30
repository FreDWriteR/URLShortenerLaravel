<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\shortandfollow;
use App\Lib\Shortener;

class longToShortAndSaveRedirect extends Controller
{
    function lToSAndSR(Request $request) { 
        self::createShortURL($request);
    }

    function redirectToLong(Request $request) {
        $token = $request->t;
        //print $longURL;
        
        $historyURL = self::readHistoryForRedirect($token);
        if (isset($historyURL[0])) {
            shortandfollow::where('short', $historyURL[0]['short'])->update(['followcount' => $historyURL[0]['followcount'] + 1]);
            //print $historyURL[0]['long'];
            return redirect($historyURL[0]['long']);
            
        }
//        if (isset($_GET['t'])) {
//            for ($i = 0; $i < count($file_array); $i++) {
//                $couple = explode(' ', $file_array[$i]);
//                if (trim($couple[0]) == $_GET['t']) {
//                    $couple[2] = (int)$couple[2] + 1;
//                    $file_array[$i] = implode(' ', $couple);
//                    $file_array[$i] .= "\r\n";
//                    file_put_contents("HistoryShortURL.txt", $file_array);
//                    header('Location: ' . $couple[1]);
//                }
//            }
//        }
    }
    
    protected static function readHistoryForRedirect($token) {
        $historyURL = shortandfollow::select('short', 'long', 'followcount')->where('short', $token)->get();
        return($historyURL);
    }
    
    protected static function readHistoryForShort($longURL) {
        $historyURL = shortandfollow::select('short', 'long')->where('long', $longURL)->get();
        return($historyURL);
    }
    
    protected static function writeHistory($longURL, $shortURL) {
        $sAF = new shortandfollow();
        $sAF->short = $shortURL;
        $sAF->long = $longURL;
        $sAF->followcount = 0;
        $sAF->save();
    }
    
    protected static function createShortURL($request) {
        $longURL = $request->post()['longURL'];
        $historyURL = self::readHistoryForShort($longURL);
//        if (isset($long[0])) {
//            print $long;
//        }
        $ShortenURL = new Shortener($longURL, $historyURL);
        $ShortenURL->longToShort();
        if (!$ShortenURL->isURLvalid) {
            print 'Не верный формат URL';
            exit;
        }
        if (!$ShortenURL->isURLExist) {
            print 'URL не существует';
            exit;
        } 
        if ($ShortenURL->URLInHistory) {
            print '<a href=http://urlshortenerlaravel/'.$ShortenURL->shortToken.'>http://urlshortenerlaravel/'.$ShortenURL->shortToken.'</a>';
            exit;
        }
        else {
            self::writeHistory($longURL, $ShortenURL->shortToken);
            print '<a href=http://urlshortenerlaravel/'.$ShortenURL->shortToken.'>http://urlshortenerlaravel/'.$ShortenURL->shortToken.'</a>';
        }
    }
}
