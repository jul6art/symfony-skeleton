if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/offline.js').then(function (registration) {
            // Registration was successful
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function (err) {
            // registration failed :(
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}

// // Detects if device is on iOS
// var isIos = function () {
//     const userAgent = window.navigator.userAgent.toLowerCase();
//     return /iphone|ipad|ipod/.test( userAgent );
// };
// // Detects if device is in standalone mode
// var isInStandaloneMode = function () {
//     return ('standalone' in window.navigator) && (window.navigator.standalone);
// };
//
// // Checks if should display install popup notification:
// if (isIos() && !isInStandaloneMode()) {
//     var setState = { showInstallMessage: true };
// }