<?php 
$this->registerJs("

    var msgBlock        = '".Yii::t('user',"User has been Blocked")."';
    var msgUnBlock      = '".Yii::t('user',"User has been Unblocked")."';
    var msgConfirmed    = '".Yii::t('user',"User has been Confirmed")."';
    var msgUnconfirmed  = '".Yii::t('user',"User has been Unconfirmed")."';

    // Get URL parameters 
    // http://www.sitepoint.com/url-parameters-jquery

    $.urlParam = function(name,url){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
        if (results==null){
           return null;
        }
        else{
           return results[1] || 0;
        }
    }

    // Pjax Event
    $(document).on('pjax:send', function(e,d,f) {
      
    });

    $(document).on('pjax:complete', function(e,d,f) {

    });

    $(document).on('pjax:success', function(data, status, xhr, options) {
        var status = $.urlParam('status',data.currentTarget.URL);
        var field  = $.urlParam('field',data.currentTarget.URL);
        var msg    = '';

        if(field=='confirmed_at'){
            if(status==1){
                msg = msgUnconfirmed;
            }else{
                msg = msgConfirmed;
            }
        }else{
            if(status==1){
                msg = msgUnBlock;
            }else{
                msg = msgBlock;
            }
        }

        if(status){
            $.notify(msg,{
                type: status==1?'warning':'success'
            });
        }
    });

");
?>