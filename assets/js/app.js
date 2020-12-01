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

    $('input#pwd_new').on('input', e => {
        const pwd = e.target.value

        const lengthRequirement = pwd => pwd.length >= 8
        const numberRequirement = pwd => ~pwd.search(/\d/)
        const uppercaseRequirement = pwd => ~pwd.search(/[A-Z]/)
        const specialRequirement = pwd => ~pwd.search(/[!@#\$%\^\&*\)\(+=._-]/)

        const requirements = [lengthRequirement, numberRequirement, uppercaseRequirement, specialRequirement]

        const validNode = document.createElement('span')
        validNode.textContent = '(fulfilled)'
        validNode.classList.add('pwd-requirement-sr-hint', 'sr-only')

        const invalidNode = document.createElement('span')
        invalidNode.textContent = '(not yet fulfilled)'
        invalidNode.classList.add('pwd-requirement-sr-hint', 'sr-only')

        $('.pwd-requirement-sr-hint').remove()

        let valid = true;
        for (const [i, requirement] of requirements.entries()) {
            if (requirement(pwd)) {
                $($('.pwd-requirement-list > li')[i])
                    .removeClass('pwd-requirement-invalid')
                    .addClass('pwd-requirement-valid')
                    .append(validNode.cloneNode(true))
            } else {
                $($('.pwd-requirement-list > li')[i])
                    .removeClass('pwd-requirement-valid')
                    .addClass('pwd-requirement-invalid')
                    .append(invalidNode.cloneNode(true))
                valid = false;
            }
        }

        if (valid) {
            e.target.setCustomValidity('')
        } else {
            e.target.setCustomValidity('Fulfil the password requirements')
        }
    })

    $('input#pwd_confirm').on('change', e => {
        if (e.target.value === $('#pwd_new')[0].value) {
            e.target.setCustomValidity('')
        } else {
            e.target.setCustomValidity('Passwords do not match')
        }
    })
})