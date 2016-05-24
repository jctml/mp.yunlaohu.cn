<?php

namespace MicroChat\Controller;

use Think\Controller;

class NativeController extends Controller{

    public function index(){

        $this->display( 'index' );
    }
}
