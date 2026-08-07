<?php
$googleReviewUrl = 'https://g.page/r/CTnrxk8vk4_GEBM/review';
$googleMapsPlaceUrl = 'https://www.google.com/maps/place/?q=place_id:ChIJ20vQ2H_K1WoROevGTy-Tj8Y';
$googlePlaceId = 'ChIJ20vQ2H_K1WoROevGTy-Tj8Y';
$googleRating = 4.4;
$googleReviewCount = 31;
$googleLiveReviews = array();

require_once __DIR__ . '/google-reviews-fetch.php';
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

                <div class="google-reviews-carousel-wrap">
                        <button type="button" class="google-reviews-carousel__nav google-reviews-carousel__nav--prev" aria-label="Previous review">
                            <span class="fa fa-angle-left"></span>
                        </button>
                        <button type="button" class="google-reviews-carousel__nav google-reviews-carousel__nav--next" aria-label="Next review">
                            <span class="fa fa-angle-right"></span>
                        </button>
                        <div id="google-reviews-carousel"
                            class="testimonial-carousel owl-carousel owl-theme w-100 google-reviews-carousel"
                            data-place-id="<?php echo htmlspecialchars($googlePlaceId, ENT_QUOTES, 'UTF-8'); ?>"
                            data-maps-url="<?php echo htmlspecialchars($googleMapsPlaceUrl, ENT_QUOTES, 'UTF-8'); ?>"
                            data-review-url="<?php echo htmlspecialchars($googleReviewUrl, ENT_QUOTES, 'UTF-8'); ?>">
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
            </div>
        </section>