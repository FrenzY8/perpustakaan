<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;
    public $otp;
    public $name;
    public function __construct($otp, $name)
    {
        $this->otp = $otp;
        $this->name = $name;
    }
    public function build()
    {
        return $this->subject('Kode Verifikasi OTP Jokopus')
            ->htmlContent();
    }
    private function htmlContent()
    {
        $html = "
            <div style='font-family: Inter, Arial, sans-serif; background:#101922; padding:40px 20px;'>
            <div style='background-image:linear-gradient(rgba(16,25,34,0.85),rgba(16,25,34,0.85)),url(https://media.istockphoto.com/id/1339845062/photo/reading-room-or-library-interior-with-leather-armchair-bookshelf-and-floor-lamp.jpg?s=612x612&w=0&k=20&c=2ghOW2DCvb49Up3D0eFeVzv1kbSMjUq-_psohUYeZB0=); max-width:480px;margin:auto;border-radius:24px;padding:40px;border:1px solid rgba(255,255,255,0.1);text-align:center;'>

                <h2 style='color:white;font-size:32px;margin-bottom:10px;font-weight:800;'>
                    Verifikasi OTP
                </h2>

                <p style='color:white;font-size:14px;margin-bottom:25px;'>
                    OTP untuk melanjutkan pendaftaran akun bernama '<strong><u>{$this->name}</u></strong>'.
                </p>

                <div style='background:black;border-radius:16px;padding:18px;
                font-size:32px;font-weight:900;letter-spacing:10px;
                color:#137fec;margin:20px 0;'>
                    {$this->otp}
                </div>

                <p style='color:#94a3b8;font-size:18px;margin-top:10px;'>
                    Kode ini akan kadaluarsa dalam <b>3 menit</b>.
                </p>

                <hr style='border:0;border-top:1px solid rgba(255,255,255,0.1);margin:30px 0;'>

                <p style='color:#64748b;font-size:14px;line-height:1.6;'>
                    Email ini dikirim otomatis oleh sistem <b style='color:white;'>Jokopus</b>.
                </p>

            </div>
            <p style='text-align:center;color:#475569;font-size:12px;margin-top:20px;'>
                © 2026 Jokopus
            </p>
        </div>
        ";

        return $this->html($html);
    }
}