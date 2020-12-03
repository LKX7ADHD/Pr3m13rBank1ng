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

    $('#interest-rate-calculator-form').on('submit', () => {
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
        const specialRequirement = pwd => ~pwd.search(/[!@#$%^&*)(+=._-]/)

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

    $('#transfer-form .account-dropdown a.dropdown-item').on('click', e => {
        const dropdown = $(e.target).parents('.dropdown')
        dropdown.children('button').text($(e.target).text())

        if (typeof ($(e.target).attr('data-otherAccount')) !== 'undefined') {
            // Other account
            dropdown.next().find('input').val('')
            dropdown.next().removeClass('d-none')

            $('a.dropdown-item').removeClass('disabled')
        } else {
            // One of own accounts
            dropdown.next().addClass('d-none')
            dropdown.next().find('input').val($(e.target).attr('data-accountNumber'))

            $('a.dropdown-item').removeClass('disabled')
            $('.account-dropdown').not(dropdown).find('a.dropdown-item[data-accountNumber="' + $(e.target).attr('data-accountNumber') + '"]').addClass('disabled')
        }
    })

    $('#transfer-form input[name=senderAccountNumber], input[name=receiverAccountNumber]').on('input', e => {
        const input = $(e.target)
        const dashIndices = [3, 9]
        let caretPos = e.target.selectionStart

        for (let i = 0; i < input.val().length; i++) {
            if (dashIndices.includes(i)) {
                if (input.val().length > i && input.val()[i] !== '-') {
                    input.val(input.val().slice(0, i) + '-' + input.val().slice(i))
                    if (caretPos === i + 1) {
                        caretPos++
                    }
                } else if (input.val().length === i + 1 && input.val()[i] === '-') {
                    input.val(input.val().slice(0, i))
                }
            } else if (input.val()[i] === '-') {
                input.val(input.val().slice(0, i) + input.val().slice(i + 1))
            }
        }

        e.target.setSelectionRange(caretPos, caretPos)
    }).on('change', e => {
        const value = $(e.target).val().replaceAll('-', '')

        if (value.length !== 10) {
            e.target.setCustomValidity("Invalid account number")
            return
        }

        let accumulator = 0;
        for (let i = 0; i < 8; i++) {
            accumulator += (parseInt(value[i]) * (17 ** i)) % 17
        }

        if (value.slice(-2) !== (accumulator % 17).toString().padStart(2, '0')) {
            e.target.setCustomValidity("Invalid account number")
        } else {
            e.target.setCustomValidity("")
        }
    })

    $('#transfer-form').on('submit', () => {
        let valid = true
        $('#sending-account-invalid-warning, #recipent-account-invalid-warning').addClass('d-none')

        if ($('input[name=senderAccountNumber]').val() === '') {
            $('#sending-account-invalid-warning').removeClass('d-none')
            valid = false
        }

        if ($('input[name=receiverAccountNumber]').val() === '') {
            $('#recipent-account-invalid-warning').removeClass('d-none')
            valid = false
        }

        return valid
    })

    $('#approvals-table .request-approval-btn').on('click', e => {
        const requestNumber = e.target.getAttribute('data-requestNumber')
        let approve

        if (e.target.getAttribute('data-approve') === 'true') {
            approve = true
        } else if (e.target.getAttribute('data-approve') === 'false') {
            approve = false
        }

        if (typeof (approve) !== 'undefined') {
            e.target.setAttribute('disabled', '')

            const formData = new FormData();
            formData.append('requestNumber', requestNumber);
            formData.append('approval', approve ? 'true' : 'false');

            fetch('process_application.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data['success']) {
                        $(e.target).parents('tr').remove()
                    } else {
                        e.target.removeAttribute('disabled')
                    }
                })
        }
    })
})
