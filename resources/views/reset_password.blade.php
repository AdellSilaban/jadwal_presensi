
    <title>Reset Password Akun Anda</title>
    <p>Halo {{ $volunteer->nama }},</p>

    <p>Anda menerima email ini karena ada permintaan untuk mereset password akun Anda.</p>

    <p>Silakan klik tautan berikut untuk mereset password Anda:</p>

    <p><a href="{{ $resetLink }}">{{ $resetLink }}</a></p>

    <p>Jika Anda tidak merasa melakukan permintaan ini, Anda bisa mengabaikan email ini. Tautan reset password hanya berlaku selama 24 jam.</p>

    <p>Terima kasih,</p>
    <p>Tim Kami</p>
