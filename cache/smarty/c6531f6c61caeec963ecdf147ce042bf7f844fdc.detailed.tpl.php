<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:09
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/detailed.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f90d8a11_62800178',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9c21af7d869343600a8cf6f3dbc1069c315a7495' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/detailed.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 3600,
),true)) {
function content_623220f90d8a11_62800178 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="products_detailed">
	<div class="product_block">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 d-md-none summary_wrp">
					
						<h1>MH 445</h1>

											
				</div>
				<div class="col-md-7 col-12 photos_wrp">
					<div id="products_photos" class="clearfix text-center">
			<a class="text-center" id="single_image" href="https://gallery-api.engine.lt/api/gallery/catalog-image/209.jpg">
			<img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/209.jpg">
			<img src="http://biswas.local/images/loading.gif" class="loading-images" alt=""/>
		</a>
	

</div>


<div class="products_photos_list owl-carousel owl-theme">

						<div class="item" onclick="focusMainImg(0)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/209.jpg"></div>
					<div class="item" onclick="focusMainImg(1)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/141.jpg"></div>
					<div class="item" onclick="focusMainImg(2)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/137.jpg"></div>
					<div class="item" onclick="focusMainImg(3)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/160.jpg"></div>
					<div class="item" onclick="focusMainImg(4)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/207.jpg"></div>
					<div class="item" onclick="focusMainImg(5)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/215.jpg"></div>
					<div class="item" onclick="focusMainImg(6)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/201.jpg"></div>
					<div class="item" onclick="focusMainImg(7)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/192.jpg"></div>
					<div class="item" onclick="focusMainImg(8)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/145.jpg"></div>
					<div class="item" onclick="focusMainImg(9)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/218.jpg"></div>
			

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
let imagesList = null;
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
				</div>
				<div class="col-md-5 col-12">
					<div class="col-12 summary_wrp d-none d-md-block">
					
						<h1>MH 445</h1>

											
					</div>
					<div class="col-12 add2cart_wrp">
						<div id="products_add2cart">
	<form action="" method="post">
		<input type="hidden" name="state" value="add2cart" />
		<input type="hidden" name="item_id" value="242" />
		<input type="hidden" name="item_quantity" value="1">

					<div class="modifications">
				<h4>-modification-title-: MH 445</h4>

									<div class="modification_selector">
													
							<a href="?item=242" class="modification active" data-id="203" data-toggle="tooltip" title="MH 445">
																	<img class="img-fluid" src="https://gallery-api.engine.lt/api/gallery/catalog-image/203.jpg"/>
                                							</a>
													
							<a href="?modification=204" class="modification " data-id="204" data-toggle="tooltip" title="MH 445 R">
																	<img class="img-fluid" src="https://gallery-api.engine.lt/api/gallery/catalog-image/204.jpg"/>
                                							</a>
											</div>
								
			</div>
		
							<div class="items">
									<h4>-item-title-: MH 445</h4>
												
			</div>
		
					<div class="quantity">
				<h4>Kiekis:</h4>
				<div class="quantity_selector">
					<div class="quantity_control input-group">
						<span class="input-group-btn">
							<button class="btn btn-outline-custom minus rounded-0 border-right-0" type="button"><span class="icon-minus"></span></button>
						</span>
						<input type="text" name="amount" class="form-control text-center rounded-0" value="1">
						<span class="input-group-btn">
							<button class="btn btn-outline-custom plus rounded-0 border-left-0" type="button"><span class="icon-plus"></span></button>
						</span>
					</div>
				</div>
			</div>
		
		

		<div class="price_info">
			<div class="price ">
									<div class='current_price'>
						659 €
					</div>
												</div>
							<button id='add2cart_button' class="btn btn-primary btn-lg rounded-0"><span class="icon-cart"></span> Į krepšelį</button>
					</div>

	</form>

	<script>
		$alert_message = 'Nepasirinkta reikšmė: -item-title-.';
		$(document).ready(function(){
			$('#products_add2cart .item_selector select').change(function(){
				var url = '?item=' + $(this).val();
				if ($('#soundestInShop-toolbar').length > 0) {
					document.location = url;
				} else {
					ajaxnav(url, '#products_detailed', 'content_types/products/detailed');
				}
			});

			$('#products_add2cart .modification_selector a').click(function(e){
				if ($('#soundestInShop-toolbar').length == 0) {
					e.preventDefault();
					ajaxnav(this.href, '#products_detailed', 'content_types/products/detailed');
				}
			});

			var total_quantity = parseInt($('#products_add2cart input[name=item_quantity]').val());
			if (total_quantity) {
				var current_quantity = 1;
				$('#products_add2cart .quantity_selector button').on('click', function () {
					if ($(this).hasClass('plus')) {
						if (current_quantity === total_quantity) {
							alert('Apgailestaujame, tačiau šiuo metu galima užsisakyti tik 1 vnt. šios prekės.');
							$(this).prop('disabled', true);
						} else {
							current_quantity = current_quantity + 1;
						}
					} else if ($(this).hasClass('minus')) {
						$('#products_add2cart .quantity_selector button.plus').prop('disabled', false);

						if (current_quantity !== 1)
							current_quantity = current_quantity - 1;
					}
				});
			}

			$('#products_add2cart > form').submit(function(e){
				e.preventDefault();

				// validacija
				if (!this.item_id.value) {
					alert ($alert_message);
					if ($('.items .bootstrap-select > button').length) {
						$('.items .bootstrap-select > button').focus();
					}
					return false;
				}

				
				ajaxnav({
					url: document.location.href,
					data: $(this).serialize(),
					method: 'POST',
					container: '#products_add2cart',
					template: 'content_types/products/add2cart',
					callback: function() {
						ajaxnav('', '#cart_info', 'content_types/carts/cart_info', false);
					}
				});
			});
		});
	</script>
</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	
		<div class="container-fluid">
			<div class="product_detailed_description_wrp">
				Kompaktiškas STIHL MH 445 kultivatorius sukurtas darbui mažuose ir vidutinio dydžio plotuose. Kultivatorius su 45 cm freza ypač tinka dirbti siauruose plotuose ir ribotose erdvėse. Kultivatoriaus sliekinė pavara perduoda galią į peilius. MH 445 modelis turi priekinę pavarą. Antivibracinė sistema ir centrinis rankenos reguliavimas užtikrina komfortišką darbą. STIHL kultivatorių lengva prižiūrėti ir valyti (frezos gali būti išmontuojamos ir sumontuojamos valymo padėtyje).
			</div>
		</div>
	

		<div id="similar_products">
		<div class="container-fluid text-center product-slider-container">
			<h2 class="title">Panašios prekės</h2>
			<div class="product_listing">
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
									<div class="product_element ">
	<a href="http://biswas.local/katalogas/RM 248/?modification=154">
		
		<span class="img-wrapper ">
			<span class="img-bg">
				 					<img src="https://gallery-api.engine.lt/api/gallery/catalog-image/154.jpg" class="img-fluid"/>
							</span>
		</span>

		<span class="title">
			<span class="brand_name">Stihl</span>
			<span class="product_name">RM 248</span>
			<span class="modification_name">RM 248</span>
			<span class="item_name"></span>
		</span>

		<span class="price_info">
							<span class="price ">
					nuo				299 €
				</span>
									</span>
	</a>
</div>
							</div>
		</div>
	</div>

	<br />
<b>Notice</b>:  Undefined offset: 205 in <b>/opt/lampp/htdocs/engine-lite-biswas/src/Controllers/Frontend/ProductsFrontendController.php</b> on line <b>407</b><br />
<br />
<b>Notice</b>:  Trying to access array offset on value of type null in <b>/opt/lampp/htdocs/engine-lite-biswas/src/Controllers/Frontend/ProductsFrontendController.php</b> on line <b>407</b><br />

</div><?php }
}
