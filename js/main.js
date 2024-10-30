function copyToClipboard(button, elementId) {
    var copyText = document.getElementById(elementId);
    navigator.clipboard.writeText(copyText.value)
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
document.addEventListener('DOMContentLoaded', function() {
    // Define a function to generate QR codes with a logo
    function generateQRCode(id, text, logoUrl) {
        var element = document.getElementById(id);
        if (!element) {
            console.error('Element not found for id:', id);
            return;
        }

        var qrcode = new QRCode(element, {
            text: text,
            width: 200,
            height: 200,
            correctLevel: QRCode.CorrectLevel.H
        });

        // Ensure the QR code is fully generated before adding the logo
        setTimeout(function() {
            var canvas = element.querySelector('canvas');
            if (canvas) {
                var ctx = canvas.getContext('2d');

                var logo = new Image();
                logo.onload = function() {
                    var logoSize = 40;
                    var x = (canvas.width / 2) - (logoSize / 2);
                    var y = (canvas.height / 2) - (logoSize / 2);
                    ctx.drawImage(logo, x, y, logoSize, logoSize);
                };
                logo.onerror = function() {
                    console.error('Failed to load logo:', logoUrl);
                };
                logo.src = logoUrl;
            } else {
                console.error('Canvas not found for id:', id);
            }
        }, 100);
    }

    var cryptos = ['bitcoin', 'ethereum', 'solana'];
    cryptos.forEach(function(crypto) {
        var addressInput = document.getElementById(crypto + '-address-input');
        if (addressInput) {
            var address = addressInput.value;
            var qrElement = document.getElementById('qrcode-' + crypto);
            var logoUrl = crypto_addresses.plugin_url + 'assets/' + crypto + '-logo.png';
            if (address && qrElement && !qrElement.hasChildNodes()) {
                generateQRCode('qrcode-' + crypto, address, logoUrl);
            } else {
                //console.log('No address found for:', crypto);
            }
        } else {
            //console.log('Address input not found for:', crypto);
        }
    });

    var cryptoSelect = document.getElementById('crypto-select');
    if (cryptoSelect) {
        cryptoSelect.addEventListener('change', function() {
            var selectedCrypto = this.value;
            document.querySelectorAll('.crypto-option').forEach(function(option) {
                option.style.display = 'none';
            });
            var option = document.getElementById('crypto-option-' + selectedCrypto);
            if (option) option.style.display = 'block';
        });

        var defaultOption = document.getElementById('crypto-option-' + cryptoSelect.value);
        if (defaultOption) defaultOption.style.display = 'block';
    }
});







