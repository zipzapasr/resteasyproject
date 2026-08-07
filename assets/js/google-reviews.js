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
    var filled = Math.max(0, Math.min(5, parseInt(rating, 10) || 5));
    var html = '<ul>';
    for (var i = 1; i <= 5; i++) {
      html += '<li class="' + (i <= filled ? 'is-filled' : 'is-empty') + '"><span class="icon-pointed-star"></span></li>';
    }
    html += '</ul>';
    return html;
  }

  function truncateText(text, max) {
    if (!text) return '';
    if (text.length <= max) return text;
    return text.slice(0, max).trim() + '\u2026';
  }

  function googleLogoSvg() {
    return (
      '<svg class="google-review-card__g-logo" viewBox="0 0 24 24" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' +
        '<path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>' +
        '<path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>' +
        '<path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>' +
        '<path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>' +
      '</svg>'
    );
  }

  function buildReviewCard(review) {
    var name = review.author_name || 'Google user';
    var photo = review.profile_photo_url || defaultAvatar;
    var time = review.relative_time_description || '';
    var text = review.text || '';
    var rating = review.rating || 5;
    var reviewUrl = review.review_url || config.mapsUrl || '';

    var cardInner =
        '<div class="google-review-card__header">' +
          '<div class="google-review-card__author">' +
            '<div class="google-review-card__avatar">' +
              '<img src="' + escapeHtml(photo) + '" alt="' + escapeHtml(name) + '" loading="lazy" referrerpolicy="no-referrer">' +
            '</div>' +
            '<div class="google-review-card__meta">' +
              '<h4 class="google-review-card__name">' + escapeHtml(name) + '</h4>' +
              (time ? '<span class="google-review-card__time">' + escapeHtml(time) + '</span>' : '') +
            '</div>' +
          '</div>' +
          '<span class="google-review-card__source">' + googleLogoSvg() + '</span>' +
        '</div>' +
        '<div class="google-review-card__rating rating-box" aria-label="' + rating + ' out of 5 stars">' +
          starsHtml(rating) +
        '</div>' +
        '<div class="google-review-card__body testimonial-three__single-text">' +
          '<p>' + escapeHtml(truncateText(text, 320)) + '</p>' +
        '</div>' +
        (reviewUrl ? '<span class="google-review-card__view">View on Google</span>' : '');

    if (reviewUrl) {
      return (
        '<a class="testimonial-three__single google-review-card google-review-card--linked" href="' + escapeHtml(reviewUrl) + '" target="_blank" rel="noopener noreferrer" aria-label="Read ' + escapeHtml(name) + '\'s review on Google">' +
          cardInner +
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
      $carousel.find('.owl-stage-outer').children().unwrap();
    }
    $carousel.owlCarousel({
      loop: true,
      autoplay: true,
      margin: 20,
      nav: false,
      dots: false,
      smartSpeed: 500,
      autoplayTimeout: 6000,
      autoplayHoverPause: true,
      responsive: {
        0: { items: 1, margin: 10 },
        576: { items: 1, margin: 15 },
        768: { items: 2, margin: 18 },
        992: { items: 3, margin: 20 },
        1200: { items: 3, margin: 24 }
      }
    });
    var $wrap = $carousel.closest('.google-reviews-carousel-wrap');
    if ($wrap.length) {
      $wrap.find('.google-reviews-carousel__nav--prev').off('click.greviews').on('click.greviews', function (e) {
        $carousel.trigger('prev.owl.carousel');
        e.preventDefault();
      });
      $wrap.find('.google-reviews-carousel__nav--next').off('click.greviews').on('click.greviews', function (e) {
        $carousel.trigger('next.owl.carousel');
        e.preventDefault();
      });
    }
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
