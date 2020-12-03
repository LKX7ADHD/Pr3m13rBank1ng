<?php require_once 'include/accounts.inc.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Meet the team behind Premier Banking's success.">
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
						<img src="./assets/img/linkedin-sales-navigator-hrhjn6ZTgrM-unsplash.jpg"
						     alt="A guy conversing with another guy on a laptop">
					</div>
				</div>
				<div class="col-lg-8 col-md-7">
					<div class="img-container">
						<img src="./assets/img/campaign-creators-qCi_MzVODoU-unsplash.jpg"
						     alt="A business office with two girls and two guys seated down discussing">
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
				<div class="col-lg-2">
					<figure class="team-container">
						<div class="team-image">
							<img src="./assets/img/kx-mugshot.png" alt="Founder & CEO of Premier Banking, Kai Xuan">
						</div>
						<figcaption class="team-text">
							<h3 class="title">
								Kai Xuan
							</h3>
							<span class="subtitle">
                                Founder & CEO
                            </span>
						</figcaption>
					</figure>
				</div>
				<div class="col-lg-2">
					<figure class="team-container">
						<div class="team-image">
							<img src="./assets/img/roy-mugshot.jpg" alt="CTO of Premier Banking, Roy">
						</div>
						<figcaption class="team-text">
							<h3 class="title">
								Roy
							</h3>
							<span class="subtitle">
                                CTO
                            </span>
						</figcaption>
					</figure>
				</div>
				<div class="col-lg-2">
					<figure class="team-container">
						<div class="team-image">
							<img src="./assets/img/sebastian-mugshot.jpg" alt="Lead Programmer of Premier Banking, Sebastian">
						</div>
						<figcaption class="team-text">
							<h3 class="title">
								Sebastian
							</h3>
							<span class="subtitle">
                                Lead Programmer
                            </span>
						</figcaption>
					</figure>
				</div>
				<div class="col-lg-2">
					<figure class="team-container">
						<div class="team-image">
							<img src="./assets/img/eddie-mugshot.png" alt="Second Lead Programmer of Premier Banking, Eddie">
						</div>
						<figcaption class="team-text">
							<h3 class="title">
								Eddie
							</h3>
							<span class="subtitle">
                                Second Lead Programmer
                            </span>
						</figcaption>
					</figure>
				</div>
				<div class="col-lg-2">
					<figure class="team-container">
						<div class="team-image">
							<img src="./assets/img/jerome-mugshot.png" alt="Programmer of Premier Banking, Jerome">
						</div>
						<figcaption class="team-text">
							<h3 class="title">
								Jerome
							</h3>
							<span class="subtitle">
                                Programmer
                            </span>
						</figcaption>
					</figure>
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
						<p>Are you interested in joining our bank? Create an account to get started!</p>
						<div class="cta__btn">
							<a href="register.php" title="Register Page">
								<button class="btn">
									Register now!
								</button>
							</a>
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
