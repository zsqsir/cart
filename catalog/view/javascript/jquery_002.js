 ;(function($){
        $.fn.jTab = function(options){
            var options = $.extend({
                'onClass'          : '',  //ѡ��ʱ�ı���
                'contentElements'  : '',    
                'event'            :'click mouseenter ',
                'timeout'          : 0,
                'scroll'           : 'left',//�Զ�ת������
                'tagOrder'         : true,//ѡ���ǩ��˳�� true��ʾ˳��һ�� ,false��ʾ˳���෴   
                'before'          : function(){},
                'after'           : function(){}  
             }, options);
             var index = 0;
             var t_event;
             
             var obj = $(this);
             var len = obj.length;
             if(len<=1){
                 return false;
             }
             
             var cObj = options.contentElements;
             var auto = function(){return setInterval(function(){if(options.scroll == "right"){index++;index=index<obj.length?index:0;}else{index--;index=index<0?obj.length-1:index;}  handler(obj,cObj,index);},options.timeout);}
             var handler = function(obj,cObj,index){
                    options.before(); 
                    obj.not(obj.eq(index).addClass(options.onClass)).removeClass(options.onClass);
                    if(options.tagOrder == false){
                        index = obj.length-index-1;
                    }
                    cObj.not(cObj.eq(index).show()).hide();
                    options.after();
             }
            this.each(function(){
                 $(this).bind(options.event,function(){
                   index = obj.index($(this));
                   handler(obj,cObj,index);
                });
                
                if(options.timeout>0){
                    $(this).hover(function () {
                        t_event = clearInterval(t_event);
                    }, function () {
                        t_event = auto();
                    });
                }
            });
            
            if(options.timeout>0){
                t_event = auto();
            }
        }
    })(jQuery);