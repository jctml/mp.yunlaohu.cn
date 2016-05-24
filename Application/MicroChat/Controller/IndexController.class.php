<?php

namespace MicroChat\Controller;

use Think\Controller;

class IndexController extends Controller{

    public function index(){
        if( isset( $_GET['echostr'] ) ){
            $this->valid();
        }else{
            $this->responseMsg();
        }
    }

    public function valid(){
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if( $this->checkSignature() ){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg(){
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if( !empty( $postStr ) ){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
              the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader( true );
            $postObj = simplexml_load_string( $postStr, 'SimpleXMLElement',
                LIBXML_NOCDATA );

            $data['fromUsername'] = $postObj->FromUserName;
            $data['toUsername']   = $postObj->ToUserName;

            switch( $postObj->MsgType ){
                case "text" :
                    $data['content'] = trim( $postObj->Content );
                    $this->reText( $data );
                    break;
                case "event":
                    switch( $postObj->Event ){
                        case "subscribe":
                            if( strpos( $postObj->EventKey, 'qrscene_' ) == 0 ){
                                $arr = explode( '_', $postObj->EventKey );
                                if( in_array( 'mchid', $arr ) ){
                                    $data['mch_id'] = $arr[2];
                                }else{
                                    $data['mch_id'] = NULL;
                                }
                                if( in_array( 'scode', $arr ) ){
                                    $data['store_code'] = $arr[4];
                                }else{
                                    $data['store_code'] = NULL;
                                }
                                $data['openid'] = $postObj->FromUserName;
                            }
//                            foreach($arr as $a){
//                                $aa .=$a.'_sub_';
//                            }
                            if( !empty( $data['mch_id'] ) ){
                                if( $this->queryMer( $data['mch_id'],
                                        $data['store_code'] ) ){
                                    //判断添加收银员成功则
                                    $data['content'] = !empty( $this->addOperat( $data ) )
                                            ? 'addoper_success' : 'addoper_fail';
//                                    $data['content'] = $this->addOperat( $data );
                                }
                            }else{
                                $data['content'] = NULL;
                            }
//                            $data['content']=$aa;
                            //运行消息回复
                            $this->reText( $data );
                            break;
                        case "unsubscribe":
                            break;
                        case "SCAN":

                            $arr = explode( '_', $postObj->EventKey );
                            if( in_array( 'mchid', $arr ) ){
                                $data['mch_id'] = $arr[1];
                            }else{
                                $data['store_code'] = NULL;
                            }
                            if( in_array( 'scode', $arr ) ){
                                $data['store_code'] = $arr[3];
                            }else{
                                $data['store_code'] = NULL;
                            }
//                            foreach( $arr as $a ){
//                                $aa .=$a . '_scan_';
//                            }
                            $data['openid'] = $postObj->FromUserName;
                            //查询商户号是否存在，如果商户号存在则添加收银员
                            if( !empty( $data['mch_id'] ) ){
                                if( $this->queryMer( $data['mch_id'],
                                        $data['store_code'] ) ){
                                    //判断添加收银员成功则
                                    $data['content'] = !empty( $this->addOperat( $data ) )
                                            ? 'addoper_success' : 'addoper_fail';
//                                    $data['content'] = $this->addOperat( $data )
                                }
                            }else{
                                $data['content'] = NULL;
                            }
//                            $data['content'] = $aa;
                            //运行消息回复
                            $this->reText( $data );
                            break;
                        case "LOCATION":
                            break;
                        case "CLICK":
                            if( $postObj->EventKey == 'baoming' ){
                                $data['content'] = "报名";
                                $this->reText( $data );
                            }
                            break;
                        case "VIEW":
                            break;
                    }
                    break;
                default:
            }
        }else{
            echo "";
            exit;
        }
    }

    private function reText( $data ){
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
        $msgType = "text";
        if( !empty( $data['content'] ) ){
            switch( $data['content'] ){
                case "报名":
                    $contentStr = '<a href="http://test.yunlaohu.cn/index.php/Home/Clear/addstore/openid/' . $data['fromUsername'] . '">点击报名，录入商户信息</a>';
                    break;
                case "你好":
                    $contentStr = '你也好!';
                    break;
                case "addoper_success":
                    if( !empty( $data['store_code'] ) ){
                        $contentStr = $data['store_code'] . '添加核消员成功!';
                    }else{
                        $contentStr = $data['mch_id'] . '添加核消员成功!';
                    }
//                    $contentStr = printf( $data );
//                    $contentStr = "addoper_success";
//                    $contentStr = $data['content'];
                    break;
                case "addoper_fail":
                    if( !empty( $data['store_code'] ) ){
                        $contentStr = $data['store_code'] . '添加核消员失败!请确认该微信号未注册过';
                    }else{
                        $contentStr = $data['mch_id'] . '添加核消员失败!请确认该微信号未注册过';
                    }
//                    $contentStr = printf( $data );
//                    $contentStr = "addoper_fail";
//                    $contentStr = $data['content'];
                    break;
                default :
                    $contentStr = $data['content'];
            }
        }else{
            $contentStr = '欢迎关注云老虎';
        }

        $resultStr = sprintf( $textTpl, $data['fromUsername'],
            $data['toUsername'], NOW_TIME, $msgType, $contentStr );
        echo $resultStr;
    }

    /**
     * 查询商户及子商户是否存在
     * @param type $mch_id
     * @return boolean
     */
    private function queryMer( $mch_id, $sub_store = FALSE ){

        $account              = !empty( $mch_id ) ? trim( $mch_id ) : null;
        $res                  = M( 'Account' );
        $condetion['account'] = $account;
        $s                    = $res->where( $condetion )->find();
        if( $s !== NULL || $s !== FALSE ){
            if( empty( $sub_store ) ){
                return TRUE;
            }else{
                $sub_store = !empty( $mch_id ) ? trim( $sub_store ) : null;

                $res                     = M( 'Store_' . $account );
                $condetion['store_code'] = $sub_store;
                $s                       = $res->where( $condetion )->find();
                if( $s !== NULL || $s !== FALSE ){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * 添加收银员
     * @param type $arr
     * @return type
     */
    private function addOperat( $arr ){
        $data['mch_id']     = !empty( $arr['mch_id'] ) ? trim( $arr['mch_id'] ) : null;
        $data['store_code'] = !empty( $arr['store_code'] ) ? trim( $arr['store_code'] )
                : $data['mch_id'];
        $data['openid']     = !empty( $arr['openid'] ) ? trim( $arr['openid'] ) : null;
        $res                = M( 'Operator_' . $data['mch_id'] );

        return $res->add( $data );
    }

    private function checkSignature(){
        // you must define TOKEN by yourself
        if( !defined( "TOKEN" ) ){
            throw new Exception( 'TOKEN is not defined!' );
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $token  = TOKEN;
        $tmpArr = array( $token,$timestamp,$nonce );
        // use SORT_STRING rule
        sort( $tmpArr, SORT_STRING );
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

}
