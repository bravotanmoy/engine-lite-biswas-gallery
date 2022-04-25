<div id="footer">
    <div class="footer-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {$frontend->view("subscribers/subscribe")}
                </div>
            </div>
        </div>
        {$frontend->view("pages/footer_advantages")}
    </div>
    <div class="footer-bottom">
        <div class="container-fluid">
            <div class="col-12 footer-menu-wrapper">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="footer-logo">
                            {assign var="logo" value="`$smarty.const.PROJECT_URL`images/logo.png"}
                            <a href="{$smarty.const.PROJECT_URL}" target="_blank"><img class="img-fluid" src="{$logo}"
                                                                                       alt=""/></a>
                        </div>
                        {if $frontend->project.social_icons}
                            <div id="footer_social">
                                <div class="social-icons">
                                    {foreach $frontend->project.social_icons as $icon}
                                        <a href="{$icon.url}" class="icon icon-{$icon.name}"></a>
                                    {/foreach}
                                </div>
                            </div>
                        {/if}
                    </div>

                    <div class="col-12 col-md-9">
                        {$frontend->view('pages/footer_menu')}
                    </div>
                </div>
            </div>

            {$frontend->view('pages/copyright')}
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
