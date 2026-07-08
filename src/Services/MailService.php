<?php

namespace KhoaLuan\QLDRL\Services;

use KhoaLuan\QLDRL\Config\MailConfig;
use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;
use Throwable;

class MailService
{
    public function send(string $toEmail, string $toName, string $subject, string $htmlBody, string $altBody = ''): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->isSMTP();
            $mail->Host = MailConfig::host();
            $mail->SMTPAuth = true;
            $mail->Username = MailConfig::username();
            $mail->Password = MailConfig::password();
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = MailConfig::port();

            $fromEmail = MailConfig::fromEmail() !== '' ? MailConfig::fromEmail() : MailConfig::username();
            $mail->setFrom($fromEmail, MailConfig::fromName());
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->AltBody = $altBody !== '' ? $altBody : strip_tags($htmlBody);

            return $mail->send();
        } catch (MailException | Throwable $exception) {
            error_log('MailService send failed: ' . $exception->getMessage());

            return false;
        }
    }

    public function sendOtp(string $toEmail, string $toName, string $otp): bool
    {
        $safeName = htmlspecialchars($toName !== '' ? $toName : 'Sinh viên', ENT_QUOTES, 'UTF-8');
        $safeOtp = htmlspecialchars($otp, ENT_QUOTES, 'UTF-8');
        $subject = 'Mã OTP đặt lại mật khẩu UniDRL';
        $body = '
            <div style="font-family:Arial,sans-serif;line-height:1.6;color:#0f172a;">
                <h2 style="color:#1f6feb;margin-bottom:12px;">Đặt lại mật khẩu UniDRL</h2>
                <p>Xin chào <strong>' . $safeName . '</strong>,</p>
                <p>Mã OTP đặt lại mật khẩu của bạn là:</p>
                <div style="font-size:28px;font-weight:700;letter-spacing:6px;color:#1f6feb;margin:18px 0;">' . $safeOtp . '</div>
                <p>Mã này có hiệu lực trong <strong>5 phút</strong>. Vui lòng không chia sẻ mã này với bất kỳ ai.</p>
                <p>Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.</p>
            </div>
        ';
        $altBody = 'Ma OTP dat lai mat khau UniDRL cua ban la: ' . $otp . '. Ma co hieu luc trong 5 phut.';

        return $this->send($toEmail, $toName, $subject, $body, $altBody);
    }
}
