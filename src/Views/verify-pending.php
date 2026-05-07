<div class="container">
    <div class="auth-page">
        <div class="verify-pending-card">
            <div class="verify-icon">🔐</div>
            <h1>Nhập mã OTP</h1>
            
            <?php if ($emailFailed ?? false): ?>
                <div class="alert alert-error">
                    ⚠️ Có lỗi khi gửi email. Vui lòng thử gửi lại.
                </div>
            <?php endif; ?>
            
            <?php if ($otpError ?? false): ?>
                <div class="alert alert-error">
                    ❌ <?= htmlspecialchars($otpError) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['resent'])): ?>
                <div class="alert alert-success">
                    ✅ Mã OTP mới đã được gửi đến email của bạn!
                </div>
            <?php endif; ?>
            
            <p class="verify-message">
                Chúng tôi đã gửi mã xác thực 6 số đến:
            </p>
            <p class="verify-email">
                <strong><?= htmlspecialchars($email ?? '') ?></strong>
            </p>
            
            <!-- OTP Input Form -->
            <form action="/verify-otp" method="POST" class="otp-form" id="otpForm">
                <div class="otp-inputs">
                    <input type="text" maxlength="1" class="otp-input" data-index="0" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" autofocus>
                    <input type="text" maxlength="1" class="otp-input" data-index="1" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-input" data-index="2" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-input" data-index="3" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-input" data-index="4" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-input" data-index="5" inputmode="numeric" pattern="[0-9]">
                </div>
                <input type="hidden" name="otp_code" id="otpCodeHidden">
                <button type="submit" class="btn btn-primary btn-verify" id="verifyBtn" disabled>✅ Xác thực</button>
            </form>
            
            <div class="verify-note">
                <p>⏰ Mã OTP sẽ hết hạn sau <strong>10 phút</strong></p>
            </div>
            
            <div class="verify-actions">
                <form action="/resend-verification" method="POST" class="resend-form">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
                    <button type="submit" class="btn btn-secondary">📤 Gửi lại mã OTP</button>
                </form>
                <a href="/login" class="btn btn-outline">← Về trang đăng nhập</a>
            </div>
        </div>
    </div>
</div>

<style>
.verify-pending-card {
    max-width: 500px;
    margin: 3rem auto;
    background: white;
    padding: 3rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.verify-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.verify-pending-card h1 {
    color: #333;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
}

.verify-message {
    color: #666;
    margin-bottom: 0.5rem;
}

.verify-email {
    color: #d70018;
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    word-break: break-all;
}

/* OTP Input Styles */
.otp-form {
    margin: 2rem 0;
}

.otp-inputs {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 1.5rem;
}

.otp-input {
    width: 52px;
    height: 60px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    border: 2px solid #ddd;
    border-radius: 12px;
    outline: none;
    transition: all 0.3s ease;
    color: #333;
    background: #f8f9fa;
}

.otp-input:focus {
    border-color: #d70018;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(215, 0, 24, 0.15);
    transform: scale(1.05);
}

.otp-input.filled {
    border-color: #d70018;
    background: #fff;
    color: #d70018;
}

.btn-verify {
    width: 100%;
    padding: 14px;
    font-size: 1.1rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    background: #d70018;
    color: white;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-verify:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.btn-verify:not(:disabled):hover {
    background: #b5001a;
    transform: translateY(-1px);
}

.verify-note {
    background: #fff3cd;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.verify-note p {
    margin: 0;
    color: #856404;
}

.verify-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.resend-form {
    margin: 0;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-error {
    background: #fee;
    color: #c00;
    border: 1px solid #fcc;
}

.alert-success {
    background: #efe;
    color: #080;
    border: 1px solid #cfc;
}

@media (max-width: 480px) {
    .otp-input {
        width: 42px;
        height: 50px;
        font-size: 20px;
    }
    .otp-inputs {
        gap: 6px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otpCodeHidden');
    const verifyBtn = document.getElementById('verifyBtn');
    const form = document.getElementById('otpForm');

    function updateHiddenInput() {
        let code = '';
        inputs.forEach(input => { code += input.value; });
        hiddenInput.value = code;
        verifyBtn.disabled = code.length !== 6;
    }

    inputs.forEach((input, index) => {
        // Only allow numbers
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value) {
                this.classList.add('filled');
                // Move to next input
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            } else {
                this.classList.remove('filled');
            }
            updateHiddenInput();
        });

        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
                inputs[index - 1].classList.remove('filled');
                updateHiddenInput();
            }
        });

        // Handle paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
            
            pasteData.split('').forEach((char, i) => {
                if (inputs[i]) {
                    inputs[i].value = char;
                    inputs[i].classList.add('filled');
                }
            });
            
            // Focus last filled or next empty
            const focusIndex = Math.min(pasteData.length, inputs.length - 1);
            inputs[focusIndex].focus();
            updateHiddenInput();
        });
    });

    // Auto-submit when all 6 digits are entered
    form.addEventListener('input', function() {
        let code = '';
        inputs.forEach(input => { code += input.value; });
        if (code.length === 6) {
            setTimeout(() => form.submit(), 300);
        }
    });
});
</script>
