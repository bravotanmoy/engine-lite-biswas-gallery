<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:09
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f91c7d70_52163049',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '842669e076183db9bc3ad11c17f8eb2e27e40746' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/footer.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 3600,
),true)) {
function content_623220f91c7d70_52163049 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="footer">
    <div class="footer-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div id='subscribers_subscribe'>
	

	<h4 class="title">Naujienlaiškio prenumerata</h4>
	<p>Užsisakykite mūsų naujienlaiškį ir pirmieji gaukite naujausius pasiūlymus bei akcijas tiesiai į el. pašto dėžutę.</p>

	<form id="newsletter_form" method="post" data-ajaxnav='true' data-ajaxnav-template='content_types/subscribers/subscribe' data-ajaxnav-container="">
		<input type="hidden" name="state" value="subscribe" />
		<input type="hidden" value="" name="ajaxnav_container">
		<input type="hidden" value="null" name="ajaxnav_popup_info">

		

		<div class="input-group">
			<input type="text" name="email" class="form-control" placeholder="El. pašto adresas" value='' />
			<span class="input-group-btn">
				<button class="btn btn-primary rounded-0" type="submit"><i class="icon icon-right-big"></i></button>
			</span>
		</div>
	</form>
</div>
                </div>
            </div>
        </div>
        	<div id="footer-advantages">
		<div class="container-fluid">
			<div class="row">
				<h2 class="text-center col-12">Mes garantuojame</h2>
									<div class="col-6 col-md-3 text-center">
						<a href="http://biswas.local/lt/asdasd" class="text-center">
							<p class="title">Pristatymas</p>
						</a>
					</div>
									<div class="col-6 col-md-3 text-center">
						<a href="http://biswas.local/lt/asdasdas" class="text-center">
							<p class="title">Kazkas dar</p>
						</a>
					</div>
									<div class="col-6 col-md-3 text-center">
						<a href="http://biswas.local/lt/copyrgiht" class="text-center">
							<p class="title">Originalios prekes</p>
						</a>
					</div>
							</div>
		</div>
	</div>

    </div>
    <div class="footer-bottom">
        <div class="container-fluid">
            <div class="col-12 footer-menu-wrapper">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="footer-logo">
                                                        <a href="http://biswas.local/" target="_blank"><img class="img-fluid" src="http://biswas.local/images/logo.png"
                                                                                       alt=""/></a>
                        </div>
                                            </div>

                    <div class="col-12 col-md-9">
                        <div id="pages_footer_menu">
                <div class="list-default">
        <h4 class="title">Informacija pirkėjui</h4>
                    <div class="submenu_list">
                <ul>
                                                                        <li >
                                <a href="http://biswas.local/informacija-pirkejui/taisykles/" title="Taisyklės">Taisyklės</a>

                                                            </li>
                                                                                                <li >
                                <a href="http://biswas.local/informacija-pirkejui/pristatymas/" title="Pristatymas">Pristatymas</a>

                                                            </li>
                                                                                                <li >
                                <a href="http://biswas.local/informacija-pirkejui/apmokejimas/" title="Apmokėjimas">Apmokėjimas</a>

                                                            </li>
                                                                                                <li >
                                <a href="http://biswas.local/informacija-pirkejui/grazinimas/" title="Grąžinimas">Grąžinimas</a>

                                                            </li>
                                                            </ul>
            </div>
            </div>

                <div class="list-default">
        <h4 class="title">Informacija pirkėjui</h4>
                    <div class="submenu_list">
                <ul>
                                                                        <li >
                                <a href="http://biswas.local/informacija-pirkejui/taisykles/" title="Taisyklės">Taisyklės</a>

                                                            </li>
                                                                                                <li >
                                <a href="http://biswas.local/informacija-pirkejui/pristatymas/" title="Pristatymas">Pristatymas</a>

                                                            </li>
                                                                                                <li >
                                <a href="http://biswas.local/informacija-pirkejui/apmokejimas/" title="Apmokėjimas">Apmokėjimas</a>

                                                            </li>
                                                                                                <li >
                                <a href="http://biswas.local/informacija-pirkejui/grazinimas/" title="Grąžinimas">Grąžinimas</a>

                                                            </li>
                                                            </ul>
            </div>
            </div>

    </div>
                    </div>
                </div>
            </div>

            <div id="copyright">
	<div class="row clearfix">
		<div class="col-12 col-sm-6">
			<div class="copyright">-copyright-</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="credits">Sprendimas: <a href="http://e-lab.lt" target="_blank">ELECTRONIC LAB</a></div>
		</div>
	</div>
</div>
        </div>
    </div>
</div>

<script>
    $(function () {
        function position_footer() {
            $('#content_wrapper').css('min-height', $(window).height() - $('#head').outerHeight() - $('#footer').outerHeight() - $('#footer_social').outerHeight() + 'px');
        }

        position_footer();
        setInterval(function () {
            position_footer();
        }, 100);
    });
</script>
<?php }
}
