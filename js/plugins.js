// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.
function formatMoney(number, decimals, decimalSeparator, thousandsSeparator, showDollarSign, signIndicator) {
    decimals = typeof decimals === 'undefined' ? 2 : decimals;
    decimalSeparator = decimalSeparator || '.';
    thousandsSeparator = thousandsSeparator || ',';

    var signLeft = number < 0 ? (signIndicator === 'brackets' ? '(' : '-') : '',
        signRight = number < 0 && signIndicator === 'brackets' ? ')' : '',
        dollarSign = showDollarSign ? '$' : '',
        leftSide = signIndicator === 'brackets' ? signLeft + dollarSign : dollarSign + signLeft, // Show ($100) but -$100
        i = parseInt(number = Math.abs(+number || 0).toFixed(decimals)) + '',
        j = (j = i.length) > 3 ? j % 3 : 0;

    return leftSide + (j ? i.substr(0, j) + thousandsSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousandsSeparator) + (decimals ? decimalSeparator + Math.abs(number - i).toFixed(decimals).slice(2) : "") + signRight;
}