<?php
// Signup page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .signup-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: url('assets/img/landingBG.jpg') no-repeat center center fixed;
            background-size: cover;
            z-index: 0;
        }
        .signup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #00000057;
            z-index: 1;
        }
        .signup-container {
            background: #fff;
            max-width: 900px;
            margin: 70px auto;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        .signup-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #263646;
            margin-bottom: 40px;
            text-align: center;
        }
        .signup-options {
            display: flex;
            justify-content: center;
            gap: 60px;
            width: 100%;
        }
        .signup-option {
            flex: 1;
            text-align: center;
            padding: 0 20px;
        }
        .signup-option h2 {
            font-size: 1.5rem;
            color: #263646;
            margin-bottom: 18px;
        }
        .signup-option p {
            color: #5a6a7a;
            font-size: 1.1rem;
            margin-bottom: 28px;
        }
        .signup-btn {
            background: #1ec773;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 14px 38px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .signup-btn:hover {
            background: #17a65e;
        }
        @media (max-width: 700px) {
            .signup-container {
                padding: 30px 10px;
            }
            .signup-options {
                flex-direction: column;
                gap: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-bg"></div>
    <div class="signup-overlay"></div>
    <div class="signup-container">
        <div class="signup-title">Sign Up Free Account</div>
        <div class="signup-options">
            <div class="signup-option">
                <h2>Employer</h2>
                <p>Post project, find freelancers<br>and hire favorite to work.</p>
                <a href="employer_signup.php" class="signup-btn">Sign Up</a>
            </div>
            <div class="signup-option">
                <h2>Freelancer</h2>
                <p>Create professional profile and<br>find freelance jobs to work.</p>
                <a href="general_information.php" class="signup-btn">Sign Up</a>
            </div>
        </div>
    </div>
</body>
</html>