$(function(){
    var $start=20;// 20 kết quả mặc định
    var $window=$(window);
    var $loading=$('#loading');
    var $status=true;
    $window.on( 'scroll',function(eS){
        if($(this).scrollTop() /( $(document).height() - $(this).height()) > 0.6 && $status ){
            $.ajax({
               type:'POST',
               url:'index/pagingajax',
               dataType:'html', 
               data:{start: $start},
               beforeSend:function(){
                  $status=false;
                  $loading.show();   
               },
               success:function(data){
                    if(data){
                        $('body tbody').append(data);
                    }else{
                        $window.off(eS);
                    }
               },
               complete:function(){
                    $status=true;
                    $loading.fadeOut();
                    $start+=10; 
               }
            });                
        }
    });
});