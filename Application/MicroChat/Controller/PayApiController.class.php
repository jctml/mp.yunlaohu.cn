<?php

namespace MicroChat\Controller;

use Think\Controller;

class PayApiController extends Controller{

    /**
     * 统一下单
     * @param type $inputObj
     * @param type $timeOut
     * @param type $sub
     */
    public static function unifiedOrder( $inputObj, $timeOut = 6, $sub = TRUE ){
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
    }

    /**
     * 
     * 查询订单，WxPayOrderQuery中out_trade_no、transaction_id至少填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayOrderQuery $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function orderQuery( $inputObj, $timeOut = 6, $sub = TRUE ){
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
    }

    /**
     * 
     * 关闭订单，WxPayCloseOrder中out_trade_no必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayCloseOrder $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function closeOrder( $inputObj, $timeOut = 6, $sub = TRUE ){
        $url = "https://api.mch.weixin.qq.com/pay/closeorder";
    }

    /**
     * 
     * 申请退款，WxPayRefund中out_trade_no、transaction_id至少填一个且
     * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayRefund $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function refund( $inputObj, $timeOut = 6, $sub = TRUE ){
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
    }

    /**
     * 查询退款
     * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，
     * 用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
     * WxPayRefundQuery中out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayRefundQuery $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function refundQuery( $inputObj, $timeOut = 6, $sub = TRUE ){
        $url = "https://api.mch.weixin.qq.com/pay/refundquery";
    }

    /**
     * 下载对账单，WxPayDownloadBill中bill_date为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayDownloadBill $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function downloadBill( $inputObj, $timeOut = 6, $sub = TRUE ){
        $url = "https://api.mch.weixin.qq.com/pay/downloadbill";
    }

    /**
     * 
     * 撤销订单API接口，WxPayReverse中参数out_trade_no和transaction_id必须填写一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayReverse $inputObj
     * @param int $timeOut
     * @throws WxPayException
     */
    public static function reverse( $inputObj, $timeOut = 6, $sub = TRUE ){
        $url = "https://api.mch.weixin.qq.com/secapi/pay/reverse";
    }

    /**
     * 
     * 测速上报，该方法内部封装在report中，使用时请注意异常流程
     * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayReport $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function report( $inputObj, $timeOut = 1, $sub = TRUE ){
        $url = "https://api.mch.weixin.qq.com/payitil/report";
    }

    /**
     * 
     * 转换短链接
     * 该接口主要用于扫码原生支付模式一中的二维码链接转成短链接(weixin://wxpay/s/XXXXXX)，
     * 减小二维码数据量，提升扫描速度和精确度。
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayShortUrl $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function shorturl( $inputObj, $timeOut = 6, $sub = TRUE ){
        $url = "https://api.mch.weixin.qq.com/tools/shorturl";
    }
    
    /**
     * 
     * 支付结果通用通知
     * @param function $callback
     * 直接回调函数使用方法: notify(you_function);
     * 回调类成员函数方法:notify(array($this, you_function));
     * $callback  原型为：function function_name($data){}
     */
    public static function notify( $callback, &$msg ){
        
    }

}
