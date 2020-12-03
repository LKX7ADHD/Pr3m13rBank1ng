<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/include/accounts.inc.php' ?>

<div class="modal fade" id="sessionExpiringModal" tabindex="-1" role="dialog"
     aria-labelledby="sessionExpiringModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionExpiringModalLabel">Your session is expiring</h5>
            </div>
            <div class="modal-body">
                <span>You will soon be logged out soon to protect your account as you have been inactive for some time. </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="session-extension-button">Extend session</button>
            </div>
        </div>
    </div>
</div>

<script>
    function handleSessionExpiringSoon() {
        const button = $('#session-extension-button')
        button.removeAttr('disabled')
        button.text('Extend session')

        $('#sessionExpiringModal').modal('show')
    }

    $('#session-extension-button').on('click', e => {
        $(e.target).attr('disabled', '')
        $(e.target).text('Extending session...')

        // make request to server to renew cookie
        fetch('/session_extend.php')
            .then(response => response.json())
            .then(data => {
                if (data['has_expired']) {
                    window.location.href = '/logout.php'
                } else {
                    setTimeout(handleSessionExpiringSoon, data['expires_in'] * 1000)
                    $('#sessionExpiringModal').modal('hide')
                }
            })
    })

    <?php
    $loggedIn = (bool)getAuthenticatedUser();

    if ($loggedIn) {
        echo 'setTimeout(handleSessionExpiringSoon, ' . ($_SESSION['session_expiry'] - time()) * 1000 . ')';
    }
    ?>
</script>
