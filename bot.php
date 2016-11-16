<?php
/**
 * Created by PhpStorm.
 * User: lin-k
 * Date: 05.11.2016
 * Time: 23:31
 */

ini_set("error_log", "php-error.log");
error_reporting(E_ALL);

require_once 'logic.php';

if (!isset($_REQUEST)) {
    return;
}

$config = json_decode(file_get_contents('config.cfg'));
$confirmation_token = $config->confirmation_token;
$token = $config->token;
$menu = $config->menu;

$data = json_decode(file_get_contents('php://input'));

switch ($data->type) {
    case 'confirmation':
        echo $confirmation_token;
        break;

    case 'message_new':
        $user_id = $data->object->user_id;
        $message_text = $data->object->body;
        markAsReaded($token, $data->object->id);
        $in = checkIsNumeric($message_text);
        $out = "";
        if (is_int($in)) {
            if($in < count($menu)) {
                $out = $menu[$in]->answer;
            } else {
                $out = generateMenu($config->menu_invitation, $menu);
            }
        } else {
            $idx = checkAll($message_text, $menu);
            if (is_int($idx)) {
                $out = $menu[$idx]->answer;
            } else {
                $out = generateMenu($config->menu_invitation, $menu);
            }
        }
        sendMessage($token, $user_id, $out);
        echo('ok');
        break;

    default:
        echo('ok');
        break;
}