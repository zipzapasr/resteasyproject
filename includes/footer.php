<style>
/* =============================================
   REST EASY FOOTER — Custom Styles
   ============================================= */
.re-footer {
    background-color: #0d1f45;
    color: #cdd5e0;
    font-family: 'Poppins', sans-serif;
    padding: 26px 0 0;
}

/* --- About column --- */
.re-footer__logo {
    margin-bottom: 20px;
}
.re-footer__logo img {
    max-width: 130px;
    height: auto;
}
.re-footer__about-text {
    font-size: 14px;
    line-height: 1.75;
    color: #b0bac8;
    margin-bottom: 24px;
}
.re-footer__contact-list {
    list-style: none;
    padding: 0;
    margin: 0 0 22px;
}
.re-footer__contact-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
    color: #cdd5e0;
    margin-bottom: 10px;
}
.re-footer__contact-list li i {
    color: #e07b2a;
    font-size: 15px;
    margin-top: 9px;
    flex-shrink: 0;
}
.re-footer__contact-list a {
    color: #cdd5e0;
    text-decoration: none;
    transition: color 0.2s;
}
.re-footer__contact-list a:hover { color: #e07b2a; }

/* Social icons */
.re-footer__social {
    display: flex;
    gap: 10px;
    list-style: none;
    padding: 0;
    margin: 0;
}
.re-footer__social li a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1.5px solid rgba(255,255,255,0.25);
    color: #cdd5e0;
    font-size: 15px;
    text-decoration: none;
    transition: border-color 0.2s, color 0.2s, background 0.2s;
}
.re-footer__social li a:hover {
    border-color: #e07b2a;
    color: #e07b2a;
    background: rgba(224,123,42,0.08);
}

/* --- Section headings --- */
.re-footer__section-title {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #e07b2a;
    margin-bottom: 18px;
}

