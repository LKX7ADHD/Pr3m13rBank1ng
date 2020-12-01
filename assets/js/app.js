//    Formula:  A = P (1 + r/n)^nt
// A = the future value of the investment/loan, including interest
// P = the principal investment amount (the initial deposit or loan amount)
// r = the annual interest rate (decimal)
// n = the number of times that interest is compounded per unit t
// t = the time the money is invested or borrowed for

function calculate_interest(P, r, n, t) {
    let amount = P * (1 + r / n / 100) ** (n * t);
    return "$".concat(amount.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}

$(() => {
    $('nav a.nav-link[href="' + location.pathname + '"]')
        .append('<span class="sr-only">(current)</span>')
        .parent().addClass('active')

    $('#interest-rate-calculator-form').on('submit', e => {
        const amount = calculate_interest(
            $('#principal').val(),
            $('#rate').val(),
            $('#times').val(),
            $('#years').val()
        )
        $('#interest-rate-calculator-amount-container > p.h1').text(amount)
        return false
    })
})