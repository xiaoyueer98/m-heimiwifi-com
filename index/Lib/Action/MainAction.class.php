<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MainAction
 *
 * @author peter
 */
class MainAction extends Action {

    //配置错误跳转地址
    function errorSendRedict($errorMessage, $displayPage, $obj) {
        $this->assign("error", $errorMessage);
        $this->assign("info", $obj);
        $this->display($displayPage);
    }

    //配置错误跳转地址
    function successsendRedict($title, $information, $waitSecond, $jumpUrl, $page) {
        $this->assign("title", $title);
        $this->assign("information", $information);
        $this->assign("waitSecond", $waitSecond);
        $this->assign("jumpUrl", $jumpUrl);
        $this->assign("page", $page);
        $this->display("Public/information");
    }

    //发送HTTP请求
    function postMethod($url, $post = null) {
        $context = array();

        if (is_array($post)) {

            $context['http'] = array(
                'method' => 'POST',
                'content' => http_build_query($post, '', '&'),
            );
        }
        return file_get_contents($url, false, stream_context_create($context));
    }

}
