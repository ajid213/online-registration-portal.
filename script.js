$(function () {
    const $form = $("#registrationForm");
    if (!$form.length) return;

    const $dob = $("#dob");
    const today = new Date();
    const localToday = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
    $dob.attr("max", localToday);

    function showError($field, message) {
        const $wrap = $field.closest(".field");
        $wrap.toggleClass("invalid", Boolean(message));
        $wrap.find(".field-error").text(message || "");
        $field.attr("aria-invalid", message ? "true" : "false");
    }

    function validateField($field) {
        const id = $field.attr("id");
        const value = $.trim($field.val() || "");
        let error = "";

        if ($field.prop("required") && !value) {
            error = "This field is required.";
        } else if (id === "email" && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            error = "Enter a valid email address.";
        } else if (id === "phone" && value && !/^[0-9+\-\s()]{7,20}$/.test(value)) {
            error = "Enter a valid phone number.";
        } else if (id === "dob" && value && value > localToday) {
            error = "Date of birth cannot be in the future.";
        } else if (id === "full_name" && value.length < 2) {
            error = "Please enter at least 2 characters.";
        }
        showError($field, error);
        return !error;
    }

    $form.find("input:not([type=checkbox]), select, textarea").on("blur change", function () {
        validateField($(this));
    });

    $form.on("submit", function (event) {
        let valid = true;
        $form.find("input:not([type=checkbox]), select, textarea").each(function () {
            if (!validateField($(this))) valid = false;
        });

        if (!$("#declaration").is(":checked")) {
            valid = false;
            $("#declaration").closest(".declaration").addClass("invalid");
        } else {
            $("#declaration").closest(".declaration").removeClass("invalid");
        }

        if (!valid) {
            event.preventDefault();
            const $firstInvalid = $form.find('[aria-invalid="true"]').first();
            if ($firstInvalid.length) $firstInvalid.trigger("focus");
        }
    });
});
