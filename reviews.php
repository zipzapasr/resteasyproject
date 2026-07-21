<?php
$googleReviewUrl = 'https://g.page/r/CTnrxk8vk4_GEBM/review';
$googleMapsPlaceUrl = 'https://www.google.com/maps/place/?q=place_id:ChIJ20vQ2H_K1WoROevGTy-Tj8Y';
$googlePlaceId = 'ChIJ20vQ2H_K1WoROevGTy-Tj8Y';
$googleRating = 4.4;
$googleReviewCount = 34;
$googleLiveReviews = array();

require_once __DIR__ . '/includes/google-reviews-fetch.php';
$googleLiveData = resteasy_fetch_google_reviews();
if (is_array($googleLiveData)) {
    if (!empty($googleLiveData['rating'])) {
        $googleRating = (float) $googleLiveData['rating'];
    }
    if (!empty($googleLiveData['reviewCount'])) {
        $googleReviewCount = (int) $googleLiveData['reviewCount'];
    }
    if (!empty($googleLiveData['reviews']) && is_array($googleLiveData['reviews'])) {
        $googleLiveReviews = $googleLiveData['reviews'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Testimonials | Rest Easy Services Reviews</title>
    <meta name="description" content="See what customers say about Rest Easy Services. Providing reliable cleaning services throughout Mornington Peninsula, Frankston, Brighton, and Melbourne's southeast." />
    <meta name="keywords" content="about us, Rest Easy cleaning, Mornington Peninsula cleaners, professional cleaning company, trusted cleaning services" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="https://www.resteasyservices.com.au/reviews" />
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Customer Testimonials | Rest Easy Services Reviews" />
    <meta property="og:description" content="See what customers say about Rest Easy Services. Providing reliable cleaning services throughout Mornington Peninsula, Frankston, Brighton, and Melbourne's southeast." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.resteasyservices.com.au/reviews" />
    <meta property="og:image" content="https://www.resteasyservices.com.au/assets/images/resources/main-logo2.png" />
    <meta property="og:site_name" content="Rest Easy Services" />
    <meta property="og:locale" content="en_AU" />
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Customer Testimonials | Rest Easy Services Reviews" />
    <meta name="twitter:description" content="See what customers say about Rest Easy Services. Providing reliable cleaning services throughout Mornington Peninsula, Frankston, Brighton, and Melbourne's southeast." />
    <meta name="twitter:image" content="https://www.resteasyservices.com.au/assets/images/resources/main-logo2.png" />
    
    <!-- Schema.org JSON-LD -->
  
    
    <!-- Favicons Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png" />
    <link rel="manifest" href="assets/images/favicons/site.webmanifest" />

    <!-- fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/vendors/animate/animate.min.css" />
    <link rel="stylesheet" href="assets/vendors/animate/custom-animate.css" />
    <link rel="stylesheet" href="assets/vendors/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/vendors/bootstrap-select/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="assets/vendors/bxslider/jquery.bxslider.css" />
    <link rel="stylesheet" href="assets/vendors/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css" />
    <link rel="stylesheet" href="assets/vendors/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="assets/vendors/nice-select/nice-select.css" />
    <link rel="stylesheet" href="assets/vendors/nouislider/nouislider.min.css" />
    <link rel="stylesheet" href="assets/vendors/nouislider/nouislider.pips.css" />
    <link rel="stylesheet" href="assets/vendors/odometer/odometer.min.css" />
    <link rel="stylesheet" href="assets/vendors/owl-carousel/owl.carousel.min.css" />
    <link rel="stylesheet" href="assets/vendors/owl-carousel/owl.theme.default.min.css" />
    <link rel="stylesheet" href="assets/vendors/swiper/swiper.min.css" />
    <link rel="stylesheet" href="assets/vendors/timepicker/timePicker.css" />
    <link rel="stylesheet" href="assets/vendors/tiny-slider/tiny-slider.min.css" />
    <link rel="stylesheet" href="assets/vendors/vegas/vegas.min.css" />
    <link rel="stylesheet" href="assets/vendors/thm-icons/style.css">
    <link rel="stylesheet" href="assets/vendors/slick-slider/slick.css">
    <link rel="stylesheet" href="assets/vendors/language-switcher/polyglot-language-switcher.css">

    <!-- template styles -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/color-1.css" />


</head>


<body>

    <div class="page-wrapper">

        <!--Start Main Header One-->
        <?php include "includes/header.php"; ?>
        <!--End Main Header One-->

        <div class="stricky-header stricky-header--one stricked-menu main-menu">
            <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
        </div><!-- /.stricky-header -->

         <!--Start Page Header-->
    <section class="page-header">
      <div class="page-header__img">
        <img src="assets/images/resources/page-header-bg.jpg" alt="#">
      </div>
      <div class="shape1 rotate-me">
        <img src="assets/images/shapes/thm-shape1.png" alt="#">
      </div>
      <div class="shape2 float-bob-y">
        <img src="assets/images/shapes/bubbles-four.png" alt="#">
      </div>
      <div class="shape3 zoominout">
        <img src="assets/images/shapes/bubbles-six.png" alt="#">
      </div>
      <div class="shape4">
        <img src="assets/images/shapes/bubbles-five.png" alt="#">
      </div>
      <div class="shape5 float-bob-y">
        <img src="assets/images/shapes/bubbles-five.png" alt="#">
      </div>
      <div class="shape6 rotate-me">
        <img src="assets/images/shapes/thm-shape1.png" alt="#">
      </div>
      <div class="container">
        <div class="page-header__inner">
          <h2> Reviews</h2>
          <ul class="thm-breadcrumb">
            <li>
              <a href="index.php">Home</a>
            </li>
            <li>
              <span class="icon-right"></span>
            </li>
            <li> Reviews</li>
          </ul>
        </div>
      </div>
    </section>
    <!--End Page Header-->

        <!--Start Page Header-->
      <section class="testimonial-three" style=" background-color: #f8f9fa;" id="reviews">
            <div class="container">
                <div class="sec-title text-center">
                    <div class="sub-title">
                        <div class="text">
                            <span>Our Clients</span>
                        </div>
                    </div>
                    <h2>What Our Clients Say</h2>
                </div>

                <div id="google-reviews-summary" class="google-reviews-summary" aria-live="polite">
                    <div class="google-reviews-summary__inner">
                        <div class="google-reviews-summary__brand">
                            <i class="fab fa-google"></i>
                            <div>
                                <strong class="google-reviews-summary__score" id="google-reviews-score"><?php echo htmlspecialchars(number_format($googleRating, 1), ENT_QUOTES, 'UTF-8'); ?></strong>
                                <div class="google-reviews-summary__stars rating-box google-reviews-summary__stars--partial" data-rating="<?php echo htmlspecialchars((string) $googleRating, ENT_QUOTES, 'UTF-8'); ?>">
                                    <ul>
                                        <li><span class="icon-pointed-star"></span></li>
                                        <li><span class="icon-pointed-star"></span></li>
                                        <li><span class="icon-pointed-star"></span></li>
                                        <li><span class="icon-pointed-star"></span></li>
                                        <li><span class="icon-pointed-star google-star-partial"></span></li>
                                    </ul>
                                </div>
                                <p class="google-reviews-summary__count" id="google-reviews-count">Based on <?php echo (int) $googleReviewCount; ?> Google reviews</p>
                            </div>
                        </div>
                        <p class="google-reviews-summary__links">
                            <a href="<?php echo htmlspecialchars($googleMapsPlaceUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Read all reviews on Google</a>
                            &middot;
                            <a href="<?php echo htmlspecialchars($googleReviewUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Leave a review</a>
                        </p>
                    </div>
                </div>

                <div class="reviews-page-grid">
                            <?php if (!empty($googleLiveReviews)): ?>
                                <?php foreach ($googleLiveReviews as $review): ?>
                                        <?php
                                        $reviewName = !empty($review['author_name']) ? $review['author_name'] : 'Google user';
                                        $reviewPhoto = !empty($review['profile_photo_url']) ? $review['profile_photo_url'] : 'assets/images/testimonial/rest-test.png';
                                        $reviewTime = !empty($review['relative_time_description']) ? $review['relative_time_description'] : '';
                                        $reviewText = resteasy_truncate_review_text(!empty($review['text']) ? $review['text'] : '');
                                        $reviewStars = !empty($review['rating']) ? (int) $review['rating'] : 5;
                                        $reviewLink = !empty($review['review_url']) ? $review['review_url'] : $googleMapsPlaceUrl;
                                        $reviewTag = $reviewLink ? 'a' : 'div';
                                        $reviewAttrs = $reviewLink
                                            ? ' href="' . htmlspecialchars($reviewLink, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer" aria-label="Read ' . htmlspecialchars($reviewName, ENT_QUOTES, 'UTF-8') . '\'s review on Google"'
                                            : '';
                                        $reviewClass = 'testimonial-three__single google-review-card' . ($reviewLink ? ' google-review-card--linked' : '');
                                        ?>
                            <<?php echo $reviewTag; ?> class="<?php echo $reviewClass; ?>"<?php echo $reviewAttrs; ?>>
                                <div class="testimonial-three__single-top">
                                    <div class="google-review-card__badge"><i class="fab fa-google"></i> Google</div>
                                    <div class="rating-box">
                                        <ul>
                                            <?php for ($star = 0; $star < 5; $star++): ?>
                                            <li><span class="icon-pointed-star"></span></li>
                                            <?php endfor; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="testimonial-three__single-text">
                                    <p><?php echo htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <div class="testimonial-three__single-bottom">
                                    <div class="img-box">
                                        <div class="round-one"></div>
                                        <div class="round-two"></div>
                                        <div class="inner">
                                            <img src="<?php echo htmlspecialchars($reviewPhoto, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($reviewName, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" referrerpolicy="no-referrer">
                                        </div>
                                    </div>
                                    <div class="client-info">
                                        <h4><?php echo htmlspecialchars($reviewName, ENT_QUOTES, 'UTF-8'); ?></h4>
                                        
                                        <?php if ($reviewLink): ?>
                                <span class="google-review-card__view">View review on Google</span>
                                <?php endif; ?>
                                    </div>
                                    
                                </div>
                            
                            </<?php echo $reviewTag; ?>>
                                    <?php endforeach; ?>
                            <?php else: ?>
                            <div class="testimonial-three__single google-review-card google-reviews-loading">
                                <div class="testimonial-three__single-text">
                                    <p>Unable to load Google reviews right now. <a href="<?php echo htmlspecialchars($googleMapsPlaceUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Read them on Google</a>.</p>
                                </div>
                            </div>
                            <?php endif; ?>
                </div>
            </div>
        </section>

      
       
        <!--Start Footer One -->
        <?php include "includes/footer.php"; ?>
        <!--End Footer One-->


    </div>
    <!-- /.page-wrapper -->




</body>

</html>
