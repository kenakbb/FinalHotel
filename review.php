<?php
session_start();
require_once 'config/db_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Review - Hotel Booking</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .review-container {
            max-width: 800px;
            margin: 20px auto;
        }
        
        .review-form {
            background: rgba(0,0,0,0.7);
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .review-card {
            background: rgba(0,0,0,0.5);
            border-left: 4px solid #ffbb2b;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .reviewer-name {
            color: #ffbb2b;
            font-weight: bold;
            font-size: 1.1em;
        }
        
        .review-date {
            color: #aaa;
            font-size: 0.9em;
        }
        
        .review-rating {
            color: #ffbb2b;
            margin-bottom: 10px;
        }
        
        .review-text {
            color: #fff;
            line-height: 1.6;
        }
        
        .rating-stars {
            display: flex;
            margin: 10px 0;
        }
        
        .star {
            font-size: 24px;
            cursor: pointer;
            color: #ccc;
            margin-right: 5px;
        }
        
        .star.active {
            color: #ffbb2b;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img class="header-banner" src="images/home_banner.jpg" alt="Hotel Banner">
        </header>
        
        <nav class="navbar">
            <ul class="nav-list">
                <li><a href="index.php">Home</a></li>
                <li><a href="room.php">Room &amp; Facilities</a></li>
                <li><a href="reservation.php">Online Reservation</a></li>
                <li class="active"><a href="review.php">Review</a></li>
                <li><a href="admin.php">Admin</a></li>
                <?php if(isset($_SESSION['uname'])): ?>
                <li><a href="logout.php">Logout (<?php echo $_SESSION['uname']; ?>)</a></li>
                <?php endif; ?>
            </ul>
            
            <div class="social-links">
                <a href="http://www.facebook.com"><img src="images/facebook.png" alt="Facebook"></a>
                <a href="http://www.twitter.com"><img src="images/twitter.png" alt="Twitter"></a>
            </div>
        </nav>
        
        <div class="review-container">
            <h3 class="section-title">Customer Reviews</h3>
            
            <!-- Review Form -->
            <div class="review-form">
                <h4 style="color: #ffbb2b; margin-bottom: 20px;">Submit Your Review</h4>
                
                <?php
                if(isset($_POST['submit_review'])) {
                    $name = mysqli_real_escape_string($conn, $_POST['name']);
                    $email = mysqli_real_escape_string($conn, $_POST['email']);
                    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
                    $review = mysqli_real_escape_string($conn, $_POST['review']);
                    
                    $sql = "INSERT INTO reviews (name, email, rating, review, created_at) 
                            VALUES ('$name', '$email', '$rating', '$review', NOW())";
                    
                    if(mysqli_query($conn, $sql)) {
                        echo '<div style="background: #4CAF50; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                                Thank you for your review! It has been submitted successfully.
                              </div>';
                    } else {
                        echo '<div style="background: #f44336; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                                Error submitting review. Please try again.
                              </div>';
                    }
                }
                
                // Check if reviews table exists, if not create it
                $check_table = "SHOW TABLES LIKE 'reviews'";
                $result = mysqli_query($conn, $check_table);
                
                if(mysqli_num_rows($result) == 0) {
                    // Create reviews table
                    $create_table = "CREATE TABLE IF NOT EXISTS reviews (
                        id INT PRIMARY KEY AUTO_INCREMENT,
                        name VARCHAR(100) NOT NULL,
                        email VARCHAR(100) NOT NULL,
                        rating INT NOT NULL,
                        review TEXT NOT NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        status ENUM('pending', 'approved') DEFAULT 'approved'
                    )";
                    
                    mysqli_query($conn, $create_table);
                    
                    // Insert sample reviews
                    $sample_reviews = [
                        ["John Doe", "john@example.com", 5, "Excellent service and comfortable rooms. Will definitely stay again!"],
                        ["Jane Smith", "jane@example.com", 4, "Great location and friendly staff. The room was clean and well-maintained."],
                        ["Robert Johnson", "robert@example.com", 5, "Best hotel experience ever! The food was amazing and staff very helpful."],
                        ["Sarah Williams", "sarah@example.com", 4, "Good value for money. Comfortable stay with all basic amenities."]
                    ];
                    
                    foreach($sample_reviews as $sample) {
                        $insert = "INSERT INTO reviews (name, email, rating, review, created_at) 
                                  VALUES ('{$sample[0]}', '{$sample[1]}', {$sample[2]}, '{$sample[3]}', NOW())";
                        mysqli_query($conn, $insert);
                    }
                }
                ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Your Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Rating:</label>
                        <div class="rating-stars" id="rating-stars">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <input type="hidden" id="rating" name="rating" value="5" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="review">Your Review:</label>
                        <textarea id="review" name="review" class="form-control" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" name="submit_review" class="btn">Submit Review</button>
                </form>
            </div>
            
            <!-- Display Reviews -->
            <div class="reviews-list">
                <h4 style="color: #ffbb2b; margin-bottom: 20px;">What Our Guests Say</h4>
                
                <?php
                $sql = "SELECT * FROM reviews WHERE status = 'approved' ORDER BY created_at DESC LIMIT 10";
                $result = mysqli_query($conn, $sql);
                
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="review-card">';
                        echo '<div class="review-header">';
                        echo '<div class="reviewer-name">' . $row['name'] . '</div>';
                        echo '<div class="review-date">' . date('F j, Y', strtotime($row['created_at'])) . '</div>';
                        echo '</div>';
                        
                        echo '<div class="review-rating">';
                        for($i = 1; $i <= 5; $i++) {
                            if($i <= $row['rating']) {
                                echo '<span style="color: #ffbb2b;">★</span>';
                            } else {
                                echo '<span style="color: #ccc;">★</span>';
                            }
                        }
                        echo ' (' . $row['rating'] . '/5)';
                        echo '</div>';
                        
                        echo '<div class="review-text">' . nl2br($row['review']) . '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div style="background: rgba(0,0,0,0.5); padding: 20px; border-radius: 4px; color: #ffbb2b;">
                            No reviews yet. Be the first to review our hotel!
                          </div>';
                }
                ?>
            </div>
        </div>
        
        <!-- Footer Sections -->
        <div class="row">
            <div class="col-md-4">
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <hr>
                    <p>Address: Patan Multiple Campus</p>
                    <p>Email: sakarshahi49@gmail.com</p>
                    <p>Email: shikhasapkota56@gmail.com</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <hr>
                    <a href="index.php">Home</a>
                    <a href="room.php">Rooms</a>
                    <a href="reservation.php">Book Now</a>
                    <a href="review.php">Reviews</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="footer-section">
                    <h4>Developed By</h4>
                    <hr>
                    <a href="#">Shikha Sapkota</a>
                    <a href="#">Sakar Shahi</a>
                    <p>BCA 4th Semester Project</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Rating stars functionality
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating');
            
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingInput.value = value;
                    
                    // Update stars display
                    stars.forEach(s => {
                        if(s.getAttribute('data-value') <= value) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });
                
                star.addEventListener('mouseover', function() {
                    const value = this.getAttribute('data-value');
                    stars.forEach(s => {
                        if(s.getAttribute('data-value') <= value) {
                            s.style.color = '#ffbb2b';
                        } else {
                            s.style.color = '#ccc';
                        }
                    });
                });
                
                star.addEventListener('mouseout', function() {
                    stars.forEach(s => {
                        const value = s.getAttribute('data-value');
                        if(value <= ratingInput.value) {
                            s.style.color = '#ffbb2b';
                        } else {
                            s.style.color = '#ccc';
                        }
                    });
                });
            });
            
            // Initialize with 5 stars
            stars.forEach(star => {
                if(star.getAttribute('data-value') <= 5) {
                    star.classList.add('active');
                }
            });
        });
    </script>
    
    <script src="js/script.js"></script>
</body>
</html>