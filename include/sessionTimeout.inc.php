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
                <button type="button" class="btn btn-primary" id="session-extension-button"
                        data-dismiss="modal">Extend session</button>
            </div>
        </div>
    </div>
</div>

<script>
    function handleSessionExpiringSoon() {
        $('#sessionExpiringModal').modal()
    }

    $('#session-extension-button').on('click', e => {
        // make request to server to renew cookie
        fetch('/')
        setTimeout(handleSessionExpiringSoon, 1200000) // 20 minutes
    })

    <?php
    $loggedIn = (bool)getAuthenticatedUser();

    if ($loggedIn) {
        echo 'setTimeout(handleSessionExpiringSoon, ' . ($_SESSION['session_expiry'] - time()) * 1000 . ')';
    }
    ?>
</script>