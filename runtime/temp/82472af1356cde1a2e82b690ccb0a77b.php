<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:53:"./template/mobile/new2/activity\ajax_coupon_list.html";i:1533876250;}*/ ?>
<?php if(is_array($coupon_list) || $coupon_list instanceof \think\Collection || $coupon_list instanceof \think\Paginator): if( count($coupon_list)==0 ) : echo "" ;else: foreach($coupon_list as $key=>$vo): if($vo[isget] != 1): ?>
        <div class="maleri30">
            <div class="alcowlone p">
                <div class="goods-limit fl">
                    <div class="goodsimgbo fl">
                        <img src="__STATIC__/images/coupon.png"/>
                    </div>
                    <div class="goods-limit-fo fl">
                        <p class="name"><?php echo $vo['name']; ?></p>
                        <p class="condition"><em><?php echo intval($vo['money']); ?></em>满<?php echo intval($vo['condition']); ?>元可用</p>
                    </div>
                </div>
                <div class="get-limit fr">
                    <canvas class="alreadyget" data-num='<?php echo ceil($vo[send_num]/$vo[createnum]*100); ?>'  width="100"  height="100"></canvas>
                    <a class="clickgetcoupon" data-coupon-id="<?php echo $vo['id']; ?>" onclick="getCoupon(this)">点击领取</a>
                </div>
            </div>
        </div>
    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