/* --- Quick Links column --- */
.re-footer__links {
    list-style: none;
    padding: 0;
    margin: 0;
}
.re-footer__links li {
    margin-bottom: 7px;
}
.re-footer__links li a {
    font-size: 14px;
    color: #cdd5e0;
    text-decoration: none;
    transition: color 0.2s;
}
.re-footer__links li a:hover { color: #e07b2a; }

/* --- Services grid --- */
.re-footer__services-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0;
    list-style: none;
    padding: 0;
    margin: 0;
}
.re-footer__services-grid li {
    width: 25%;
    padding: 0 0 8px;
    box-sizing: border-box; line-height: 21px;
}
.re-footer__services-grid li a {
    font-size: 14px;
    color: #cdd5e0;
    text-decoration: none;
    transition: color 0.2s;
}
.re-footer__services-grid li a:hover { color: #e07b2a; }

/* --- Areas We Serve grid --- */
.re-footer__areas-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0;
    list-style: none;
    padding: 0;
    margin: 0;
}
.re-footer__areas-grid li {
    width: 25%; /* 8 per row */
    padding: 0 0 3px;
    box-sizing: border-box;
    line-height: 21px;
}
.re-footer__areas-grid li a {
    font-size: 13px;
    color: #cdd5e0;
    text-decoration: none;
    transition: color 0.2s;
}
.re-footer__areas-grid li a:hover { color: #e07b2a; }
.re-footer__areas-grid li span {
    font-size: 13px;
    color: #cdd5e0;
}

/* Divider between services and areas — hidden */
.re-footer__divider {
    display: none;
}

/* --- Bottom bar --- */
.re-footer__bottom {
    background-color: #ff5e15;
    padding: 14px 0;
    margin-top: 40px;
}
.re-footer__bottom-inner {
    /*display: flex;*/
    align-items: center;
    text-align: center;
    /*justify-content: space-between;*/
    flex-wrap: wrap;
    gap: 8px;
}
.re-footer__bottom-copy {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #fff;
    margin: 0;
}
.re-footer__bottom-copy a { color: #fff; text-decoration: none; }
.re-footer__bottom-copy a:hover { text-decoration: underline; }
.re-footer__bottom-terms {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #fff;
    text-decoration: none;
    white-space: nowrap;
}
.re-footer__bottom-terms:hover { text-decoration: underline; color: #fff; }

/* ===========================
   MOBILE ACCORDION (≤ 767px)
   =========================== */
@media (max-width: 767px) {

    .re-footer { padding: 36px 0 0; }

    /* Hide the multi-column service + areas content */
    .re-footer__services-grid,
    .re-footer__areas-grid { display: none; }

    /* Services and areas sections use accordion on mobile */
    .re-footer__accordion-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        padding: 14px 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .re-footer__accordion-header .re-footer__section-title { margin: 0; }
    .re-footer__accordion-chevron {
        color: #cdd5e0;
        font-size: 14px;
        transition: transform 0.25s;
    }
    .re-footer__accordion.is-open .re-footer__accordion-chevron {
        transform: rotate(180deg);
    }
    .re-footer__accordion-body {
        display: none;
        padding: 12px 0 4px;
    }
    .re-footer__accordion.is-open .re-footer__accordion-body {
        display: block;
    }

    /* On mobile show services/areas as a single-column list */
    .re-footer__services-grid.mob-list,
    .re-footer__areas-grid.mob-list {
        display: flex;
        flex-direction: column;
    }
    .re-footer__services-grid.mob-list li,
    .re-footer__areas-grid.mob-list li {
        width: 100%;
        padding-bottom: 8px;
    }

    /* Quick links — always visible on mobile (no accordion) */
    .re-footer__quick-links-section .re-footer__accordion-header {
        display: flex;
    }
    .re-footer__quick-links-section.is-open .re-footer__links { display: block; }
    .re-footer__quick-links-section .re-footer__links { display: none; padding-top: 10px; }
    .re-footer__quick-links-section.is-open .re-footer__accordion-chevron {
        transform: rotate(180deg);
    }

    /* Bottom bar stacks on mobile */
    .re-footer__bottom-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }

    .re-footer__divider { margin: 16px 0; }

    /* Social icons bigger on mobile */
    .re-footer__social { margin-top: 20px; margin-bottom: 6px; }
}

@media (min-width: 768px) {
    /* Desktop: hide accordion controls */
    .re-footer__accordion-header { cursor: default; }
    .re-footer__accordion-chevron { display: none; }
    .re-footer__accordion-body { display: block !important; }
    /* Quick links section title not clickable */
    .re-footer__quick-links-section .re-footer__accordion-header { display: block; }
    .re-footer__quick-links-section .re-footer__links { display: block !important; }
}

/* Tablet: areas grid fewer per row */
@media (min-width: 768px) and (max-width: 1199px) {
    .re-footer__areas-grid li { width: 20%; }
    .re-footer__services-grid li { width: 50%; }
    .re-footer__divider { display: none; }
}
</style>

<footer class="re-footer">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5">

            <!-- ===== Column 1: About + Contact + Social ===== -->
            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                <div class="re-footer__logo">
                    <a href="index.php">
                        <img src="assets/images/resources/main-logo2.png" alt="Rest Easy Services Logo" loading="lazy">
                    </a>
                </div>
                <p class="re-footer__about-text">
                    Rest Easy Services provides reliable cleaning, linen, gardening, and maintenance services across
                    Melbourne &amp; the Mornington Peninsula. With experience since 2012, our friendly and professional
                    team delivers thorough service you can trust.
                </p>
                <div><strong>Locate Us</strong></div>
                <ul class="re-footer__contact-list">
                    <li>
                        
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        <a href="https://www.google.com/maps/place/?q=place_id:ChIJ20vQ2H_K1WoROevGTy-Tj8Y" target="_blank" rel="noopener noreferrer">
                            6/7 Suffolk St, Capel Sound VIC 3940
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt" aria-hidden="true"></i>
                        <a href="tel:0429780896">0429 780 896</a>
                    </li>
                    <li>
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:sales@resteasyservices.com.au">sales@resteasyservices.com.au</a>
                    </li>
                </ul>
           
            </div>

            <!-- ===== Column 2: Quick Links ===== -->
            <div class="col-lg-2 col-md-12 mb-4 mb-lg-0">
                <div class="re-footer__quick-links-section re-footer__accordion" id="re-footer-quicklinks">
                    <div class="re-footer__accordion-header" onclick="reFooterToggle('re-footer-quicklinks')">
                        <p class="re-footer__section-title">Quick Links</p>
                        <i class="re-footer__accordion-chevron fas fa-chevron-down"></i>
                    </div>
                    <div class="re-footer__accordion-body">
                        <ul class="re-footer__links">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about-us">About Us</a></li>
                            <li><a href="reviews">Reviews</a></li>
                            <li><a href="contact">Contact</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Bookings</a></li>
                             
                        </ul>
                             <ul class="re-footer__social">
                    <li><a href="https://www.facebook.com/resteasyservicesau/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="https://www.instagram.com/resteasyservicesau/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="https://www.linkedin.com/company/rest-easy-services-australia" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>
                </ul>
                    </div>
                </div>
            </div>

            <!-- ===== Column 3+4: Services + Areas We Serve ===== -->
            <div class="col-lg-7 col-md-12">

                <!-- Services -->
                <div class="re-footer__accordion" id="re-footer-services">
                    <div class="re-footer__accordion-header" onclick="reFooterToggle('re-footer-services')">
                        <p class="re-footer__section-title">Services</p>
                        <i class="re-footer__accordion-chevron fas fa-chevron-down"></i>
                    </div>
                    <div class="re-footer__accordion-body">
                        <!-- Desktop: 3-column grid -->
                        <ul class="re-footer__services-grid d-none d-md-flex">
                            <li><a href="house-cleaning">House Cleaning</a></li>
                            <li><a href="carpet-cleaning">Carpet Cleaning</a></li>
                            <li><a href="linen-hire">Linen Hire</a></li>
                            <li><a href="vacate-cleaning">Vacate Cleaning</a></li>
                            <li><a href="pressure-washing">Pressure Washing</a></li>
                            <li><a href="window-cleaning">Window Cleaning</a></li>
                            <li><a href="airbnb-cleaning">Airbnb Cleaning</a></li>
                            <li><a href="car-park-cleaning">Car Park Cleaning</a></li>
                            <li><a href="ndis-cleaning-services">NDIS Cleaning</a></li>
                            <li><a href="house-maintenance">House Maintenance</a></li>
                            <li><a href="locksmith-services">Locksmith</a></li>
                            <li><a href="garden-maintenance-services">Gardening</a></li>
                        </ul>
                        <!-- Mobile: single column list -->
                        <ul class="re-footer__services-grid mob-list d-flex d-md-none">
                            <li><a href="house-cleaning">House Cleaning</a></li>
                            <li><a href="vacate-cleaning">Vacate Cleaning</a></li>
                            <li><a href="airbnb-cleaning">Airbnb Cleaning</a></li>
                            <li><a href="carpet-cleaning">Carpet Cleaning</a></li>
                            <li><a href="pressure-washing">Pressure Washing</a></li>
                            <li><a href="car-park-cleaning">Car Park Cleaning</a></li>
                            <li><a href="linen-hire">Linen Hire</a></li>
                            <li><a href="window-cleaning">Window Cleaning</a></li>
                            <li><a href="ndis-cleaning-services">NDIS Cleaning</a></li>
                            <li><a href="house-maintenance">House Maintenance</a></li>
                            <li><a href="locksmith-services">Locksmith</a></li>
                            <li><a href="garden-maintenance-services">Gardening</a></li>
                        </ul>
                    </div>
                </div>

                <hr class="re-footer__divider">

                <!-- Areas We Serve -->
                <div class="re-footer__accordion" id="re-footer-areas">
                    <div class="re-footer__accordion-header" onclick="reFooterToggle('re-footer-areas')">
                        <p class="re-footer__section-title">Areas We Serve</p>
                        <i class="re-footer__accordion-chevron fas fa-chevron-down"></i>
                    </div>
                    <div class="re-footer__accordion-body">
                        <!-- Desktop: 8-per-row grid -->
                        <ul class="re-footer__areas-grid d-none d-md-flex">
                            <li><a href="cleaning-services-rye">Rye</a></li>
                            <li><a href="cleaning-services-rosebud">Rosebud</a></li>
                            <li><a href="cleaning-services-dromana">Dromana</a></li>
                            <li><a href="cleaning-services-mount-eliza">Mount Eliza</a></li>
                            <li><a href="cleaning-services-mornington">Mornington</a></li>
                            <li><span>Mount Martha</span></li>
                            <li><span>Safety Beach</span></li>
                            <li><a href="cleaning-services-somerville">Somerville</a></li>
                            <li><a href="cleaning-services-carrum-downs">Carrum Downs</a></li>
                            <li><a href="cleaning-services-langwarrin">Langwarrin</a></li>
                            <li><a href="cleaning-services-brighton">Brighton</a></li>
                            <li><a href="cleaning-services-patterson-lakes">Patterson Lakes</a></li>
                            <li><a href="cleaning-services-skye">Skye</a></li>
                            <li><a href="cleaning-services-glen-waverley">Glen Waverley</a></li>
                            <li><a href="cleaning-services-wheelers-hill">Wheelers Hill</a></li>
                            <li><a href="cleaning-services-frankston">Frankston</a></li>
                            <li><a href="cleaning-services-mt-waverley">Mt Waverley</a></li>
                            <li><a href="cleaning-services-toorak">Toorak</a></li>
                            <li><a href="cleaning-services-doncaster">Doncaster</a></li>
                            <li><a href="cleaning-services-donvale">Donvale</a></li>
                            <li><a href="cleaning-services-boxhill">Boxhill</a></li>
                            <li><a href="cleaning-services-burwood">Burwood</a></li>
                            <li><a href="cleaning-services-vermont">Vermont</a></li>
                            <li><a href="cleaning-services-camberwell">Camberwell</a></li>
                            <li><a href="cleaning-services-chelsea-heights">Chelsea Heights</a></li>
                        </ul>
                        <!-- Mobile: single column list -->
                        <ul class="re-footer__areas-grid mob-list d-flex d-md-none">
                            <li><a href="cleaning-services-rye">Rye</a></li>
                            <li><a href="cleaning-services-rosebud">Rosebud</a></li>
                            <li><a href="cleaning-services-dromana">Dromana</a></li>
                            <li><a href="cleaning-services-mount-eliza">Mount Eliza</a></li>
                            <li><a href="cleaning-services-mornington">Mornington</a></li>
                            <li><span>Mount Martha</span></li>
                            <li><span>Safety Beach</span></li>
                            <li><a href="cleaning-services-somerville">Somerville</a></li>
                            <li><a href="cleaning-services-carrum-downs">Carrum Downs</a></li>
                            <li><a href="cleaning-services-langwarrin">Langwarrin</a></li>
                            <li><a href="cleaning-services-brighton">Brighton</a></li>
                            <li><a href="cleaning-services-patterson-lakes">Patterson Lakes</a></li>
                            <li><a href="cleaning-services-skye">Skye</a></li>
                            <li><a href="cleaning-services-glen-waverley">Glen Waverley</a></li>
                            <li><a href="cleaning-services-wheelers-hill">Wheelers Hill</a></li>
                            <li><a href="cleaning-services-frankston">Frankston</a></li>
                            <li><a href="cleaning-services-mt-waverley">Mt Waverley</a></li>
                            <li><a href="cleaning-services-toorak">Toorak</a></li>
                            <li><a href="cleaning-services-doncaster">Doncaster</a></li>
                            <li><a href="cleaning-services-donvale">Donvale</a></li>
                            <li><a href="cleaning-services-boxhill">Boxhill</a></li>
                            <li><a href="cleaning-services-burwood">Burwood</a></li>
                            <li><a href="cleaning-services-vermont">Vermont</a></li>
                            <li><a href="cleaning-services-camberwell">Camberwell</a></li>
                            <li><a href="cleaning-services-chelsea-heights">Chelsea Heights</a></li>
                        </ul>
                    </div>
                </div>

            </div>
            <!-- end col-7 -->

        </div>
        <!-- end row -->
    </div>
    <!-- end container -->

    <!-- ===== Bottom Bar ===== -->
    <div class="re-footer__bottom">
        <div class="container-fluid px-4 px-lg-5">
            <div class="re-footer__bottom-inner">
                <p class="re-footer__bottom-copy">
                    Copyright &copy; 2026 All Rights Reserved. Website Designed &amp; Developed by <a href="https://www.webcooks.in/" target="_blank" rel="noopener noreferrer">Webcooks</a>
                </p>
                <a href="termsandcondition" class="re-footer__bottom-terms">Terms &amp; Conditions</a> | <a href="privacypolicy" class="re-footer__bottom-terms">Privacy Policy</a>
            </div>
        </div>
    </div>

</footer>

<script>
/* Footer accordion — mobile only */
function reFooterToggle(id) {
    if (window.innerWidth >= 768) return;
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('is-open');
}

/* On desktop always ensure bodies are visible; on resize reset */
(function () {
    function syncFooterAccordions() {
        var sections = document.querySelectorAll('.re-footer__accordion');
        if (window.innerWidth >= 768) {
            sections.forEach(function (s) {
                s.classList.remove('is-open');
                var body = s.querySelector('.re-footer__accordion-body');
                if (body) body.style.display = '';
            });
        }
    }
    document.addEventListener('DOMContentLoaded', syncFooterAccordions);
    window.addEventListener('resize', function () {
        clearTimeout(window._reFooterResize);
        window._reFooterResize = setTimeout(syncFooterAccordions, 150);
    });
})();
</script>

<a href="#" data-target="html" class="scroll-to-target scroll-to-top">
    <i class="icon-down-arrow"></i>
</a>



<!-- Mobile Nav Wrapper - Must be before scripts for proper initialization -->
<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler">
            <i class="icon-plus"></i>
        </span>
        <div class="logo-box">
            <a href="index.php" aria-label="logo image">
                <img src="assets/images/resources/main-logo2.png" alt="Rest Easy Services logo" />
            </a>
        </div>
        <div class="mobile-nav__container"></div>
        <ul class="mobile-nav__contact list-unstyled">
            <li>
                <i class="fa fa-envelope"></i>
                <a href="mailto:sales@resteasyservices.com.au">sales@resteasyservices.com.au</a>
            </li>
            <li>
                <i class="fa fa-phone-alt"></i>
                <a href="tel:0429780896">0429 780 896</a>
            </li>
        </ul>
        <div class="mobile-nav__social">
            <a href="https://www.facebook.com/resteasyservicesau/" class="fab fa-facebook-square"></a>
            <a href="https://www.instagram.com/resteasyservicesau/" class="fab fa-instagram"></a>
            <a href="https://www.linkedin.com/in/rest-easy-services/" class="fab fa-linkedin-in"></a>

        </div>
    </div>
</div>
<div class="footer-bottom-links-1">
            <div class="mobile-footer-container row">
                <div  class="col-sm-5 col link-container call-link">
                    <a style="color:white" href="tel:0429 780 896">
 CALL US</a>
                </div>
                 <div class="col-sm-5 col link-container social-link ">
                    <a style="color:white" id="footer-mobile-whatsapp" href="" target="_blank" data-bs-toggle="modal" data-bs-target="#exampleModal" > Book Now</a>
                </div>
            </div>
        </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <div class="myform bg-dark">
                        <span class="text-center" style="color:black">Let’s Connect</span><br/><br/>
                            
                        <div class="ajax-form-messages"
                            style="display: none; padding: 15px; margin-bottom: 20px; border-radius: 8px;"></div>
                        <?php
                        require_once __DIR__ . '/google-form-config.php';
                        $googleForm = $resteasyGoogleForm;
                        ?>
                        <form id="contact-form"
                            action="<?php echo htmlspecialchars($googleForm['action'], ENT_QUOTES, 'UTF-8'); ?>"
                            method="POST" target="gform_hidden_iframe_modal" class="comment-one__form"
                            data-google-form="1"
                            data-enquiry-email-url="<?php echo htmlspecialchars($resteasyEnquiryEmailUrl, ENT_QUOTES, 'UTF-8'); ?>"
                            autocomplete="on">
                            <div class="row">
                                <div class="col-xl-6 col-lg-12">
                                    <div class="comment-form__input-box">
                                        <input type="text" placeholder="Full name *"
                                            name="<?php echo htmlspecialchars($googleForm['fields']['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-enquiry-field="name" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12">
                                    <div class="comment-form__input-box">
                                        <input type="email" placeholder="Email address *"
                                            name="<?php echo htmlspecialchars($googleForm['fields']['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-enquiry-field="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-lg-12">
                                    <div class="comment-form__input-box">
                                        <input type="tel" placeholder="Phone *"
                                            name="<?php echo htmlspecialchars($googleForm['fields']['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-enquiry-field="phone" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12">
                                    <div class="comment-form__input-box">
                                        <input type="text"
                                            name="<?php echo htmlspecialchars($googleForm['fields']['suburb'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-enquiry-field="suburb" placeholder="Suburb *" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <div class="comment-form__input-boxx">
                                        <textarea
                                            name="<?php echo htmlspecialchars($googleForm['fields']['message'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-enquiry-field="message"
                                            placeholder="Your Message *" required></textarea>
                                    </div>
                                    <br>

                                    <input type="hidden" name="fvv" value="1">
                                    <input type="hidden" name="fbzx"
                                        value="<?php echo htmlspecialchars($googleForm['fbzx'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="pageHistory" value="0">
                                    <input type="hidden" name="submit" value="Submit">
                                    <button class="thm-btn" type="submit" id="submit-btn">
                                        <span class="txt">Send Message +</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <iframe name="gform_hidden_iframe_modal" id="gform_hidden_iframe_modal"
                            style="display:none;"></iframe>
                        <script>
                            (function () {
                                var form = document.getElementById('contact-form');
                                if (!form) return;

                                var action = (form.getAttribute('action') || '').toLowerCase();
                                var isGoogle = action.indexOf('docs.google.com/forms') !== -1 || action.indexOf('google.com/forms') !== -1;
                                if (!isGoogle) return;

                                var submitted = false;
                                var iframe = document.getElementById('gform_hidden_iframe_modal');
                                var msg = form.closest('.myform') ? form.closest('.myform').querySelector('.ajax-form-messages') : null;
                                var btn = document.getElementById('submit-btn');
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
                                        if (btnTxt) btnTxt.textContent = originalBtnText || 'Send Message +';
                                    });
                                }
                            })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/scripts.php'; ?>

