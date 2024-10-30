jQuery(document).ready(function ($) {
    // Function to copy text to clipboard
    function copyCodeToClipboard(button, elementId) {
        console.log("copyCodeToClipboard called", button, elementId);

        var copyText = document.getElementById(elementId);
        var textToCopy;

        // Check if the element is an input or textarea
        if (copyText.tagName === 'INPUT' || copyText.tagName === 'TEXTAREA') {
            textToCopy = copyText.value;
            console.log("Right", textToCopy);
        } else {

            textToCopy = copyText.textContent;
            console.log("Else", textToCopy);
        }

        navigator.clipboard.writeText(textToCopy)
        .then(() => {
            // Hide the copy icon and show the done icon
            button.querySelector('.copy-icon').style.display = 'none';
            button.querySelector('.done-icon').style.display = 'block';

            // Optionally, revert the icons after some time
            setTimeout(() => {
                button.querySelector('.copy-icon').style.display = 'block';
                button.querySelector('.done-icon').style.display = 'none';
            }, 2000); // 2 seconds delay
        })
        .catch(err => {
            console.error('Error in copying text: ', err);
        });
    }
    window.copyCodeToClipboard = copyCodeToClipboard;
    
    // Toggle between QR codes in the admin preview based on selection
    $('#crypto-select-preview').change(function () {
        $('.crypto-option').hide();

        var selectedCrypto = $(this).val();
        $('#crypto-option-' + selectedCrypto).show();
    }).change(); // Trigger change to display the initial selection

    // Refresh the select menu when addresses are updated
    function refreshCryptoSelect() {
        var $select = $('#default_crypto');
        var currentValue = $select.val(); // Capture the current value
        $select.empty();

        var cryptos = {
            'bitcoin': $('#crypto_bitcoin_address').val(),
            'ethereum': $('#crypto_ethereum_address').val(),
            'solana': $('#crypto_solana_address').val()
        };

        $.each(cryptos, function (crypto, address) {
            if ((address.length >= 26 && address.length <= 62 && crypto === 'bitcoin') ||
                (address.length === 42 && crypto === 'ethereum') ||
                (address.length >= 32 && address.length <= 44 && crypto === 'solana')) {
                $select.append('<option value="' + crypto + '">' + crypto.charAt(0).toUpperCase() + crypto.slice(1) + '</option>');
            }
        });

        // Restore the selected value if it exists
        if ($select.find('option[value="' + currentValue + '"]').length > 0) {
            $select.val(currentValue);
        } else {
            $select.val($select.find('option:first').val()); // Default to the first option if the previous value is not found
        }
    }

    // Call refresh on load
    refreshCryptoSelect();

    // Attach event listeners to address fields
    $(document).on('input', '#crypto_bitcoin_address, #crypto_ethereum_address, #crypto_solana_address', function () {
        refreshCryptoSelect();
    });
});
