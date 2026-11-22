<?php
session_start();

$errors = [];
$data = [];
$submitted = false;

function clean($value) {
    return trim((string)$value);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fields = [
        "full_name" => "Full name",
        "dob" => "Date of birth",
        "gender" => "Gender",
        "email" => "Email",
        "phone" => "Phone number",
        "address" => "Address",
        "course" => "Course",
        "qualification" => "Previous qualification",
    ];

    foreach ($fields as $key => $label) {
        $data[$key] = clean($_POST[$key] ?? "");
        if ($data[$key] === "") {
            $errors[] = "$label is required.";
        }
    }

    if (!filter_var($data["email"] ?? "", FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $data["phone"] ?? "")) {
        $errors[] = "Please enter a valid phone number.";
    }
    if (!empty($data["dob"]) && $data["dob"] > date("Y-m-d")) {
        $errors[] = "Date of birth cannot be in the future.";
    }
    if (!in_array($data["gender"] ?? "", ["Female", "Male", "Prefer not to say"], true)) {
        $errors[] = "Please choose a valid gender option.";
    }
    if (!in_array($data["course"] ?? "", ["Computer Science", "Information Technology", "Commerce", "Business Administration", "Other"], true)) {
        $errors[] = "Please choose a valid course.";
    }
    if (empty($_POST["declaration"])) {
        $errors[] = "Please accept the declaration to submit.";
    }

    if (!$errors) {
        $submitted = true;
        $data["application_id"] = "APP-" . date("Ymd") . "-" . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        $_SESSION["last_application"] = $data;
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Registration Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<header class="site-header">
    <div class="brand"><span class="brand-mark">OR</span><span>Online Registration <small>APPLICATION PORTAL</small></span></div>
    <span class="header-tag">Secure application form</span>
</header>

<main class="container">
<?php if ($submitted): ?>
    <section class="receipt" id="receipt">
        <div class="success-banner">
            <div class="success-icon">✓</div>
            <div><h1>Application submitted successfully</h1><p>Your details have been received and are shown below.</p></div>
        </div>
        <div class="receipt-top">
            <div><p class="eyebrow">APPLICATION RECEIPT</p><h2>Registration details</h2></div>
            <div class="application-number"><span>APPLICATION ID</span><strong><?= e($data["application_id"]) ?></strong></div>
        </div>
        <div class="receipt-grid">
            <div class="detail"><span>Full name</span><strong><?= e($data["full_name"]) ?></strong></div>
            <div class="detail"><span>Date of birth</span><strong><?= e($data["dob"]) ?></strong></div>
            <div class="detail"><span>Gender</span><strong><?= e($data["gender"]) ?></strong></div>
            <div class="detail"><span>Email address</span><strong><?= e($data["email"]) ?></strong></div>
            <div class="detail"><span>Phone number</span><strong><?= e($data["phone"]) ?></strong></div>
            <div class="detail"><span>Course applied for</span><strong><?= e($data["course"]) ?></strong></div>
            <div class="detail full"><span>Address</span><strong><?= nl2br(e($data["address"])) ?></strong></div>
            <div class="detail full"><span>Previous qualification</span><strong><?= e($data["qualification"]) ?></strong></div>
        </div>
        <p class="receipt-note">Keep this application ID for your records. This is a demonstration receipt, not an official admission confirmation.</p>
        <div class="receipt-actions no-print">
            <button class="button primary" onclick="window.print()">Print / Save as PDF</button>
            <a class="button secondary" href="index.php">Submit another application</a>
        </div>
    </section>
<?php else: ?>
    <section class="intro">
        <p class="eyebrow">WELCOME</p>
        <h1>Start your application</h1>
        <p>Fill in the details below to complete your online registration.</p>
    </section>

    <?php if ($errors): ?>
        <div class="alert" role="alert">
            <strong>Please correct the following:</strong>
            <ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form id="registrationForm" class="form-card" method="post" action="index.php" novalidate>
        <div class="section-heading"><span>01</span><div><h2>Personal information</h2><p>Enter your details as they appear on your documents.</p></div></div>
        <div class="form-grid">
            <div class="field"><label for="full_name">Full name <em>*</em></label><input id="full_name" name="full_name" type="text" maxlength="100" placeholder="Enter your full name" value="<?= e($data["full_name"] ?? "") ?>" required><small class="field-error"></small></div>
            <div class="field"><label for="dob">Date of birth <em>*</em></label><input id="dob" name="dob" type="date" value="<?= e($data["dob"] ?? "") ?>" required><small class="field-error"></small></div>
            <div class="field"><label for="gender">Gender <em>*</em></label><select id="gender" name="gender" required><option value="">Select gender</option><?php foreach (["Female","Male","Prefer not to say"] as $option): ?><option <?= (($data["gender"] ?? "") === $option) ? "selected" : "" ?>><?= e($option) ?></option><?php endforeach; ?></select><small class="field-error"></small></div>
            <div class="field"><label for="phone">Phone number <em>*</em></label><input id="phone" name="phone" type="tel" maxlength="20" placeholder="e.g. 9876543210" value="<?= e($data["phone"] ?? "") ?>" required><small class="field-error"></small></div>
            <div class="field full"><label for="email">Email address <em>*</em></label><input id="email" name="email" type="email" maxlength="150" placeholder="you@example.com" value="<?= e($data["email"] ?? "") ?>" required><small class="field-error"></small></div>
            <div class="field full"><label for="address">Address <em>*</em></label><textarea id="address" name="address" rows="3" maxlength="500" placeholder="House number, street, city, state, PIN code" required><?= e($data["address"] ?? "") ?></textarea><small class="field-error"></small></div>
        </div>

        <div class="section-heading section-gap"><span>02</span><div><h2>Application information</h2><p>Choose the course you want to apply for.</p></div></div>
        <div class="form-grid">
            <div class="field"><label for="course">Course <em>*</em></label><select id="course" name="course" required><option value="">Select course</option><?php foreach (["Computer Science","Information Technology","Commerce","Business Administration","Other"] as $option): ?><option <?= (($data["course"] ?? "") === $option) ? "selected" : "" ?>><?= e($option) ?></option><?php endforeach; ?></select><small class="field-error"></small></div>
            <div class="field"><label for="qualification">Previous qualification <em>*</em></label><input id="qualification" name="qualification" type="text" maxlength="150" placeholder="e.g. PUC / 12th / Diploma" value="<?= e($data["qualification"] ?? "") ?>" required><small class="field-error"></small></div>
        </div>

        <div class="declaration"><label><input type="checkbox" name="declaration" id="declaration" value="yes" required> <span>I confirm that the information provided is accurate to the best of my knowledge.</span></label></div>
        <div class="form-footer"><p><em>*</em> Required fields</p><button class="button primary" type="submit">Submit application <span aria-hidden="true">→</span></button></div>
    </form>
<?php endif; ?>
</main>
<footer class="site-footer">Online Registration Portal <span>•</span> Student application demo</footer>
</body>
</html>
