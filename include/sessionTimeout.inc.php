<?php require_once '../include/accounts.inc.php' ?>

<div class="modal fade" id="sessionExpiringModal" tabindex="-1" role="dialog"
     aria-labelledby="sessionExpiringModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionExpiringModalLabel">Your session is expiring</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span>You will be logged out soon to protect your account as you have been inactive for some time. </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Extend session</button>
            </div>
        </div>
    </div>
</div>

<script>
    function handleSessionExpiringSoon() {

    }

    <?php
    $loggedIn = (bool)getAuthenticatedUser();

    if ($loggedIn) {
        echo 'setTimeout(handleSessionExpiringSoon, ' . ($_SESSION['session_expiry'] - time()) * 1000 . ')';
    }
    ?>
</script>