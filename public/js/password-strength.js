(function () {
    'use strict';

    var requirements = [
        { key: 'length', label: 'Tối thiểu 8 ký tự', test: function (value) { return value.length >= 8; } },
        { key: 'lower', label: 'Có chữ thường', test: function (value) { return /[a-z]/.test(value); } },
        { key: 'upper', label: 'Có chữ hoa', test: function (value) { return /[A-Z]/.test(value); } },
        { key: 'number', label: 'Có số', test: function (value) { return /[0-9]/.test(value); } },
        { key: 'special', label: 'Có ký tự đặc biệt', test: function (value) { return /[^A-Za-z0-9]/.test(value); } }
    ];

    var levels = [
        { className: 'very-weak', text: 'Mật khẩu rất yếu' },
        { className: 'very-weak', text: 'Mật khẩu rất yếu' },
        { className: 'weak', text: 'Mật khẩu yếu' },
        { className: 'medium', text: 'Mật khẩu trung bình' },
        { className: 'strong', text: 'Mật khẩu mạnh' },
        { className: 'very-strong', text: 'Mật khẩu rất mạnh' }
    ];

    function injectStyles() {
        if (document.getElementById('passwordStrengthStyles')) return;

        var style = document.createElement('style');
        style.id = 'passwordStrengthStyles';
        style.textContent = [
            '.password-strength{margin-top:7px;display:grid;gap:6px;font-size:12px;color:#64748b}',
            '.password-strength__bar{height:6px;border-radius:999px;background:#e5e7eb;overflow:hidden}',
            '.password-strength__fill{height:100%;width:0;border-radius:999px;background:#ef4444;transition:width .18s ease,background .18s ease}',
            '.password-strength__status{font-weight:800;color:#64748b;line-height:1.25}',
            '.password-strength.is-very-weak .password-strength__fill{background:#dc2626}',
            '.password-strength.is-weak .password-strength__fill{background:#f97316}',
            '.password-strength.is-medium .password-strength__fill{background:#eab308}',
            '.password-strength.is-strong .password-strength__fill{background:#22c55e}',
            '.password-strength.is-very-strong .password-strength__fill{background:#15803d}',
            '.password-client-error{margin-top:6px;color:#dc2626;font-size:12px;font-weight:700;line-height:1.25;display:none;overflow-wrap:anywhere}',
            '.password-client-error.is-visible{display:block}'
        ].join('');
        document.head.appendChild(style);
    }

    function evaluatePassword(value) {
        var results = requirements.map(function (rule) {
            return { key: rule.key, label: rule.label, valid: rule.test(value) };
        });
        var score = results.filter(function (rule) { return rule.valid; }).length;
        return {
            score: score,
            valid: score === requirements.length,
            level: levels[score],
            results: results
        };
    }

    function fieldContainer(input) {
        return input.closest('.modal-field, .form-field, .mb-2, .mb-3, .form-row') || input.parentNode;
    }

    function insertAfterInputGroup(input, node) {
        var parent = input.parentNode;
        var anchor = parent && parent.matches('.pwd-wrap, .modal-input-wrap, .account-password-wrap')
            ? parent
            : input;
        anchor.insertAdjacentElement('afterend', node);
    }

    function getErrorNode(input) {
        var selector = input.getAttribute('data-password-error');
        if (selector) {
            var explicit = document.querySelector(selector);
            if (explicit) return explicit;
        }

        var container = fieldContainer(input);
        var existing = container.querySelector('.password-client-error');
        if (existing) return existing;

        var error = document.createElement('div');
        error.className = 'password-client-error';
        insertAfterInputGroup(input, error);
        return error;
    }

    function setError(input, message) {
        var error = getErrorNode(input);
        error.textContent = message || '';
        error.classList.toggle('is-visible', Boolean(message));
    }

    function createMeter(input) {
        var meter = document.createElement('div');
        meter.className = 'password-strength is-very-weak';
        meter.innerHTML =
            '<div class="password-strength__bar"><div class="password-strength__fill"></div></div>' +
            '<div class="password-strength__status">Mật khẩu rất yếu</div>';

        insertAfterInputGroup(input, meter);
        return meter;
    }

    function updateMeter(input) {
        var meter = input._passwordStrengthMeter || createMeter(input);
        input._passwordStrengthMeter = meter;

        var value = input.value || '';
        var result = evaluatePassword(value);
        var className = 'password-strength is-' + result.level.className;
        var width = Math.max(1, result.score) * 20;

        meter.className = className;
        meter.querySelector('.password-strength__fill').style.width = width + '%';
        meter.querySelector('.password-strength__status').textContent = result.level.text;

        input.setAttribute('aria-invalid', result.valid ? 'false' : 'true');
        return result;
    }

    function validateStrength(input, showError) {
        var optional = input.getAttribute('data-password-optional') === 'true';
        var value = input.value || '';
        var result = updateMeter(input);

        if (optional && value === '') {
            setError(input, '');
            input.setAttribute('aria-invalid', 'false');
            return true;
        }

        if (!result.valid) {
            if (showError) {
                setError(input, value === '' ? 'Vui lòng nhập mật khẩu.' : 'Mật khẩu chưa đạt đủ các điều kiện.');
            }
            return false;
        }

        setError(input, '');
        return true;
    }

    function validateMatch(input, showError) {
        var targetSelector = input.getAttribute('data-password-match');
        var target = targetSelector ? document.querySelector(targetSelector) : null;
        if (!target) return true;

        var optional = target.getAttribute('data-password-optional') === 'true' && (target.value || '') === '';
        if (optional && (input.value || '') === '') {
            setError(input, '');
            input.setAttribute('aria-invalid', 'false');
            return true;
        }

        if ((input.value || '') === '') {
            if (showError) setError(input, 'Vui lòng xác nhận mật khẩu.');
            input.setAttribute('aria-invalid', 'true');
            return false;
        }

        if (input.value !== target.value) {
            if (showError) setError(input, 'Xác nhận mật khẩu không khớp.');
            input.setAttribute('aria-invalid', 'true');
            return false;
        }

        setError(input, '');
        input.setAttribute('aria-invalid', 'false');
        return true;
    }

    function initPasswordStrength(root) {
        injectStyles();
        root = root || document;

        var strengthInputs = Array.prototype.slice.call(root.querySelectorAll('[data-password-strength]'));
        var matchInputs = Array.prototype.slice.call(root.querySelectorAll('[data-password-match]'));
        var forms = [];

        strengthInputs.forEach(function (input) {
            if (input._passwordStrengthReady) return;
            input._passwordStrengthReady = true;
            updateMeter(input);
            input.addEventListener('input', function () {
                validateStrength(input, false);
                matchInputs.forEach(function (matchInput) {
                    if (matchInput.getAttribute('data-password-match') === '#' + input.id) {
                        validateMatch(matchInput, false);
                    }
                });
            });
            if (input.form && forms.indexOf(input.form) === -1) forms.push(input.form);
        });

        matchInputs.forEach(function (input) {
            if (input._passwordMatchReady) return;
            input._passwordMatchReady = true;
            input.addEventListener('input', function () {
                validateMatch(input, false);
            });
            if (input.form && forms.indexOf(input.form) === -1) forms.push(input.form);
        });

        forms.forEach(function (form) {
            if (form._passwordStrengthSubmitReady) return;
            form._passwordStrengthSubmitReady = true;
            form.addEventListener('submit', function (event) {
                var valid = true;
                form.querySelectorAll('[data-password-strength]').forEach(function (input) {
                    valid = validateStrength(input, true) && valid;
                });
                form.querySelectorAll('[data-password-match]').forEach(function (input) {
                    valid = validateMatch(input, true) && valid;
                });

                if (!valid) {
                    event.preventDefault();
                    var firstInvalid = form.querySelector('[aria-invalid="true"]');
                    if (firstInvalid) firstInvalid.focus();
                }
            });
        });
    }

    window.PasswordStrength = {
        init: initPasswordStrength,
        evaluate: evaluatePassword
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { initPasswordStrength(document); });
    } else {
        initPasswordStrength(document);
    }
})();
