<footer class="vc-footer">

    <div class="container">

        <div class="vc-footer-grid">

            <!-- Company -->
            <div class="vc-footer-company">

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="DNA Vendor Portal"
                    class="footer-logo"
                >

                <h3>DNA Vendor Portal</h3>

                <p>
                    Secure Vendor Registration Platform for trusted business
                    partnerships with DNA Advertising. Register your company
                    quickly, securely, and transparently through our digital
                    vendor management system.
                </p>

            </div>

            <!-- Quick Links -->
            <div class="vc-footer-links">

                <h4>Quick Links</h4>

                <ul>

                    <li>
                        <a href="/">Home</a>
                    </li>

                    <li>
                        <a href="{{ route('vendor.register') }}">
                            Register Vendor
                        </a>
                    </li>

                    <li>
                        <a href="#benefits">
                            Why Partner With Us
                        </a>
                    </li>

                    <li>
                        <a href="#faq">
                            FAQ
                        </a>
                    </li>

                </ul>

            </div>

            <!-- Contact -->
            <div class="vc-footer-contact">

                <h4>Contact Information</h4>

                <p>

                    <i class="fa-solid fa-location-dot"></i>

                    <span>
                        JL. Taman Dhika BL 6 No.5<br>
                        Sono, Sidokerto<br>
                        Buduran, Sidoarjo
                    </span>

                </p>

                <p>

                    <i class="fa-regular fa-clock"></i>

                    <span>
                        Monday – Friday<br>
                        08:00 – 17:00 WIB
                    </span>

                </p>

                <p>

                    <i class="fa-solid fa-envelope"></i>

                    <span>
                        Email Coming Soon
                    </span>

                </p>

                <p>

                    <i class="fa-solid fa-phone"></i>

                    <span>
                        Phone Coming Soon
                    </span>

                </p>

            </div>

            <!-- Help Card -->
            <div class="vc-footer-help">

                <div class="vc-help-card">

                    <div class="help-icon">

                        <i class="fa-solid fa-headset"></i>

                    </div>

                    <h4>Need Assistance?</h4>

                    <p>

                        Having trouble while registering your company?

                        Feel free to contact our team during working hours.
                        We are ready to assist your registration process.

                    </p>

                    <a
                        href="{{ route('vendor.register') }}"
                        class="help-btn"
                    >
                        Register Now
                    </a>

                </div>

            </div>

        </div>

        <hr class="vc-footer-divider">

        <div class="vc-footer-bottom">

            <span>

                © {{ date('Y') }}
                DNA Advertising.
                All rights reserved.

            </span>

            <span>

                Vendor Registration Portal

            </span>

        </div>

    </div>

</footer>