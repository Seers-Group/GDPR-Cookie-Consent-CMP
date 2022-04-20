<?php //if (MODE == 'live') { ?>
    <?php echo ((!empty($current_user['host'])) ? '<script src="https://unpkg.com/@shopify/app-bridge@2.0.0"></script>' : "" ); ?>
    <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
    <script type="text/javascript">
        var hostval = "<?php echo ((!empty($current_user['host'])) ? $current_user['host'] : "" ); ?>";

        if (hostval) {
            var AppBridge = window['app-bridge'];

            var createApp = AppBridge.createApp;
            var TitleBar = AppBridge.TitleBar;
            var Button = AppBridge.Button;
            var actions = AppBridge.actions;
            var Loading = actions.Loading;
        }
        
        var app = "";
        var loading = "";
        
        
        if (hostval) {
            app = createApp({
                apiKey: '<?php echo SHOPIFY_API_KEY; ?>',
                host: '<?php echo ((!empty($current_user['host'])) ? $current_user['host'] : "" ); ?>',
                shop: 'https://<?php echo $shop; ?>',
                shopOrigin: 'https://<?php echo $shop; ?>',
                forceRedirect: true,
            });

            loading = Loading.create(app);
        }
    </script>
    <script type="text/javascript">
        var query_output = '';

        if (!hostval) {

            ShopifyApp.init({
                forceRedirect: true,
                apiKey: '<?php echo SHOPIFY_API_KEY; ?>',
                shopOrigin: 'https://<?php echo $shop; ?>'
            });

        }
        
        ShopifyApp.ready(function () {
            if (!hostval) {
                ShopifyApp.Bar.initialize({
                    buttons: {
                        secondary: [{"label": "Dashboard", "href": "index.php?shop=<?php //echo $shop; ?>"}]}
                });
            } else {
                const dashboardButton = Button.create(app, { label: 'Dashboard', "href": "index.php?shop=<?php echo $shop; ?>" });
                const titleBarOptions = {
                buttons: {
                    secondary: dashboardButton,
                },
                };
                const myTitleBar = TitleBar.create(app, titleBarOptions);
            }

            

            var shopifyQL = 'SHOW ua_browser_version, ua_os, ua_form_factor, ua_os_version, ua_browser, referrer_host, page_type, page_path, page_url, page_resource_id, referrer_host, referrer_name, referrer_path, referrer_url, referrer_source, utm_campaign_content, utm_campaign_term, utm_campaign_medium, utm_campaign_source, utm_campaign_name, location_country, location_region, location_city OVER day(timestamp) AS day FROM visits SINCE -7d UNTIL today ORDER BY day ASC';
            //var shopifyQL = 'SELECT * FROM shopify.online_store_sessions';
            var renderData = function (response) {
                query_output = response;
                console.log('renderData',JSON.stringify(response));
                // do amazing things here
            };
            var handleError = function (response) {
                console.log('handleError',response);
                // handle missing API errors here (missing scopes, back shopifyql, etc...)
            };
            ShopifyApp.Analytics.fetch({
                query: shopifyQL,
                success: renderData,
                error: handleError
            });
        });
        //ShopifyApp.Bar.loadingOff();
        if (hostval) {
            loading.dispatch(Loading.Action.STOP);
        } else {
            ShopifyApp.Bar.loadingOff();
        }
    </script>
<?php //} ?>
