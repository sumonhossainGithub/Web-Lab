/**
 * Student Registration System - Apex University
 * Core JavaScript: Form Validation & Dynamic DOM Manipulation
 */

document.addEventListener('DOMContentLoaded', () => {
  // --- DOM Element References ---
  const form = document.getElementById('studentRegistrationForm');
  const submitBtn = document.getElementById('submitBtn');
  const resetBtn = document.getElementById('resetBtn');
  const statusBanner = document.getElementById('status-banner');

  // Input Fields
  const fullNameInput = document.getElementById('fullName');
  const emailInput = document.getElementById('email');
  const phoneInput = document.getElementById('phone');
  const dobInput = document.getElementById('dob');
  const departmentSelect = document.getElementById('department');
  const genderRadios = document.querySelectorAll('input[name="gender"]');
  const genderFieldset = document.getElementById('group-gender');
  const passwordInput = document.getElementById('password');
  const togglePasswordBtn = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eye-icon');
  const eyeOffIcon = document.getElementById('eye-off-icon');

  // Error Message Elements (Beside / Below fields)
  const errors = {
    fullName: document.getElementById('error-fullName'),
    email: document.getElementById('error-email'),
    phone: document.getElementById('error-phone'),
    dob: document.getElementById('error-dob'),
    department: document.getElementById('error-department'),
    gender: document.getElementById('error-gender'),
    password: document.getElementById('error-password'),
  };

  // Table & Record Display Elements
  const emptyState = document.getElementById('empty-state');
  const tableWrapper = document.getElementById('table-wrapper');
  const studentsTableBody = document.getElementById('studentsTableBody');
  const recordCounter = document.getElementById('record-counter');
  const clearAllBtn = document.getElementById('clearAllBtn');

  // Registered students array
  let studentRecords = [];
  let recordIdSequence = 1;

  // Max date constraint for Date of Birth (cannot be born in future or today)
  const today = new Date().toISOString().split('T')[0];
  dobInput.setAttribute('max', today);

  // --- Password Visibility Toggle ---
  if (togglePasswordBtn) {
    togglePasswordBtn.addEventListener('click', () => {
      const isPassword = passwordInput.getAttribute('type') === 'password';
      passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
      togglePasswordBtn.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
      togglePasswordBtn.setAttribute(
        'aria-label',
        isPassword ? 'Hide password' : 'Show password as plain text'
      );

      if (isPassword) {
        eyeIcon.style.display = 'none';
        eyeOffIcon.style.display = 'block';
      } else {
        eyeIcon.style.display = 'block';
        eyeOffIcon.style.display = 'none';
      }
    });
  }

  // --- Validation Helper Functions ---

  /**
   * Display error message beside the field
   */
  function showError(fieldKey, message, controlElement) {
    const errorEl = errors[fieldKey];
    if (errorEl) {
      errorEl.textContent = message;
      errorEl.classList.add('active');
    }

    if (controlElement) {
      controlElement.classList.add('is-invalid');
      controlElement.setAttribute('aria-invalid', 'true');
    }
  }

  /**
   * Clear error message for a specific field
   */
  function clearError(fieldKey, controlElement) {
    const errorEl = errors[fieldKey];
    if (errorEl) {
      errorEl.textContent = '';
      errorEl.classList.remove('active');
    }

    if (controlElement) {
      controlElement.classList.remove('is-invalid');
      controlElement.removeAttribute('aria-invalid');
    }
  }

  /**
   * Clear all error states from form
   */
  function clearAllErrors() {
    Object.keys(errors).forEach((key) => {
      const errorEl = errors[key];
      if (errorEl) {
        errorEl.textContent = '';
        errorEl.classList.remove('active');
      }
    });

    form.querySelectorAll('.is-invalid').forEach((el) => {
      el.classList.remove('is-invalid');
      el.removeAttribute('aria-invalid');
    });

    genderFieldset.classList.remove('is-invalid');
  }

  /**
   * Validate Email Format using RFC 5322 compliant regex
   */
  function isValidEmail(email) {
    const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/;
    return emailRegex.test(email.trim());
  }

  /**
   * Validate Phone Format (minimum 7 digits, allowed punctuation)
   */
  function isValidPhone(phone) {
    const cleanDigits = phone.replace(/[^0-9]/g, '');
    const phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s./0-9]{6,15}$/;
    return cleanDigits.length >= 7 && phoneRegex.test(phone.trim());
  }

  // --- Individual Field Validators ---

  function validateFullName() {
    const val = fullNameInput.value.trim();
    if (!val) {
      showError('fullName', 'Full Name is required.', fullNameInput);
      return false;
    }
    if (val.length < 2) {
      showError('fullName', 'Full Name must be at least 2 characters long.', fullNameInput);
      return false;
    }
    if (!/^[a-zA-Z\s.'-]+$/.test(val)) {
      showError('fullName', 'Full Name should only contain alphabetic characters.', fullNameInput);
      return false;
    }
    clearError('fullName', fullNameInput);
    return true;
  }

  function validateEmail() {
    const val = emailInput.value.trim();
    if (!val) {
      showError('email', 'Email Address is required.', emailInput);
      return false;
    }
    if (!isValidEmail(val)) {
      showError('email', 'Please enter a valid email address (e.g. name@domain.com).', emailInput);
      return false;
    }
    clearError('email', emailInput);
    return true;
  }

  function validatePhone() {
    const val = phoneInput.value.trim();
    if (!val) {
      showError('phone', 'Phone number is required.', phoneInput);
      return false;
    }
    if (!isValidPhone(val)) {
      showError('phone', 'Please enter a valid phone number (at least 7 digits).', phoneInput);
      return false;
    }
    clearError('phone', phoneInput);
    return true;
  }

  function validateDob() {
    const val = dobInput.value;
    if (!val) {
      showError('dob', 'Date of Birth is required.', dobInput);
      return false;
    }
    const selectedDate = new Date(val);
    const currentDate = new Date();
    if (selectedDate > currentDate) {
      showError('dob', 'Date of Birth cannot be in the future.', dobInput);
      return false;
    }
    // Check reasonable age (at least 5 years old)
    const ageDiff = currentDate.getFullYear() - selectedDate.getFullYear();
    if (ageDiff < 5) {
      showError('dob', 'Student must be at least 5 years old.', dobInput);
      return false;
    }
    clearError('dob', dobInput);
    return true;
  }

  function validateDepartment() {
    const val = departmentSelect.value;
    if (!val) {
      showError('department', 'Please select an academic department.', departmentSelect);
      return false;
    }
    clearError('department', departmentSelect);
    return true;
  }

  function validateGender() {
    let isSelected = false;
    for (const radio of genderRadios) {
      if (radio.checked) {
        isSelected = true;
        break;
      }
    }
    if (!isSelected) {
      showError('gender', 'Please select a gender option.', genderFieldset);
      return false;
    }
    clearError('gender', genderFieldset);
    return true;
  }

  function validatePassword() {
    const val = passwordInput.value;
    if (!val) {
      showError('password', 'Password is required.', passwordInput);
      return false;
    }
    if (val.length < 8) {
      showError('password', 'Password must be at least 8 characters long.', passwordInput);
      return false;
    }
    clearError('password', passwordInput);
    return true;
  }

  // --- Real-time Validation Triggers (Clear error on input) ---
  fullNameInput.addEventListener('input', () => {
    if (fullNameInput.classList.contains('is-invalid')) validateFullName();
  });
  emailInput.addEventListener('input', () => {
    if (emailInput.classList.contains('is-invalid')) validateEmail();
  });
  phoneInput.addEventListener('input', () => {
    if (phoneInput.classList.contains('is-invalid')) validatePhone();
  });
  dobInput.addEventListener('change', () => {
    if (dobInput.classList.contains('is-invalid')) validateDob();
  });
  departmentSelect.addEventListener('change', () => {
    if (departmentSelect.classList.contains('is-invalid')) validateDepartment();
  });
  genderRadios.forEach((radio) => {
    radio.addEventListener('change', () => {
      validateGender();
    });
  });
  passwordInput.addEventListener('input', () => {
    if (passwordInput.classList.contains('is-invalid')) validatePassword();
  });

  // --- Form Submission Handler ---
  form.addEventListener('submit', (e) => {
    // Prevent default browser submission/page reload
    e.preventDefault();

    // Perform validation across all required fields
    const isNameValid = validateFullName();
    const isEmailValid = validateEmail();
    const isPhoneValid = validatePhone();
    const isDobValid = validateDob();
    const isDeptValid = validateDepartment();
    const isGenderValid = validateGender();
    const isPassValid = validatePassword();

    const isFormValid = (
      isNameValid &&
      isEmailValid &&
      isPhoneValid &&
      isDobValid &&
      isDeptValid &&
      isGenderValid &&
      isPassValid
    );

    if (!isFormValid) {
      // Focus the first invalid element without using alert boxes
      const firstInvalidControl = form.querySelector('.is-invalid');
      if (firstInvalidControl) {
        if (firstInvalidControl.tagName === 'FIELDSET') {
          const firstRadio = firstInvalidControl.querySelector('input[type="radio"]');
          if (firstRadio) firstRadio.focus();
        } else {
          firstInvalidControl.focus();
        }
      }

      showStatusBanner('Please correct the highlighted errors before submitting the form.', 'error');
      return;
    }

    // Retrieve selected gender value
    let selectedGender = '';
    genderRadios.forEach((radio) => {
      if (radio.checked) selectedGender = radio.value;
    });

    // Extract student record object
    const studentData = {
      id: recordIdSequence++,
      fullName: fullNameInput.value.trim(),
      email: emailInput.value.trim(),
      phone: phoneInput.value.trim(),
      dob: dobInput.value,
      department: departmentSelect.value,
      gender: selectedGender,
      registeredAt: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    };

    // Store in records
    studentRecords.push(studentData);

    // Dynamic DOM manipulation: Append record to table
    appendStudentRow(studentData);

    // Update Counter
    updateCounter();

    // Show accessible confirmation banner
    showStatusBanner(`Success! Student "${escapeHTML(studentData.fullName)}" has been registered.`, 'success');

    // Reset Form
    form.reset();
    clearAllErrors();

    // Scroll to the records table if on mobile, or gently notify
    const recordsSection = document.getElementById('records-section');
    if (recordsSection && window.innerWidth <= 768) {
      recordsSection.scrollIntoView({ behavior: 'smooth' });
    }
  });

  // --- Reset Form Handler ---
  form.addEventListener('reset', () => {
    clearAllErrors();
    statusBanner.hidden = true;
    statusBanner.textContent = '';
    statusBanner.className = 'status-banner';
    // Reset password toggle view
    passwordInput.setAttribute('type', 'password');
    togglePasswordBtn.setAttribute('aria-pressed', 'false');
    eyeIcon.style.display = 'block';
    eyeOffIcon.style.display = 'none';
  });

  // --- Clear All Records Handler ---
  if (clearAllBtn) {
    clearAllBtn.addEventListener('click', () => {
      studentRecords = [];
      studentsTableBody.innerHTML = '';
      updateCounter();
      tableWrapper.style.display = 'none';
      emptyState.style.display = 'block';
      clearAllBtn.style.display = 'none';
      showStatusBanner('All student records have been cleared.', 'success');
    });
  }

  // --- DOM Manipulation: Table Rendering ---

  /**
   * Dynamically appends a new table row for the registered student
   */
  function appendStudentRow(student) {
    // Hide empty state and show table
    emptyState.style.display = 'none';
    tableWrapper.style.display = 'block';
    if (clearAllBtn) clearAllBtn.style.display = 'inline-flex';

    // Create table row element
    const tr = document.createElement('tr');
    tr.id = `student-row-${student.id}`;

    // Format Date for clear display
    const formattedDob = formatDateString(student.dob);

    // Safely construct table cells with textContent or sanitized markup
    tr.innerHTML = `
      <td><strong>#${student.id}</strong></td>
      <td><strong>${escapeHTML(student.fullName)}</strong></td>
      <td><a href="mailto:${escapeHTML(student.email)}" class="email-link">${escapeHTML(student.email)}</a></td>
      <td>${escapeHTML(student.phone)}</td>
      <td>${formattedDob}</td>
      <td><span class="badge badge-dept">${escapeHTML(student.department)}</span></td>
      <td><span class="badge badge-gender">${escapeHTML(student.gender)}</span></td>
      <td>
        <button type="button" class="btn-delete-row" data-id="${student.id}" aria-label="Delete record for ${escapeHTML(student.fullName)}">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
          <span>Delete</span>
        </button>
      </td>
    `;

    // Attach row deletion event listener
    const deleteBtn = tr.querySelector('.btn-delete-row');
    deleteBtn.addEventListener('click', () => {
      removeStudentRow(student.id, student.fullName);
    });

    // Append to tbody
    studentsTableBody.appendChild(tr);
  }

  /**
   * Dynamically removes a student row via DOM manipulation
   */
  function removeStudentRow(id, name) {
    const row = document.getElementById(`student-row-${id}`);
    if (row) {
      row.remove();
      studentRecords = studentRecords.filter((s) => s.id !== id);
      updateCounter();

      if (studentRecords.length === 0) {
        tableWrapper.style.display = 'none';
        emptyState.style.display = 'block';
        if (clearAllBtn) clearAllBtn.style.display = 'none';
      }

      showStatusBanner(`Removed student record for "${escapeHTML(name)}".`, 'success');
    }
  }

  /**
   * Update the badge counter in navigation
   */
  function updateCounter() {
    if (recordCounter) {
      recordCounter.textContent = studentRecords.length;
    }
  }

  /**
   * Accessible Status Banner Helper
   */
  function showStatusBanner(message, type) {
    statusBanner.hidden = false;
    statusBanner.className = `status-banner ${type}`;
    const icon = type === 'success' ? '✅' : '⚠️';
    statusBanner.innerHTML = `<span>${icon}</span> <span>${message}</span>`;

    // Auto-scroll into view if needed
    statusBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  /**
   * Format YYYY-MM-DD date into human friendly format
   */
  function formatDateString(dateStr) {
    if (!dateStr) return '';
    try {
      const parts = dateStr.split('-');
      if (parts.length === 3) {
        const year = parts[0];
        const month = parseInt(parts[1], 10);
        const day = parseInt(parts[2], 10);
        const monthNames = [
          'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
          'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
        ];
        return `${monthNames[month - 1]} ${day}, ${year}`;
      }
      return dateStr;
    } catch {
      return dateStr;
    }
  }

  /**
   * Prevent XSS when inserting into table HTML
   */
  function escapeHTML(str) {
    if (!str) return '';
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }
});
