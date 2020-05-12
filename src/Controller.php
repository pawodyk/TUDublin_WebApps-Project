<?php


namespace TUDublin;


class Controller
{

    public function logError(string $message){
        $_SESSION['messages'][] = [ 'type'=>'error','text' =>$message, ];
    }

    public function logMessage(string $message){
        $_SESSION['messages'][] = [ 'type'=>'success','text' =>$message, ];
    }

    public function redirect(string $url, $args = []){

        if ($args){
            $url .= '?';
            foreach ($args as $var => $value){
                $url .= $var . '=' . $value . '&';
            }
            $url = rtrim($url, '&');
        }

        header('Location: ' . $url );
        die;
    }
}