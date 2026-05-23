<?php
session_start();
include '../configuration/config.php';

// Prefill form fields for logged-in users
$user_name = '';
$user_email = '';
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $user_name = htmlspecialchars($user['username']);
        $user_email = htmlspecialchars($user['email']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - GreenLife Wellness Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=block" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @font-face {
            font-family: 'Algerian';
            src: url('https://fonts.cdnfonts.com/css/algerian') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }

        :root {
            --green-accent: #4caf50;
            --green-dark: #2e7d32;
            --yellow-accent: #ffca28;
            --brown-accent: #b74213;
            --space-xl: 2rem;
            --space-l: 1.5rem;
            --space-m: 1.25rem;
            --space: 1rem;
            --space-s: 0.5rem;
            --fs-l: 1.4375rem;
            --fs-m: 1.25rem;
            --fs-default: 1rem;
            --fs-s: 0.9rem;
            --anim-time--hi: 266ms;
            --anim-time--med: 400ms;
            --anim-time--lo: 600ms;
        }

        body {
            line-height: 1.6;
            color: #333;
            background-color: #fff;
            scroll-behavior: smooth;
            padding-top: 80px;
        }

        /* Navigation Bar */
        header {
            background-color: #fff;
            color: #333;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav {
            max-width: 100%;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 70px;
            border-radius: 8px;
            border: 2px solid var(--yellow-accent);
            background: linear-gradient(45deg, var(--brown-accent), var(--yellow-accent));
            padding: 5px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .logo span {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--brown-accent);
            margin-left: 10px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 25px;
        }

        nav ul li a {
            color: var(--yellow-accent);
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        nav ul li a:hover {
            color: var(--brown-accent);
            transform: translateY(-2px);
        }

        nav ul li a.active {
            color: var(--brown-accent);
            font-weight: 600;
            border-bottom: 2px solid var(--brown-accent);
        }

        /* Intro Section */
        .intro-section {
            text-align: center;
            padding: 80px 20px;
            background: url('../photos/footer-gds-1.png') no-repeat center/cover;
            color: #fff;
            border-bottom: 5px solid var(--green-accent);
        }

        .intro-section h2 {
            font-family: 'Algerian', serif;
            font-size: 3.2rem;
            font-weight: bold;
            color: #181817;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .intro-section p {
            font-size: 1.2rem;
            color: #19a424;
        }

        /* Feedback Form Section */
        .feedback {
            padding: 60px 20px;
            background-color: #e8f5e9;
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--yellow-accent);
        }

        .feedback h2 {
            font-family: 'Algerian', serif;
            font-size: 2.5rem;
            color: var(--brown-accent);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 1rem;
            color: var(--brown-accent);
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--yellow-accent);
            outline: none;
        }

        .form-group input.invalid,
        .form-group textarea.invalid {
            border-color: #ff4444;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .rating input {
            display: none;
        }

        .rating label {
            font-size: 1.5rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: var(--yellow-accent);
        }

        /* Animated Button Styling */
        .feedback button {
            position: relative;
            outline: none;
            text-decoration: none;
            border-radius: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            text-transform: uppercase;
            height: 60px;
            width: 210px;
            opacity: 1;
            background-color: var(--green-accent);
            border: 1px solid var(--green-dark);
            margin: 1rem auto;
            transition: all 0.3s ease;
        }

        .feedback button span {
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.7px;
        }

        .feedback button:hover {
            animation: rotate 0.7s ease-in-out both;
        }

        .feedback button:hover span {
            animation: storm 0.7s ease-in-out both;
            animation-delay: 0.06s;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg) translate3d(0, 0, 0); }
            25% { transform: rotate(3deg) translate3d(0, 0, 0); }
            50% { transform: rotate(-3deg) translate3d(0, 0, 0); }
            75% { transform: rotate(1deg) translate3d(0, 0, 0); }
            100% { transform: rotate(0deg) translate3d(0, 0, 0); }
        }

        @keyframes storm {
            0% { transform: translate3d(0, 0, 0) translateZ(0); }
            25% { transform: translate3d(4px, 0, 0) translateZ(0); }
            50% { transform: translate3d(-3px, 0, 0) translateZ(0); }
            75% { transform: translate3d(2px, 0, 0) translateZ(0); }
            100% { transform: translate3d(0, 0, 0) translateZ(0); }
        }

        .message {
            text-align: center;
            margin-top: 1rem;
            font-size: 1rem;
            display: none;
        }

        .message.success {
            color: var(--green-accent);
            display: block;
        }

        .message.error {
            color: #ff4444;
            display: block;
        }

        /* Feedback Display Section */
        .feedback-display {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feedback-display h2 {
            font-family: 'Algerian', serif;
            font-size: 2.5rem;
            color: var(--brown-accent);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .feedback-list {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            justify-content: center;
        }

        .feedback-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--yellow-accent);
            width: 300px;
            text-align: left;
        }

        .feedback-card h3 {
            font-family: 'Algerian', serif;
            font-size: 1.5rem;
            color: var(--brown-accent);
            margin-bottom: 0.5rem;
        }

        .feedback-card p {
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .rating-stars {
            font-size: 1.2rem;
            color: var(--yellow-accent);
            margin-bottom: 0.5rem;
        }

        .submitted-at {
            font-size: 0.8rem;
            color: #666;
        }

        .no-feedback {
            text-align: center;
            font-size: 1rem;
            color: #666;
        }

        /* Footer */
        footer {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../photos/footer.jpg');
            color: #fff;
            padding: 3rem 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 2rem;
        }

        .footer-column {
            flex: 1 1 250px;
        }

        .footer-column h3 {
            font-family: 'Algerian', serif;
            color: var(--yellow-accent);
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li a {
            color: #ccc;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-column ul li a:hover {
            color: var(--yellow-accent);
        }

        .footer-contact p {
            font-size: 0.9rem;
            margin-bottom: 0.6rem;
        }

        .footer-contact a {
            color: #ccc;
            text-decoration: none;
        }

        .footer-contact a:hover {
            color: var(--yellow-accent);
        }

        .newsletter form {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .newsletter input[type="email"] {
            padding: 0.7rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            background-color: #fff;
        }

        .newsletter input[type="email"].invalid {
            border: 2px solid #ff4444;
        }

        .newsletter button {
            background-color: var(--green-accent);
            color: #fff;
            padding: 0.7rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .newsletter button:hover {
            background-color: var(--green-dark);
        }

        .newsletter .error-message {
            color: #ff4444;
            font-size: 0.9rem;
            display: none;
        }

        .social-icons a {
            color: var(--yellow-accent);
            font-size: 1.5rem;
            margin-right: 10px;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .social-icons a:hover {
            color: #fff;
            transform: scale(1.1);
        }

        .bottom-bar {
            width: 100%;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #ccc;
        }

        /* Accessibility */
        a:focus, button:focus, input:focus, textarea:focus {
            outline: 2px solid var(--yellow-accent);
            outline-offset: 2px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 15px;
            }

            nav ul {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .logo img {
                height: 60px;
            }

            .logo span {
                font-size: 1.1rem;
            }

            .intro-section h2 {
                font-size: 2.4rem;
            }

            .feedback h2,
            .feedback-display h2 {
                font-size: 2rem;
            }

            .feedback-card {
                width: 100%;
            }

            footer {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .intro-section h2 {
                font-size: 2rem;
            }

            .feedback h2,
            .feedback-display h2 {
                font-size: 1.8rem;
            }

            .logo img {
                height: 50px;
            }

            .feedback button {
                width: 100%;
            }

            .rating label {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <header>
        <nav role="navigation" aria-label="Main navigation">
            <div class="logo">
                <img src="../photos/logo.png" alt="GreenLife Wellness Center Logo">
                <span>GreenLife Wellness</span>
            </div>
            <ul>
               <li><a href="customerhome.php">Home</a></li>
      <li><a href="Services.php">Services</a></li>
      <li><a href="doctors.php">Doctors contact</a></li>
      <li><a href="MedicalHistory.php"> Medical History</a></li>
      <li><a href="feedback.php"class="active">feedback</a></li>
      <li><a href="packages.php" >Packages</a></li>
            </ul>
        </nav>
    </header>

    <!-- Intro Section -->
    <section class="intro-section">
        <h2>Share Your Feedback</h2>
        <p>Your thoughts help us improve our services at GreenLife Wellness Center.</p>
    </section>

    <!-- Feedback Form Section -->
    <section class="feedback">
        <div class="form-container">
            <h2><i class="fas fa-comment"></i> Submit Your Feedback</h2>
            <form id="feedback-form">
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Your Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo $user_name; ?>" required aria-label="Your Name">
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Your Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo $user_email; ?>" required aria-label="Your Email">
                </div>
                <div class="form-group">
                    <label for="feedback"><i class="fas fa-comment-dots"></i> Feedback</label>
                    <textarea id="feedback" name="feedback" placeholder="Enter your feedback" required aria-label="Feedback"></textarea>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-star"></i> Rating</label>
                    <div class="rating">
                        <input type="radio" id="star5" name="rating" value="5" required aria-label="5 stars">
                        <label for="star5">★</label>
                        <input type="radio" id="star4" name="rating" value="4" aria-label="4 stars">
                        <label for="star4">★</label>
                        <input type="radio" id="star3" name="rating" value="3" aria-label="3 stars">
                        <label for="star3">★</label>
                        <input type="radio" id="star2" name="rating" value="2" aria-label="2 stars">
                        <label for="star2">★</label>
                        <input type="radio" id="star1" name="rating" value="1" aria-label="1 star">
                        <label for="star1">★</label>
                    </div>
                </div>
                <button type="submit"><span>Submit</span></button>
            </form>
            <div class="message" id="message" role="alert"></div>
        </div>
    </section>

    <!-- Feedback Display Section -->
    <section class="feedback-display">
        <h2><i class="fas fa-comments"></i> Customer Feedback</h2>
        <div class="feedback-list">
            <?php
            try {
                $conn = new PDO("mysql:host=localhost;dbname=greenlife", "root", "");
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT name, email, feedback, rating, submitted_at FROM feedback ORDER BY submitted_at DESC");
                $stmt->execute();
                $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($feedbacks) > 0) {
                    foreach ($feedbacks as $feedback) {
                        $name = htmlspecialchars($feedback['name']);
                        $email = htmlspecialchars($feedback['email']);
                        $feedback_text = htmlspecialchars($feedback['feedback']);
                        $rating = (int)$feedback['rating'];
                        $submitted_at = $feedback['submitted_at'];

                        $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);

                        echo "
                        <div class='feedback-card'>
                            <h3>$name</h3>
                            <p><strong>Email:</strong> $email</p>
                            <p><strong>Feedback:</strong> $feedback_text</p>
                            <div class='rating-stars'>$stars</div>
                            <p class='submitted-at'>Submitted on: $submitted_at</p>
                        </div>";
                    }
                } else {
                    echo "<p class='no-feedback'>No feedback submitted yet.</p>";
                }
            } catch (PDOException $e) {
                echo "<p class='no-feedback'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }

            $conn = null;
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer role="contentinfo">
        <div class="footer-column">
            <h3>GreenLife Wellness Center</h3>
            <ul>
                <li><a href="customerhome.php">Home</a></li>
                <li><a href="treatments.php">Treatments</a></li>
                <li><a href="Services.php">Services</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="contact.php">Feedback</a></li>
                <li><a href="dashboard.php">Packages</a></li>
            </ul>
        </div>
        <div class="footer-column footer-contact">
            <h3>Get in Touch</h3>
            <p>📍 GreenLife Wellness Center, Colombo, Sri Lanka</p>
            <p>📧 <a href="mailto:GreenLifeWellnessCenter@gmail.com" style="color:#ccc;">GreenLifeWellnessCenter@gmail.com</a></p>
            <p>📞 <a href="tel:+94769889741">+94 76 988 9741</a> (International)</p>
            <p>WhatsApp: <a href="https://wa.me/94769889741">+94 76 988 9741</a></p>
        </div>
        <div class="footer-column newsletter">
            <h3>Stay Connected</h3>
            <form id="newsletter-form">
                <input type="email" id="newsletter-email" placeholder="Enter your email" required aria-label="Email for newsletter subscription">
                <p class="error-message" id="newsletter-email-error">Please enter a valid email address.</p>
                <button type="submit">Subscribe</button>
            </form>
            <div class="social-icons">
                <a href="https://www.facebook.com" aria-label="Visit our Facebook page"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com" aria-label="Visit our Instagram page"><i class="fab fa-instagram"></i></a>
                <a href="https://x.com" aria-label="Visit our Twitter page"><i class="fab fa-x-twitter"></i></a>
                <a href="https://www.youtube.com" aria-label="Visit our YouTube channel"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        <div class="bottom-bar">
            &copy; <?php echo date("Y"); ?> GreenLife Wellness Center. All rights reserved.
        </div>
    </footer>

    <script>
        // Feedback Form Submission
        document.getElementById('feedback-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const submitButton = this.querySelector('button');
            submitButton.disabled = true;
            submitButton.querySelector('span').textContent = 'Submitting...';

            const formData = {
                name: document.getElementById('name').value.trim(),
                email: document.getElementById('email').value.trim(),
                feedback: document.getElementById('feedback').value.trim(),
                rating: document.querySelector('input[name="rating"]:checked')?.value
            };

            const messageDiv = document.getElementById('message');
            if (!formData.rating) {
                messageDiv.textContent = 'Please select a rating.';
                messageDiv.className = 'message error';
                submitButton.disabled = false;
                submitButton.querySelector('span').textContent = 'Submit';
                return;
            }

            try {
                const response = await fetch('../backend/submit_feedback.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                const result = await response.json();
                if (response.ok) {
                    messageDiv.textContent = result.message;
                    messageDiv.className = 'message success';
                    document.getElementById('feedback-form').reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    messageDiv.textContent = result.error;
                    messageDiv.className = 'message error';
                }
            } catch (error) {
                messageDiv.textContent = 'Failed to submit feedback. Please try again.';
                messageDiv.className = 'message error';
            } finally {
                submitButton.disabled = false;
                submitButton.querySelector('span').textContent = 'Submit';
            }
        });

        // Newsletter Form Validation
        document.getElementById('newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = document.getElementById('newsletter-email');
            const emailError = document.getElementById('newsletter-email-error');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(emailInput.value)) {
                emailInput.classList.add('invalid');
                emailError.style.display = 'block';
            } else {
                emailInput.classList.remove('invalid');
                emailError.style.display = 'none';
                alert('Thank you for subscribing!');
                emailInput.value = '';
            }
        });

        // Smooth Scroll for Navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>