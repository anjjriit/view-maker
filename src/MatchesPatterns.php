<?php

namespace Evercode1\ViewMaker;

use App\Http\Controllers\ApiController;

trait MatchesPatterns
{
    private function patternMatchAndReturn($start, $end, $str)
    {
        $pattern = sprintf(
            '/%s(.+?)%s/ims',
            preg_quote($start, '/'), preg_quote($end, '/')
        );

        // set existing to matches[0] because it'a an array

        if (preg_match($pattern, $str, $matches)) {

            $existing = ($matches[0]);

            return $existing;

        } else {

            $existing = null;

            return $existing;
        }
    }

    private function patternMatchView($pattern, $str)
    {


       return preg_match($pattern, $str, $matches) ? true : false;




    }




}