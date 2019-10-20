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

// Detects if device is on iOS
var isIos = function () {
    return /macintosh|iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
};

// Detects if device is in standalone mode
var isInStandaloneMode = function () {
    return ('standalone' in window.navigator) && window.navigator.standalone;
};

// Checks if should display install popup notification:
if (isIos() && !isInStandaloneMode()) {
    console.log('INSTALLE L APPLI SUR TON MAC');
    // var setState = { showInstallMessage: true };
}