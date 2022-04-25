{if $modificationStatus != false }
<div id="products_photos" class="clearfix">
{else}
<div id="products_photos" class="clearfix text-center">
{/if}
	{if $modificationStatus != false }
		<a id="single_image" href="{$gallery->dimensions[2]->images[0]}">
			<img class="img-fluid" alt="" src="{$gallery->dimensions[2]->images[0]}">
			<img src="{$smarty.const.PROJECT_URL}images/loading.gif" class="loading-images" alt=""/>
		</a>
	{else}
		<a class="text-center" id="single_image" href="{$gallery[0]['photo']}">
			<img class="img-fluid" alt="" src="{$gallery[0]['photo']}">
			<img src="{$smarty.const.PROJECT_URL}images/loading.gif" class="loading-images" alt=""/>
		</a>
	{/if}


</div>


<div class="products_photos_list owl-carousel owl-theme">

	{if $modificationStatus != false }
		{foreach $gallery->dimensions[0]->images as $keyvar=>$productImg}
			<div class="item" onclick="focusMainImg({$keyvar})"><img class="img-fluid" alt="" src="{$productImg}"></div>
		{/foreach}
	{else}
		{foreach $gallery as $keyvar=>$productImg}
			<div class="item" onclick="focusMainImg({$keyvar})"><img class="img-fluid" alt="" src="{$gallery[$keyvar]['photo']}"></div>
		{/foreach}
	{/if}


</div>


<script language="JavaScript" type="text/JavaScript">

// Owl Carousel Configaration
$('.owl-carousel').owlCarousel({
	items: 7,
    margin:10,
    nav:true,
	dots: false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
})

// Fancybox Configaration
$(document).ready(function() {

	/* This is basic - uses default settings */
	$("a#single_image").fancybox();
	
	/* Using custom settings */
	
	$("a#inline").fancybox({
		'hideOnContentClick': true
	});

	/* Apply fancybox to multiple items */
	
	$("a.group").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	true
	});
	
});


// Custom Script For Main Product Image View
let imagesList = {json_encode($gallery->dimensions[2]->images)};
console.dir(imagesList);

function focusMainImg(e){
	let itemNumber = e;
	let produceImage = imagesList[itemNumber];
	$('#products_photos a').attr("href", produceImage);
	$('#products_photos img.img-fluid').attr("src", produceImage);

	$('#products_photos .loading-images').fadeIn();

	$("#products_photos img.img-fluid").on("load", function() {
		$('#products_photos .loading-images').fadeOut();
	}).attr("src", produceImage);

}

</script>