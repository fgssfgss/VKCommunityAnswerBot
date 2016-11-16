<?php
/**
 * Created by PhpStorm.
 * User: lin-k
 * Date: 05.11.2016
 * Time: 23:53
 */

/*
 * Send Answer to user
 */
function sendMessage($token, $user_id, $text)
{
    $request_params = array(
        'message' => $text,
        'user_id' => $user_id,
        'access_token' => $token,
        'v' => '5.0'
    );
    $get_params = http_build_query($request_params);
    file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);
}

function markAsReaded($token, $message_id)
{
    $request_params = array(
        'message_ids' => $message_id,
        'access_token' => $token,
        'v' => '5.6'
    );
    $get_params = http_build_query($request_params);
    file_get_contents('https://api.vk.com/method/messages.markAsRead?' . $get_params);
}

/*
 * Find keyword in string
 */
function parseMessage($message, $keywords)
{
    $keys = explode(" ", $keywords);
    $values = explode(" ", $message);
    return !empty(array_intersect($keys, $values));
}

/*
 * Find occurrences in config file
 * Returns index in menu array
 */
function checkAll($text, $menu)
{
    foreach ($menu as $value) {
        if (parseMessage($text, $value->keywords)) {
            return $value->index;
        }
    }
    return false;
}

/*
 * Check string for only 1 number
 */
function checkIsNumeric($message)
{
    if (is_numeric($message)) {
        return intval($message);
    } else {
        return false;
    }
}

/*
 * Function to generate text menu
 */

function generateMenu($invitation, $menu)
{
    $result = $invitation;
    foreach ($menu as $value) {
        $result .= strval($value->index) . " - " . $value->menu_text . ";<br>";
    }
    return $result;
}