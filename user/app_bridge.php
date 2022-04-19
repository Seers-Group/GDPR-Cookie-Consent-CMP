<?php if (MODE == 'live') { ?>
    <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
    <script type="text/javascript">
        var query_output = '';
        ShopifyApp.init({
            forceRedirect: true,
            apiKey: '<?php echo SHOPIFY_API_KEY; ?>',
            shopOrigin: 'https://<?php echo $shop; ?>'
        });
        ShopifyApp.ready(function () {
            ShopifyApp.Bar.initialize({
                buttons: {
                    secondary: [{"label": "Dashboard", "href": "index.php?shop=<?php echo $shop; ?>"}]}
            });
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
        ShopifyApp.Bar.loadingOff();
    </script>
<?php } ?>