<!-- Newsletter Form Handler -->
<script>
    function submitNewsletter(e) {
        e.preventDefault();

        var email = document.getElementById('newsletter-email').value;
        var btn = document.getElementById('newsletter-btn');
        var msgDiv = document.getElementById('newsletter-message');
        var newsletterUrl = <?php
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $base = str_replace('\\', '/', dirname($scriptName));
        if ($base === '.' || $base === '/') {
            $base = '';
        }
        echo json_encode($base . '/assets/inc/newsletter.php');
        ?>;

        if (!email) {
            return false;
        }

        btn.disabled = true;
        msgDiv.style.display = 'none';

        var formData = new FormData();
        formData.append('email', email);

        fetch(newsletterUrl, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData
        })
            .then(function (response) {
                return response.json().catch(function () {
                    return null;
                }).then(function (data) {
                    if (!response.ok) {
                        var message = (data && data.message) ? data.message : ('Request failed (' + response.status + '). Please try again.');
                        return { success: false, message: message };
                    }
                    if (!data) {
                        return { success: false, message: 'Unexpected server response. Please try again.' };
                    }
                    return data;
                });
            })
            .then(function (data) {
                if (data.success) {
                    msgDiv.style.backgroundColor = 'rgba(40, 167, 69, 0.2)';
                    msgDiv.style.color = '#90EE90';
                    msgDiv.style.border = '1px solid rgba(40, 167, 69, 0.5)';
                    document.getElementById('newsletter-email').value = '';
                } else {
                    msgDiv.style.backgroundColor = 'rgba(220, 53, 69, 0.2)';
                    msgDiv.style.color = '#ff6b6b';
                    msgDiv.style.border = '1px solid rgba(220, 53, 69, 0.5)';
                }
                msgDiv.innerHTML = data.message;
                msgDiv.style.display = 'block';
            })
            .catch(function () {
                msgDiv.style.backgroundColor = 'rgba(220, 53, 69, 0.2)';
                msgDiv.style.color = '#ff6b6b';
                msgDiv.style.border = '1px solid rgba(220, 53, 69, 0.5)';
                msgDiv.innerHTML = 'Something went wrong. Please try again.';
                msgDiv.style.display = 'block';
            })
            .finally(function () {
                btn.disabled = false;
            });

        return false;
    }
