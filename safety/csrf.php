<?php

function csrf(): string
{
    $nbChars = 30;
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $res = "";

    for($i = 0 ; $i < $nbChars ; $i++)
    {
        $index = random_int(0, strlen($chars) - 1);
        $res .= $chars[$index];
    }

    return $res;
}