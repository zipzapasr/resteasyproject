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

                <div class="row align-items-stretch g-4">
                    <div class="col-xl-8 col-lg-7 d-flex">
                        <div class="google-reviews-carousel-wrap w-100">
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
                    </div>
                    <div class="col-xl-4 col-lg-5 d-flex">
                        <div class="home-inline-contact contact-page__form">
                            <div class="home-inline-contact__head">
                          
                                <h3 class="home-inline-contact__title">Send a message</h3>
                                <p class="home-inline-contact__text">Request a quote or ask a question—we will get back to you shortly.</p>
                            </div>
                            <div id="form-messages-home"
                                style="display: none; padding: 15px; margin-bottom: 20px; border-radius: 8px;"></div>
                            <?php
                            require_once __DIR__ . '/google-form-config.php';
                            $googleFormHome = $resteasyGoogleForm;
                            ?>
                            <form id="contact-form-home"
                                action="<?php echo htmlspecialchars($googleFormHome['action'], ENT_QUOTES, 'UTF-8'); ?>"
                                method="POST" target="gform_hidden_iframe_home" class="comment-one__form"
                                data-google-form="1"
                                data-enquiry-email-url="<?php echo htmlspecialchars($resteasyEnquiryEmailUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                autocomplete="on">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="comment-form__input-box">
                                            <input type="text" placeholder="Full name *"
                                                name="<?php echo htmlspecialchars($googleFormHome['fields']['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-enquiry-field="name" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="comment-form__input-box">
                                            <input type="email" placeholder="Email address *"
                                                name="<?php echo htmlspecialchars($googleFormHome['fields']['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-enquiry-field="email" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="comment-form__input-box">
                                            <input type="tel" placeholder="Phone *"
                                                name="<?php echo htmlspecialchars($googleFormHome['fields']['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-enquiry-field="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="comment-form__input-box">
                                            <input type="text"
                                                name="<?php echo htmlspecialchars($googleFormHome['fields']['suburb'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-enquiry-field="suburb" placeholder="Suburb *" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="comment-form__input-box">
                                            <textarea
                                                name="<?php echo htmlspecialchars($googleFormHome['fields']['message'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-enquiry-field="message"
                                                placeholder="Your message *" rows="3" style="min-height: 80px; height: 80px;"
                                                required></textarea>
                                        </div>
                                        <input type="hidden" name="fvv" value="1">
                                        <input type="hidden" name="fbzx"
                                            value="<?php echo htmlspecialchars($googleFormHome['fbzx'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="pageHistory" value="0">
                                        <input type="hidden" name="submit" value="Submit">
                                        <button class="thm-btn home-inline-contact__btn" type="submit" id="submit-btn-home">
                                            <span class="txt">Send message +</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <iframe name="gform_hidden_iframe_home" id="gform_hidden_iframe_home"
                                style="display:none;"></iframe>
                            <script>
                                (function () {
                                    var form = document.getElementById('contact-form-home');
                                    if (!form) return;
                                    var action = (form.getAttribute('action') || '').toLowerCase();
                                    var isGoogle = action.indexOf('docs.google.com/forms') !== -1 || action.indexOf('google.com/forms') !== -1;
                                    if (!isGoogle) return;
                                    var submitted = false;
                                    var iframe = document.getElementById('gform_hidden_iframe_home');
                                    var msg = document.getElementById('form-messages-home');
                                    var btn = document.getElementById('submit-btn-home');
                                    var btnTxt = btn ? btn.querySelector('.txt') : null;
                                    var originalBtnText = btnTxt ? btnTxt.textContent : '';
                                    function showMessage(ok, html) {
                                        if (!msg) return;
                                        msg.style.display = 'block';
                                        msg.style.padding = '15px';
                                        msg.style.marginBottom = '20px';
                                        msg.style.borderRadius = '8px';
                                        if (ok) {
                                            msg.style.backgroundColor = '#d4edda';
                                            msg.style.color = '#155724';
                                            msg.style.border = '1px solid #c3e6cb';
                                        } else {
                                            msg.style.backgroundColor = '#f8d7da';
                                            msg.style.color = '#721c24';
                                            msg.style.border = '1px solid #f5c6cb';
                                        }
                                        msg.innerHTML = html;
                                    }
                                    form.addEventListener('submit', function () {
                                        submitted = true;
                                        if (btn) btn.disabled = true;
                                        if (btnTxt) btnTxt.textContent = 'Sending...';
                                        if (msg) msg.style.display = 'none';
                                    });
                                    if (iframe) {
                                        iframe.addEventListener('load', function () {
                                            if (!submitted) return;
                                            submitted = false;
                                            showMessage(true, '<strong>Success!</strong> Thank you for your enquiry! We have received your message and will get back to you as soon as possible.');
                                            try { form.reset(); } catch (e) { }
                                            if (btn) btn.disabled = false;
                                            if (btnTxt) btnTxt.textContent = originalBtnText || 'Send message +';
                                        });
                                    }
                                })();
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </section>