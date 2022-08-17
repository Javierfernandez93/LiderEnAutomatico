<?php
/**
*
*/

namespace JFStudio;

class Whatsapp
{
	public static function send(string $number = null,string $text = null) : string
    {
        return "https://wa.me/{$number}?text={$text}";
    }
}