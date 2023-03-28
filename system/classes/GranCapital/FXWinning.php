<?php

namespace GranCapital;

class FXWinning
{
    const TEMPLATE = 'app/application/';
    const OUTPUT = 'src/files/pdf/fxwinning';

    const COORDS = [
        'name' => [
            'x' => 110,
            'y' => 55,
        ],
        'fullName' => [
            'x' => 40,
            'y' => 160,
        ],
        'idNumber' => [
            'x' => 40,
            'y' => 187,
        ],
        'address' => [
            'x' => 40,
            'y' => 214,
        ],
        'email' => [
            'x' => 40,
            'y' => 241,
        ],
        'investorNumber' => [
            'x' => 120,
            'y' => 85,
        ],
        'fullNameEnd' => [
            'x' => 55,
            'y' => 28,
        ],
        'birthday' => [
            'x' => 45,
            'y' => 52,
        ],
        'signature' => [
            'x' => 65,
            'y' => 57,
        ],
        'fullNameEndSpecial' => [
            'x' => 55,
            'y' => 28+48,
        ],
        'birthdaySpecial' => [
            'x' => 45,
            'y' => 52+48,
        ],
        'signatureSpecial' => [
            'x' => 65,
            'y' => 57+48,
        ],
    ];

    public static function getCoords(string $key = null) : array
    {
        return isset(self::COORDS[$key]) ? self::COORDS[$key] : [];
    }

    public static function getSourceTemplateOutput(string $root = null,int $user_login_id = null) : string
    {
        return $root."/".self::OUTPUT."/{$user_login_id}.pdf";
    }

    public static function getSourceTemplate(string $root = null,string $lpoa = null) : string
    {
        $lpoa = $lpoa ? $lpoa : "fxWinningTemplate";
        
        return $root."/".self::TEMPLATE.$lpoa.".pdf";
    }
}