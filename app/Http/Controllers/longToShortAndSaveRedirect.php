<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortAndFollow;
use App\Lib\Shortener;

class longToShortAndSaveRedirect extends Controller
{
    function lToSAndSR(Request $request) { 
        self::createShortURL($request);
    }

    function redirectToLong(Request $request) {
        $token = $request->t;
        $historyURL = self::readHistoryForRedirect($token);
        if (isset($historyURL[0])) {
            ShortAndFollow::where('short', $historyURL[0]['short'])->update(['followcount' => $historyURL[0]['followcount'] + 1]);
            return redirect($historyURL[0]['long']);
        }
    }
    
    protected static function readHistoryForRedirect($token) {
        $historyURL = ShortAndFollow::select('short', 'long', 'followcount')->where('short', $token)->get();
        return($historyURL);
    }
    
    protected static function readHistoryForShort($longURL) {
        $historyURL = ShortAndFollow::select('short', 'long')->where('long', $longURL)->get();
        return($historyURL);
    }
    
    protected static function writeHistory($longURL, $shortURL) {
        $sAF = new ShortAndFollow();
        $sAF->short = $shortURL;
        $sAF->long = $longURL;
        $sAF->followcount = 0;
        $sAF->save();
    }
    
    protected static function createShortURL($request) {
        $longURL = $request->post()['longURL'];
        $historyURL = self::readHistoryForShort($longURL);
        $ShortenURL = new Shortener($longURL, $historyURL);
        $ShortenURL->longToShort();
        if (!$ShortenURL->isURLvalid) {
            print 'Не верный формат URL';
            return false;
        }
        if (!$ShortenURL->isURLExist) {
            print 'URL не существует';
            return false;
        } 
        if ($ShortenURL->URLInHistory) {
            print '<a href=http://urlshortenerlaravel/'.$ShortenURL->shortToken.'>http://urlshortenerlaravel/'.$ShortenURL->shortToken.'</a>';
            return true;
        }
        else {
            self::writeHistory($longURL, $ShortenURL->shortToken);
            print '<a href=http://urlshortenerlaravel/'.$ShortenURL->shortToken.'>http://urlshortenerlaravel/'.$ShortenURL->shortToken.'</a>';
            return true;
        }
    }
}
