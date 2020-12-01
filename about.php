<?php require_once 'include/accounts.inc.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Premier Banking | About Us</title>
    <?php include 'include/imports.inc.php' ?>
</head>
<body>

<?php include "include/navbar.inc.php" ?>

<header id="about" class="bg-light">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<h1 class="title">About Us</h1>
				<p class="about__text">A Bank for you and only you. We will make sure you get the service you
					deserve.</p>
			</div>
		</div>
	</div>
</header>

<main>
	<section class="mission">
		<div class="container">
			<div class="row">
				<div class="mission__title">
					<h2>
						Our Mission is to make sure you never lose sight of your money.
					</h2>
					<div class="mission__paragraph">
						<p>
							Money should be handled wisely. Without a proper bank, you will easily lose sight.
						</p>
					</div>
				</div>

			</div>

			<div class="row">
				<div class="col-lg-4 col-md-5">
					<div class="img-container">
						<img src="./assets/img/linkedin-sales-navigator-hrhjn6ZTgrM-unsplash.jpg" alt="">
					</div>
				</div>
				<div class="col-lg-8 col-md-7">
					<div class="img-container">
						<img src="./assets/img/campaign-creators-qCi_MzVODoU-unsplash.jpg" alt="">
					</div>
				</div>
			</div>
		</div>
	</section>

	<!--    Team Section -->
	<section class="team">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-6 text-center">
					<div class="team__title">
						<h2>Meet The Team</h2>
						<p>The team that makes sure you never regret banking with us.</p>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<div class="team-container">
						<div class="team-image">
							<img src="./assets/img/joseph-gonzalez-iFgRcqHznqg-unsplash.jpg" alt="">
						</div>
						<div class="team-text">
							<h3 class="title">
								Kai Huan
							</h3>
							<span class="subtitle">
                                Founder & CEO
                            </span>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="team-container">
						<div class="team-image">
							<img src="./assets/img/joseph-gonzalez-iFgRcqHznqg-unsplash.jpg" alt="">
						</div>
						<div class="team-text">
							<h3 class="title">
								Kai Huan
							</h3>
							<span class="subtitle">
                                Founder & CEO
                            </span>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="team-container">
						<div class="team-image">
							<img src="./assets/img/joseph-gonzalez-iFgRcqHznqg-unsplash.jpg" alt="">
						</div>
						<div class="team-text">
							<h3 class="title">
								Kai Huan
							</h3>
							<span class="subtitle">
                                Founder & CEO
                            </span>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="team-container">
						<div class="team-image">
							<img src="./assets/img/joseph-gonzalez-iFgRcqHznqg-unsplash.jpg" alt="">
						</div>
						<div class="team-text">
							<h3 class="title">
								Kai Huan
							</h3>
							<span class="subtitle">
                                Founder & CEO
                            </span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--    End of Team Section -->

	<!--    Start of Call To Action -->
	<section class="cta-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-7 col-xl-6">

					<div class="cta__title text-center">
						<h2 class="title">
							Want to Join?
						</h2>
						<p>Are you interested in joining our team? Check out our job openings!</p>
						<div class="cta__btn">
							<button class="btn">
								Check Job Openings
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!--    End of Call To Action -->
</main>


<?php include "include/sessionTimeout.inc.php" ?>
<?php include "include/footer.inc.php" ?>
</body>
</html>
