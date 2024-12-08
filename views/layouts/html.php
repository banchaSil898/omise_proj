<?php

use app\assets\AppAsset;
use app\models\Configuration;
use yii\bootstrap\Html;
use yii\web\View;

/* @var $this View */
$bundle = AppAsset::register($this);
$this->registerCssFile($bundle->baseUrl . '/css/theme/' . Configuration::getValue('web_theme', 'green') . '.css', [
    'depends' => [
        'app\assets\AppAsset',
    ],
]);

$this->registerJs("
    (function(w,d,s,l,i){
        w[l]=w[l]||[];
        w[l].push({
            'gtm.start': new Date().getTime(),event:'gtm.js'
        });
        var
            f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
            j.async=true;
            j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
            f.parentNode.insertBefore(j,f);
    }) (window,document,'script','dataLayer','GTM-MXPC92S');", View::POS_HEAD);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width">
        <meta name="description" content="">
        <link rel="apple-touch-icon" sizes="57x57" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?= $bundle->baseUrl; ?>/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= $bundle->baseUrl; ?>/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?= $bundle->baseUrl; ?>/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?= $bundle->baseUrl; ?>/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="<?= $bundle->baseUrl; ?>/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?= $bundle->baseUrl; ?>/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->context->title); ?> - MatichonBook.com</title>
        <?php $this->head() ?>
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-NK3N1Y8Q8S">
	</script>
	<script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-NK3N1Y8Q8S');
	</script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-16461165974"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'AW-16461165974');
    </script>
    <script>
        gtag('event', 'conversion', {
            'send_to': 'AW-16461165974/bE9bCK3pvpIZEJbrpak9',
            'transaction_id': ''
        });
    </script>
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '242124103321471');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=242124103321471&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->

   <script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

  ttq.load('COUSOQJC77U8DR5JQREG');
  ttq.page();
}(window, document, 'ttq');
</script>

<script>
ttq.identify({
 "email": "<hashed_email_address>", // string. The email of the customer if available. It must be hashed with SHA-256 on the client side.
 "phone_number": "<hashed_phone_number>", // string. The phone number of the customer if available. It must be hashed with SHA-256 on the client side.
 "external_id": "<hashed_extenal_id>" // string. Any unique identifier, such as loyalty membership IDs, user IDs, and external cookie IDs.It must be hashed with SHA-256 on the client side.
});

ttq.track('Search', {
 "contents": [
  {
   "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
   "content_type": "<content_type>", // string. Either product or product_group.
   "content_name": "<content_name>", // string. The name of the page or product. Example: "shirt".
   "content_category": "<content_category>", // string. The category of the page or product. Example: "apparel".
   "quantity": "<content_quantity>", // number. The number of items. Example: 4.
   "price": "<content_price>" // number. The price of a single item. Example: 25.
  }
 ],
 "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
 "currency": "<content_currency>", // string. The 4217 currency code. Example: "USD".
 "query": "<search_keywords>", // string. The word or phrase used to search. Example: "SAVE10COUPON".
 "status": "<content_status>" // string. The status of an order, item, or service. Example: "submitted".
});

ttq.track('ViewContent', {
 "contents": [
  {
   "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
   "content_type": "<content_type>", // string. Either product or product_group.
   "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
  }
 ],
 "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
 "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
});

ttq.track('Subscribe', {
 "contents": [
  {
   "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
   "content_type": "<content_type>", // string. Either product or product_group.
   "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
  }
 ],
 "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
 "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
});

ttq.track('ClickButton', {
 "contents": [
  {
   "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
   "content_type": "<content_type>", // string. Either product or product_group.
   "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
  }
 ],
 "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
 "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
});

ttq.track('AddPaymentInfo', {
 "contents": [
  {
   "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
   "content_type": "<content_type>", // string. Either product or product_group.
   "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
  }
 ],
 "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
 "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
});

ttq.track('InitiateCheckout', {
 "contents": [
  {
   "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
   "content_type": "<content_type>", // string. Either product or product_group.
   "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
  }
 ],
 "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
 "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
});

ttq.track('AddToCart', {
 "contents": [
  {
   "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
   "content_type": "<content_type>", // string. Either product or product_group.
   "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
  }
 ],
 "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
 "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
});

ttq.track('CompletePayment', {
 "contents": [
  {
   "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
   "content_type": "<content_type>", // string. Either product or product_group.
   "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
  }
 ],
 "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
 "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
});

ttq.track('PlaceAnOrder', {
 "contents": [
  {
   "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
   "content_type": "<content_type>", // string. Either product or product_group.
   "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
  }
 ],
 "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
 "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
});
</script>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MXPC92S" height="0" width="0" style="display:none;visibility:hidden;">

        </iframe>
        <?= $content; ?>
        <?php $this->endBody() ?>
        <script>
            var doc = document.documentElement;
            doc.setAttribute('data-useragent', navigator.userAgent);
        </script>
    </body>
</html>
<?php $this->endPage() ?>
