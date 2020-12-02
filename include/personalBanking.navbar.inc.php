<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
	<div class="container">
		<a class="navbar-brand" href="/">Premier Banking</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar"
		        aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse pl-3 pl-lg-0" id="navbar">
			<ul class="navbar-nav mt-2 mt-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="/personal_banking/">My Accounts</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/personal_banking/transfer.php">Transfer</a>
				</li>
                <li class="nav-item">
                    <a class="nav-link" href="/personal_banking/applications.php">View Applications</a>
                </li>
			</ul>
			<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="/personal_banking/profile.php">
                        <?php echo getAuthenticatedUser()->username ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
