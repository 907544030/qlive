<?php
include_once 'php/header.php';
?>
<div class="living-list">
</div>
<div class="living-page">
</div>
<script>
var page=0;
function load_list(site,cate,page,name){
	$(".living-list").removeClass('hidden');
	$(".living-list").html('');
	$(".living-page").html('');
	$(".living-list").append('<div class="dashhead"><div class="dashhead-titles"><h2 class="dashhead-title">'+name+'<a type="button" class="btn btn-pill  btn-default-outline" onclick="load_cate(\''+site+'\')" href="#collapseExample" data-toggle="collapse" aria-expanded="true" aria-controls="collapseExample">分类</a></h2></div></div><div class="cate-list collapse" id="collapseExample"></div>');
  $.getJSON("/api.php?site="+site+"&cate="+cate+"&page="+page,function(result){
    $.each(result, function(i,row){
      $(".living-list").append('<div class="video-thumb"><a href="#" onclick="load_living(\''+site+'\',\''+row.roomid+'\')"><img class="img-responsive" alt="'+row.title+'" src="'+row.thumb+'"></a><div class="video-title">'+row.title+'</div><div class="video-info">'+row.nickName+'</div></div>');
    });
	page++;
	$(".living-list").append('<button type="button" class="btn btn-primary-outline btn-block btn-pill" onclick="load_list(\''+site+'\',\''+cate+'\',\''+page+'\',\''+name+'\')">下一页</button>');
  });
}
load_list('panda','all',0,'全部');
function load_living(site,roomid){
	$(".living-list").addClass('hidden');
	$('.iconav-slider').addClass('hidden-xs');
  $.getJSON("/api.php?site="+site+"&roomid="+roomid,function(result){
	  $(".living-page").html('<div class="alert alert-info hidden-xs" role="alert"><p>电脑端请用 PotPlayer 或 VLC 观看，直播源：</p><p>'+result.m3u8+'</p><p><button type="button" class="btn btn-pill btn-default" onclick="is_mobile()">大哥，你认错人了，我不是电脑端</button></p></div><video class="visible-xs" height="100%" width="100%" autoplay><source src="'+result.m3u8+'" type="application/x-mpegURL"></video><h3 id="title">'+result.title+'</h3><h4 id="name">'+result.name+'</h4><button type="button" class="btn btn-primary-outline btn-block btn-pill" onclick="back()">返回</button>');
  });
}
function is_mobile(){
	$("video").removeClass('visible-xs');
	$(".alert").html('');
}
function back(){
	$(".living-list").removeClass('hidden');
	$(".living-page").html('');
}
function load_cate(site){
	$(".cate-list").html('');
  $.getJSON("/api.php?site="+site+"&getcate",function(result){
    $.each(result, function(i,row){
      $(".cate-list").append('<button type="button" class="btn btn-primary-outline btn-pull btn-margin" onclick="load_list(\''+site+'\',\''+row.cate+'\',0,\''+row.name+'\')">'+row.name+'</button>');
    });
  });
}

</script>
<?php
include_once 'php/footer.php';