(function () {
  'use strict';

  var config = window.REST_EASY_GOOGLE_REVIEWS;
  var carouselEl = document.getElementById('google-reviews-carousel');
  var scoreEl = document.getElementById('google-reviews-score');
  var countEl = document.getElementById('google-reviews-count');

  if (!config || !carouselEl) {
    return;
  }

  var defaultAvatar = 'assets/images/testimonial/rest-test.png';
  var endpoint = config.endpoint || 'api/google-reviews.php';

  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function starsHtml(rating) {
    var html = '<ul>';
    for (var i = 0; i < 5; i++) {
      html += '<li><span class="icon-pointed-star"></span></li>';
    }
    html += '</ul>';
    return html;
  }

  function truncateText(text, max) {
    if (!text) return '';
    if (text.length <= max) return text;
    return text.slice(0, max).trim() + '\u2026';
  }

  function buildReviewCard(review) {
    var name = review.author_name || 'Google user';
    var photo = review.profile_photo_url || defaultAvatar;
    var time = review.relative_time_description || '';
    var text = review.text || '';
    var rating = review.rating || 5;
    var reviewUrl = review.review_url || config.mapsUrl || '';

    var cardInner =
        '<div class="testimonial-three__single-top">' +
          '<div class="google-review-card__badge"><i class="fab fa-google"></i> Google</div>' +
          '<div class="rating-box">' + starsHtml(rating) + '</div>' +
        '</div>' +
        '<div class="testimonial-three__single-text">' +
          '<p>' + escapeHtml(truncateText(text, 320)) + '</p>' +
        '</div>' +
        '<div class="testimonial-three__single-bottom">' +
          '<div class="img-box">' +
            '<div class="round-one"></div>' +
            '<div class="round-two"></div>' +
            '<div class="inner">' +
              '<img src="' + escapeHtml(photo) + '" alt="' + escapeHtml(name) + '" loading="lazy" referrerpolicy="no-referrer">' +
            '</div>' +
          '</div>' +
          '<div class="client-info">' +
            '<h4>' + escapeHtml(name) + '</h4>' +
            '<span>' + escapeHtml(time) + '</span>' +
          '</div>' +
        '</div>';

    if (reviewUrl) {
      return (
        '<a class="testimonial-three__single google-review-card google-review-card--linked" href="' + escapeHtml(reviewUrl) + '" target="_blank" rel="noopener noreferrer" aria-label="Read ' + escapeHtml(name) + '\'s review on Google">' +
          cardInner +
          '<span class="google-review-card__view">View review on Google</span>' +
        '</a>'
      );
    }

    return '<div class="testimonial-three__single google-review-card">' + cardInner + '</div>';
  }

  function updateSummaryStars(rating) {
    var starsWrap = document.querySelector('.google-reviews-summary__stars');
    if (!starsWrap || rating == null) return;
    starsWrap.setAttribute('data-rating', Number(rating).toFixed(1));
  }

  function updateSummary(data) {
    var rating = data.rating != null ? data.rating : (config.rating != null ? config.rating : null);
    var total = data.reviewCount != null ? data.reviewCount : (config.reviewCount != null ? config.reviewCount : null);

    if (rating != null && scoreEl) {
      scoreEl.textContent = Number(rating).toFixed(1);
      updateSummaryStars(rating);
    }
    if (total != null && countEl) {
      countEl.textContent = 'Based on ' + total + ' Google review' + (total === 1 ? '' : 's');
    }
  }

  function refreshOwlCarousel() {
    if (!window.jQuery || !jQuery.fn.owlCarousel) return;
    var $carousel = jQuery(carouselEl);
    if ($carousel.data('owl.carousel')) {
      $carousel.trigger('destroy.owl.carousel');
    }
    $carousel.owlCarousel({
      loop: true,
      autoplay: true,
      margin: 20,
      nav: false,
      dots: true,
      smartSpeed: 500,
      autoplayTimeout: 6000,
      autoplayHoverPause: true,
      responsive: {
        0: { items: 1, margin: 10 },
        576: { items: 1, margin: 15 },
        768: { items: 1, margin: 20 },
        992: { items: 2, margin: 22 },
        1200: { items: 2, margin: 25 }
      }
    });
  }

  function showError(message) {
    carouselEl.innerHTML =
      '<div class="testimonial-three__single google-review-card google-reviews-loading">' +
        '<div class="testimonial-three__single-text">' +
          '<p>' + escapeHtml(message) + '</p>' +
        '</div>' +
      '</div>';
  }

  function renderLiveReviews(data) {
    var reviews = data.reviews || [];
    if (!reviews.length) {
      showError('No Google reviews are available right now.');
      updateSummary(data);
      return;
    }

    carouselEl.innerHTML = reviews.map(buildReviewCard).join('');
    updateSummary(data);
    refreshOwlCarousel();
  }

  function fetchReviews() {
    var url = endpoint + (endpoint.indexOf('?') === -1 ? '?' : '&') + '_=' + Date.now();
    fetch(url, { credentials: 'same-origin', cache: 'no-store' })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('Request failed');
        }
        return response.json();
      })
      .then(function (data) {
        if (!data || data.error) {
          throw new Error(data && data.error ? data.error : 'Invalid response');
        }
        renderLiveReviews(data);
      })
      .catch(function () {
        showError('Unable to load Google reviews right now. Please try again later or read them on Google.');
      });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', fetchReviews);
  } else {
    fetchReviews();
  }
})();