</script>

<!-- Contact Form AJAX Handler (site-wide) -->
<script>
    (function ($) {
        if (!$) return;

        function getMessageBox($form) {
            var $existing = $form.closest('.contact-page__form, .myform, .modal-body, .container, body').find('#form-messages').first();
            if ($existing.length) return $existing;

            var $box = $form.closest('.contact-page__form, .myform, .modal-body, .container, body').find('.ajax-form-messages').first();
            if ($box.length) return $box;

            $box = $('<div class="ajax-form-messages" style="display:none; padding:15px; margin-bottom:20px; border-radius:8px;"></div>');
            $form.before($box);
            return $box;
        }

        function setBoxStyle($box, isSuccess) {
            if (isSuccess) {
                $box.css({
                    'background-color': '#d4edda',
                    'color': '#155724',
                    'border': '1px solid #c3e6cb'
                });
            } else {
                $box.css({
                    'background-color': '#f8d7da',
                    'color': '#721c24',
                    'border': '1px solid #f5c6cb'
                });
            }
        }

        $(function () {
            $('form[action*="assets/inc/sendemail.php"]').each(function () {
                var $form = $(this);
                if ($form.data('ajaxBound')) return;
                $form.data('ajaxBound', true);

                $form.on('submit', function (e) {
                    e.preventDefault();

                    var $btn = $form.find('button[type="submit"], input[type="submit"]').first();
                    var $btnTxt = $btn.find('.txt');
                    var originalBtnText = $btnTxt.length ? $btnTxt.text() : $btn.val() || $btn.text();
                    var $messages = getMessageBox($form);

                    $btn.prop('disabled', true);
                    if ($btnTxt.length) $btnTxt.text('Sending...');
                    else if ($btn.is('input')) $btn.val('Sending...');
                    else $btn.text('Sending...');

                    $messages.hide();

                    $.ajax({
                        type: 'POST',
                        url: $form.attr('action'),
                        data: $form.serialize(),
                        dataType: 'json',
                        success: function (response) {
                            var ok = !!(response && response.success);
                            setBoxStyle($messages, ok);
                            $messages.html((ok ? '<strong>Success!</strong> ' : '<strong>Error:</strong> ') + (response && response.message ? response.message : 'Unexpected response.')).fadeIn();

                            if (ok) {
                                $form[0].reset();
                            }
                        },
                        error: function () {
                            setBoxStyle($messages, false);
                            $messages.html('<strong>Error:</strong> There was a problem submitting your enquiry. Please try again or contact us directly at <a href="mailto:sales@resteasyservices.com.au">sales@resteasyservices.com.au</a>').fadeIn();
                        },
                        complete: function () {
                            $btn.prop('disabled', false);
                            if ($btnTxt.length) $btnTxt.text(originalBtnText);
                            else if ($btn.is('input')) $btn.val(originalBtnText);
                            else $btn.text(originalBtnText);

                            // On normal pages, scroll to the message. In modals, keep it in place.
                            if ($form.closest('.modal').length === 0 && $messages.length && $messages.offset()) {
                                $('html, body').animate({ scrollTop: $messages.offset().top - 100 }, 500);
                            }
                        }
                    });

                    return false;
                });
            });
        });
    })(window.jQuery);
</script>

<!-- Footer Accordion for Mobile -->
<script>
    (function () {
        function initFooterAccordion() {
            var accordionHeaders = document.querySelectorAll('.footer-accordion__header');

            if (window.innerWidth <= 767) {
                accordionHeaders.forEach(function (header) {
                    header.removeEventListener('click', toggleAccordion);
                    header.addEventListener('click', toggleAccordion);
                });
            } else {
                document.querySelectorAll('.footer-accordion').forEach(function (accordion) {
                    accordion.classList.remove('active');
                });
            }
        }

        function toggleAccordion(e) {
            if (window.innerWidth > 767) return;

            var accordion = this.closest('.footer-accordion');
            var isActive = accordion.classList.contains('active');

            document.querySelectorAll('.footer-accordion').forEach(function (item) {
                item.classList.remove('active');
            });

            if (!isActive) {
                accordion.classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', initFooterAccordion);
        window.addEventListener('resize', function () {
            clearTimeout(window.footerAccordionResize);
            window.footerAccordionResize = setTimeout(initFooterAccordion, 150);
        });
    })();
</script>