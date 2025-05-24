


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Flights - Nova Travels</title>
    <style>
        :root {
            --cherry-red: #b22234; /* Same red */
            --off-white: #ffff;  /* Background color */
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: var(--off-white);
        }

        /* Navbar */
        header {
            background-color: var(--cherry-red);
            padding: 0.8rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0 40px;
            background-color: #ffffff;
            height: 80px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .logo {
            padding-top: 15px;
            width: 170px; /* العرض */
            height: auto; /* يجعل الارتفاع يتناسب تلقائياً */
            margin-right: 140px;  
            margin-bottom: 1mm; /* إضافة المسافة بين الشعار و شريط البحث */
        }

        .search-bar input {
            margin-left: 1px;
            padding: 10px 20px;
            border-radius: 25px;
            border: 1.4px solid #B22234;
            width: 300px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #B22234;
            box-shadow: 0 0 5px #7d010b;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-left: 40px;
        }

        .nav-link {
            text-decoration: none;
            color: #B22234;
            font-weight: bold;
            font-size: 16px;
            position: relative;
            padding-bottom: 5px;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0%;
            height: 2px;
            background-color: #B22234;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: #7d010b;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
            margin-left: auto;
            padding-right: 20px;
        }

        .login-btn,
        .signup-btn {
            background-color: #B22234;
            color: #ffffff;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-btn:hover,
        .signup-btn:hover {
            background-color: #7d010b;
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Main content */
        main {
            padding: 40px 20px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        /* Flight List */
        .flight-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .flight-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            width: 250px;
            text-align: left;
            transition: transform 0.3s ease;
        }

        .flight-card:hover {
            transform: translateY(-10px);
        }

        /* Button Styles for "View Details" */
        .btn-details {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--cherry-red);
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 15px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn-details:hover {
            background-color: #a01d1d; /* darker red when hovering */
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <img src="logo.png" alt="Logo" class="logo">
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <div class="nav-links">
                <a href="index.html" class="nav-link">Home</a>
                <a href="reservation.html" class="nav-link">Book a Flight</a>
                <a href="reservations.html" class="nav-link">Reservations</a>
                <a href="login.html" class="nav-link">Log In</a>
            </div>
            <div class="auth-buttons">
                <button class="login-btn">Login</button>
                <button class="signup-btn">Sign Up</button>
            </div>
        </nav>
    </header>

    <main>
    <h1>Available Flights</h1>

    <div class="flight-list">
    <?php if (!empty($flights)): ?>
        <?php foreach ($flights as $flight): ?>
            <div class="flight-card">
                <h3>Flight Number: <?php echo htmlspecialchars($flight['flight_number']); ?></h3>
                <p>From: <?php echo htmlspecialchars($flight['departure_city']); ?></p>
                <p>To: <?php echo htmlspecialchars($flight['arrival_city']); ?></p>
                <p>Departure: <?php echo htmlspecialchars($flight['departure_time']); ?></p>
                <p>Arrival: <?php echo htmlspecialchars($flight['arrival_time']); ?></p>
                <a href="flight.php?action=details&flight_number=<?php echo urlencode($flight['flight_number']); ?>" class="btn-details">View Details</a>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No flights found matching your criteria.</p>
    <?php endif; ?>
</div>

            <!-- Add more flights as needed -->
            </div> 
    </main>
</body>
</html>





