<?php
    function readHistory($longURL) {
        $long = shortandfollow::select('short', 'long')->where('long', $longURL)->get();
        return($long);
    }

