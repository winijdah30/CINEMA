<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de commande</title>
</head>
<body>
    <h1>Merci pour votre commande!</h1>
    
    <p>Voici votre QR code pour accéder à la séance:</p>

    <p>Détails de la commande:</p>
    <ul>
        <li>Film: {{ $order->cart->movie->title }}</li>
        <li>Adultes: {{ $order->cart->adult }}</li>
        <li>Étudiants: {{ $order->cart->etudiant }}</li>
        <li>Enfants: {{ $order->cart->enfant }}</li>
    </ul>
    <div id="qrCodeContainer">
    {!! $qrCodeSvg !!}
</div>

<button onclick="downloadPNG()">Télécharger PNG</button>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
<script>
function downloadPNG() {
    domtoimage.toPng(document.getElementById('qrCodeContainer'))
        .then(function(dataUrl) {
            const link = document.createElement('a');
            link.download = 'qrcode.png';
            link.href = dataUrl;
            link.click();
        });
}
</script>
</body>
</html>