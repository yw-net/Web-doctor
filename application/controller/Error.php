<?php


namespace app\controller;


class Error
{
	use \traits\controller\Jump;
    public function error()
    {
        $this->redirect('index/login');
    }
    public function _empty()
    {
        $this->redirect('index/login');
    }
}