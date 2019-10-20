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
    return /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
};

// Detects if device is in standalone mode
var isInStandaloneMode = function () {
    /** THIS TEST DOES NOT WORK! YOU CAN TRUST WINDOW.NAVIGATOR FOR EVERY USECASES */
    return ('standalone' in window.navigator) && window.navigator.standalone;
};

// Checks if should display install popup notification:
if (isIos() && !isInStandaloneMode()) {
    // setTimeout(function () {
    //     var body = document.getElementsByTagName('body')[0];
    //     var script = body.appendChild(document.createElement('script'));
    //
    //     script.textContent = 'alert("you may install the app on you home screen!");';
    // }, 1000);
}