<?php if (!defined('ABSPATH')) { exit; } // No direct access allowed. ?>

<div class="wrap">
    <div id="error-message" style="display: none; color: red;"></div>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div id="crypto-support-box-admin" style="display: flex;">
        <div class="crypto-settings" style="width: 50%; padding-right: 20px; border-right: 1px solid #ddd;">
            <form method="post" action="options.php">
                <?php
                settings_fields('crypto_support_box_options');
                do_settings_sections('crypto_support_box');
                submit_button('Save Changes');
                ?>
            </form>
        </div>
        <div class="crypto-preview" style="width: 50%; padding-left: 20px; text-align: center;">
            <div class="crypto-support-box light-theme">
                <h2>Help Keep This Plugin Awesome by Supporting The Developer!</h2>
                <select id="crypto-select-preview" class="crypto-select">
                    <option value="bitcoin">Bitcoin</option>
                    <option value="solana" selected>Solana</option>
                </select>

                <div class="crypto-option" id="crypto-option-bitcoin" style="display: none;">
                    <img decoding="async" src="<?php echo esc_url(plugins_url('images/aiscores-bitcoin-address.png', __FILE__)); ?>" alt="Bitcoin QR Code" style="min-width: 200px; height: auto;">
                    <div class="crypto-address-field">
                        <input type="text" value="381QsjKxhHijnrxnKvnQRmWhjg4sPnhUxu" id="bitcoin-address-input" readonly="">
                        <button onclick="copyCodeToClipboard(this, 'bitcoin-address-input')" class="copy-address-button" data-copied="false">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path fill="none" d="M0 0h24v24H0z"></path>
                                <path d="M16 1H4C2.9 1 2 1.9 2 3v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"></path>
                            </svg>
                            <svg class="done-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"></path>
                            </svg>
                        </button>
                    </div>
					<div class="suggested-donation">
                        Suggested donation: <b>0.00025 BTC</b> ~ $15 (as of Aug 15, 2024)
                    </div>
                </div>

                <div class="crypto-option" id="crypto-option-solana" style="display: none;">
                    <img decoding="async" src="<?php echo esc_url(plugins_url('images/aiscores-solana-address.png', __FILE__)); ?>" alt="Solana QR Code" style="min-width: 200px; height: auto;">
                    <div class="crypto-address-field">
                        <input type="text" value="6VZ6Ewh94HFX2agxLTFY2G9AvhfjrNCyv6s4ud4aVsGt" id="solana-address-input" readonly="">
                        <button onclick="copyCodeToClipboard(this, 'solana-address-input')" class="copy-address-button" data-copied="false">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path fill="none" d="M0 0h24v24H0z"></path>
                                <path d="M16 1H4C2.9 1 2 1.9 2 3v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"></path>
                            </svg>
                            <svg class="done-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"></path>
                            </svg>
                        </button>
                    </div>
					<div class="suggested-donation">
                        Suggested donation: <b>0.1 SOL</b> ~ $15 (as of Aug. 15, 2024)
                    </div>
                </div>
            </div>
             <hr style="margin-top: 30px; margin-bottom: 30px; border: 1px solid #ddd; ">
            <div class="solana-mobile-promotion" style="margin-top: 20px;">
                <a href="https://joelbooks.com/solanamobile" target="_blank">
                    <img decoding="async" src="<?php echo esc_url(plugins_url('images/solana-mobile-promotion.png', __FILE__)); ?>" alt="Solana Mobile 2" style="max-width: 400px; height: auto;">
                </a>
            </div>
        </div>
    </div>
</div>
