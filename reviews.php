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
                                <div class="google-review-card__header">
                                    <div class="google-review-card__author">
                                        <div class="google-review-card__avatar">
                                            <img src="<?php echo htmlspecialchars($reviewPhoto, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($reviewName, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" referrerpolicy="no-referrer">
                                        </div>
                                        <div class="google-review-card__meta">
                                            <h4 class="google-review-card__name"><?php echo htmlspecialchars($reviewName, ENT_QUOTES, 'UTF-8'); ?></h4>
                                            <?php if ($reviewTime): ?>
                                            <span class="google-review-card__time"><?php echo htmlspecialchars($reviewTime, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="google-review-card__source" aria-hidden="true">
                                        <svg class="google-review-card__g-logo" viewBox="0 0 24 24" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="google-review-card__rating rating-box" aria-label="<?php echo (int) $reviewStars; ?> out of 5 stars">
                                    <ul>
                                        <?php for ($star = 1; $star <= 5; $star++): ?>
                                        <li class="<?php echo $star <= $reviewStars ? 'is-filled' : 'is-empty'; ?>">
                                            <span class="icon-pointed-star"></span>
                                        </li>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                                <div class="google-review-card__body testimonial-three__single-text">
                                    <p><?php echo htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <?php if ($reviewLink): ?>
                                <span class="google-review-card__view">View on Google</span>
                                <?php endif; ?>
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
