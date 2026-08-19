(function ($) {
  "use strict";



  let thmAutoplayPausedSwipers = [];
  let thmIsAutoplayPausedByTheme = false;
  let thmAutoplayPauseLocks = 0;

  function thmGetAllSwipers() {
    // Swiper attaches its instance to the container element as `el.swiper`
    let swipers = [];
    document.querySelectorAll(".swiper-container").forEach(function (el) {
      if (el && el.swiper) swipers.push(el.swiper);
    });
    return swipers;
  }

  function thmPauseSwiperAutoplay() {
    if (thmIsAutoplayPausedByTheme) return;
    thmIsAutoplayPausedByTheme = true;
    thmAutoplayPausedSwipers = [];
    thmGetAllSwipers().forEach(function (swiper) {
      if (!swiper || !swiper.params || !swiper.params.autoplay) return;
      if (!swiper.autoplay || typeof swiper.autoplay.stop !== "function") return;

      const isRunning =
        typeof swiper.autoplay.running === "boolean" ? swiper.autoplay.running : true;

      if (isRunning) {
        thmAutoplayPausedSwipers.push(swiper);
        swiper.autoplay.stop();
      }
    });
  }

  function thmResumeSwiperAutoplay() {
    if (!thmIsAutoplayPausedByTheme) return;
    thmAutoplayPausedSwipers.forEach(function (swiper) {
      if (!swiper || !swiper.params || !swiper.params.autoplay) return;
      if (!swiper.autoplay || typeof swiper.autoplay.start !== "function") return;

      const isRunning =
        typeof swiper.autoplay.running === "boolean" ? swiper.autoplay.running : false;

      if (!isRunning) {
        swiper.autoplay.start();
      }
    });
    thmAutoplayPausedSwipers = [];
    thmIsAutoplayPausedByTheme = false;
  }

  function thmBindMobileNavPauseOnHover() {
    const pauseTargets = [".mobile-nav__toggler", ".main-slider .thm-btn"].join(", ");
    if (!$(pauseTargets).length) return;

    $(pauseTargets)
      .on("mouseenter focus", function () {
        thmAutoplayPauseLocks += 1;
        thmPauseSwiperAutoplay();
      })
      .on("mouseleave blur", function () {
        thmAutoplayPauseLocks = Math.max(0, thmAutoplayPauseLocks - 1);
        if (thmAutoplayPauseLocks > 0) return;
        if ($(".mobile-nav__wrapper").hasClass("expanded")) return;
        thmResumeSwiperAutoplay();
      });
  }

  //Hide Loading Box (Preloader)
  function handlePreloader() {
    if ($('.loader-wrap').length) {
      $('.loader-wrap').delay(1000).fadeOut(500);
    }
  }

  if ($(".preloader-close").length) {
    $(".preloader-close").on("click", function () {
      $('.loader-wrap').delay(200).fadeOut(500);
    })
  }


  function thmSwiperInit() {
    // swiper slider
    if ($(".thm-swiper__slider").length) {
      $(".thm-swiper__slider").each(function () {
        let elm = $(this);
        let options = elm.data('swiper-options');
        let thmSwiperSlider = new Swiper(elm, options);
      });
    }
  }



  function thmOwlInit() {
    // owl slider

    if ($(".thm-owl__carousel").length) {
      $(".thm-owl__carousel").each(function () {
        let elm = $(this);
        let options = elm.data('owl-options');
        let thmOwlCarousel = elm.owlCarousel(options);
      });
    }

    if ($(".thm-owl__carousel--custom-nav").length) {
      $(".thm-owl__carousel--custom-nav").each(function () {
        let elm = $(this);
        let owlNavPrev = elm.data('owl-nav-prev');
        let owlNavNext = elm.data('owl-nav-next');
        $(owlNavPrev).on("click", function (e) {
          elm.trigger('prev.owl.carousel');
          e.preventDefault();
        })

        $(owlNavNext).on("click", function (e) {
          elm.trigger('next.owl.carousel');
          e.preventDefault();
        })
      });
    }

    // Client testimonials (includes/reviews.php) — must run on all pages that embed it, not only index.
    if ($(".testimonial-carousel").length && $.fn.owlCarousel) {
      $(".testimonial-carousel").each(function () {
        let elm = $(this);
        if (elm.data("owl.carousel")) return;
        let isGoogleReviews = elm.hasClass("google-reviews-carousel");
        elm.owlCarousel({
          loop: true,
          autoplay: true,
          margin: 20,
          nav: false,
          dots: !isGoogleReviews,
          smartSpeed: 500,
          autoplayTimeout: 6000,
          autoplayHoverPause: true,
          navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
          responsive: {
            0: { items: 1, margin: 10 },
            576: { items: 1, margin: 15 },
            768: { items: 2, margin: 18 },
            992: { items: 3, margin: 20 },
            1200: { items: 3, margin: 24 }
          }
        });
        if (isGoogleReviews) {
          let wrap = elm.closest(".google-reviews-carousel-wrap");
          if (wrap.length) {
            wrap.find(".google-reviews-carousel__nav--prev").on("click", function (e) {
              elm.trigger("prev.owl.carousel");
              e.preventDefault();
            });
            wrap.find(".google-reviews-carousel__nav--next").on("click", function (e) {
              elm.trigger("next.owl.carousel");
              e.preventDefault();
            });
          }
        }
      });
    }

    // Services cards: horizontal carousel on mobile only
    (function initFeatureThreeMobileCarousel() {
      var $wrap = $(".feature-three__carousel-wrap");
      var $grid = $(".feature-three__grid");
      if (!$wrap.length || !$grid.length || !$.fn.owlCarousel) return;

      var mobileMq = window.matchMedia("(max-width: 767px)");
      var $carousel = null;

      function buildCarousel() {
        if ($carousel && $carousel.data("owl.carousel")) return;

        var $cards = $();
        $grid.children('[class*="col-"]').each(function () {
          var $card = $(this).children(".feature-three__single").first().clone(true);
          if ($card.length) {
            $cards = $cards.add($card);
          }
        });
        if (!$cards.length) return;

        $carousel = $('<div class="feature-three__mobile-carousel owl-carousel owl-theme"></div>');
        $cards.each(function () {
          $carousel.append($('<div class="item"></div>').append(this));
        });
        $wrap.prepend($carousel);
        $grid.addClass("is-mobile-hidden");

        $carousel.owlCarousel({
          loop: true,
          autoplay: true,
          autoplayTimeout: 5000,
          autoplayHoverPause: true,
          margin: 14,
          nav: false,
          dots: true,
          smartSpeed: 450,
          items: 1,
          stagePadding: 28
        });

        $wrap.find(".feature-three__mobile-nav-btn--prev").off("click.ft3").on("click.ft3", function (e) {
          e.preventDefault();
          $carousel.trigger("prev.owl.carousel");
        });
        $wrap.find(".feature-three__mobile-nav-btn--next").off("click.ft3").on("click.ft3", function (e) {
          e.preventDefault();
          $carousel.trigger("next.owl.carousel");
        });
        $wrap.find(".feature-three__mobile-nav").attr("aria-hidden", "false");
      }

      function destroyCarousel() {
        if ($carousel && $carousel.data("owl.carousel")) {
          $carousel.trigger("destroy.owl.carousel");
          $carousel.remove();
          $carousel = null;
        }
        $grid.removeClass("is-mobile-hidden");
        $wrap.find(".feature-three__mobile-nav").attr("aria-hidden", "true");
      }

      function syncMode() {
        if (mobileMq.matches) {
          buildCarousel();
        } else {
          destroyCarousel();
        }
      }

      syncMode();
      if (typeof mobileMq.addEventListener === "function") {
        mobileMq.addEventListener("change", syncMode);
      } else if (typeof mobileMq.addListener === "function") {
        mobileMq.addListener(syncMode);
      }
    })();

    // House cleaning offers: horizontal carousel on mobile only
    (function initHcOffersMobileCarousel() {
      var $wrap = $(".hc-offers__carousel-wrap");
      var $list = $(".hc-offers__list");
      if (!$wrap.length || !$list.length || !$.fn.owlCarousel) return;

      var mobileMq = window.matchMedia("(max-width: 767px)");
      var $carousel = null;

      function buildCarousel() {
        if ($carousel && $carousel.data("owl.carousel")) return;

        var $cards = $();
        $list.children(".hc-offer").each(function () {
          var $card = $(this).clone(true);
          if ($card.length) {
            $cards = $cards.add($card);
          }
        });
        if (!$cards.length) return;

        $carousel = $('<div class="hc-offers__mobile-carousel owl-carousel owl-theme"></div>');
        $cards.each(function () {
          $carousel.append($('<div class="item"></div>').append(this));
        });
        $wrap.prepend($carousel);
        $list.addClass("is-mobile-hidden");

        $carousel.owlCarousel({
          loop: true,
          autoplay: true,
          autoplayTimeout: 5500,
          autoplayHoverPause: true,
          margin: 14,
          nav: false,
          dots: true,
          smartSpeed: 450,
          items: 1,
          stagePadding: 18
        });

        $wrap.find(".hc-offers__mobile-nav-btn--prev").off("click.hcOffers").on("click.hcOffers", function (e) {
          e.preventDefault();
          $carousel.trigger("prev.owl.carousel");
        });
        $wrap.find(".hc-offers__mobile-nav-btn--next").off("click.hcOffers").on("click.hcOffers", function (e) {
          e.preventDefault();
          $carousel.trigger("next.owl.carousel");
        });
        $wrap.find(".hc-offers__mobile-nav").attr("aria-hidden", "false");
      }

      function destroyCarousel() {
        if ($carousel && $carousel.data("owl.carousel")) {
          $carousel.trigger("destroy.owl.carousel");
          $carousel.remove();
          $carousel = null;
        }
        $list.removeClass("is-mobile-hidden");
        $wrap.find(".hc-offers__mobile-nav").attr("aria-hidden", "true");
      }

      function syncMode() {
        if (mobileMq.matches) {
          buildCarousel();
        } else {
          destroyCarousel();
        }
      }

      syncMode();
      if (typeof mobileMq.addEventListener === "function") {
        mobileMq.addEventListener("change", syncMode);
      } else if (typeof mobileMq.addListener === "function") {
        mobileMq.addListener(syncMode);
      }
    })();

    // What's included cards: horizontal carousel on mobile only
    (function initHcIncludedMobileCarousel() {
      var $wrap = $(".hc-included__carousel-wrap");
      var $list = $(".hc-included__carousel-wrap .hc-included__grid");
      if (!$wrap.length || !$list.length || !$.fn.owlCarousel) return;

      var mobileMq = window.matchMedia("(max-width: 767px)");
      var $carousel = null;

      function buildCarousel() {
        if ($carousel && $carousel.data("owl.carousel")) return;

        var $cards = $();
        $list.children(".hc-included__card").each(function () {
          var $card = $(this).clone(false);
          if ($card.length) {
            $cards = $cards.add($card);
          }
        });
        if (!$cards.length) return;

        $carousel = $('<div class="hc-included__mobile-carousel owl-carousel owl-theme"></div>');
        $cards.each(function () {
          $carousel.append($('<div class="item"></div>').append(this));
        });
        $wrap.prepend($carousel);
        $list.addClass("is-mobile-hidden");

        $carousel.owlCarousel({
          loop: true,
          autoplay: true,
          autoplayTimeout: 5500,
          autoplayHoverPause: true,
          margin: 14,
          nav: false,
          dots: true,
          smartSpeed: 450,
          items: 1,
          stagePadding: 18
        });

        $wrap.find(".hc-included__mobile-nav-btn--prev").off("click.hcIncluded").on("click.hcIncluded", function (e) {
          e.preventDefault();
          $carousel.trigger("prev.owl.carousel");
        });
        $wrap.find(".hc-included__mobile-nav-btn--next").off("click.hcIncluded").on("click.hcIncluded", function (e) {
          e.preventDefault();
          $carousel.trigger("next.owl.carousel");
        });
        $wrap.find(".hc-included__mobile-nav").attr("aria-hidden", "false");
      }

      function destroyCarousel() {
        if ($carousel && $carousel.data("owl.carousel")) {
          $carousel.trigger("destroy.owl.carousel");
          $carousel.remove();
          $carousel = null;
        }
        $list.removeClass("is-mobile-hidden");
        $wrap.find(".hc-included__mobile-nav").attr("aria-hidden", "true");
      }

      function syncMode() {
        if (mobileMq.matches) {
          buildCarousel();
        } else {
          destroyCarousel();
        }
      }

      syncMode();
      if (typeof mobileMq.addEventListener === "function") {
        mobileMq.addEventListener("change", syncMode);
      } else if (typeof mobileMq.addListener === "function") {
        mobileMq.addListener(syncMode);
      }
    })();

    // Why choose us: carousel on desktop and mobile
    if ($(".hc-why-carousel").length && $.fn.owlCarousel) {
      $(".hc-why-carousel").each(function () {
        var elm = $(this);
        var $wrap = elm.closest(".hc-why-carousel-wrap");
        if (elm.data("owl.carousel")) return;
        elm.owlCarousel({
          loop: true,
          autoplay: true,
          autoplayTimeout: 5000,
          autoplayHoverPause: true,
          margin: 16,
          nav: false,
          dots: true,
          dotsContainer: $wrap.find(".hc-why-carousel__dots"),
          smartSpeed: 450,
          items: 1,
          responsive: {
            0: { items: 1 },
            768: { items: 2 }
          }
        });

        $wrap.find(".hc-why-carousel__nav--prev").on("click", function (e) {
          e.preventDefault();
          elm.trigger("prev.owl.carousel");
        });
        $wrap.find(".hc-why-carousel__nav--next").on("click", function (e) {
          e.preventDefault();
          elm.trigger("next.owl.carousel");
        });
      });
    }

    // Location page service cards: read more + mobile carousel
    (function initLocationServicesSection() {
      var $wraps = $(".location-services__carousel-wrap");
      if (!$wraps.length) return;

      function setupLocationServicesReadMoreDom($scope) {
        $scope.find(".blog-one__single__content").each(function () {
          var $content = $(this);
          if ($content.find(".location-services__read-more").length) return;

          var $p = $content.children("p").first();
          if (!$p.length) return;

          $p.addClass("location-services__desc");
          $(
            '<button type="button" class="location-services__read-more" aria-expanded="false">Read more</button>'
          ).insertAfter($p);
        });
      }

      $wraps.each(function () {
        var $wrap = $(this);
        var $grid = $wrap.find(".location-services__grid").first();
        if (!$grid.length) return;

        setupLocationServicesReadMoreDom($grid);

        $wrap.off("click.locReadMore", ".location-services__read-more").on("click.locReadMore", ".location-services__read-more", function (e) {
          e.preventDefault();
          e.stopPropagation();
          var $btn = $(this);
          var $p = $btn.prev(".location-services__desc");
          var expanded = $p.toggleClass("is-expanded").hasClass("is-expanded");
          $btn.text(expanded ? "Read less" : "Read more");
          $btn.attr("aria-expanded", expanded ? "true" : "false");
        });

        if (!$.fn.owlCarousel) return;

        var mobileMq = window.matchMedia("(max-width: 767px)");
        var $carousel = null;

        function buildCarousel() {
          if ($carousel && $carousel.data("owl.carousel")) return;

          var $cards = $();
          $grid.children('[class*="col-"]').each(function () {
            var $card = $(this).children(".blog-one__single").first().clone(false);
            if ($card.length) {
              $cards = $cards.add($card);
            }
          });
          if (!$cards.length) return;

          $carousel = $('<div class="location-services__mobile-carousel owl-carousel owl-theme"></div>');
          $cards.each(function () {
            $carousel.append($('<div class="item"></div>').append(this));
          });
          $wrap.prepend($carousel);
          $grid.addClass("is-mobile-hidden");

          $carousel.owlCarousel({
            loop: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            margin: 14,
            nav: false,
            dots: true,
            smartSpeed: 450,
            items: 1,
            stagePadding: 28
          });

          $wrap.find(".location-services__mobile-nav-btn--prev").off("click.locSvc").on("click.locSvc", function (e) {
            e.preventDefault();
            $carousel.trigger("prev.owl.carousel");
          });
          $wrap.find(".location-services__mobile-nav-btn--next").off("click.locSvc").on("click.locSvc", function (e) {
            e.preventDefault();
            $carousel.trigger("next.owl.carousel");
          });
          $wrap.find(".location-services__mobile-nav").attr("aria-hidden", "false");
          setupLocationServicesReadMoreDom($carousel);
        }

        function destroyCarousel() {
          if ($carousel && $carousel.data("owl.carousel")) {
            $carousel.trigger("destroy.owl.carousel");
            $carousel.remove();
            $carousel = null;
          }
          $grid.removeClass("is-mobile-hidden");
          $wrap.find(".location-services__mobile-nav").attr("aria-hidden", "true");
        }

        function syncMode() {
          if (mobileMq.matches) {
            buildCarousel();
          } else {
            destroyCarousel();
          }
        }

        syncMode();
        if (typeof mobileMq.addEventListener === "function") {
          mobileMq.addEventListener("change", syncMode);
        } else if (typeof mobileMq.addListener === "function") {
          mobileMq.addListener(syncMode);
        }
      });
    })();

  }


  function dynamicCurrentMenuClass(selector) {
    let FileName = window.location.href.split("/").reverse()[0];

    selector.find("li").each(function () {
      let anchor = $(this).find("a");
      if ($(anchor).attr("href") == FileName) {
        $(this).addClass("current");
      }
    });
    // if any li has .current elmnt add class
    selector.children("li").each(function () {
      if ($(this).find(".current").length) {
        $(this).addClass("current");
      }
    });
    // if no file name return
    if ("" == FileName) {
      selector.find("li").eq(0).addClass("current");
    }

  }



  if ($(".main-menu__list").length) {
    // dynamic current class
    let mainNavUL = $(".main-menu__list");
    dynamicCurrentMenuClass(mainNavUL);
  }


  if ($(".service-details__sidebar-service-list").length) {
    // dynamic current class
    let mainNavUL = $(".service-details__sidebar-service-list");
    dynamicCurrentMenuClass(mainNavUL);
  }


  if ($(".main-menu__list").length && $(".mobile-nav__container").length) {
    let navContent = document.querySelector(".main-menu__list").outerHTML;
    let mobileNavContainer = document.querySelector(".mobile-nav__container");
    mobileNavContainer.innerHTML = navContent;
  }



  if ($(".sticky-header__content").length) {
    let navContent = document.querySelector(".main-menu").innerHTML;
    let mobileNavContainer = document.querySelector(".sticky-header__content");
    mobileNavContainer.innerHTML = navContent;
  }


  if ($(".mobile-nav__container .main-menu__list").length) {
    let dropdownAnchor = $(
      ".mobile-nav__container .main-menu__list .dropdown > a"
    );
    dropdownAnchor.each(function () {
      let self = $(this);
      let toggleBtn = document.createElement("BUTTON");
      toggleBtn.setAttribute("aria-label", "dropdown toggler");
      toggleBtn.innerHTML = "<i class='fa fa-angle-down'></i>";
      self.append(function () {
        return toggleBtn;
      });
      self.find("button").on("click", function (e) {
        e.preventDefault();
        let self = $(this);
        self.toggleClass("expanded");
        self.parent().toggleClass("expanded");
        self.parent().parent().children("ul").slideToggle();
      });
    });
  }


  if ($(".mobile-nav__toggler").length) {
    $(".mobile-nav__toggler").on("click", function (e) {
      e.preventDefault();
      let isExpanded = $(".mobile-nav__wrapper").toggleClass("expanded").hasClass("expanded");
      $("body").toggleClass("locked");

      // While the mobile nav is open, stop slider autoplay
      if (isExpanded) {
        thmPauseSwiperAutoplay();
      } else {
        if (thmAutoplayPauseLocks === 0) {
          thmResumeSwiperAutoplay();
        }
      }
    });
  }

  //===Language switcher===
  if ($("#polyglot-language-options").length) {
    $('#polyglotLanguageSwitcher').polyglotLanguageSwitcher({
      effect: 'slide',
      animSpeed: 500,
      testMode: true,
      onChange: function (evt) {
        alert("The selected language is: " + evt.selectedItem);
      }

    });
  }


  //Fact Counter + Text Count
  if ($(".count-box").length) {
    $(".count-box").appear(
      function () {
        var $t = $(this),
          n = $t.find(".count-text").attr("data-stop"),
          r = parseInt($t.find(".count-text").attr("data-speed"), 10);

        if (!$t.hasClass("counted")) {
          $t.addClass("counted");
          $({
            countNum: $t.find(".count-text").text()
          }).animate({
            countNum: n
          }, {
            duration: r,
            easing: "linear",
            step: function () {
              $t.find(".count-text").text(Math.floor(this.countNum));
            },
            complete: function () {
              $t.find(".count-text").text(this.countNum);
            }
          });
        }
      }, {
        accY: 0
      }
    );
  }

  // Progress Bar
  if ($('.count-bar').length) {
    $('.count-bar').appear(function () {
      var el = $(this);
      var percent = el.data('percent');
      $(el).css('width', percent).addClass('counted');
    }, {
      accY: -50
    });

  }

  // Apartments Plan Tab Box
  if ($('.apartments-plan-tab-box').length) {
    $('.apartments-plan-tab-box .tabs-button-box .tab-btn-item').on('click', function (e) {
      e.preventDefault();
      var target = $($(this).attr('data-tab'));

      if ($(target).hasClass('actve-tab')) {
        return false;
      } else {
        $('.apartments-plan-tab-box .tabs-button-box .tab-btn-item').removeClass('active-btn-item');
        $(this).addClass('active-btn-item');
        $('.apartments-plan-tab-box .tabs-content-box .tab-content-box-item').removeClass('tab-content-box-item-active');
        $(target).addClass('tab-content-box-item-active');
      }
    });
  }

  // Search Form Tab Box
  if ($('.search-form-tab-box').length) {
    $('.search-form-tab-box .tabs-button-box .tab-btn-item').on('click', function (e) {
      e.preventDefault();
      var target = $($(this).attr('data-tab'));

      if ($(target).hasClass('actve-tab')) {
        return false;
      } else {
        $('.search-form-tab-box .tabs-button-box .tab-btn-item').removeClass('active-btn-item');
        $(this).addClass('active-btn-item');
        $('.search-form-tab-box .tabs-content-box .tab-content-box-item').removeClass('tab-content-box-item-active');
        $(target).addClass('tab-content-box-item-active');
      }
    });
  }


  // ===Portfolio Grid===
  function projectMasonaryLayout() {
    if ($('.masonary-layout').length) {
      $('.masonary-layout').isotope({
        layoutMode: 'masonry'
      });
    }
    if ($('.post-filter').length) {
      $('.post-filter li').children('.filter-text').on('click', function () {
        var Self = $(this);
        var selector = Self.parent().attr('data-filter');
        $('.post-filter li').removeClass('active');
        Self.parent().addClass('active');
        $('.filter-layout').isotope({
          filter: selector,
          animationOptions: {
            duration: 500,
            easing: 'linear',
            queue: false
          }
        });
        return false;
      });
    }

    if ($('.post-filter.has-dynamic-filters-counter').length) {
      // var allItem = $('.single-filter-item').length;
      var activeFilterItem = $('.post-filter.has-dynamic-filters-counter').find('li');
      activeFilterItem.each(function () {
        var filterElement = $(this).data('filter');
        var count = $('.filter-layout').find(filterElement).length;
        $(this).children('.filter-text').append('<span class="count">' + count + '</span>');
      });
    };
  }

  //======Circle Progress
  if ($(".circle-progress").length) {
    $(".circle-progress").appear(function () {
      let circleProgress = $(".circle-progress");
      circleProgress.each(function () {
        let progress = $(this);
        let progressOptions = progress.data("options");
        progress.circleProgress(progressOptions);
      });
    });
  }

  //======Circle Progress
  if ($('.dial').length) {
    $('.dial').appear(function () {
      var elm = $(this);
      var color = elm.attr('data-fgColor');
      var perc = elm.attr('value');
      elm.knob({
        'value': 0,
        'min': 0,
        'max': 100,
        'skin': 'tron',
        'readOnly': true,
        'thickness': 0.1,
        'dynamicDraw': true,
        'displayInput': false
      });
      $({
        value: 0
      }).animate({
        value: perc
      }, {
        duration: 2000,
        easing: 'swing',
        progress: function () {
          elm.val(Math.ceil(this.value)).trigger('change');
        }
      });
      $(this).append(function () {});
    }, {
      accY: 20
    });
  }



  //====== Magnific Popup
  if ($(".video-popup").length) {
    $(".video-popup").magnificPopup({
      type: "iframe",
      mainClass: "mfp-fade",
      removalDelay: 160,
      preloader: true,

      fixedContentPos: false
    });
  }



  //====== Img Popup
  if ($(".img-popup").length) {
    var groups = {};
    $(".img-popup").each(function () {
      var id = parseInt($(this).attr("data-group"), 10);

      if (!groups[id]) {
        groups[id] = [];
      }

      groups[id].push(this);
    });

    $.each(groups, function () {
      $(this).magnificPopup({
        type: "image",
        closeOnContentClick: true,
        closeBtnInside: false,
        gallery: {
          enabled: true
        }
      });
    });
  }


  //Bottom Parallax
  if ($('.bottom-parallax').length) {
    var windowpos = $(window).scrollTop();
    var siteFooter = $('.footer-area').height();
    var sitebodyHeight = $('.boxed_wrapper').height();
    var finalHeight = sitebodyHeight - siteFooter - 1000;
    if (windowpos >= finalHeight) {
      $('body').addClass('parallax-visible');
    } else {
      $('body').removeClass('parallax-visible');
    }
  }



  if ($("#video-gallery-items-thumb").length) {
    let learningcoursesThumb = new Swiper("#video-gallery-items-thumb", {
      speed: 1400,
      watchSlidesVisibility: true,
      watchSlidesProgress: true,
      loop: false,
      autoplay: {
        delay: 5000
      },
      "breakpoints": {

        "0": {
          "spaceBetween": 30,
          "slidesPerView": 1
        },
        "375": {
          "spaceBetween": 30,
          "slidesPerView": 1
        },
        "575": {
          "spaceBetween": 20,
          "slidesPerView": 2
        },
        "767": {
          "spaceBetween": 20,
          "slidesPerView": 3
        },
        "991": {
          "spaceBetween": 20,
          "slidesPerView": 4
        },
        "1199": {
          "spaceBetween": 30,
          "slidesPerView": 5
        }

      }

    });

    let learningcoursesCarousel = new Swiper("#video-gallery-items-carousel", {
      observer: true,
      observeParents: true,
      loop: false,
      speed: 1400,
      mousewheel: true,
      slidesPerView: 1,
      spaceBetween: 0,
      autoplay: {
        delay: 5000000000
      },
      thumbs: {
        swiper: learningcoursesThumb
      },
      pagination: {
        el: '#learning-courses-carousel-pagination',
        type: 'bullets',
        clickable: true
      },

      "navigation": {
        "nextEl": "#learning-courses__swiper-button-next",
        "prevEl": "#learning-courses__swiper-button-prev"
      },

    });

  }

  // AOS Animation
  if ($("[data-aos]").length) {
    AOS.init({
      duration: '1000',
      disable: 'false',
      easing: 'ease',
      mirror: true
    });
  }


  //Contact Form Validation
  if ($("#contact-form").length) {
    var $contactForm = $("#contact-form");
    var action = ($contactForm.attr("action") || "").toLowerCase();
    var isGoogleForm = action.indexOf("docs.google.com/forms") !== -1 || action.indexOf("google.com/forms") !== -1 || !!$contactForm.data("googleForm");

    // For Google Forms we only validate; we do NOT submit via AJAX (cross-domain).
    if (isGoogleForm) {
      $contactForm.validate();
    } else {
      $contactForm.validate({
        submitHandler: function (form) {
          var form_btn = $(form).find('button[type="submit"]');
          var form_result_div = '#form-result';
          $(form_result_div).remove();
          form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
          var form_btn_old_msg = form_btn.html();
          form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
          $(form).ajaxSubmit({
            dataType: 'json',
            success: function (data) {
              if (data.status = 'true') {
                $(form).find('.form-control').val('');
              }
              form_btn.prop('disabled', false).html(form_btn_old_msg);
              $(form_result_div).html(data.message).fadeIn('slow');
              setTimeout(function () {
                $(form_result_div).fadeOut('slow')
              }, 6000);
            }
          });
        }
      });
    }
  }


  if ($("#datepicker").length) {
    $("#datepicker").datepicker();
  }


  if ($.fn.ptTimeSelect && $('input[name="time"]').length) {
    $('input[name="time"]').ptTimeSelect();
  }


  if ($(".odometer").length) {
    var odo = $(".odometer");
    odo.each(function () {
      $(this).appear(function () {
        var countNumber = $(this).attr("data-count");
        $(this).html(countNumber);
      });
    });
  }


  if ($(".banner-bg-slide").length) {
    $(".banner-bg-slide").each(function () {
      var Self = $(this);
      var bgSlideOptions = Self.data("options");
      var bannerTwoSlides = Self.vegas(bgSlideOptions);
    });
  }


  if ($(".wow").length) {
    var wow = new WOW({
      boxClass: "wow", // animated element css class (default is wow)
      animateClass: "animated", // animation css class (default is animated)
      mobile: false, // reduce animation overhead on mobile devices
      live: true // act on asynchronously loaded content (default is true)
    });
    wow.init();
  }


  if ($(".search-toggler").length) {
    $(".search-toggler").on("click", function (e) {
      e.preventDefault();
      $(".search-popup").toggleClass("active");
      $(".mobile-nav__wrapper").removeClass("expanded");
      $("body").toggleClass("locked");
    });
  }


  // ===Testimonials Two Carousel===
  if ($("#testimonial-two__thumb").length) {
    let testimonialsThumb = new Swiper("#testimonial-two__thumb", {
      slidesPerView: 3,
      spaceBetween: 20,
      speed: 1400,
      watchSlidesVisibility: true,
      watchSlidesProgress: true,
      loop: true,
      autoplay: {
        delay: 5000
      }
    });

    let testimonialsCarousel = new Swiper("#testimonial-two__carousel", {
      observer: true,
      observeParents: true,
      speed: 1400,
      mousewheel: true,
      slidesPerView: 1,
      autoplay: {
        delay: 5000
      },
      thumbs: {
        swiper: testimonialsThumb
      },
      pagination: {
        el: '#testimonial-two__carousel-pagination',
        type: 'bullets',
        clickable: true
      },
    });
  }


  //Tabs Box
  if ($(".tabs-box").length) {
    $(".tabs-box .tab-buttons .tab-btn").on("click", function (e) {
      e.preventDefault();
      var target = $($(this).attr("data-tab"));

      if ($(target).is(":visible")) {
        return false;
      } else {
        target
          .parents(".tabs-box")
          .find(".tab-buttons")
          .find(".tab-btn")
          .removeClass("active-btn");
        $(this).addClass("active-btn");
        target
          .parents(".tabs-box")
          .find(".tabs-content")
          .find(".tab")
          .fadeOut(0);
        target
          .parents(".tabs-box")
          .find(".tabs-content")
          .find(".tab")
          .removeClass("active-tab");
        $(target).fadeIn(300);
        $(target).addClass("active-tab");
      }
    });
  }

  // ===Checkout Payment===
  if ($(".checkout__payment__title").length) {

    $(".checkout__payment__item").find('.checkout__payment__content').hide();
    $(".checkout__payment__item--active").find('.checkout__payment__content').show();

    $(".checkout__payment__title").on("click", function (e) {
      e.preventDefault();


      $(this).parents('.checkout__payment').find('.checkout__payment__item').removeClass("checkout__payment__item--active");
      $(this).parents(".checkout__payment").find(".checkout__payment__content").slideUp();

      $(this).parent().addClass("checkout__payment__item--active");
      $(this).parent().find(".checkout__payment__content").slideDown();

    })
  }


  // ===Shop Details One Thumb Carousel===
  if ($("#shop-details-one__thumb").length) {
    let testimonialsThumb = new Swiper("#shop-details-one__thumb", {
      slidesPerView: 3,
      spaceBetween: 10,
      speed: 1400,
      watchSlidesVisibility: true,
      watchSlidesProgress: true,
      loop: true,
      "navigation": {
        "nextEl": "#shop-details-thumb__swiper-button-next",
        "prevEl": "#shop-details-thumb__swiper-button-prev"
      },
      autoplay: {
        delay: 5000
      }
    });

    let testimonialsCarousel = new Swiper("#shop-details-one__carousel", {
      observer: true,
      observeParents: true,
      loop: true,
      speed: 1400,
      mousewheel: true,
      slidesPerView: 1,
      autoplay: {
        delay: 5000
      },
      thumbs: {
        swiper: testimonialsThumb
      },
      pagination: {
        el: '#testimonials-one__carousel-pagination',
        type: 'bullets',
        clickable: true
      },

      "navigation": {
        "nextEl": "#shop-details-top__swiper-button-next",
        "prevEl": "#shop-details-top__swiper-button-prev"
      },

    });
  }

  //=== CountDownTimer===
  if ($('.time-countdown').length) {
    $('.time-countdown').each(function () {
      var Self = $(this);
      var countDate = Self.data('countdown-time'); // getting date

      Self.countdown(countDate, function (event) {
        $(this).html('<h2>' + event.strftime('%D : %H : %M : %S') + '</h2>');
      });
    });
  };

  if ($('.time-countdown-two').length) {
    $('.time-countdown-two').each(function () {
      var Self = $(this);
      var countDate = Self.data('countdown-time'); // getting date

      Self.countdown(countDate, function (event) {
        $(this).html('<li> <div class="box"> <span class="days">' + event.strftime('%D') + '</span> <span class="timeRef">days</span> </div> </li> <li> <div class="box"> <span class="hours">' + event.strftime('%H') + '</span> <span class="timeRef clr-1">hours</span> </div> </li> <li> <div class="box"> <span class="minutes">' + event.strftime('%M') + '</span> <span class="timeRef clr-2">min</span> </div> </li> <li> <div class="box"> <span class="seconds">' + event.strftime('%S') + '</span> <span class="timeRef clr-3">sec</span> </div> </li>');
      });
    });
  };


  //Accordion Box
  if ($('.accordion-box').length) {
    $(".accordion-box").off('click.accToggle').on('click.accToggle', '.acc-btn', function (e) {
      e.preventDefault();

      var $btn = $(this);
      var $outerBox = $btn.closest('.accordion-box');
      var $target = $btn.closest('.accordion');
      var $content = $btn.next('.acc-content');

      // Clicking an open item closes it
      if ($btn.hasClass('active')) {
        $btn.removeClass('active');
        $target.removeClass('active-block');
        $content.stop(true, true).slideUp(300);
        return;
      }

      // Open this item and close others in the same column
      $outerBox.find('.accordion .acc-btn').removeClass('active');
      $outerBox.find('.accordion').removeClass('active-block');
      $outerBox.find('.accordion > .acc-content').stop(true, true).slideUp(300);

      $btn.addClass('active');
      $target.addClass('active-block');
      $content.stop(true, true).slideDown(300);
    });
  }


  function SmoothMenuScroll() {
    var anchor = $(".scrollToLink");
    if (anchor.length) {
      anchor.children("a").bind("click", function (event) {
        if ($(window).scrollTop() > 10) {
          var headerH = "90";
        } else {
          var headerH = "90";
        }
        var target = $(this);
        $("html, body")
          .stop()
          .animate({
              scrollTop: $(target.attr("href")).offset().top - headerH + "px"
            },
            1200,
            "easeInOutExpo"
          );
        anchor.removeClass("current");
        anchor.removeClass("current-menu-ancestor");
        anchor.removeClass("current_page_item");
        anchor.removeClass("current-menu-parent");
        target.parent().addClass("current");
        event.preventDefault();
      });
    }
  }
  SmoothMenuScroll();


  function OnePageMenuScroll() {
    var windscroll = $(window).scrollTop();
    if (windscroll >= 117) {
      var menuAnchor = $(".one-page-scroll-menu .scrollToLink").children("a");
      menuAnchor.each(function () {
        var sections = $(this).attr("href");
        $(sections).each(function () {
          if ($(this).offset().top <= windscroll + 100) {
            var Sectionid = $(sections).attr("id");
            $(".one-page-scroll-menu").find("li").removeClass("current");
            $(".one-page-scroll-menu").find("li").removeClass("current-menu-ancestor");
            $(".one-page-scroll-menu").find("li").removeClass("current_page_item");
            $(".one-page-scroll-menu").find("li").removeClass("current-menu-parent");
            $(".one-page-scroll-menu")
              .find("a[href*=\\#" + Sectionid + "]")
              .parent()
              .addClass("current");
          }
        });
      });
    } else {
      $(".one-page-scroll-menu li.current").removeClass("current");
      $(".one-page-scroll-menu li:first").addClass("current");
    }
  }



  // window load event
  $(window).on("load", function () {


    thmSwiperInit();
    thmOwlInit();
    projectMasonaryLayout();
    handlePreloader();
    thmBindMobileNavPauseOnHover();


    //Jquery Spinner / Quantity Spinner
    if ($('.quantity-spinner').length) {
      $("input.quantity-spinner").TouchSpin({
        verticalbuttons: true
      });
    }

    //Jquery Curved Circle
    if ($.fn.circleType && $('.curved-circle').length) {
      $('.curved-circle').circleType({
        position: 'absolute',
        dir: 1,
        radius: 70,
        forceHeight: true,
        forceWidth: true
      });
    }

  });




  // window scroll event
  $(window).on("scroll", function () {

    //Stricked Menu Fixed
    if ($(".stricked-menu").length) {
      var headerScrollPos = 130;
      var stricky = $(".stricked-menu");
      if ($(window).scrollTop() > headerScrollPos) {
        stricky.addClass("stricky-fixed");
      } else if ($(this).scrollTop() <= headerScrollPos) {
        stricky.removeClass("stricky-fixed");
      }
    }

    //Scroll To Top
    if ($(".scroll-to-top").length) {
      var strickyScrollPos = 100;
      if ($(window).scrollTop() > strickyScrollPos) {
        $(".scroll-to-top").fadeIn(500);
      } else if ($(this).scrollTop() <= strickyScrollPos) {
        $(".scroll-to-top").fadeOut(500);
      }
    }

    OnePageMenuScroll();

  });




  if ($(".scroll-to-target").length) {
    $(".scroll-to-target").on("click", function () {
      var target = $(this).attr("data-target");
      // animate
      $("html, body").animate({
          scrollTop: $(target).offset().top
        },
        100
      );

      return false;
    });
  }


  $(document).ready(function () {
    if ($.fn.niceSelect) {
      $('select:not(.ignore)').niceSelect();
    }
  });



  // Jquery Dependency
  $("input[data-type='currency']").on({
    keyup: function () {
      formatCurrency($(this));
    },
    blur: function () {
      formatCurrency($(this), "blur");
    }
  });


  function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  }


  function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.

    // get input value
    var input_val = input.val();

    // don't validate empty input
    if (input_val === "") {
      return;
    }

    // original length
    var original_len = input_val.length;

    // initial caret position 
    var caret_pos = input.prop("selectionStart");

    // check for decimal
    if (input_val.indexOf(".") >= 0) {

      // get position of first decimal
      // this prevents multiple decimals from
      // being entered
      var decimal_pos = input_val.indexOf(".");

      // split number by decimal point
      var left_side = input_val.substring(0, decimal_pos);
      var right_side = input_val.substring(decimal_pos);

      // add commas to left side of number
      left_side = formatNumber(left_side);

      // validate right side
      right_side = formatNumber(right_side);

      // On blur make sure 2 numbers after decimal
      if (blur === "blur") {
        right_side += "00";
      }

      // Limit decimal to only 2 digits
      right_side = right_side.substring(0, 2);

      // join number by .
      input_val = "$" + left_side + "." + right_side;

    } else {
      // no decimal entered
      // add commas to number
      // remove all non-digits
      input_val = formatNumber(input_val);
      input_val = "$" + input_val;

      // final formatting
      if (blur === "blur") {
        input_val += ".00";
      }
    }

    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
  }









})(jQuery);