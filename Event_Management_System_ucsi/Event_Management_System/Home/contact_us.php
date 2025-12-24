<?php
$page_title = "Contact Us - ICSDI 2026";
ob_start();

// PROCESS FORM SUBMISSION
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ajax'])) {
    header("Content-Type: application/json");

    $db = require_once(__DIR__ . '/../Database/database.php');

    $first   = trim($_POST['firstName'] ?? '');
    $last    = trim($_POST['lastName'] ?? '');
    $name    = $first . ' ' . $last;
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($first === '' || $last === '' || $email === '' || $subject === '' || $message === '') {
        echo json_encode(["status" => "error", "message" => "All required fields must be filled."]);
        exit;
    }

    try {
        $stmt = $db->prepare("
            INSERT INTO contact_us (name, email, subject, message)
            VALUES (:name, :email, :subject, :message)
        ");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':subject' => $subject,
            ':message' => $message
        ]);

        echo json_encode(["status" => "success", "message" => "Your message was sent successfully."]);
        exit;

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Database Error: ".$e->getMessage()]);
        exit;
    }
}
?>

<!-- ======================= CONTACT PAGE FRONTEND ========================== -->

<div class="container py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="fw-bold display-4 mb-3">Contact Us</h1>
            <p class="lead">Get in touch with the ICSDI 2026 organizing committee. We're here to help!</p>
        </div>
    </div>

    <div class="row">
        <!-- Contact Information -->
        <div class="col-lg-4 mb-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">Get In Touch</h3>

                    <div class="contact-item d-flex mb-4">
                        <div class="icon-wrapper bg-primary text-white rounded-circle me-3" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Address</h5>
                            <p class="text-muted mb-0">ucsi<br></p>
                        </div>
                    </div>

                    <div class="contact-item d-flex mb-4">
                        <div class="icon-wrapper bg-success text-white rounded-circle me-3" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Phone</h5>
                            <p class="text-muted mb-0"><br></p>
                        </div>
                    </div>

                    <div class="contact-item d-flex mb-4">
                        <div class="icon-wrapper bg-warning text-white rounded-circle me-3" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Email</h5>
                            <p class="text-muted mb-0">info@icsdi2026.org<br>support@icsdi2026.org</p>
                        </div>
                    </div>

                    <div class="contact-item d-flex">
                        <div class="icon-wrapper bg-info text-white rounded-circle me-3" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Office Hours</h5>
                            <p class="text-muted mb-0">Sunday - Thursday<br>8:00 AM - 5:00 PM GST</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-primary fs-4"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-primary fs-4"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-primary fs-4"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-primary fs-4"><i class="fab fa-instagram"></i></a>
                    </div>

                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">Send us a Message</h3>

                    <form id="contactForm" method="POST" novalidate>
                        <input type="hidden" name="ajax" value="1">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                                <div class="invalid-feedback">Please provide your first name.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                                <div class="invalid-feedback">Please provide your last name.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Please provide a valid email.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Subject *</label>
                            <select class="form-select" id="subject" name="subject" required>
                                <option value="">Select a subject</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Registration Help">Registration Help</option>
                                <option value="Paper Submission">Paper Submission</option>
                                <option value="Sponsorship">Sponsorship Opportunities</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback">Please select a subject.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                            <div class="invalid-feedback">Please provide a message.</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                <label class="form-check-label" for="newsletter">Subscribe to our newsletter</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i>Send Message
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== JS (AJAX + VALIDATION) ========== -->
<script>
$(document).ready(function () {

    $('#contactForm').on('submit', function (e) {
        e.preventDefault();

        let form = this;
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        let submitBtn = $(form).find('button[type="submit"]');
        let oldText = submitBtn.html();

        submitBtn.prop("disabled", true)
                 .html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');

        $.post("", $(form).serialize(), function (response) {

            if (response.status === "success") {
                alert("Thank you for contacting us. We will respond soon.");
                form.reset();
                form.classList.remove('was-validated');
            } else {
                alert("Error: " + response.message);
            }

            submitBtn.prop("disabled", false).html(oldText);

        }, "json");
    });

    // Phone formatting
    $('#phone').on('input', function () {
        let v = $(this).val().replace(/\D/g, '');
        if (v.length > 0) v = v.match(/.{1,4}/g).join(' ');
        $(this).val(v);
    });

});
</script>

<?php
$content = ob_get_clean();
include 'base.php';
?>
