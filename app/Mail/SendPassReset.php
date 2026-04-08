<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPassReset extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $resetLink;
    public function __construct($name, $resetLink)
    {
        $this->name = $name;
        $this->resetLink = $resetLink;
    }
    public function build()
    {
        return $this->subject('Permintaan Reset Password Jokopus')
            ->html($this->resetPasswordHtml());
    }
    private function resetPasswordHtml()
    {
        return "
        <div style='font-family: Inter, Arial, sans-serif; background:#101922; padding:40px 20px;'>
            <div style='background-color:#16212c; max-width:480px; margin:auto; border-radius:24px; padding:40px; border:1px solid rgba(255,255,255,0.1); text-align:center;'>
                
                <div style='margin-bottom: 20px;'>
                    <span style='font-size: 50px;'>🔐</span>
                </div>

                <h2 style='color:white; font-size:28px; margin-bottom:10px;'>
                    Reset Password
                </h2>

                <p style='color:#94a3b8; font-size:15px; margin-bottom:30px; line-height:1.5;'>
                    Halo <strong>{$this->name}</strong>,<br><br>
                    Kami menerima permintaan untuk mengatur ulang password akun Jokopus kamu. Klik tombol di bawah untuk melanjutkan.
                </p>

                <a href='{$this->resetLink}' style='display:inline-block; background:#137fec; color:white; padding:16px 32px; border-radius:14px; text-decoration:none; font-weight:bold; font-size:16px;'>
                    Reset Password
                </a>

                <p style='color:#ffffff; font-size:13px; margin-top:30px; line-height:1.6;'>
                    Jika kamu tidak merasa melakukan permintaan ini, abaikan email ini.
                </p>
            </div>
        </div>
        ";
    }
}