<?php
// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

function crypto_support_box_display() {
    // Retrieve default cryptocurrency setting
    $default_crypto = get_option('default_crypto', 'solana');
    
    // Retrieve default theme setting
    $default_theme = get_option('default_theme', 'light');
    
    // Retrieve cryptocurrency data
    $cryptos = [
        'bitcoin' => [
            'address' => get_option('crypto_bitcoin_address'),
            'qr_url' => get_option('crypto_bitcoin_qr_url')
        ],
        'ethereum' => [
            'address' => get_option('crypto_ethereum_address'),
            'qr_url' => get_option('crypto_ethereum_qr_url')
        ],
        'solana' => [
            'address' => get_option('crypto_solana_address'),
            'qr_url' => get_option('crypto_solana_qr_url')
        ]
    ];

    // Filter out the cryptocurrencies without an address and qr url set
    $cryptos = array_filter($cryptos, function($crypto) {
        //return !empty($crypto['address']) && !empty($crypto['qr_url']);
        return !empty($crypto['address']);
    });

    // Start output buffering
    ob_start();
    ?>
    <div class="crypto-support-box <?php echo esc_attr($default_theme . '-theme'); ?>">
        <h2><?php echo esc_html(get_option('crypto_title', 'Support Our Mission')); ?></h2>
        <?php if (count($cryptos) > 0): ?>
            <select id="crypto-select" class="crypto-select">
                <?php foreach ($cryptos as $crypto => $data): ?>
                    <option value="<?php echo esc_attr($crypto); ?>" <?php selected($crypto, $default_crypto); ?>>
                        <?php echo esc_html(ucfirst($crypto)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <?php foreach ($cryptos as $crypto => $data): ?>
            <div class="crypto-option" id="crypto-option-<?php echo esc_attr($crypto); ?>" style="display: none;">
                <?php if (!empty($data['qr_url'])): ?>
                    <img src="<?php echo esc_url($data['qr_url']); ?>" alt="<?php echo esc_attr(ucfirst($crypto) . ' QR Code'); ?>" style="min-width: 200px; height: auto;">
                <?php else: ?>
                    <div id="qrcode-<?php echo esc_attr($crypto); ?>" style="width: 200px; height: 200px;"></div>
                <?php endif; ?>
                <div class="crypto-address-field">
                    <input type="text" value="<?php echo esc_attr($data['address']); ?>" id="<?php echo esc_attr($crypto); ?>-address-input" readonly>
                    <button onclick="copyToClipboard(this, '<?php echo esc_attr($crypto); ?>-address-input')" class="copy-address-button" data-copied="false">
                        <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path d="M16 1H4C2.9 1 2 1.9 2 3v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                        </svg>
                        <svg class="done-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    $output = ob_get_clean();
    return $output;
}


