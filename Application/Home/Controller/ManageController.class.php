<?php

namespace Home\Controller;

use Think\Controller;

class ManageController extends Controller{

    public function index(){

        $this->display( 'index' );
    }

    /**
     * 登录验证
     */
    public function login(){
        if( !empty( I() ) ){
            $user_info = $this->checkUserPass( I( 'username' ), I( 'password' ) );
            if( $user_info !== FALSE ){
                session( 'user_name', $user_info['name'] );
                if( !empty( $user_info['store_sn'] ) ){
                    S( 'store_sn', $user_info['store_sn'] );
                }else{
                    S( 'store_sn', 0 );
                }

                S( 'user_role', $user_info['role'] );
                S( 'user_pass', $user_info['pass'] );

                $this->redirect( 'Home/Index/index', '', 0, '页面加载中...' );
            }else{
                $this->error( '用户名或密码错误！！！' );
            }
        }
        $this->display( 'login' );
    }

    /**
     * 退出登录
     */
    function logout(){
//        删除session信息
        session( null );
        S( NULL );
        $this->redirect( "Home/Manage/login" );
    }

    /**
     * 验证用户名及密码
     * @param type $username
     * @param type $password
     * @return boolean
     */
    function checkUserPass( $username, $password ){

        $pattern  = "/^([0-9A-Za-z\\-_\\.]{1,10})@([0-9]+)$/i";
        $pattern2 = "/^[0-9]{10}$/i";
        if( preg_match( $pattern, $username ) ){
            $arr      = explode( "@", $username );
            $user     = D( "Operator_" . $arr[1] );
            $userinfo = $user->getByAccount( $arr[0] );

            if( $userinfo !== null && $userinfo['pass'] == $password ){
                S( 'sub_mch_id', $arr[1] );
                return $userinfo;
            }
        }elseif( preg_match( $pattern2, $username ) ){
            $user     = D( "Account" );
            $userinfo = $user->getByAccount( $username );
            if( $userinfo !== null && $userinfo['pass'] == $password ){
                S( 'sub_mch_id', $username );
                return $userinfo;
            }
        }else{
            $user     = D( "Manage" );
            $userinfo = $user->getByAccount( $username );
            if( $userinfo !== null && $userinfo['pass'] == $password ){
                return $userinfo;
            }
        }

        return FALSE;
    }

//生成验证码
    function verifyImg(){
        import( "ORG.Util.Image" );
        echo Image::buildImageVerify();
    }

}
