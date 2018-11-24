<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:54:"./application/admin/view2/machine\ajaxMachineList.html";i:1543036854;}*/ ?>
<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
    <table width="100%">
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
            <tr>
                <td class="sign">
                    <div style="width: 24px;"><i class="ico-check"></i></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: left; width: 30px;"><?php echo $vo['machine_id']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: left; width: 200px;"><?php echo $vo['machine_name']; ?></div>
                </td>
               <!--  <td align="left" class="">
                    <div style="text-align: left; width: 100px;"><?php echo $vo['user_money']; ?></div>
                </td> -->
               <!--  <td align="left" class="">
                    <div style="text-align: left; width: 100px;"><?php echo $vo['machine_admin']; ?></div>
                </td> -->
                <td align="left" class="">
                    <div style="text-align: left; width: 100px;"><?php echo $vo['user_name']; ?></div>
                </td>
               <!--  <td align="left" class="">
                    <div style="text-align: left; width: 100px;"><?php echo $vo['partner']; ?></div>
                </td> -->
                <td align="left" class="">
                    <div style="text-align: left; width: 150px;"><?php echo $vo['type_name']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: left; width: 100px;"><?php echo $vo['is_online']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: left; width: 100px;"><?php echo $vo['model']; ?></div>
                </td>
                <td align="center" class="handle">
                    <div style="text-align: center; width: 150px; max-width:170px;">
                    <?php if($vo['type_id'] == 2): ?>
                         <a href="<?php echo U('Machine/luck_code',array('id'=>$vo['machine_id'])); ?>"
                               class="btn blue"><i class="fa fa-pencil-square-o"></i>生成二维码</a>
                    <?php endif; ?>
                        <a href="<?php echo U('Machine/optionMachine',array('act'=>'_OPTION_','id'=>$vo['machine_id'])); ?>"
                           class="btn blue"><i class="fa fa-pencil-square-o"></i>配置</a>
                        <a href="<?php echo U('Machine/addEditMachine',array('act'=>'_EDIT_','id'=>$vo['machine_id'])); ?>"
                           class="btn blue"><i class="fa fa-pencil-square-o"></i>编辑</a>
                        <a class="btn red" href="javascript:void(0)" data-url="<?php echo U('Machine/addEditMachine'); ?>"
                           data-id="<?php echo $vo['machine_id']; ?>" onClick="delfunc(this)"><i class="fa fa-trash-o"></i>删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>
<div class="iDiv" style="display: none;"></div>
<!--分页位置-->
<?php echo $page; ?>
<script>
    $(document).ready(function () {
        // 表格行点击选中切换
        $('#flexigrid>table>tbody>tr').click(function () {
            $(this).toggleClass('trSelected');
        });
        $('#count').empty().html("<?php echo $pager->totalRows; ?>");
    });
    // 点击分页触发的事件
    $(".pagination  a").click(function () {
        cur_page = $(this).data('p');
        ajax_get_table('search-form2', cur_page);
    });

    function delfunc(obj) {
        layer.confirm('确认删除？', {
                    btn: ['确定', '取消'] //按钮
                }, function (index) {
                    layer.close(index);
                    // 确定
                    $.ajax({
                        type: 'post',
                        url: $(obj).attr('data-url'),
                        data: {act: '_DEL_', id: $(obj).attr('data-id')},
                        dataType: 'json',
                        success: function (v) {
                            if (v.status == 1) {
                                layer.msg('操作成功', {icon: 1});
                                $(obj).parent().parent().parent().remove();
                            } else {
                                layer.msg(v.msg, {icon: 2, time: 2000});
                            }
                        }
                    })
                }, function (index) {
                    layer.close(index);
                }
        );
    }
</script>