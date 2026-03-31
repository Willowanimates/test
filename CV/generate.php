<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.html");
    exit();
}

// Data Processing
$fullname  = htmlspecialchars($_POST['fullname'] ?? 'Name Not Provided');
$email     = htmlspecialchars($_POST['email'] ?? '');
$phone     = htmlspecialchars($_POST['phone'] ?? '');
$address   = htmlspecialchars($_POST['address'] ?? '');
$summary   = htmlspecialchars($_POST['summary'] ?? '');
$education = htmlspecialchars($_POST['education'] ?? '');
$skills    = htmlspecialchars($_POST['skills'] ?? '');

// Image Handling
$photoData = "";
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $imgBinary = file_get_contents($_FILES['profile_pic']['tmp_name']);
    $photoData = "data:" . $_FILES['profile_pic']['type'] . ";base64," . base64_encode($imgBinary);
} else {
    $photoData = "https://ui-avatars.com/api/?name=" . urlencode($fullname) . "&size=300&background=000&color=fff";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $fullname; ?> | Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@300;400;600;800&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --dark: #000000;
            --accent: #6366f1;
            --light-grey: #f8fafc;
            --border: #e2e8f0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: var(--dark);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 20px;
        }

        .cv-wrapper {
            width: 210mm;
            min-height: 297mm;
            background: white;
            position: relative;
            box-shadow: 0 40px 100px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Fixes accidental horizontal scroll */
        }

        /* 1. Header */
        .header-bg {
            background-color: var(--dark);
            min-height: 280px;
            width: 100%;
            position: relative;
            padding: 60px 80px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content { max-width: 60%; }

        .header-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(32px, 5vw, 56px);
            line-height: 1.1;
            margin-bottom: 10px;
            word-wrap: break-word;
        }

        .header-content p {
            font-family: 'JetBrains Mono', monospace;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 13px;
        }

        .profile-frame {
            width: 200px;
            height: 260px;
            background: var(--accent);
            padding: 8px;
            transform: rotate(3deg);
            box-shadow: 15px 15px 0px #1a1a1a;
            flex-shrink: 0;
        }

        .profile-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* 2. Contact Strip - Fixed Wrap */
        .contact-strip {
            background: var(--light-grey);
            padding: 20px 80px;
            display: flex;
            flex-wrap: wrap; /* Allows wrapping on small screens or long text */
            gap: 25px;
            border-bottom: 1px solid var(--border);
        }

        .contact-item {
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            word-break: break-all;
        }

        .contact-item i { color: var(--accent); font-size: 14px; }

        /* 3. Content Body */
        .content-body {
            padding: 60px 80px;
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 50px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-style: italic;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--dark);
        }

        .section-title::after {
            content: "";
            height: 1px;
            flex-grow: 1;
            background: var(--border);
        }

        .text-block {
            font-size: 14px;
            line-height: 1.8;
            color: #334155;
            white-space: pre-line;
            margin-bottom: 40px;
            /* CRITICAL FIX FOR OVERFLOW */
            overflow-wrap: break-word; 
            word-wrap: break-word;
            word-break: break-word;
        }

        /* Skills */
        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .skill-chip {
            border: 2px solid var(--dark);
            padding: 6px 14px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            background: white;
            transition: 0.2s;
        }

        .skill-chip:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0px var(--dark);
        }

        /* Action Bar */
        .action-bar {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 14px 30px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
            border: 2px solid var(--dark);
            text-decoration: none;
            font-size: 12px;
        }

        .btn-black { background: var(--dark); color: white; }
        .btn-edit { background: transparent; color: var(--dark); }

        @media print {
            body { background: none; padding: 0; }
            .cv-wrapper { box-shadow: none; border: none; width: 100%; }
            .action-bar { display: none; }
            .header-bg { -webkit-print-color-adjust: exact; }
            .profile-frame { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="cv-wrapper">
        <header class="header-bg">
            <div class="header-content">
                <p>Digital Profile</p>
                <h1><?php echo $fullname; ?></h1>
                <p>Tech Enthusiast // Solutions Specialist</p>
            </div>
            <div class="profile-frame">
                <img src="<?php echo $photoData; ?>" alt="Profile">
            </div>
        </header>

        <div class="contact-strip">
            <div class="contact-item"><i class="fa-solid fa-envelope"></i> <?php echo $email; ?></div>
            <div class="contact-item"><i class="fa-solid fa-phone"></i> <?php echo $phone; ?></div>
            <div class="contact-item"><i class="fa-solid fa-location-dot"></i> <?php echo $address; ?></div>
        </div>

        <div class="content-body">
            <div class="main-col">
                <section>
                    <h2 class="section-title">Professional Summary</h2>
                    <div class="text-block"><?php echo $summary; ?></div>
                </section>

                <section>
                    <h2 class="section-title">Education</h2>
                    <div class="text-block"><?php echo $education; ?></div>
                </section>
            </div>

            <div class="side-col">
                <section>
                    <h2 class="section-title">Expertise</h2>
                    <div class="skills-container">
                        <?php 
                        $skillsList = explode(',', $skills);
                        foreach($skillsList as $s) {
                            if(trim($s) != "") {
                                echo '<span class="skill-chip">' . trim($s) . '</span>';
                            }
                        }
                        ?>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="action-bar">
        <button class="btn btn-black" onclick="window.print()">Download PDF</button>
        <a href="index.html" class="btn btn-edit">Modify Info</a>
    </div>

</body>
</html>