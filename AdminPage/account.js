// Password visibility toggle functionality 
function togglePasswordVisibility(icon, targetId) {
    const passwordInput = document.getElementById(targetId);
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        togglePasswordVisibility(this, targetId);
    });
});

// Password validation function
function checkRequirement(isValid, element, message) {
    if (isValid) {
        element.innerHTML = `<i class="fa-solid fa-check requirement-met"></i> ${message}`;
        element.classList.add('requirement-met');
        element.classList.remove('requirement-unmet');
    } else {
        element.innerHTML = `<i class="fa-solid fa-x requirement-unmet"></i> ${message}`;
        element.classList.remove('requirement-met');
        element.classList.add('requirement-unmet');
    }
}

function validatePassword(password, lengthId, uppercaseId, numberId, specialId) {
    checkRequirement(password.length >= 8, document.getElementById(lengthId), 'At least 8 characters');
    checkRequirement(/[A-Z]/.test(password), document.getElementById(uppercaseId), 'At least 1 uppercase letter');
    checkRequirement(/[0-9]/.test(password), document.getElementById(numberId), 'At least 1 number');
    checkRequirement(/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password), document.getElementById(specialId), 'At least 1 special character');
}

// Check if password meets all requirements
function checkPasswordRequirements(password) {
    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    
    return hasLength && hasUppercase && hasNumber && hasSpecial;
}

// Password validation for change password form
const newPasswordInput = document.getElementById('newPassword');
const confirmPasswordInput = document.getElementById('confirmPassword');

if (newPasswordInput) {
    newPasswordInput.addEventListener('input', function() {
        validatePassword(this.value, 'length-requirement', 'uppercase-requirement', 'number-requirement', 'special-requirement');
    });
}

// Password validation for trader form
const traderPasswordInput = document.getElementById('password');
const confirmTraderPasswordInput = document.getElementById('confirmTraderPassword');

if (traderPasswordInput) {
    traderPasswordInput.addEventListener('input', function() {
        validatePassword(this.value, 'trader-length-requirement', 'trader-uppercase-requirement', 'trader-number-requirement', 'trader-special-requirement');
    });
}

// Show/hide password requirements on focus/blur for new password
if (newPasswordInput) {
    const newRequirements = document.getElementById('new-password-requirements');
    
    newPasswordInput.addEventListener('focus', function () {
        newRequirements.style.display = 'block';
    });

    newPasswordInput.addEventListener('blur', function () {
        setTimeout(() => {
            newRequirements.style.display = 'none';
        }, 150);
    });
}

// Show/hide password requirements on focus/blur for trader password
if (traderPasswordInput) {
    const traderRequirements = document.getElementById('trader-password-requirements');

    traderPasswordInput.addEventListener('focus', function () {
        traderRequirements.style.display = 'block';
    });

    traderPasswordInput.addEventListener('blur', function () {
        setTimeout(() => {
            traderRequirements.style.display = 'none';
        }, 150);
    });
}

// Logout functionality
document.getElementById("logoutBtn").addEventListener("click", function() {
    const confirmLogout = confirm("Are you sure you want to log out?");
    if (confirmLogout) {
        window.location.href = "login.html";
    }
});

// Show/hide content
function showContent(contentId) {
    // Hide all content cards
    document.querySelectorAll('.content-card').forEach(card => {
        card.classList.add('hidden');
    });
    
    // Show the selected content
    document.getElementById(contentId + '-content').classList.remove('hidden');
}

// Button click handlers
const buttons = [
    { id: 'edit-profile-btn', content: 'edit-profile' },
    { id: 'change-password-btn', content: 'change-password' },
    { id: 'create-trader-btn', content: 'create-trader' }
];

buttons.forEach(button => {
    document.getElementById(button.id).addEventListener('click', () => {
        showContent(button.content);
    });
});

// Back button handlers
document.getElementById('back-from-edit').addEventListener('click', function(e) {
    e.preventDefault();
    showContent('admin-profile');
});

document.getElementById('back-from-password').addEventListener('click', function(e) {
    e.preventDefault();
    showContent('admin-profile');
});

document.getElementById('back-from-trader').addEventListener('click', function(e) {
    e.preventDefault();
    showContent('admin-profile');
});

// Form submission handlers
function validateForm(formId, passwordInputId, confirmPasswordInputId, confirmPasswordMessage, alertMessage) {
    const password = document.getElementById(passwordInputId).value;
    const confirmPassword = document.getElementById(confirmPasswordInputId).value;

    if (!checkPasswordRequirements(password)) {
        alert('Please make sure your password meets all requirements.');
        return false;
    }
    
    if (password !== confirmPassword) {
        alert(confirmPasswordMessage);
        return false;
    }

    const requiredFields = document.getElementById(formId).querySelectorAll('[required]');
    let allFieldsValid = true;
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('invalid-input');
            allFieldsValid = false;
        } else {
            field.classList.remove('invalid-input');
        }
    });

    if (!allFieldsValid) {
        alert('Please fill in all required fields.');
        return false;
    }

    alert(alertMessage);
    return true;
}

document.getElementById('edit-profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Profile updated successfully!');
    showContent('admin-profile');
});

document.getElementById('change-password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    if (validateForm('change-password-form', 'newPassword', 'confirmPassword', 'New password and confirm password do not match!', 'Password changed successfully!')) {
        showContent('admin-profile');
    }
});

document.getElementById('create-trader-form').addEventListener('submit', function(e) {
    e.preventDefault();
    if (validateForm('create-trader-form', 'password', 'confirmTraderPassword', 'Password and confirm password do not match!', 'Trader account created successfully!')) {
        showContent('admin-profile');
    }
});

// Handle the fixed country code "+63" in contact number
document.getElementById("contactNumber").addEventListener("input", function(e) {
    const input = e.target;
    
    // Ensure that only the digits after +63 can be typed.
    if (input.value.length <= 3) {
        input.value = '+63'; // Ensure the +63 stays at the start
    } else {
        // Limit the input to 13 characters (+63 + 10 digits max)
        input.value = '+63' + input.value.slice(4, 14);
    }
});

