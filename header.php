<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-BY2H5WV3QL"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-BY2H5WV3QL');
  </script>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width" />
  <meta name="description"
    content="Spécialiste de la location de matériel audiovisuel et broadcast à Rennes, Nantes, Brest, Lorient, Angers, Caen depuis plus de 12 ans. SprayLoc est expérimenté en équipements vidéo et audio professionnels. Avec notre large sélection de matériel vidéo, accessoires et audio à louer, vous trouverez le meilleur pour vos projets vidéo et cinéma !">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="shortcut icon" sizes="16x16 24x24 32x32 48x48" href="favicon.ico">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon.png">

  <!-- Begin Sendinblue Form -->
  <!-- START - We recommend to place the below code in head tag of your website html  -->
  <style>
    @font-face {
      font-display: block;
      font-family: Roboto;
      src: url(https://assets.sendinblue.com/font/Roboto/Latin/normal/normal/7529907e9eaf8ebb5220c5f9850e3811.woff2) format("woff2"), url(https://assets.sendinblue.com/font/Roboto/Latin/normal/normal/25c678feafdc175a70922a116c9be3e7.woff) format("woff")
    }

    @font-face {
      font-display: fallback;
      font-family: Roboto;
      font-weight: 600;
      src: url(https://assets.sendinblue.com/font/Roboto/Latin/medium/normal/6e9caeeafb1f3491be3e32744bc30440.woff2) format("woff2"), url(https://assets.sendinblue.com/font/Roboto/Latin/medium/normal/71501f0d8d5aa95960f6475d5487d4c2.woff) format("woff")
    }

    @font-face {
      font-display: fallback;
      font-family: Roboto;
      font-weight: 700;
      src: url(https://assets.sendinblue.com/font/Roboto/Latin/bold/normal/3ef7cf158f310cf752d5ad08cd0e7e60.woff2) format("woff2"), url(https://assets.sendinblue.com/font/Roboto/Latin/bold/normal/ece3a1d82f18b60bcce0211725c476aa.woff) format("woff")
    }


    .sib-form {
      background-color: transparent !important;
    }

    #sib-form * {
      font-family: "Titillium Web", sans-serif !important;
    }

    #sib-form-container {
      background-color: transparent !important;
    }

    #sib-container input:-ms-input-placeholder {
      text-align: left;
      font-family: "Helvetica", sans-serif;
      color: #c0ccda;
    }

    #sib-container input::placeholder {
      text-align: left;
      font-family: "Helvetica", sans-serif;
      color: #c0ccda;
    }

    #sib-container textarea::placeholder {
      text-align: left;
      font-family: "Helvetica", sans-serif;
      color: #c0ccda;
    }
  </style>
  <link rel="stylesheet" href="https://sibforms.com/forms/end-form/build/sib-styles.css">
  <!--  END - We recommend to place the above code in head tag of your website html -->

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <div class="btn-page-top">
    <i class="fas fa-chevron-up"></i>
    <span class="text">Haut de Page</span>
  </div>

  <div id="main-wrapper">




    <?php echo custom_menu_V2(); ?>


    <!-- </div> -->
    </nav>


    <div id="sprayloc-container">

      <?php

      $api_key = get_option("sprayloc_plugin_admin")["rentman_api_key"];


      ?>